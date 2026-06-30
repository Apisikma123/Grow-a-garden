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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->cascadeOnDelete();
            $table->foreignId('plant_template_id')->constrained('plant_templates')->cascadeOnDelete();
            $table->date('planted_date');
            $table->date('transplant_date')->nullable(); // nullable, reset HST=0
            $table->integer('current_hst')->default(0);  // Hari Setelah Tanam
            $table->enum('stage', [
                'SEED',
                'GERMINATION',
                'SEEDLING',
                'VEGETATIVE',
                'FLOWERING',
                'FRUITING',
                'HARVEST',
                'FINISHED',
                'DEAD',
            ])->default('SEED');
            $table->enum('status', [
                'ACTIVE',
                'PRODUCTIVE',
                'HARVESTING',
                'FINISHED',
                'DEAD',
            ])->default('ACTIVE');
            $table->boolean('multiple_harvest_override')->nullable();
            $table->timestamps();

            $table->index('garden_id');
            $table->index('plant_template_id');
            $table->index('stage');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
