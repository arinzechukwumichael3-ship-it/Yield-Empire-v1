<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Check UserAuthorization model ===\n";
$model = new \App\Models\UserAuthorization();
echo "Table: " . $model->getTable() . "\n";
echo "Fillable: " . json_encode($model->getFillable()) . "\n";

echo "\n=== Test code comparison with integer cast ===\n";
$testCode = '569252';
echo "Looking for code='{$testCode}' (string)...\n";
$found = DB::table('user_authorizations')->where('code', $testCode)->first();
echo "String comparison: " . ($found ? "FOUND" : "NOT FOUND") . "\n";

echo "\nLooking for code=569252 (integer)...\n";
$found = DB::table('user_authorizations')->where('code', (int)$testCode)->first();
echo "Integer comparison: " . ($found ? "FOUND" : "NOT FOUND") . "\n";
