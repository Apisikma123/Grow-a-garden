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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->cascadeOnDelete();
            $table->foreignId('event_type_id')->constrained('event_type_catalog')->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->enum('status', ['PENDING', 'COMPLETED', 'MISSED', 'SKIPPED'])->default('PENDING');
            $table->enum('priority', ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW'])->nullable(); // override default jika perlu
            $table->string('message')->nullable();
            $table->integer('missed_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('plant_id');
            $table->index('event_type_id');
            $table->index('scheduled_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
