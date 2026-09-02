<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Notifications\User\Auth\SendAuthorizationCode;

echo "=== TEST OTP EMAIL DELIVERY ===\n\n";

// Simulate the registration flow exactly
$user = new \App\Models\User();
$user->id = 888;
$user->firstname = 'OTP';
$user->lastname = 'Test';
$user->email = 'testotp_' . time() . '@yopmail.com'; // YOPmail for testing

$data = (object) [
    'user_id'    => $user->id,
    'code'       => generate_random_code(),
    'token'      => generate_unique_string('user_authorizations', 'token', 200),
    'created_at' => now(),
];

echo "Email: {$user->email}\n";
echo "Code: {$data->code}\n";
echo "Token: {$data->token}\n\n";

try {
    // Test if the notification itself can build without errors
    $notification = new SendAuthorizationCode($data);
    echo "✓ Notification created\n";
    
    // Test building the mail message
    $mailMessage = $notification->toMail($user);
    echo "✓ Mail message built: " . $mailMessage->subject . "\n";
    
    // Test signed URL generation
    $signedUrl = URL::temporarySignedRoute(
        'email.unsubscribe',
        now()->addDays(60),
        ['email' => $user->email, 'id' => $user->id]
    );
    echo "✓ Signed URL generated: " . substr($signedUrl, 0, 80) . "...\n";
    
    // Actually send the notification
    Mail::raw('Test email body', function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Test from YieldEmpire');
    });
    echo "✓ Raw mail sent!\n";
    
    // Send the notification
    $user->notify($notification);
    echo "✓ Notification sent!\n";
    
} catch (\Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
