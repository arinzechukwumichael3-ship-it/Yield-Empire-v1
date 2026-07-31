<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use App\Models\User;
use Tests\TestCase;

class AuthUiRegressionTest extends TestCase
{
    public function test_login_page_emits_non_empty_primary_color()
    {
        // basic_settings is empty in this database: the header must fall back
        // to a real color, otherwise --primary-color: ; hides .btn--base buttons.
        $response = $this->get(route('user.login'));
        $response->assertStatus(200);
        $response->assertSee('--primary-color: #1D4ED8;', false);
        $response->assertDontSee('--primary-color: ;', false);
    }

    public function test_login_page_defaults_theme_to_os_preference()
    {
        // No localStorage in a fresh browser: fall back to the OS setting
        // instead of forcing dark mode (which paints the form navy).
        $response = $this->get(route('user.login'));
        $response->assertStatus(200);
        $response->assertSee("prefers-color-scheme: dark", false);
        $response->assertDontSee("localStorage.getItem('theme') || 'dark'", false);
    }

    public function test_failed_login_error_is_visible_on_login_page()
    {
        $user = User::firstOrCreate(['username' => 'uifailtest'], [
            'firstname' => 'Ui', 'lastname' => 'Fail', 'email' => 'ui-fail-test@enzobank.org',
            'password' => bcrypt('correct-password'), 'email_verified' => true, 'status' => true,
        ]);

        $this->post(route('user.login.submit'), [
            'credentials' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(302);

        $response = $this->get(route('user.login'));
        $response->assertStatus(200);
        $response->assertSee('These credentials do not match our records.', false);
    }

    public function test_admin_login_page_renders_brand_logo()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('enzobank-logo-white.png', false);
        $this->assertFileExists(
            public_path('backend/images/web-settings/image-assets/enzobank-logo-white.png')
        );
    }
}
