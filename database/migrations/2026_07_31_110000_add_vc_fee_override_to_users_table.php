<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-user override for the virtual card purchase fee.
     * The model, helper and admin form already reference this column,
     * but it was never migrated into the database, so Laravel treated
     * it as guarded and silently dropped the value on save (the admin
     * fee edit kept reverting to the global fee).
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('vc_fee_override', 18, 8)->nullable()->after('crypto_limit');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('vc_fee_override');
        });
    }
};
