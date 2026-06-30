<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $templates = [
            // ── Sayuran Daun (category_id = 1) ──
            [
                'category_id'        => 1,
                'name_id'            => 'Bayam',
                'scientific_name'    => 'Amaranthus cruentus',
                'family'             => 'Amaranthaceae',
                'germination_day'    => 7, // <10 hari
                'seedling_day'       => 21,
                'vegetative_day'     => 14,
                'flowering_day'      => null,
                'fruiting_day'       => null,
                'harvest_start_day'  => 21,
                'harvest_end_day'    => 28,
                'multiple_harvest'   => false,
                'soil_ph_min'        => 6.0,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 35.0,
                'water_requirement'  => '4 L/m² per hari, 2x sehari (pagi & sore) saat kemarau',
                'sunlight'           => 'Full Sun',
                'recommended_months' => json_encode([1,2,3,4,5,6,7,8,9,10,11,12]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => '4 L/m² per hari untuk bayam muda, 2x sehari pagi dan sore.',
                    'harvest_warning' => 'Masa panen optimal 21-28 hari, cabut sebelum 30 hari untuk menghindari batang keras (over-mature).'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 1,
                'name_id'            => 'Pakcoy',
                'scientific_name'    => 'Brassica rapa var. chinensis',
                'family'             => 'Brassicaceae',
                'germination_day'    => 3,
                'seedling_day'       => 21,
                'vegetative_day'     => 21,
                'flowering_day'      => null,
                'fruiting_day'       => null,
                'harvest_start_day'  => 21,
                'harvest_end_day'    => 25,
                'multiple_harvest'   => false,
                'soil_ph_min'        => 6.0,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 35.0,
                'water_requirement'  => '2x sehari (pagi & sore) saat kemarau',
                'sunlight'           => 'Full Sun to Partial Shade',
                'recommended_months' => json_encode([1,2,3,4,5,6,7,8,9,10,11,12]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => 'Jaga kelembapan, siram 2x sehari pagi dan sore.',
                    'harvest_warning' => 'Masa panen ideal 21-25 HST, hindari melewati 30 HST agar tekstur tidak terlalu berserat.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 1,
                'name_id'            => 'Kangkung Darat',
                'scientific_name'    => 'Ipomoea aquatica',
                'family'             => 'Convolvulaceae',
                'germination_day'    => 7,
                'seedling_day'       => 7,
                'vegetative_day'     => 14,
                'flowering_day'      => null,
                'fruiting_day'       => null,
                'harvest_start_day'  => 25,
                'harvest_end_day'    => 30,
                'multiple_harvest'   => true,
                'soil_ph_min'        => 5.5,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 38.0,
                'water_requirement'  => '2x sehari (pagi & sore) saat kemarau',
                'sunlight'           => 'Full Sun',
                'recommended_months' => json_encode([1,2,3,4,5,6,7,8,9,10,11,12]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => 'Butuh air banyak, siram 2x sehari pagi dan sore hari.',
                    'harvest_warning' => 'Panen dengan cara dipotong sisakan 2-3 cm dari pangkal agar dapat tumbuh kembali.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 1,
                'name_id'            => 'Selada',
                'scientific_name'    => 'Lactuca sativa',
                'family'             => 'Asteraceae',
                'germination_day'    => 3,
                'seedling_day'       => 10,
                'vegetative_day'     => 21,
                'flowering_day'      => null,
                'fruiting_day'       => null,
                'harvest_start_day'  => 60,
                'harvest_end_day'    => 60,
                'multiple_harvest'   => false,
                'soil_ph_min'        => 5.0,
                'soil_ph_max'        => 6.5,
                'max_temperature'    => 30.0,
                'water_requirement'  => 'Rutin, jaga kelembapan tanah',
                'sunlight'           => 'Partial Shade',
                'recommended_months' => json_encode([4,5,6,7,8,9]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => 'Siram rutin untuk menjaga kelembapan tanah, hindari genangan.',
                    'shading' => 'Gunakan paranet atau Partial Shade untuk menghindari bolting di suhu tinggi.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 1,
                'name_id'            => 'Seledri',
                'scientific_name'    => 'Apium graveolens',
                'family'             => 'Apiaceae',
                'germination_day'    => 7,
                'seedling_day'       => 14,
                'vegetative_day'     => 28,
                'flowering_day'      => null,
                'fruiting_day'       => null,
                'harvest_start_day'  => 60,
                'harvest_end_day'    => 75,
                'multiple_harvest'   => true,
                'soil_ph_min'        => 6.0,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 30.0,
                'water_requirement'  => 'Rutin, jaga kelembapan tanah',
                'sunlight'           => 'Partial Shade',
                'recommended_months' => json_encode([4,5,6,7,8,9]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => 'Siram rutin menjaga kelembapan tanah.',
                    'fertilizer' => 'Pupuk cair tinggi nitrogen tiap 10-14 hari.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            // ── Sayuran Buah (category_id = 2) ──
            [
                'category_id'        => 2,
                'name_id'            => 'Cabai Merah',
                'scientific_name'    => 'Capsicum annuum',
                'family'             => 'Solanaceae',
                'germination_day'    => 4,
                'seedling_day'       => 28,
                'vegetative_day'     => 28,
                'flowering_day'      => 45,
                'fruiting_day'       => 55,
                'harvest_start_day'  => 75,
                'harvest_end_day'    => 80,
                'multiple_harvest'   => true,
                'soil_ph_min'        => 5.5,
                'soil_ph_max'        => 6.8,
                'max_temperature'    => 35.0,
                'water_requirement'  => 'Daily during nursery, as needed post-transplant',
                'sunlight'           => 'High/Full',
                'recommended_months' => json_encode([3,4,5,9,10,11]),
                'source_refs'        => json_encode(['SOP Budidaya Cabai Merah (2014)']),
                'care_rules'         => json_encode([
                    'watering' => '150-250 ml/tanaman 2x sehari saat kemarau.',
                    'staking' => 'Pasang ajir (staking) pada umur 7 HST.',
                    'pruning' => 'Perempelan tunas air di bawah cabang Y (umur 15-60 HST).',
                    'fertilizer' => 'Pemupukan susulan 2x seminggu.',
                    'opt' => 'Inspeksi OPT rutin setiap 2-3 hari.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 2,
                'name_id'            => 'Cabai Rawit',
                'scientific_name'    => 'Capsicum frutescens',
                'family'             => 'Solanaceae',
                'germination_day'    => 5,
                'seedling_day'       => 45,
                'vegetative_day'     => 45,
                'flowering_day'      => 55,
                'fruiting_day'       => 65,
                'harvest_start_day'  => 75,
                'harvest_end_day'    => 80,
                'multiple_harvest'   => true,
                'soil_ph_min'        => 6.0,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 35.0,
                'water_requirement'  => '150-250 ml/tanaman 2x sehari saat kering',
                'sunlight'           => 'High/Full',
                'recommended_months' => json_encode([3,4,5,9,10,11]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => '150-250 ml/tanaman 2x sehari saat kering.',
                    'staking' => 'Pasang ajir (staking) pada umur 7 HST.',
                    'pruning' => 'Perempelan (pruning) tunas air di bawah cabang Y (umur 15-60 HST).',
                    'fertilizer' => 'Pemupukan susulan 2x seminggu.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 2,
                'name_id'            => 'Terung',
                'scientific_name'    => 'Solanum melongena',
                'family'             => 'Solanaceae',
                'germination_day'    => 3,
                'seedling_day'       => 35, // average of 25-42
                'vegetative_day'     => 42,
                'flowering_day'      => 50,
                'fruiting_day'       => 55,
                'harvest_start_day'  => 45,
                'harvest_end_day'    => 45,
                'multiple_harvest'   => true,
                'soil_ph_min'        => 6.0,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 35.0,
                'water_requirement'  => 'Rutin, sesuai kebutuhan setelah transplantasi',
                'sunlight'           => 'High/Full',
                'recommended_months' => json_encode([3,4,5,9,10,11]),
                'source_refs'        => json_encode(['Teknik Sederhana Budidaya Terong (2021)']),
                'care_rules'         => json_encode([
                    'watering' => 'Siram rutin, pastikan tanah tidak kekeringan.',
                    'staking' => 'Pasang ajir (staking) pada umur 7-14 HST.',
                    'pruning' => 'Perempelan tunas air di bawah bunga pertama.',
                    'fertilizer' => 'Pemupukan susulan NPK 2 minggu sekali.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id'        => 2,
                'name_id'            => 'Tomat',
                'scientific_name'    => 'Solanum lycopersicum',
                'family'             => 'Solanaceae',
                'germination_day'    => 4,
                'seedling_day'       => 21,
                'vegetative_day'     => 35,
                'flowering_day'      => 45,
                'fruiting_day'       => 55,
                'harvest_start_day'  => 65,
                'harvest_end_day'    => 80,
                'multiple_harvest'   => true,
                'soil_ph_min'        => 5.5,
                'soil_ph_max'        => 7.0,
                'max_temperature'    => 35.0,
                'water_requirement'  => 'Harian saat persemaian, sesuai kebutuhan setelah transplantasi',
                'sunlight'           => 'High/Full',
                'recommended_months' => json_encode([4,5,6,7,8]),
                'source_refs'        => json_encode(['Comparative Agronomic Database (2026)']),
                'care_rules'         => json_encode([
                    'watering' => 'Penyiraman teratur, hindari penyiraman dari atas daun langsung.',
                    'staking' => 'Wajib pasang lanjaran (staking) untuk menopang buah.',
                    'pruning' => 'Pemangkasan tunas liar untuk fokuskan nutrisi ke buah utama.'
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        DB::table('plant_templates')->insert($templates);
    }
}
