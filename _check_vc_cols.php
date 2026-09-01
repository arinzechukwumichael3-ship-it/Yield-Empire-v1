<?php
$pdo = new PDO("pgsql:host=aws-0-eu-west-1.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require", "postgres.wwuiijrkrmlbspglxrel", "Madueke468$", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->query("SELECT column_name, data_type, udt_name FROM information_schema.columns WHERE table_name = 'strowallet_virtual_cards' ORDER BY ordinal_position");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['column_name']} => {$row['data_type']} ({$row['udt_name']})\n";
}
