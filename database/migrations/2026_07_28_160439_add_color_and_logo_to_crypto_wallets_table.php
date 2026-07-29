<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table("crypto_wallets", function (Blueprint $table) {
            $table->string("color", 20)->nullable()->after("network");
            $table->string("logo", 255)->nullable()->after("color");
        });
    }

    public function down()
    {
        Schema::table("crypto_wallets", function (Blueprint $table) {
            $table->dropColumn(["color", "logo"]);
        });
    }
};
