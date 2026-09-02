<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== email_verified column type ===\n";
$cols = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_schema='public' AND table_name='users' AND column_name='email_verified'");
foreach ($cols as $c) {
    echo "  {$c->column_name}: {$c->data_type}\n";
}

echo "\n=== Test UPDATE email_verified = true ===\n";
try {
    // Create a test user
    $user = DB::table('users')->insertGetId([
        'account_type' => 'personal',
        'firstname' => 'Test',
        'lastname' => 'User',
        'email' => 'test_verify_' . time() . '@example.com',
        'password' => bcrypt('password'),
        'email_verified' => false,
        'sms_verified' => false,
        'kyc_verified' => 0,
        'username' => 'testverify' . time(),
        'account_no' => '9988776655' . rand(1000,9999),
        'network_bank_name' => 'YieldEmpire',
        'network_account_number' => '9988776655' . rand(1000,9999),
        'network_iban' => 'EZ9988776655' . rand(100000,999999),
        'network_swift' => 'YELDUS33',
        'address' => json_encode(['country' => 'Niger']),
        'company_name' => '',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "  Created user: id={$user}\n";

    // Try to update email_verified to true
    DB::table('users')->where('id', $user)->update(['email_verified' => true]);
    echo "  Updated email_verified to true\n";

    // Check the value
    $updated = DB::table('users')->where('id', $user)->first();
    echo "  email_verified value: " . var_export($updated->email_verified, true) . "\n";

    // Cleanup
    DB::table('users')->where('id', $user)->delete();
    echo "  Cleaned up\n";
} catch (\Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}
