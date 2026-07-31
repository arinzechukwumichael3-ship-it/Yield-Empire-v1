<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use App\Models\Admin\AdminHasRole;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminRoleHasPermission;
use App\Models\Admin\AdminRolePermission;
use App\Models\Admin\Currency;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\UserWallet;
use Tests\TestCase;

/**
 * End-to-end checks for the bank details system: user CRUD page, send page
 * wallet selector, internal-transfer gating, and the admin user details view.
 *
 * These tests create their own fixtures (user, admin, currencies, wallets) so
 * they run against any database, seeded or empty.
 */
class BankDetailsManualCheckTest extends TestCase
{
    private User $user;
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::where('email', 'test-one@enzobank.org')->first()
            ?? User::firstOrCreate(['username' => 'test-one'], [
                'firstname' => 'Tester', 'lastname' => 'One', 'email' => 'test-one@enzobank.org',
                'password' => bcrypt('password123'), 'email_verified' => true, 'status' => true,
            ]);

        $this->admin = Admin::firstOrCreate(['username' => 'admin-test'], [
            'firstname' => 'Admin', 'lastname' => 'One', 'email' => 'admin-test@enzobank.org',
            'password' => bcrypt('password123'), 'status' => true,
        ]);

        // Make the admin a super admin so the role guard lets the request through
        $role = AdminRole::where('name', 'Super Admin')->first()
            ?? AdminRole::create(['admin_id' => $this->admin->id, 'name' => 'Super Admin', 'status' => true]);
        AdminHasRole::firstOrCreate(
            ['admin_id' => $this->admin->id, 'admin_role_id' => $role->id],
            ['last_edit_by' => $this->admin->id]
        );
        $permission = AdminRolePermission::where('admin_role_id', $role->id)->first()
            ?? AdminRolePermission::create([
                'admin_role_id' => $role->id, 'admin_id' => $this->admin->id, 'name' => 'All', 'slug' => 'all', 'status' => true,
            ]);
        AdminRoleHasPermission::firstOrCreate(
            ['admin_role_permission_id' => $permission->id, 'route' => 'all'],
            ['admin_id' => $this->admin->id, 'title' => 'All', 'last_edit_by' => $this->admin->id]
        );

        // Ensure the three main fiat currencies + wallets exist
        foreach ([['US Dollar', 'USD', '$', 1], ['British Pound', 'GBP', '£', 0.79], ['Euro', 'EUR', '€', 0.92]] as $row) {
            $currency = Currency::firstOrCreate(['code' => $row[1]], [
                'admin_id' => $this->admin->id, 'country' => 'US', 'name' => $row[0],
                'symbol' => $row[2], 'type' => 'FIAT', 'flag' => '', 'rate' => $row[3],
                'sender' => true, 'receiver' => true, 'default' => $row[1] === 'USD', 'status' => true,
            ]);
            UserWallet::firstOrCreate(
                ['user_id' => $this->user->id, 'currency_id' => $currency->id],
                ['balance' => 1000, 'status' => true]
            );
        }
    }

    private function ensureBankDetail(): UserBankDetail
    {
        return UserBankDetail::firstOrCreate(
            ['user_id' => $this->user->id, 'bank_name' => 'Test Bank'],
            ['recipient_name' => 'Test', 'account_number_iban' => '123', 'country' => 'US', 'swift_bic' => 'TEST']
        );
    }

    public function test_bank_details_page_renders_for_authenticated_user()
    {
        $this->ensureBankDetail();
        $response = $this->actingAs($this->user)->get(route('user.bank.details.index'));
        $response->assertStatus(200);
        $response->assertSee('Bank Details');
        $response->assertSee('Test Bank');
    }

    public function test_send_page_renders_with_wallet_selector()
    {
        $response = $this->actingAs($this->user)->get(route('user.rise.send'));
        $response->assertStatus(200);
        $response->assertSee('internalWallet');
    }

    public function test_internal_transfer_blocked_without_bank_details()
    {
        $wallet = $this->user->wallets()->whereHas('currency', fn ($q) => $q->where('code', 'USD'))->firstOrFail();
        UserBankDetail::where('user_id', $this->user->id)->delete();
        $response = $this->actingAs($this->user)->post(route('user.rise.send.submit'), [
            'type' => 'internal',
            'account' => 'someaccount',
            'amount' => 10,
            'wallet_id' => $wallet->id,
        ]);
        $response->assertSessionHas('error');
        $this->assertStringContainsString('add your bank details', session('error')[0]);
        $this->ensureBankDetail();
    }

    public function test_internal_transfer_succeeds_with_bank_details()
    {
        $this->ensureBankDetail();
        $recipient = User::where('email', 'test-two@enzobank.org')->first()
            ?? User::firstOrCreate(['username' => 'test-two'], [
                'firstname' => 'Recipient', 'lastname' => 'Two', 'email' => 'test-two@enzobank.org',
                'account_no' => '9876543210',
                'password' => bcrypt('password123'), 'email_verified' => true, 'status' => true,
            ]);
        $usd = Currency::where('code', 'USD')->firstOrFail();
        $recipientWallet = UserWallet::firstOrCreate(
            ['user_id' => $recipient->id, 'currency_id' => $usd->id],
            ['balance' => 500, 'status' => true]
        );
        $wallet = $this->user->wallets()->where('currency_id', $usd->id)->firstOrFail();

        // Reset balances so this test is idempotent
        $wallet->update(['balance' => 1000]);
        $recipientWallet->update(['balance' => 500]);

        $response = $this->actingAs($this->user)->post(route('user.rise.send.submit'), [
            'type' => 'internal',
            'account' => '9876543210',
            'amount' => 25,
            'wallet_id' => $wallet->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('user_wallets', ['id' => $wallet->id, 'balance' => 975.0]);
        $this->assertDatabaseHas('user_wallets', ['id' => $recipientWallet->id, 'balance' => 525.0]);
        $this->assertDatabaseHas('transactions', ['user_id' => $this->user->id, 'type' => 'MOBILE-WALLET-TRANSFER', 'request_currency' => 'USD']);
    }

    public function test_admin_user_details_shows_bank_details_and_wallets()
    {
        $this->ensureBankDetail();
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.users.details', $this->user->username));
        $response->assertStatus(200);
        $response->assertSee('Bank Details');
        $response->assertSee('Test Bank');
        $response->assertSee('USD');
        $response->assertSee('GBP');
        $response->assertSee('EUR');
    }
}
