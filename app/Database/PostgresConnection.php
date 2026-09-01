<?php

namespace App\Database;

use Illuminate\Database\PostgresConnection as BasePostgresConnection;
use Illuminate\Database\Query\Grammars\Grammar;

class PostgresConnection extends BasePostgresConnection
{
    /**
     * Prepare the query bindings for execution.
     *
     * Laravel 9's default PostgresConnection converts PHP booleans to integer
     * 1/0 in the bindings (see test output: binding = 1). PostgreSQL then
     * rejects "boolean = integer" with:
     *   operator does not exist: boolean = integer
     *
     * Fix: override prepareBindings to pass boolean values as PostgreSQL
     * boolean literals ('true'/'false') as strings. PostgreSQL interprets
     * these as the native boolean type and the comparison succeeds.
     *
     * This app's "boolean" columns (status, email_verified, etc.) are stored
     * as smallint; those are queried with integer values (1/0) directly,
     * never via PHP booleans, so they are unaffected by this override.
     */
    public function prepareBindings(array $bindings): array
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
            if ($value instanceof \DateTimeInterface) {
                $bindings[$key] = $value->format($grammar->getDateFormat());
            } elseif (is_bool($value)) {
                // PostgreSQL native boolean literal.
                $bindings[$key] = $value ? 'true' : 'false';
            }
        }

        return $bindings;
    }
}
