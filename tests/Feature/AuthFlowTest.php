<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use App\Models\Admin\AdminHasRole;
use App\Models\Admin\AdminRole;
use App\Models\User;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    public function test_user_can_register_with_empty_basic_settings()
    {
        User::where('email', 'authflow-register@enzobank.org')->delete();
        $response = $this->post(route('user.register.submit'), [
            'account_type' => 'personal',
            'firstname' => 'Auth',
            'lastname' => 'Flow',
            'email' => 'authflow-register@enzobank.org',
            'country' => 'United States',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
        $user = User::where('email', 'authflow-register@enzobank.org')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->account_no, 'account number should be generated');
        $this->assertNotNull($user->username, 'username should be generated');
        // Wallets should be created for the fiat currencies present in the DB
        $this->assertTrue($user->wallets()->count() > 0, 'wallets should be created on registration');
    }

    public function test_user_login_works_without_existing_wallets()
    {
        // A user with no wallets must still be able to log in (login refreshes wallets)
        $user = User::where('email', 'authflow-login@enzobank.org')->first();
        if ($user) {
            $user->wallets()->delete();
        } else {
            $user = User::create([
                'username' => 'authflowlogin', 'firstname' => 'Auth', 'lastname' => 'Login',
                'email' => 'authflow-login@enzobank.org',
                'password' => bcrypt('password123'), 'email_verified' => true, 'status' => true,
            ]);
        }

        $response = $this->post(route('user.login.submit'), [
            'credentials' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
        // Wallet refresh on login should have created wallets
        $this->assertTrue($user->wallets()->count() > 0, 'wallets should be created on login');
    }

    public function test_registered_user_can_log_back_in_with_same_password()
    {
        $email = 'authflow-roundtrip@enzobank.org';
        User::where('email', $email)->delete();

        $this->post(route('user.register.submit'), [
            'account_type' => 'personal',
            'firstname' => 'Round',
            'lastname' => 'Trip',
            'email' => $email,
            'country' => 'United States',
            'password' => 'roundtrip123',
            'password_confirmation' => 'roundtrip123',
        ])->assertStatus(302);

        auth('web')->logout();
        $this->assertGuest('web');

        // The same email + password must authenticate after a fresh session
        $response = $this->post(route('user.login.submit'), [
            'credentials' => $email,
            'password' => 'roundtrip123',
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticated('web');
        $this->assertNull(session('errors'), 'login should not fail for a newly registered account');
    }

    public function test_admin_login_redirects_to_dashboard()
    {
        $admin = Admin::where('email', 'authflow-admin@enzobank.org')->first()
            ?? Admin::create([
                'username' => 'authflowadmin', 'firstname' => 'Auth', 'lastname' => 'Admin',
                'email' => 'authflow-admin@enzobank.org',
                'password' => bcrypt('password123'), 'status' => true,
            ]);
        $role = AdminRole::where('name', 'Super Admin')->first();
        if ($role) {
            AdminHasRole::firstOrCreate(
                ['admin_id' => $admin->id, 'admin_role_id' => $role->id],
                ['last_edit_by' => $admin->id]
            );
        }

        $response = $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.dashboard'));
    }
}
