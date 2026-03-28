<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportSeeder extends Seeder
{
    
    public function run()
    {
        DB::statement('TRUNCATE TABLE 
            order_items, 
            orders, 
            wallet_transactions,
            wallets,
            books, 
            authors, 
            categories,
            users
            RESTART IDENTITY CASCADE;');

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

        foreach ($tables as $tableName) {

            echo "Ko‘chirilmoqda: $tableName\n";

            $rows = DB::connection('mysql2')->table($tableName)->get();

            foreach ($rows as $row) {
                DB::connection('pgsql')->table($tableName)->insert((array)$row);
            }
        }
    }
}
