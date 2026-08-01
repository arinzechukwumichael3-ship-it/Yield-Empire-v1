<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Verifies the shared WhatsApp support helpers used by the crypto deposit
 * help card, so the support deep link always points at the real number and
 * prefilled messages are URL-safe.
 */
class SupportWhatsappHelperTest extends TestCase
{
    public function test_support_whatsapp_number_is_digits_only()
    {
        $this->assertSame('447464483316', support_whatsapp_number());
        $this->assertMatchesRegularExpression('/^[0-9]+$/', support_whatsapp_number());
    }

    public function test_support_whatsapp_link_without_message()
    {
        $this->assertSame('https://wa.me/447464483316', support_whatsapp_link());
        $this->assertSame('https://wa.me/447464483316', support_whatsapp_link(''));
    }

    public function test_support_whatsapp_link_prefills_message()
    {
        $link = support_whatsapp_link('Need help with a $250.00 deposit?');
        $this->assertStringStartsWith('https://wa.me/447464483316?text=', $link);
        $this->assertStringContainsString(rawurlencode('Need help with a $250.00 deposit?'), $link);
    }

    public function test_support_whatsapp_number_prefers_per_user_override()
    {
        $user = new \App\Models\User(['support_whatsapp' => '1 (234) 567-8900']);
        $this->assertSame('12345678900', support_whatsapp_number($user));
        $this->assertSame('https://wa.me/12345678900', support_whatsapp_link(null, $user));
    }

    public function test_support_whatsapp_number_falls_back_when_user_has_no_override()
    {
        $user = new \App\Models\User(['support_whatsapp' => null]);
        $this->assertSame('447464483316', support_whatsapp_number($user));
    }

    public function test_support_whatsapp_number_uses_general_setting_before_env()
    {
        app()->instance(
            \App\Providers\Admin\BasicSettingsProvider::class,
            new \App\Providers\Admin\BasicSettingsProvider((object) ['support_whatsapp' => '44 7000 000001'])
        );

        $this->assertSame('447000000001', support_whatsapp_number());
    }

    public function test_support_whatsapp_link_passes_per_user_number_through()
    {
        $user = new \App\Models\User(['support_whatsapp' => '  +44 7700 900123 ']);
        $link = support_whatsapp_link('Deposit help', $user);
        $this->assertStringStartsWith('https://wa.me/447700900123?text=', $link);
        $this->assertStringContainsString(rawurlencode('Deposit help'), $link);
    }

    public function test_deposit_page_renders_help_options_with_per_user_number()
    {
        $user = \App\Models\User::where('email', 'test-one@enzobank.org')->first()
            ?? \App\Models\User::firstOrCreate(['username' => 'test-one'], [
                'firstname' => 'Tester', 'lastname' => 'One', 'email' => 'test-one@enzobank.org',
                'password' => bcrypt('password123'), 'email_verified' => true, 'status' => true,
                'pin_status' => true,
            ]);
        $user->update([
            'kyc_verified' => 1,
            'pin_status' => true,
            'support_whatsapp' => '447700900123',
        ]);

        $response = $this->actingAs($user)->get(route('user.add.money.index'));
        $response->assertStatus(200);
        $response->assertSee('Need help depositing?', false);
        $response->assertSee('Deposit via International Bank Transfer', false);
        $response->assertSee('SWIFT / IBAN', false);
        $response->assertSee('I don&#039;t have a crypto wallet', false);
        // The per-user number must drive the WhatsApp deep link on the page
        $response->assertSee('https://wa.me/447700900123', false);
    }

    public function test_fund_wallet_page_renders_help_options()
    {
        $user = \App\Models\User::where('email', 'test-one@enzobank.org')->first()
            ?? \App\Models\User::firstOrCreate(['username' => 'test-one'], [
                'firstname' => 'Tester', 'lastname' => 'One', 'email' => 'test-one@enzobank.org',
                'password' => bcrypt('password123'), 'email_verified' => true, 'status' => true,
                'pin_status' => true,
            ]);
        $user->update(['kyc_verified' => 1, 'pin_status' => true, 'support_whatsapp' => null]);

        $response = $this->actingAs($user)->get(route('user.crypto.deposit.index'));
        $response->assertStatus(200);
        $response->assertSee('Need help depositing?', false);
        $response->assertSee('Deposit via International Bank Transfer', false);
    }

    public function test_admin_basic_settings_page_renders_general_whatsapp_field()
    {
        $admin = \App\Models\Admin\Admin::where('username', 'admin-test')->first()
            ?? \App\Models\Admin\Admin::firstOrCreate(['username' => 'admin-test'], [
                'firstname' => 'Admin', 'lastname' => 'One', 'email' => 'admin-test@enzobank.org',
                'password' => bcrypt('password123'), 'status' => true,
            ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.web.settings.basic.settings'));
        $response->assertStatus(200);
        $response->assertSee('General WhatsApp Support Number', false);
        $response->assertSee('support_whatsapp', false);
    }
}
