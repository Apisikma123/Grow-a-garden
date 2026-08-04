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
        Schema::create('plant_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('plant_categories')->cascadeOnDelete();
            $table->string('name_id');            // Cabai Merah, Bayam, dll
            $table->string('scientific_name');     // Capsicum annuum
            $table->string('family')->nullable();  // Solanaceae — dipakai rule Pruning
            $table->integer('germination_day')->nullable();
            $table->integer('seedling_day')->nullable();
            $table->integer('vegetative_day')->nullable();
            $table->integer('flowering_day')->nullable();   // nullable: sayuran daun tidak berbunga
            $table->integer('fruiting_day')->nullable();    // nullable: sayuran daun tidak berbuah
            $table->integer('harvest_start_day');
            $table->integer('harvest_end_day');
            $table->boolean('multiple_harvest')->default(false);
            $table->decimal('soil_ph_min', 3, 1);
            $table->decimal('soil_ph_max', 3, 1);
            $table->decimal('max_temperature', 4, 1)->nullable(); // untuk HEAT_WARNING
            $table->string('water_requirement')->nullable();
            $table->string('sunlight')->nullable();
            $table->json('recommended_months')->nullable(); // untuk Seasonal Rules
            $table->json('source_refs')->nullable();        // sitasi SOP/Balitsa dll
            $table->timestamps();

            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_templates');
    }
};
