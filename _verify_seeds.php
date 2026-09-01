<?php
$dsn = "pgsql:host=aws-0-eu-west-1.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require";
$pdo = new PDO($dsn, "postgres.wwuiijrkrmlbspglxrel", "Madueke468$");

echo "=== Currencies ===\n";
$stmt = $pdo->query("SELECT id, name, code, symbol FROM currencies ORDER BY id");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "$r[id]. $r[name] ($r[code]) $r[symbol]\n";
}

echo "\n=== Investment Plans ===\n";
$stmt = $pdo->query("SELECT id, name, min_amount, max_amount, roi_percent, duration_days FROM investment_plans ORDER BY id");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "$r[id]. $r[name] min=\$$r[min_amount] max=\$$r[max_amount] roi=$r[roi_percent]% ${r[duration_days]}d\n";
}

echo "\n=== User Wallets ===\n";
$stmt = $pdo->query("SELECT u.username, c.code, uw.balance FROM user_wallets uw JOIN users u ON u.id = uw.user_id JOIN currencies c ON c.id = uw.currency_id ORDER BY u.id, c.id");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "$r[username]: $r[code] = $r[balance]\n";
}
