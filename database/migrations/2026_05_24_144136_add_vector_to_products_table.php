<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Cek apakah kolom search_context belum ada
            if (!Schema::hasColumn('products', 'search_context')) {
                $table->text('search_context')->nullable();
            }
            
            // Cek apakah kolom embedding belum ada
            if (!Schema::hasColumn('products', 'embedding')) {
                $table->json('embedding')->nullable(); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['search_context', 'embedding']);
        });
    }
};