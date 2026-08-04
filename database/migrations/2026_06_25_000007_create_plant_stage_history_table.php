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
        Schema::create('plant_stage_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->cascadeOnDelete();
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
            ]);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            $table->index('plant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_stage_history');
    }
};
