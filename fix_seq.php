<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = [
    'categories',
    'authors',
    'users',
    'wallets',
    'books',
    'orders',
    'order_items',
    'wallet_transactions',
];

foreach ($tables as $t) {
    try {
        $max = DB::table($t)->max('id');
        if ($max) {
            DB::statement("SELECT setval(pg_get_serial_sequence('$t', 'id'), $max)");
            echo "Updated sequence for $t to $max\n";
        }
    } catch (\Exception $e) {
        echo "Error on $t: " . $e->getMessage() . "\n";
    }
}
