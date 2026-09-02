<?php
require __DIR__ . \"/vendor/autoload.php\";
$app = require __DIR__ . \"/bootstrap/app.php\";
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

use Illuminate\\Support\\Facades\\Mail;

echo \"=== TESTING ACTUAL EMAIL DELIVERY ===\" . PHP_EOL;
echo \"MAIL_MAILER: \" . config(\"mail.default\") . PHP_EOL;
echo \"MAIL_HOST: \" . config(\"mail.mailers.smtp.host\") . PHP_EOL;
echo \"MAIL_PORT: \" . config(\"mail.mailers.smtp.port\") . PHP_EOL;
echo \"MAIL_USERNAME: \" . config(\"mail.mailers.smtp.username\") . PHP_EOL;
echo \"MAIL_PASSWORD: \" . substr(config(\"mail.mailers.smtp.password\"), 0, 8) . \"...\" . PHP_EOL;
echo \"MAIL_ENCRYPTION: \" . config(\"mail.mailers.smtp.encryption\") . PHP_EOL;
echo \"MAIL_FROM_ADDRESS: \" . config(\"mail.from.address\") . PHP_EOL;
echo \"MAIL_FROM_NAME: \" . config(\"mail.from.name\") . PHP_EOL;
echo PHP_EOL;

// Test with a real email
$testEmail = \"arinzechukwumichael3@gmail.com\";
echo \"Sending test OTP to: {$testEmail}\" . PHP_EOL;

try {
    Mail::raw(\"Your YieldEmpire verification code is: 123456\", function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject(\"Your YieldEmpire verification code\");
    });
    echo \"✓ Mail facade returned success!\" . PHP_EOL;
} catch (\\Throwable $e) {
    echo \"✗ ERROR: \" . $e->getMessage() . PHP_EOL;
}
