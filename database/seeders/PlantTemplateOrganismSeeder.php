<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantTemplateOrganismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $templateIds = DB::table('plant_templates')
            ->pluck('id', 'name_id')
            ->toArray();

        $organisms = [
            // Bayam
            ['plant_template_id' => $templateIds['Bayam'], 'name' => 'Ulat Daun',     'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Bayam'], 'name' => 'Pengorok Daun', 'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            // Pakcoy
            ['plant_template_id' => $templateIds['Pakcoy'], 'name' => 'Ulat Tritip',    'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Pakcoy'], 'name' => 'Kutu Daun',      'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Pakcoy'], 'name' => 'Akar Gada',      'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            // Kangkung Darat
            ['plant_template_id' => $templateIds['Kangkung Darat'], 'name' => 'Karat Putih', 'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Kangkung Darat'], 'name' => 'Ulat Daun',   'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            // Selada
            ['plant_template_id' => $templateIds['Selada'], 'name' => 'Kutu Daun',      'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Selada'], 'name' => 'Busuk Daun',     'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            // Seledri
            ['plant_template_id' => $templateIds['Seledri'], 'name' => 'Bercak Daun',    'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Seledri'], 'name' => 'Kutu Daun',      'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            // Cabai Merah
            ['plant_template_id' => $templateIds['Cabai Merah'], 'name' => 'Kutu Daun (Aphids)', 'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Merah'], 'name' => 'Thrips',              'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Merah'], 'name' => 'Ulat Grayak',         'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Merah'], 'name' => 'Antraknosa',           'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Merah'], 'name' => 'Virus Kuning',         'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Merah'], 'name' => 'Layu Bakteri',         'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            // Cabai Rawit
            ['plant_template_id' => $templateIds['Cabai Rawit'], 'name' => 'Kutu Daun (Aphids)', 'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Rawit'], 'name' => 'Thrips',              'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Cabai Rawit'], 'name' => 'Antraknosa',           'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            // Terung
            ['plant_template_id' => $templateIds['Terung'], 'name' => 'Kutu Kebul',       'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Terung'], 'name' => 'Penggerek Buah',   'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Terung'], 'name' => 'Layu Bakteri',     'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Terung'], 'name' => 'Busuk Buah',       'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            // Tomat
            ['plant_template_id' => $templateIds['Tomat'], 'name' => 'Kutu Kebul',                'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Tomat'], 'name' => 'Lalat Buah',                'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Tomat'], 'name' => 'Ulat Buah',                 'type' => 'PEST',    'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Tomat'], 'name' => 'Layu Fusarium',             'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
            ['plant_template_id' => $templateIds['Tomat'], 'name' => 'Busuk Buah (Phytophthora)', 'type' => 'DISEASE', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('plant_template_organisms')->insert($organisms);
    }
}
