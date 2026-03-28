<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('books')) {
            DB::statement('UPDATE books SET price = ROUND(price)');
            DB::statement('UPDATE books SET discount_price = ROUND(discount_price) WHERE discount_price IS NOT NULL');
            DB::statement('ALTER TABLE books MODIFY price BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE books MODIFY discount_price BIGINT UNSIGNED NULL');
        }

        if (Schema::hasTable('orders')) {
            DB::statement('UPDATE orders SET total_price = ROUND(total_price)');
            DB::statement('ALTER TABLE orders MODIFY total_price BIGINT UNSIGNED NOT NULL');
        }

        if (Schema::hasTable('order_items')) {
            DB::statement('UPDATE order_items SET price = ROUND(price)');
            DB::statement('ALTER TABLE order_items MODIFY price BIGINT UNSIGNED NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('books')) {
            DB::statement('ALTER TABLE books MODIFY price DECIMAL(10,2) NOT NULL');
            DB::statement('ALTER TABLE books MODIFY discount_price DECIMAL(10,2) NULL');
        }

        if (Schema::hasTable('orders')) {
            DB::statement('ALTER TABLE orders MODIFY total_price DECIMAL(10,2) NOT NULL');
        }

        if (Schema::hasTable('order_items')) {
            DB::statement('ALTER TABLE order_items MODIFY price DECIMAL(10,2) NOT NULL');
        }
    }
};
