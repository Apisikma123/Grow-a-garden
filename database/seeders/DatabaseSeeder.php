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
        // User::factory(10)->create();

        if (!\App\Models\User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Seed Heirloom Tomato Mastery template
        $tomato = \App\Models\Template::create([
            'name' => 'Heirloom Tomato Mastery',
            'category' => 'Nightshade',
            'duration_min' => 85,
            'duration_max' => 100,
            'image' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=150&h=150&fit=crop',
        ]);

        \App\Models\TemplateStage::create([
            'template_id' => $tomato->id,
            'stage_order' => 1,
            'stage_name' => 'Germination',
            'start_day' => 0,
            'end_day' => 7,
            'icon' => 'eco',
        ]);

        \App\Models\TemplateStage::create([
            'template_id' => $tomato->id,
            'stage_order' => 2,
            'stage_name' => 'Seedling',
            'start_day' => 7,
            'end_day' => 21,
            'icon' => 'psychiatry',
        ]);

        \App\Models\TemplateStage::create([
            'template_id' => $tomato->id,
            'stage_order' => 3,
            'stage_name' => 'Vegetative',
            'start_day' => 21,
            'end_day' => 100,
            'icon' => 'yard',
        ]);

        // Seed Thai Bird's Eye Chili template
        $chili = \App\Models\Template::create([
            'name' => "Thai Bird's Eye Chili",
            'category' => 'Capsicum',
            'duration_min' => 90,
            'duration_max' => 120,
            'image' => 'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=150&h=150&fit=crop',
        ]);

        \App\Models\TemplateStage::create([
            'template_id' => $chili->id,
            'stage_order' => 1,
            'stage_name' => 'Germination',
            'start_day' => 0,
            'end_day' => 10,
            'icon' => 'eco',
        ]);

        \App\Models\TemplateStage::create([
            'template_id' => $chili->id,
            'stage_order' => 2,
            'stage_name' => 'Seedling',
            'start_day' => 10,
            'end_day' => 28,
            'icon' => 'psychiatry',
        ]);

        \App\Models\TemplateStage::create([
            'template_id' => $chili->id,
            'stage_order' => 3,
            'stage_name' => 'Vegetative',
            'start_day' => 28,
            'end_day' => 120,
            'icon' => 'yard',
        ]);
    }
}
