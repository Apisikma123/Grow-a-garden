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
        Schema::create('activity_weather_rules', function (Blueprint $table) {
            $table->id();
            $table->string('activity_type');       // fertilizing / spraying / watering / harvest
            $table->string('weather_field');        // rain_probability
            $table->enum('operator', ['>', '<', '>=', '<=', '==', '!=']);
            $table->decimal('threshold', 8, 2);
            $table->string('action');               // DITUNDA / TIDAK_DISARANKAN
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('activity_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_weather_rules');
    }
};
