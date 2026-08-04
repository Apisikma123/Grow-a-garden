<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityWeatherRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rules = [
            [
                'activity_type' => 'spraying',
                'weather_field' => 'rain_probability',
                'operator'      => '>',
                'threshold'     => 50.00,
                'action'        => 'TIDAK_DISARANKAN',
                'message'       => "⚠️ Penyemprotan Tidak Disarankan\n\nAlasan:\nHujan diperkirakan turun.\n\nDampak:\nPestisida dapat tercuci sehingga efektivitas menurun.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
            [
                'activity_type' => 'fertilizing',
                'weather_field' => 'rain_probability',
                'operator'      => '>',
                'threshold'     => 70.00,
                'action'        => 'DITUNDA',
                'message'       => "⚠️ Pemupukan Ditunda\n\nAlasan:\nPotensi hujan tinggi.\n\nDampak:\nPupuk dapat larut dan terbawa air hujan.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
            [
                'activity_type' => 'watering',
                'weather_field' => 'rain_probability',
                'operator'      => '>',
                'threshold'     => 60.00,
                'action'        => 'TIDAK_DIPERLUKAN',
                'message'       => "🌧 Penyiraman Tidak Diperlukan\n\nAlasan:\nCurah hujan diperkirakan mencukupi kebutuhan air tanaman.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
            [
                'activity_type' => 'harvest',
                'weather_field' => 'rain_probability',
                'operator'      => '>',
                'threshold'     => 80.00,
                'action'        => 'TIDAK_DISARANKAN',
                'message'       => "⚠️ Cuaca Tidak Ideal untuk Panen\n\nAlasan:\nPrediksi hujan sangat tinggi.\n\nRekomendasi:\nPertimbangkan percepatan panen jika tanaman sudah siap.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
        ];

        DB::table('activity_weather_rules')->insert($rules);
    }
}
