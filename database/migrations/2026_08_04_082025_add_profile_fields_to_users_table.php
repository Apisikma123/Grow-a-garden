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
            $table->string('avatar')->nullable()->after('phone');
            $table->string('province')->nullable()->after('avatar');
            $table->string('language')->default('id')->after('province');
            $table->boolean('email_notifications')->default(true)->after('language');
            $table->boolean('push_notifications')->default(true)->after('email_notifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'province', 'language', 'email_notifications', 'push_notifications']);
        });
    }
};
