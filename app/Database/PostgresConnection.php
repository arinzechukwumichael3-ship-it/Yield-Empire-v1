<?php

namespace App\Database;

use Illuminate\Database\PostgresConnection as BasePostgresConnection;
use Illuminate\Database\Query\Grammars\Grammar;
use Closure;

class PostgresConnection extends BasePostgresConnection
{
    /**
     * Cache of column types: ["table.column" => "boolean"|"smallint"|"other"]
     */
    private static array $colTypeCache = [];

    /**
     * Extract the first table name from a SQL query (FROM or INSERT INTO).
     */
    private function extractTableName(string $sql): ?string
    {
        if (preg_match('/FROM\s+"([^"]+)"/i', $sql, $m)) {
            return $m[1];
        }
        if (preg_match('/FROM\s+(\w+)/i', $sql, $m)) {
            return $m[1];
        }
        if (preg_match('/INSERT\s+INTO\s+"([^"]+)"/i', $sql, $m)) {
            return $m[1];
        }
        if (preg_match('/INSERT\s+INTO\s+(\w+)/i', $sql, $m)) {
            return $m[1];
        }
        if (preg_match('/UPDATE\s+"([^"]+)"/i', $sql, $m)) {
            return $m[1];
        }
        if (preg_match('/UPDATE\s+(\w+)/i', $sql, $m)) {
            return $m[1];
        }
        return null;
    }

    /**
     * Extract column names from WHERE/AND/OR clauses in order.
     */
    private function extractWhereColumns(string $sql): array
    {
        $cols = [];
        foreach ([
            '/(?:WHERE|AND|OR)\s+"([^"]+)"\s*=\s*\?/i',
            '/(?:WHERE|AND|OR)\s+(\w+)\s*=\s*\?/i',
        ] as $pattern) {
            if (preg_match_all($pattern, $sql, $m)) {
                $cols = array_merge($cols, $m[1]);
            }
        }
        return array_values(array_unique($cols));
    }

    /**
     * Extract column names from UPDATE SET clause.
     * e.g. update "users" set "email_verified" = ?, "updated_at" = ? where "id" = ?
     */
    private function extractUpdateColumns(string $sql): array
    {
        if (preg_match('/UPDATE\s+["`]?(\w+)`?]\s+SET\s+(.+?)\s+WHERE/i', $sql, $m)) {
            $setClause = $m[2];
            $cols = [];
            foreach ([
                '/["`]([^"`]+)["`]\s*=\s*\?/i',
                '/(\w+)\s*=\s*\?/i',
            ] as $pattern) {
                if (preg_match_all($pattern, $setClause, $matches)) {
                    $cols = array_merge($cols, $matches[1]);
                }
            }
            return $cols;
        }
        return [];
    }

    /**
     * Extract column names from INSERT INTO clause.
     * e.g. insert into "users" ("col1", "col2", "col3") values (?, ?, ?)
     */
    private function extractInsertColumns(string $sql): array
    {
        if (preg_match('/INSERT\s+INTO\s+"[^"]+"\s*\(([^)]+)\)/i', $sql, $m)) {
            $cols = array_map('trim', explode(',', $m[1]));
            return array_map(fn($c) => trim($c, '" '), $cols);
        }
        if (preg_match('/INSERT\s+INTO\s+\w+\s*\(([^)]+)\)/i', $sql, $m)) {
            $cols = array_map('trim', explode(',', $m[1]));
            return array_map(fn($c) => trim($c, '" '), $cols);
        }
        return [];
    }

    /**
     * Look up the type of a column from information_schema (cached).
     */
    private function getColumnType(string $table, string $column): ?string
    {
        $key = "$table.$column";
        if (isset(self::$colTypeCache[$key])) {
            return self::$colTypeCache[$key];
        }

        try {
            $sql = "SELECT data_type, udt_name FROM information_schema.columns 
                    WHERE table_schema = 'public' AND table_name = ? AND column_name = ?";
            $stmt = $this->getPdo()->prepare($sql);
            $stmt->execute([$table, $column]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) {
                self::$colTypeCache[$key] = null;
                return null;
            }
            $type = $row['data_type'] === 'USER-DEFINED' ? $row['udt_name'] : $row['data_type'];
            self::$colTypeCache[$key] = $type;
            return $type;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function run($query, $bindings, Closure $callback)
    {
        $table = $this->extractTableName($query);
        $whereCols = $this->extractWhereColumns($query);
        $insertCols = $this->extractInsertColumns($query);
        $updateCols = $this->extractUpdateColumns($query);

        foreach ($bindings as $i => $v) {
            if (!is_bool($v) && $v !== 1 && $v !== 0) {
                continue;
            }

            // Determine which column this binding maps to
            $col = null;
            if (count($updateCols) > 0) {
                $col = $updateCols[$i] ?? null;
            } elseif (count($insertCols) > 0) {
                $col = $insertCols[$i % count($insertCols)] ?? null;
            } elseif (count($whereCols) > 0) {
                $col = $whereCols[$i % count($whereCols)] ?? null;
            }
            if ($col === null || $table === null) {
                // No context — convert booleans to 'true'/'false' strings
                if (is_bool($v)) {
                    $bindings[$i] = $v ? 'true' : 'false';
                }
                continue;
            }

            $type = $this->getColumnType($table, $col);
            if ($type === null) {
                if (is_bool($v)) {
                    $bindings[$i] = $v ? 'true' : 'false';
                }
                continue;
            }

            $isBooleanCol = in_array($type, ['boolean', 'bool'], true);
            $isSmallintCol = in_array($type, ['smallint', 'integer', 'bigint'], true);

            if (is_bool($v)) {
                if ($isSmallintCol) {
                    $bindings[$i] = $v ? 1 : 0;
                } else {
                    $bindings[$i] = $v ? 'true' : 'false';
                }
            } elseif (($v === 1 || $v === 0) && $isBooleanCol) {
                $bindings[$i] = $v ? 'true' : 'false';
            }
        }

        return parent::run($query, $bindings, $callback);
    }
}
