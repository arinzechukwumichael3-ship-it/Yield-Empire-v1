<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class WidenTransactionSettingsDecimals extends Migration
{
    /**
     * Widen the charge/limit columns so any fee value entered in the
     * admin (e.g. a large virtual card purchase fee) persists instead
     * of failing with a numeric overflow. Uses raw DDL because Laravel's
     * ->change() requires doctrine/dbal, which is not installed.
     */
    public function up()
    {
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN fixed_charge TYPE decimal(16, 4)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN percent_charge TYPE decimal(16, 4)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN min_limit TYPE decimal(16, 4)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN max_limit TYPE decimal(16, 4)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN monthly_limit TYPE decimal(16, 4)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN daily_limit TYPE decimal(16, 4)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN fixed_charge TYPE decimal(8, 2)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN percent_charge TYPE decimal(8, 2)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN min_limit TYPE decimal(8, 2)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN max_limit TYPE decimal(8, 2)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN monthly_limit TYPE decimal(8, 2)');
        DB::statement('ALTER TABLE transaction_settings ALTER COLUMN daily_limit TYPE decimal(8, 2)');
    }
}
