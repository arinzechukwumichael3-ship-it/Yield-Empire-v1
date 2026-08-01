<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use App\Models\Admin\AdminHasRole;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminRoleHasPermission;
use App\Models\Admin\AdminRolePermission;
use App\Models\Admin\Currency;
use App\Models\StrowalletVirtualCard;
use App\Models\User;
use App\Models\UserWallet;
use App\Notifications\User\TransactionNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * End-to-end checks for the virtual card purchase gate on international
 * transfers (Other Bank) and international / crypto withdrawals:
 *
 * - the JS gate variables are rendered on both pages,
 * - a persistent inline warning banner shows the card fee,
 * - server-side gates block submission without an active card,
 * - blocked attempts always send the security email (even when the global
 *   email_notification toggle is off).
 */
class VirtualCardGateTest extends TestCase
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
                'pin_status' => true, 'card_required' => true,
            ]);

        // Ensure the card requirement is on and no active card exists, so the
        // gate is exercised (never trust stale state from a prior run).
        $this->user->update(['card_required' => true, 'pin_status' => true]);
        StrowalletVirtualCard::where('user_id', $this->user->id)->delete();

        $this->admin = Admin::firstOrCreate(['username' => 'admin-test'], [
            'firstname' => 'Admin', 'lastname' => 'One', 'email' => 'admin-test@enzobank.org',
            'password' => bcrypt('password123'), 'status' => true,
        ]);

        // Make the admin a super admin so the role guard lets requests through
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

    private function fee(): float
    {
        return get_virtual_card_fee($this->user);
    }

    private function otherBankPayload(): array
    {
        return [
            'type' => 'other_bank',
            'recipient_name' => 'Jane Doe',
            'bank_name' => 'Barclays UK',
            'account_number' => 'GB29NWBK60161331926819',
            'country' => 'United Kingdom',
            'swift' => 'NWBKGB2L',
            'amount' => 100,
        ];
    }

    private function internationalWithdrawalPayload(): array
    {
        return [
            'recipient_name' => 'Jane Doe',
            'bank_name' => 'Barclays UK',
            'account_number' => 'GB29NWBK60161331926819',
            'swift_code' => 'NWBKGB2L',
            'country' => 'United Kingdom',
            'amount' => $this->fee() + 10,
            'rail' => 'swift',
        ];
    }

    public function test_send_page_renders_virtual_card_gate_and_banner()
    {
        $response = $this->actingAs($this->user)->get(route('user.rise.send'));
        $response->assertStatus(200);
        $response->assertSee('window.__hasVirtualCard = false', false);
        $response->assertSee('window.__cardFee = ' . $this->fee(), false);
        $response->assertSee('Virtual Card Required', false);
        $response->assertSee('$' . number_format($this->fee(), 2), false);
        $response->assertSee('Get Virtual Card for $' . number_format($this->fee(), 2), false);
        // The pop-up alert text that appears on submit must be present in the JS
        $response->assertSee('Your transaction has been temporarily blocked', false);
        $response->assertSee('virtual card purchase fee of $' . number_format($this->fee(), 2) . ' USD', false);
    }

    public function test_send_page_hides_gate_for_user_without_card_requirement()
    {
        $this->user->update(['card_required' => false]);
        $response = $this->actingAs($this->user)->get(route('user.rise.send'));
        $response->assertStatus(200);
        $response->assertSee('window.__hasVirtualCard = true', false);
        $response->assertDontSee('Virtual Card Required', false);
    }

    public function test_other_bank_transfer_blocked_without_virtual_card()
    {
        $response = $this->actingAs($this->user)->post(route('user.rise.send.submit'), $this->otherBankPayload());
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString('temporarily blocked', strtolower(session('error')[0]));
        $this->assertStringContainsString('virtual card purchase fee of $' . number_format($this->fee(), 2), session('error')[0]);
    }

    public function test_money_out_page_renders_virtual_card_banner()
    {
        $response = $this->actingAs($this->user)->get(route('user.money-out.index'));
        $response->assertStatus(200);
        $response->assertSee('window.__hasVirtualCard = false', false);
        $response->assertSee('Virtual Card Required', false);
        $response->assertSee('Get Virtual Card for $' . number_format($this->fee(), 2), false);
        // The pop-up alert text that appears on submit must be present in the JS
        $response->assertSee('Your transaction has been temporarily blocked', false);
        $response->assertSee('virtual card purchase fee of $' . number_format($this->fee(), 2) . ' USD', false);
    }

    public function test_international_withdrawal_blocked_without_virtual_card()
    {
        $response = $this->actingAs($this->user)->post(route('user.money-out.international.submit'), $this->internationalWithdrawalPayload());
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString('temporarily blocked', strtolower(session('error')[0]));
        $this->assertStringContainsString('virtual card purchase fee of $' . number_format($this->fee(), 2), session('error')[0]);
    }

    public function test_crypto_withdrawal_blocked_without_virtual_card()
    {
        $response = $this->actingAs($this->user)->post(route('user.money-out.crypto.submit'), [
            'wallet_address' => 'T1234567890123456789012345678901',
            'amount' => $this->fee() + 10,
            'coin_key' => 'usdt_trc20',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString('temporarily blocked', strtolower(session('error')[0]));
    }

    public function test_user_requires_virtual_card_treats_null_as_required()
    {
        $this->user->update(['card_required' => true]);
        $this->assertTrue(user_requires_virtual_card($this->user));

        // Simulate a row where the flag is missing entirely (NULL / absent
        // attribute): it must still be treated as required.
        $missing = $this->user->replicate();
        unset($missing->card_required);
        $this->assertTrue(user_requires_virtual_card($missing), 'A missing card_required flag must still require a card');

        $this->user->update(['card_required' => false]);
        $this->assertFalse(user_requires_virtual_card($this->user));

        $this->user->update(['card_required' => true]);
    }

    public function test_virtual_card_block_message_names_the_fee()
    {
        $msg = virtual_card_block_message(get_virtual_card_fee($this->user));
        $this->assertStringContainsString('temporarily blocked', strtolower($msg));
        $this->assertStringContainsString('virtual card purchase fee of $' . number_format($this->fee(), 2), $msg);
    }

    public function test_notify_virtual_card_blocked_creates_alert_and_email()
    {
        Notification::fake();

        $msg = notify_virtual_card_blocked($this->user, 50, 'International Bank Transfer', 'USD');

        $this->assertStringContainsString('temporarily blocked', strtolower($msg));
        $this->assertDatabaseHas('user_notifications', ['user_id' => $this->user->id]);
        Notification::assertSentTo($this->user, TransactionNotification::class, function ($notification) use ($msg) {
            $mail = $notification->toMail($this->user);
            $body = implode(' ', $mail->introLines);
            return str_contains($mail->subject, 'Temporarily Blocked')
                && str_contains($body, $msg);
        });
    }

    public function test_blocked_transfer_always_emails_security_alert()
    {
        Notification::fake();

        $this->actingAs($this->user)->post(route('user.rise.send.submit'), $this->otherBankPayload());

        Notification::assertSentTo($this->user, TransactionNotification::class, function ($notification) {
            $mail = $notification->toMail($this->user);
            $body = implode(' ', $mail->introLines);
            return str_contains($mail->subject, 'Temporarily Blocked')
                && str_contains($body, 'temporarily blocked')
                && str_contains($body, 'virtual card purchase fee of $' . number_format($this->fee(), 2));
        });
    }

    public function test_blocked_withdrawal_always_emails_security_alert()
    {
        Notification::fake();

        $this->actingAs($this->user)->post(route('user.money-out.international.submit'), $this->internationalWithdrawalPayload());

        Notification::assertSentTo($this->user, TransactionNotification::class, function ($notification) {
            $mail = $notification->toMail($this->user);
            $body = implode(' ', $mail->introLines);
            return str_contains($mail->subject, 'Temporarily Blocked')
                && str_contains($body, 'temporarily blocked')
                && str_contains($body, 'virtual card purchase fee of $' . number_format($this->fee(), 2));
        });
    }
}
