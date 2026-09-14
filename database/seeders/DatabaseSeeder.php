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
        if (!User::where('email', 'user@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Demo User',
                'email' => 'user@example.com',
                'role' => 'user',
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
