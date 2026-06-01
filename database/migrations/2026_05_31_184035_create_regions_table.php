<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id(); // Sesuai dengan province_id RajaOngkir
            $table->string('name');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id(); // Sesuai dengan city_id RajaOngkir
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('type'); // Kabupaten / Kota
            $table->string('name');
            $table->string('postal_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};