<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'free@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Free User',
                'email' => 'free@example.com',
                'role' => 'free',
            ]);
        }

        if (!User::where('email', 'pro@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Pro User',
                'email' => 'pro@example.com',
                'role' => 'pro',
            ]);
        }

        if (!User::where('email', 'premium@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Premium User',
                'email' => 'premium@example.com',
                'role' => 'premium',
            ]);
        }

        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ]);
        }

        // ── Master Data Seeders ──
        $this->call([
            PlantCategorySeeder::class,
            PlantTemplateSeeder::class,
            PlantTemplateOrganismSeeder::class,
            EventTypeCatalogSeeder::class,
            WeatherRuleSeeder::class,
            ActivityWeatherRuleSeeder::class,
            BadgeSeeder::class,
        ]);
    }
}
