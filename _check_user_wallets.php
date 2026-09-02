<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Check user wallets ===\n";
$users = DB::table('users')->get();
foreach ($users as $user) {
    echo "User: {$user->email}\n";
    $wallets = DB::table('user_wallets')
        ->join('currencies', 'user_wallets.currency_id', '=', 'currencies.id')
        ->where('user_wallets.user_id', $user->id)
        ->select('currencies.code', 'currencies.name', 'user_wallets.balance')
        ->get();
    foreach ($wallets as $w) {
        echo "  {$w->code} - {$w->name}: {$w->balance}\n";
    }
    if ($wallets->isEmpty()) {
        echo "  (no wallets)\n";
    }
    echo "\n";
}
