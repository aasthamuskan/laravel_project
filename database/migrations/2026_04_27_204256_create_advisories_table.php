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
        Schema::create('advisories', function (Blueprint $table) {
            $table->id();
            // FK to crops — cascades on delete
            $table->foreignId('crop_id')->constrained()->cascadeOnDelete();
            $table->string('season');           // Spring, Summer, Monsoon, Winter
            $table->string('weather_condition'); // Clear, Cloudy, Rainy, Stormy, Cold
            $table->text('advice');
            $table->timestamps();

            // Composite index for fast advisory matching queries
            $table->index(['crop_id', 'season', 'weather_condition'], 'advisory_lookup_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisories');
    }
};
