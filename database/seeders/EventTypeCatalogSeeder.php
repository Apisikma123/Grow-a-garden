<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypeCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $types = [
            // LIFECYCLE
            ['code' => 'PLANTING',         'label' => 'Penanaman',          'category' => 'LIFECYCLE', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'GERMINATION',      'label' => 'Germinasi',          'category' => 'LIFECYCLE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SEEDLING',         'label' => 'Pembibitan',         'category' => 'LIFECYCLE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'TRANSPLANT',       'label' => 'Transplantasi',      'category' => 'LIFECYCLE', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'VEGETATIVE',       'label' => 'Fase Vegetatif',     'category' => 'LIFECYCLE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'FLOWERING',        'label' => 'Pembungaan',         'category' => 'LIFECYCLE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'FRUITING',         'label' => 'Pembuahan',          'category' => 'LIFECYCLE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            // HARVEST
            ['code' => 'HARVEST_READY',    'label' => 'Siap Panen',         'category' => 'HARVEST',   'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            // MAINTENANCE
            ['code' => 'WATERING_REMINDER',    'label' => 'Pengingat Penyiraman',  'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'FERTILIZER_REMINDER',  'label' => 'Pengingat Pemupukan',   'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PEST_INSPECTION',      'label' => 'Inspeksi Hama',         'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PRUNING',              'label' => 'Perempelan',            'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'STAKING',              'label' => 'Pemasangan Ajir',       'category' => 'MAINTENANCE', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'REPLANT',              'label' => 'Tanam Ulang',           'category' => 'MAINTENANCE', 'default_priority' => 'LOW',    'created_at' => $now, 'updated_at' => $now],
            // WARNING
            ['code' => 'LATE_HARVEST_WARNING',      'label' => 'Peringatan Panen Terlambat',  'category' => 'WARNING', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MISSED_WATERING_WARNING',   'label' => 'Peringatan Siram Terlewat',   'category' => 'WARNING', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MISSED_FERTILIZER_WARNING', 'label' => 'Peringatan Pupuk Terlewat',   'category' => 'WARNING', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'HEAT_WARNING',              'label' => 'Peringatan Suhu Tinggi',      'category' => 'WARNING', 'default_priority' => 'MEDIUM', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'HEAVY_RAIN_WARNING',        'label' => 'Peringatan Hujan Lebat',      'category' => 'WARNING', 'default_priority' => 'HIGH',   'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('event_type_catalog')->insert($types);
    }
}
