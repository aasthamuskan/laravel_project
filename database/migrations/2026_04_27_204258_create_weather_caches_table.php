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
        Schema::create('weather_caches', function (Blueprint $table) {
            $table->id();
            // Indexed city column — enables fast lookups and fallback reads
            $table->string('city')->index()->unique();
            // Store full API response as JSON text
            $table->longText('data_json');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_caches');
    }
};
