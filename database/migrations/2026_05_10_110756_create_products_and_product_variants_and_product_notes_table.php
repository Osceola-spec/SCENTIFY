<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['Designer', 'Niche', 'Local']);
            $table->enum('gender_type', ['Men', 'Women', 'Unisex']);
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->boolean('is_new_arrival')->default(false);
            $table->integer('discount_percent')->default(0);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size'); // e.g., '50ml', '100ml'
            $table->unsignedBigInteger('price'); // Menggunakan integer untuk Rupiah
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        Schema::create('product_notes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scent_note_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'scent_note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_notes');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};