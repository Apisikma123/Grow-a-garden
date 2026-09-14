<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop foreign key and tables
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('subscriptions');

        // 2. Normalize user roles to 'user' or 'admin'
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('free', 'pro', 'premium', 'admin', 'user') NOT NULL DEFAULT 'user'");
            DB::table('users')->where('role', '!=', 'admin')->update(['role' => 'user']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
        } catch (\Throwable $e) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->change();
            });
            DB::table('users')->where('role', '!=', 'admin')->update(['role' => 'user']);
        }

        // 3. Remove subscription-related badges and their user assignments
        $badgeNames = [
            'Sang Pro',
            'Kebun Profesional',
            'Pekebun Panen Raya',
            'Elite Grower',
        ];

        $badgeIds = DB::table('badges')->whereIn('name', $badgeNames)->pluck('id')->toArray();

        if (!empty($badgeIds)) {
            DB::table('user_badges')->whereIn('badge_id', $badgeIds)->delete();
            DB::table('badges')->whereIn('id', $badgeIds)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
