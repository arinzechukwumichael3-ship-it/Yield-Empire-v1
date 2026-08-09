<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddInternationalDetailsToUsersTable extends Migration
{
    /**
     * Auto-generated international details for every user, linked to their
     * network bank account number. These are what another YieldEmpire user
     * needs in order to send money to this account.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('network_bank_name')->nullable()->after('account_no');
            $table->string('network_account_number')->nullable()->after('network_bank_name');
            $table->string('network_iban')->nullable()->after('network_account_number');
            $table->string('network_swift')->nullable()->after('network_iban');
        });

        // Backfill existing users so they also get international details.
        $existingIbans = DB::table('users')->whereNotNull('network_iban')->pluck('network_iban')->all();
        $users = DB::table('users')->whereNull('network_account_number')->get(['id', 'account_no']);

        foreach ($users as $user) {
            do {
                $iban = 'EZ' . $this->randomDigits(20);
            } while (in_array($iban, $existingIbans, true));
            $existingIbans[] = $iban;

            DB::table('users')->where('id', $user->id)->update([
                'network_bank_name'      => 'YieldEmpire',
                'network_account_number' => $user->account_no,
                'network_iban'           => $iban,
                'network_swift'          => 'YELDUS33',
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('network_iban');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_network_iban_unique');
            $table->dropColumn(['network_bank_name', 'network_account_number', 'network_iban', 'network_swift']);
        });
    }

    private function randomDigits(int $length): string
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= random_int(0, 9);
        }
        return $digits;
    }
}
