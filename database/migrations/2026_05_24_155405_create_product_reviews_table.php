<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel reviews belum ada, buat baru
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->tinyInteger('rating'); // 1-5
                $table->string('title', 100)->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();

                // Satu user hanya bisa review satu produk per order
                $table->unique(['user_id', 'product_id', 'order_id']);
            });
        } else {
            // Jika sudah ada, tambah kolom yang kurang
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'order_id')) {
                    $table->foreignId('order_id')->after('product_id')->constrained()->cascadeOnDelete();
                }
                if (!Schema::hasColumn('reviews', 'title')) {
                    $table->string('title', 100)->nullable()->after('order_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};