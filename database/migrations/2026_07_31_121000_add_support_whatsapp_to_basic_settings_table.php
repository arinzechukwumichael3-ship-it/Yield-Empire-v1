<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * General WhatsApp support number shown on deposit-help links when the
     * user has no per-user override. Backfilled from the SUPPORT_WHATSAPP env
     * so the live number keeps working without manual entry.
     */
    public function up()
    {
        if (! Schema::hasColumn('basic_settings', 'support_whatsapp')) {
            Schema::table('basic_settings', function (Blueprint $table) {
                $table->string('support_whatsapp')->nullable()->after('web_version');
            });
        }

        $envNumber = preg_replace('/[^0-9]/', '', (string) env('SUPPORT_WHATSAPP', '447464483316'));
        if ($envNumber !== '') {
            DB::table('basic_settings')->update(['support_whatsapp' => $envNumber]);
        }
    }

    public function down()
    {
        if (Schema::hasColumn('basic_settings', 'support_whatsapp')) {
            Schema::table('basic_settings', function (Blueprint $table) {
                $table->dropColumn('support_whatsapp');
            });
        }
    }
};
