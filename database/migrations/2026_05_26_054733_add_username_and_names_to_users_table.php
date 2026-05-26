<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    // Langkah 1: Buat kolom HANYA JIKA kolom tersebut belum ada di database
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'username')) {
            $table->string('username', 20)->nullable()->after('id');
        }
        if (!Schema::hasColumn('users', 'first_name')) {
            $table->string('first_name', 50)->nullable()->after('username');
        }
        if (!Schema::hasColumn('users', 'last_name')) {
            $table->string('last_name', 50)->nullable()->after('first_name');
        }
    });

    // Langkah 2: Isi data user lama secara otomatis (ambil dari potongan email atau id)
    $users = DB::table('users')->get();
    foreach ($users as $user) {
        $temporaryUsername = 'user_' . $user->id; 
        
        $nameParts = explode(' ', $user->name ?? 'User');
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : null;

        DB::table('users')->where('id', $user->id)->update([
            // Jika username saat ini masih kosong/null, isi dengan username sementara
            'username'   => $user->username ?: $temporaryUsername,
            'first_name' => $user->first_name ?: $firstName,
            'last_name'  => $user->last_name ?: $lastName,
        ]);
    }

    // Langkah 3: Amankan struktur kolom menjadi tidak nullable (required) dan jadikan UNIQUE
    Schema::table('users', function (Blueprint $table) {
        $table->string('username', 20)->unique()->nullable(false)->change();
        $table->string('first_name', 50)->nullable(false)->change();
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'first_name', 'last_name']);
        });
    }
};