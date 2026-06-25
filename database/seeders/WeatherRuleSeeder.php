<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeatherRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rules = [
            [
                'name'          => 'Risiko Penyakit Jamur Tinggi',
                'weather_field' => 'humidity',
                'operator'      => '>',
                'threshold'     => 85.00,
                'severity'      => 'HIGH',
                'message'       => "⚠️ Risiko Penyakit Jamur Tinggi\n\nAlasan:\nKelembapan udara sangat tinggi.\n\nRekomendasi:\nLakukan inspeksi daun dan batang tanaman.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
            [
                'name'          => 'Angin Kencang Terdeteksi',
                'weather_field' => 'wind_speed',
                'operator'      => '>',
                'threshold'     => 30.00,
                'severity'      => 'HIGH',
                'message'       => "⚠️ Angin Kencang Terdeteksi\n\nRekomendasi:\nPeriksa ajir dan penyangga tanaman.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
            [
                'name'          => 'Suhu Tinggi',
                'weather_field' => 'temperature',
                'operator'      => '>',
                'threshold'     => 35.00,
                'severity'      => 'MEDIUM',
                'message'       => "🔥 Suhu Tinggi\n\nRekomendasi:\nTingkatkan frekuensi penyiraman dan pantau kondisi tanaman.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
            [
                'name'          => 'Suhu Rendah',
                'weather_field' => 'temperature',
                'operator'      => '<',
                'threshold'     => 15.00,
                'severity'      => 'MEDIUM',
                'message'       => "🥶 Suhu Rendah\n\nRekomendasi:\nPantau pertumbuhan tanaman dan kemungkinan stres suhu.",
                'is_active'     => true,
                'created_at'    => $now, 'updated_at' => $now,
            ],
        ];

        DB::table('weather_rules')->insert($rules);
    }
}
