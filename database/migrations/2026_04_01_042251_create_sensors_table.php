<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('sensors', function (Blueprint $table) {
        $table->id();
        $table->string('nama_sensor');      // contoh: suhu, kelembaban
        $table->float('nilai');             // nilai sensor
        $table->string('satuan');           // °C, %, dll
        $table->timestamps();
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
