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
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'super_admin') NOT NULL DEFAULT 'user'");
        } catch (\Throwable $e) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->change();
            });
        }

        // Assign super_admin role to admin@admin.com
        DB::table('users')->where('email', 'admin@admin.com')->update([
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
        } catch (\Throwable $e) {
            //
        }
    }
};
