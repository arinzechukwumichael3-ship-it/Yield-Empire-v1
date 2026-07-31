<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_qualifying_deposit')->default(false)->after('strowallet_customer');
            $table->decimal('qualifying_deposit_amount', 12, 2)->default(0)->after('has_qualifying_deposit');
            $table->timestamp('qualifying_deposit_date')->nullable()->after('qualifying_deposit_amount');
            $table->boolean('card_unlocked')->default(false)->after('qualifying_deposit_date');
            $table->boolean('withdrawal_unlocked')->default(false)->after('card_unlocked');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'has_qualifying_deposit',
                'qualifying_deposit_amount',
                'qualifying_deposit_date',
                'card_unlocked',
                'withdrawal_unlocked',
            ]);
        });
    }
};
