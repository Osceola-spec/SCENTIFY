<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perintah untuk menghapus kolom 'name'
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Fungsi rollback: jika migrasi dibatalkan, kembalikan kolom 'name'
            $table->string('name')->nullable()->after('id');
        });
    }
};