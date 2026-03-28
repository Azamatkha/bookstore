<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'is_admin')) {
                    $table->boolean('is_admin')->default(false);
                }
            });

            if (Schema::hasColumn('users', 'role')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('role');
                });
            }
        }

        if (Schema::hasTable('books')) {
            $duplicateSlugs = DB::table('books')
                ->select('id', 'slug')
                ->orderBy('id')
                ->get()
                ->groupBy('slug')
                ->filter(fn ($group) => $group->count() > 1);

            foreach ($duplicateSlugs as $slug => $records) {
                foreach ($records->slice(1) as $record) {
                    DB::table('books')
                        ->where('id', $record->id)
                        ->update(['slug' => "{$slug}-{$record->id}"]);
                }
            }

            Schema::table('books', function (Blueprint $table) {
                if (! Schema::hasColumn('books', 'rating')) {
                    $table->decimal('rating', 3, 1)->default(4.5);
                }
            });

            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE books MODIFY price BIGINT UNSIGNED NOT NULL');
                DB::statement('ALTER TABLE books MODIFY discount_price BIGINT UNSIGNED NULL');
                DB::statement('ALTER TABLE books MODIFY stock INT UNSIGNED NOT NULL DEFAULT 0');
            }

            Schema::table('books', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('books') && Schema::hasColumn('books', 'rating')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('rating');
            });
        }
    }
};
