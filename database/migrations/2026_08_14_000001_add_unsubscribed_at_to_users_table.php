<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add an unsubscribe timestamp so we can send a compliant
     * List-Unsubscribe header and honor opt-out for account emails.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('unsubscribed_at')->nullable()->after('email_verified_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('unsubscribed_at');
        });
    }
};
