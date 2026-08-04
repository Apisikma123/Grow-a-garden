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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->cascadeOnDelete();
            $table->string('activity_type');      // watering / fertilizing / pruning / harvest
            $table->date('scheduled_date');
            $table->timestamp('performed_at')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'MISSED', 'SKIPPED'])->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('plant_id');
            $table->index('activity_type');
            $table->index('scheduled_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
