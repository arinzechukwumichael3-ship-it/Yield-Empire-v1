<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('crypto_deposits', function (Blueprint $table) {
            $table->boolean('qualifies_for_unlock')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('crypto_deposits', function (Blueprint $table) {
            $table->dropColumn('qualifies_for_unlock');
        });
    }
};
