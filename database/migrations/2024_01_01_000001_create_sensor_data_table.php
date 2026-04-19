<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->float('temperature');
            $table->float('humidity_air');
            $table->float('humidity_soil');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sensor_data');
    }
};