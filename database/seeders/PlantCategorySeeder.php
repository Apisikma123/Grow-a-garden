<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plant_categories')->insert([
            ['id' => 1, 'name' => 'Sayuran Daun', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Sayuran Buah', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
