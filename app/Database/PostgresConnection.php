<?php

namespace App\Database;

use Illuminate\Database\PostgresConnection as BasePostgresConnection;
use Illuminate\Database\Query\Grammars\Grammar;
use Closure;

class PostgresConnection extends BasePostgresConnection
{
    private const REAL_BOOLEAN = [
        "qualifies_for_unlock", "is_active", "is_read",
        "add_money_status", "card_required", "card_unlocked",
        "crypto_status", "fund_transfer_status", "has_qualifying_deposit",
        "money_out_status", "own_bank_transfer_blocked",
        "virtual_card_status", "withdrawal_unlocked",
    ];

    private const SMALLINT = [
        "status", "email_verified", "sms_verified", "kyc_verified",
        "two_factor_verified", "two_factor_status", "pin_status",
    ];

    private static ?array $columnTypes = null;

    private static function columnTypes(): array
    {
        if (self::$columnTypes !== null) return self::$columnTypes;
        $types = [];
        foreach (self::REAL_BOOLEAN as $c) $types[$c] = "real_boolean";
        foreach (self::SMALLINT as $c) $types[$c] = "smallint";
        self::$columnTypes = $types;
        return $types;
    }

    private function extractWhereColumnNames(string $sql): array
    {
        $cols = [];
        // Merge ALL patterns — don't gate later patterns on earlier ones.
        foreach ([
            '/WHERE\s+"([^"]+)"\s*=\s*\?/i',
            '/WHERE\s+(\w+)\s*=\s*\?/i',
            '/(?:AND|OR)\s+"([^"]+)"\s*=\s*\?/i',
            '/(?:AND|OR)\s+(\w+)\s*=\s*\?/i',
        ] as $pattern) {
            if (preg_match_all($pattern, $sql, $m)) {
                $cols = array_merge($cols, $m[1]);
            }
        }
        return array_values(array_unique($cols));
    }

    public function run($query, $bindings, Closure $callback)
    {
        $columnTypes = self::columnTypes();
        $whereCols = $this->extractWhereColumnNames($query);
        foreach ($bindings as $i => $v) {
            if (!is_bool($v)) continue;
            $col = isset($whereCols[$i]) ? $whereCols[$i] : null;
            if ($col !== null && isset($columnTypes[$col])) {
                $bindings[$i] = $columnTypes[$col] === "smallint" ? ($v ? 1 : 0) : ($v ? "true" : "false");
            } else {
                $bindings[$i] = $v ? "true" : "false";
            }
        }
        return parent::run($query, $bindings, $callback);
    }
}
