<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Verify both column types work with integer 1 ===\n\n";

// 1. strowallet_virtual_cards (SMALLINT) - was failing with "true" string
echo "1. strowallet_virtual_cards is_default (smallint): ";
try { \App\Models\StrowalletVirtualCard::where("is_default", true)->limit(1)->value("id"); echo "OK (passed)\n"; }
catch(Exception $e) { echo "FAIL: " . substr($e->getMessage(), 0, 80) . "\n"; }

// 2. investment_plans (REAL BOOLEAN) - needs to keep working
echo "2. investment_plans is_active (real boolean): ";
try { \App\Models\InvestmentPlan::where("is_active", true)->limit(1)->value("id"); echo "OK (passed)\n"; }
catch(Exception $e) { echo "FAIL: " . substr($e->getMessage(), 0, 80) . "\n"; }

// 3. admins status (SMALLINT) - existing test
echo "3. admins status (smallint): ";
try { \App\Models\Admin\Admin::where("status", true)->limit(1)->value("id"); echo "OK (passed)\n"; }
catch(Exception $e) { echo "FAIL: " . substr($e->getMessage(), 0, 80) . "\n"; }

// 4. users email_verified (SMALLINT)
echo "4. users email_verified (smallint): ";
try { \App\Models\User::where("email_verified", true)->limit(1)->value("id"); echo "OK (passed)\n"; }
catch(Exception $e) { echo "FAIL: " . substr($e->getMessage(), 0, 80) . "\n"; }

echo "\n=== Done ===\n";
