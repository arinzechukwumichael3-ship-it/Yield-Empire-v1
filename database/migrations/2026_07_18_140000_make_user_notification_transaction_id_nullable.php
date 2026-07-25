<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Allow user notifications that are not tied to a transaction
     * (e.g. security / rule-block alerts) by making transaction_id nullable.
     */
    public function up()
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE user_notifications ALTER COLUMN transaction_id DROP NOT NULL');
        } else {
            DB::statement('ALTER TABLE user_notifications MODIFY transaction_id BIGINT UNSIGNED NULL');
        }
    }

    public function down()
    {
        // Leave nullable to avoid breaking rows created while it was nullable.
    }
};
