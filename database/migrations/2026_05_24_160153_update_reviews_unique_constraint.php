<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {

            // Hapus unique constraint lama hanya jika memang ada
            $indexes = DB::select("SHOW INDEX FROM reviews WHERE Key_name = 'reviews_user_id_product_id_order_id_unique'");
            if (count($indexes) > 0) {
                $table->dropUnique('reviews_user_id_product_id_order_id_unique');
            }

            // Tambah kolom order_item_id jika belum ada
            if (!Schema::hasColumn('reviews', 'order_item_id')) {
                $table->foreignId('order_item_id')
                    ->nullable()
                    ->after('order_id')
                    ->constrained('order_items')
                    ->nullOnDelete();
            }

            // Tambah unique baru hanya jika belum ada
            $newIndex = DB::select("SHOW INDEX FROM reviews WHERE Key_name = 'reviews_user_order_item_unique'");
            if (count($newIndex) === 0) {
                $table->unique(['user_id', 'order_item_id'], 'reviews_user_order_item_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $index = DB::select("SHOW INDEX FROM reviews WHERE Key_name = 'reviews_user_order_item_unique'");
            if (count($index) > 0) {
                $table->dropUnique('reviews_user_order_item_unique');
            }

            if (Schema::hasColumn('reviews', 'order_item_id')) {
                $table->dropForeign(['order_item_id']);
                $table->dropColumn('order_item_id');
            }
        });
    }
};