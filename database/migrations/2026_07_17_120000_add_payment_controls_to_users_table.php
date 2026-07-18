<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-per-user payment controls:
     *  - per-method enable/disable toggles
     *  - per-user amount caps for virtual card & crypto deposits
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('virtual_card_status')->default(true)->after('two_factor_secret');
            $table->boolean('crypto_status')->default(true)->after('virtual_card_status');
            $table->boolean('add_money_status')->default(true)->after('crypto_status');
            $table->boolean('fund_transfer_status')->default(true)->after('add_money_status');
            $table->boolean('money_out_status')->default(true)->after('fund_transfer_status');
            $table->decimal('virtual_card_limit', 18, 8)->nullable()->after('money_out_status');
            $table->decimal('crypto_limit', 18, 8)->nullable()->after('virtual_card_limit');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'virtual_card_status',
                'crypto_status',
                'add_money_status',
                'fund_transfer_status',
                'money_out_status',
                'virtual_card_limit',
                'crypto_limit',
            ]);
        });
    }
};
