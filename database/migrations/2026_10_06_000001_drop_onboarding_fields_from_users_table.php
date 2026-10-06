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
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'gardening_experience')) {
                $columnsToDrop[] = 'gardening_experience';
            }
            if (Schema::hasColumn('users', 'gardening_scale')) {
                $columnsToDrop[] = 'gardening_scale';
            }
            if (Schema::hasColumn('users', 'gardening_goal')) {
                $columnsToDrop[] = 'gardening_goal';
            }
            if (Schema::hasColumn('users', 'onboarding_completed_at')) {
                $columnsToDrop[] = 'onboarding_completed_at';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gardening_experience')->nullable()->after('province');
            $table->string('gardening_scale')->nullable()->after('gardening_experience');
            $table->string('gardening_goal')->nullable()->after('gardening_scale');
            $table->timestamp('onboarding_completed_at')->nullable()->after('gardening_goal');
        });
    }
};
