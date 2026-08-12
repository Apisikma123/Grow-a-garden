<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Get today's weather forecast for a specific location.
     * Uses Open-Meteo API (Free, no API key required).
     */
    public function getTodayWeather(float $latitude, float $longitude): ?array
    {
        // Round lat/long to 2 decimal places to increase cache hit rate for nearby locations
        $lat = round($latitude, 2);
        $lng = round($longitude, 2);
        
        $cacheKey = "weather_{$lat}_{$lng}_" . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($lat, $lng) {
            try {
                $response = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'daily' => 'temperature_2m_max,precipitation_probability_max,wind_speed_10m_max,relative_humidity_2m_mean',
                    'timezone' => 'Asia/Jakarta',
                    'forecast_days' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!isset($data['daily'])) {
                        return null;
                    }
                    
                    return [
                        'temperature' => $data['daily']['temperature_2m_max'][0] ?? null,
                        'rain_probability' => $data['daily']['precipitation_probability_max'][0] ?? null,
                        'wind_speed' => $data['daily']['wind_speed_10m_max'][0] ?? null,
                        'humidity' => $data['daily']['relative_humidity_2m_mean'][0] ?? null,
                    ];
                }
                
                Log::error('Weather API failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            } catch (\Exception $e) {
                Log::error('Weather API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Analyze weather parameters using realistic agricultural/farming principles.
     * Returns actionable care guidelines for watering, fertilization, pest control, and plant protection.
     */
    public function analyzeAgronomicConditions(?array $weather): array
    {
        $temp = $weather['temperature'] ?? 29;
        $rainProb = $weather['rain_probability'] ?? 0;
        $windSpeed = $weather['wind_speed'] ?? 10;
        $humidity = $weather['humidity'] ?? 70;

        $guidelines = [
            'status' => 'NORMAL',
            'summary' => 'Kondisi cuaca ideal untuk perawatan harian.',
            'temperature' => $temp,
            'rain_probability' => $rainProb,
            'wind_speed' => $windSpeed,
            'humidity' => $humidity,
            'watering' => [
                'action' => 'NORMAL',
                'title' => 'Siram Standar',
                'time_window' => 'Pagi (07.00 - 09.00) atau Sore (16.00 - 17.30)',
                'advice' => 'Siram 1x sehari di dekat perakaran. Jaga kelembapan tanah tetap konsisten.',
                'badge' => 'Standar 1x/Hari',
                'badge_bg' => 'bg-emerald-100 text-emerald-800'
            ],
            'fertilization' => [
                'allowed' => true,
                'advice' => 'Aman untuk pemupukan rutin (organik/NPK). Aplikasikan pada pagi atau sore hari.',
                'badge' => 'Pemupukan Aman',
                'badge_bg' => 'bg-emerald-100 text-emerald-800'
            ],
            'pest_disease' => [
                'risk_level' => 'LOW',
                'advice' => 'Risiko hama & jamur tergolong rendah. Inspeksi mingguan rutin.',
                'badge' => 'Risiko Low',
                'badge_bg' => 'bg-emerald-100 text-emerald-800'
            ],
            'protection' => [
                'needed' => false,
                'advice' => 'Kondisi angin & paparan sinar matahari stabil.',
                'badge' => 'Lingkungan Aman',
                'badge_bg' => 'bg-emerald-100 text-emerald-800'
            ]
        ];

        // 1. HIGH RAIN / DRIZZLE (Peluang Hujan >= 40% atau Kelembapan >= 85%)
        if ($rainProb >= 40 || $humidity >= 85) {
            $guidelines['status'] = 'RAIN';
            $guidelines['summary'] = "Hujan/Gerimis terdeteksi (Peluang Hujan {$rainProb}%, Kelembapan {$humidity}%).";
            
            // Watering strategy
            $guidelines['watering'] = [
                'action' => 'SKIP_OR_REDUCE',
                'title' => 'Tunda / Kurangi Siram',
                'time_window' => 'Hanya jika media tanam bagian dalam terasa kering',
                'advice' => 'Tunda penyiraman tanaman outdoor untuk mencegah busuk akar (root rot). Cek kelembapan tanah sebelum menyiram.',
                'badge' => 'Hemat Air / Tunda',
                'badge_bg' => 'bg-blue-100 text-blue-800'
            ];

            // Fertilization strategy: DO NOT LIQUID FERTILIZE DURING RAIN
            $guidelines['fertilization'] = [
                'allowed' => false,
                'advice' => 'TUNDA pemupukan cair/daun hari ini! Air hujan akan membilas nutrisi (leaching) sehingga pupuk terbuang sia-sia.',
                'badge' => 'Tunda Pupuk Cair',
                'badge_bg' => 'bg-amber-100 text-amber-800'
            ];

            // Pest & Fungal Risk: High humidity triggers fungal mildew
            $guidelines['pest_disease'] = [
                'risk_level' => 'HIGH_FUNGUS',
                'advice' => 'Waspada Jamur Daun & Antraknosa! Kelembapan tinggi memicu perkembangan jamur. Pangkas daun tua yang menempel ke tanah.',
                'badge' => 'Cek Jamur Daun',
                'badge_bg' => 'bg-purple-100 text-purple-800'
            ];
        } 
        // 2. HIGH TEMPERATURE / EXTREME HEAT (Suhu >= 31°C)
        elseif ($temp >= 31) {
            $guidelines['status'] = 'HEAT';
            $guidelines['summary'] = "Suhu Terik {$temp}°C terdeteksi. Risiko penguapan cepat & daun terbakar.";

            // Watering strategy: DO NOT WATER AT NOON!
            $guidelines['watering'] = [
                'action' => 'INCREASE_SCHEDULED',
                'title' => 'Siram Ekstra Pagi/Sore',
                'time_window' => 'Wajib Pagi (< 08.00) atau Sore (> 16.30). JANGAN siram jam 11.00-14.00!',
                'advice' => 'Penyiraman di siang terik dapat membakar daun (efek lensa air) dan mengejutkan akar (root thermal shock). Tambah siram 50% di sore hari.',
                'badge' => 'Siram Ekstra Pagi/Sore',
                'badge_bg' => 'bg-orange-100 text-orange-800'
            ];

            // Fertilization strategy: CAUTION ON HIGH CONCENTRATION
            $guidelines['fertilization'] = [
                'allowed' => true,
                'advice' => 'Gunakan dosis pupuk encer (1/2 konsentrasi). Jangan pupuk pekat saat suhu panas terik untuk mencegah terbakar (fertilizer burn).',
                'badge' => 'Pupuk Dosis Encer',
                'badge_bg' => 'bg-orange-100 text-orange-800'
            ];

            // Protection strategy: Mulching & Shading
            $guidelines['protection'] = [
                'needed' => true,
                'advice' => 'Berikan mulsa (jerami/daun kering) di atas tanah pot atau lindungi bibit muda dengan peneduh (paranet).',
                'badge' => 'Beri Mulsa / Naungan',
                'badge_bg' => 'bg-amber-100 text-amber-800'
            ];
        }

        // 3. HIGH WIND SPEED (Kecepatan Angin >= 20 km/j)
        if ($windSpeed >= 20) {
            $guidelines['protection'] = [
                'needed' => true,
                'advice' => "Angin kencang ({$windSpeed} km/j) terdeteksi. Pasang/ikat ajir kayu pada tanaman tinggi (cabai/tomat) & amankan pot gantung.",
                'badge' => 'Pasang Ajir / Penyangga',
                'badge_bg' => 'bg-red-100 text-red-800'
            ];
        }

        return $guidelines;
    }
}
