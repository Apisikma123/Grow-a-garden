<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Get today's weather forecast for a specific location.
     * Uses Open-Meteo API with real-time current & 6-hour hourly precision.
     */
    public function getTodayWeather(float $latitude, float $longitude): ?array
    {
        // Round lat/long to 2 decimal places to increase cache hit rate for nearby locations
        $lat = round($latitude, 2);
        $lng = round($longitude, 2);
        
        // Cache per hour for 15 minutes to guarantee fresh precision weather updates
        $cacheKey = "weather_v4_{$lat}_{$lng}_" . now()->format('Y-m-d_H_i');
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($lat, $lng) {
            try {
                $response = Http::withoutVerifying()->timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,showers,weather_code,cloud_cover,wind_speed_10m',
                    'hourly' => 'temperature_2m,relative_humidity_2m,precipitation_probability,precipitation,weather_code,wind_speed_10m',
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max',
                    'timezone' => 'auto',
                    'forecast_days' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    $current = $data['current'] ?? [];
                    $hourly = $data['hourly'] ?? [];
                    $daily = $data['daily'] ?? [];

                    $currentCode = $current['weather_code'] ?? ($daily['weather_code'][0] ?? 0);
                    $currentTemp = $current['temperature_2m'] ?? ($daily['temperature_2m_max'][0] ?? 29);
                    $apparentTemp = $current['apparent_temperature'] ?? $currentTemp;
                    $currentHumidity = $current['relative_humidity_2m'] ?? ($daily['relative_humidity_2m_mean'][0] ?? 70);
                    $currentWind = $current['wind_speed_10m'] ?? 10;
                    $currentPrecip = $current['precipitation'] ?? 0;

                    // Get current real-time hour precipitation probability
                    $currentHour = (int) now()->format('H');
                    $hourlyProbs = $hourly['precipitation_probability'] ?? [];
                    $currentRainProb = isset($hourlyProbs[$currentHour]) ? (int) $hourlyProbs[$currentHour] : (int) ($daily['precipitation_probability_max'][0] ?? 0);
                    $rainProb24h = (int) ($daily['precipitation_probability_max'][0] ?? 0);

                    return [
                        'weather_code' => (int) $currentCode,
                        'temperature' => (float) $currentTemp,
                        'apparent_temperature' => (float) $apparentTemp,
                        'rain_probability' => (int) $currentRainProb,
                        'rain_probability_24h' => (int) $rainProb24h,
                        'wind_speed' => (float) $currentWind,
                        'humidity' => (int) $currentHumidity,
                        'precipitation' => (float) $currentPrecip,
                        'is_day' => (int) ($current['is_day'] ?? 1),
                        'cloud_cover' => (int) ($current['cloud_cover'] ?? 0),
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
     * Evaluates Smart Irrigation decision based on strict real-time current conditions:
     * 1. CURRENT RAIN / HEAVY_RAIN / THUNDERSTORM -> SKIP
     * 2. RECENT HEAVY RAIN TODAY -> SKIP
     * 3. CURRENT RAIN PROBABILITY >= 70% -> SKIP
     * 4. CURRENT RAIN PROBABILITY 50% - 69% -> REDUCE
     * 5. TEMPERATURE >= 33°C (without rain) -> NORMAL_PLUS
     * 6. DRIZZLE -> REDUCE
     * 7. FOG -> REDUCE
     * 8. DEFAULT (CLEAR, PARTLY_CLOUDY, CLOUDY) -> NORMAL
     */
    public function analyzeAgronomicConditions(?array $weather, bool $hasRecentRain = false): array
    {
        $temp = $weather['temperature'] ?? 29;
        $rainProb = $weather['rain_probability'] ?? 0;
        $windSpeed = $weather['wind_speed'] ?? 10;
        $humidity = $weather['humidity'] ?? 70;
        $code = $weather['weather_code'] ?? 0;
        $precip = $weather['precipitation'] ?? 0;

        // 8 Weather Condition Spectrum Categories
        $conditionCategory = 'PARTLY_CLOUDY';
        $title = 'Cerah Berawan';
        $icon = 'partly_cloudy_day';
        $badgeBg = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
        $summary = 'Cuaca sejuk & sinar matahari cukup. Kondisi ideal untuk pertumbuhan tanaman.';

        if (in_array($code, [95, 96, 99])) {
            $conditionCategory = 'THUNDERSTORM';
            $title = 'Hujan Petir';
            $icon = 'thunderstorm';
            $badgeBg = 'bg-purple-100 text-purple-900 border border-purple-300';
            $summary = "WASPADA Hujan Badai & Angin Kencang ({$windSpeed} km/j)! Amankan pot gantung dan bibit muda.";
        } elseif (in_array($code, [65, 82]) || $precip > 5.0) {
            $conditionCategory = 'HEAVY_RAIN';
            $title = 'Hujan Lebat';
            $icon = 'rainy';
            $badgeBg = 'bg-blue-200 text-blue-900 border border-blue-300';
            $summary = "Hujan Lebat terdeteksi saat ini (Curah hujan {$precip} mm, Peluang {$rainProb}%). Penyiraman otomatis dilewati.";
        } elseif (in_array($code, [61, 63, 80, 81]) || $precip > 0 || $rainProb >= 70) {
            $conditionCategory = 'RAIN';
            $title = 'Hujan';
            $icon = 'rainy';
            $badgeBg = 'bg-blue-100 text-blue-800 border border-blue-200';
            $summary = "Hujan terdeteksi saat ini (Peluang Hujan saat ini {$rainProb}%). Tunda penyiraman & pemupukan cair.";
        } elseif (in_array($code, [51, 53, 55])) {
            $conditionCategory = 'DRIZZLE';
            $title = 'Gerimis';
            $icon = 'water_drop';
            $badgeBg = 'bg-sky-100 text-sky-800 border border-sky-200';
            $summary = "Gerimis terdeteksi di sekitar lokasi kebun. Kurangi volume air atau pertimbangkan tunda.";
        } elseif (in_array($code, [45, 48])) {
            $conditionCategory = 'FOG';
            $title = 'Berkabut';
            $icon = 'foggy';
            $badgeBg = 'bg-stone-100 text-stone-800 border border-stone-200';
            $summary = "Kondisi berkabut saat ini. Kelembapan udara tinggi, pertimbangkan pengurangan penyiraman.";
        } elseif ($code === 3 || ($humidity >= 75 && $rainProb >= 30)) {
            $conditionCategory = 'CLOUDY';
            $title = 'Berawan';
            $icon = 'cloud';
            $badgeBg = 'bg-slate-100 text-slate-800 border border-slate-200';
            $summary = "Berawan saat ini. Kelembapan tanah stabil & sejuk, waktu ideal untuk perawatan harian.";
        } elseif ($code === 0 || $code === 1 || $temp >= 33) {
            $conditionCategory = 'CLEAR';
            $title = $temp >= 33 ? 'Sangat Panas' : 'Cerah';
            $icon = 'wb_sunny';
            $badgeBg = $temp >= 33 ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-yellow-100 text-yellow-800 border border-yellow-200';
            $summary = "Cuaca cerah saat ini dengan suhu {$temp}°C. Risiko penguapan tinggi jika terik.";
        } else {
            $conditionCategory = 'PARTLY_CLOUDY';
            $title = 'Cerah Berawan';
            $icon = 'partly_cloudy_day';
            $badgeBg = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
            $summary = "Cuaca sejuk & sinar matahari cukup. Kondisi ideal untuk pertumbuhan tanaman.";
        }

        // Priority Evaluation Chain for Smart Irrigation
        $fixedWindow = 'Pagi (06.00 - 09.00) & Sore (16.00 - 18.00)';
        $decision = 'NORMAL';
        $waterStatus = 'WAITING';

        // 1. Current Weather is HEAVY_RAIN, THUNDERSTORM, or RAIN -> SKIP
        if (in_array($conditionCategory, ['HEAVY_RAIN', 'THUNDERSTORM', 'RAIN'])) {
            $decision = 'SKIP';
            $waterStatus = 'RAINED';
            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman',
                'time_window' => $fixedWindow,
                'advice' => "Sedang {$title} (Peluang Hujan {$rainProb}% saat ini). Penyiraman dilewati untuk mencegah pembusukan akar.",
                'badge' => 'Penyiraman Dilewati (Hujan)',
                'badge_bg' => 'bg-red-100 text-red-800 border border-red-200'
            ];
        }
        // 2. Recent Heavy Rain Today -> SKIP
        elseif ($hasRecentRain) {
            $decision = 'SKIP';
            $waterStatus = 'RAINED';
            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman',
                'time_window' => $fixedWindow,
                'advice' => "Hujan baru saja terjadi hari ini. Tanaman telah mendapatkan air dari hujan sehingga penyiraman sesi ini dilewati.",
                'badge' => 'Penyiraman Dilewati (Hujan Sebelumnya)',
                'badge_bg' => 'bg-purple-100 text-purple-800 border border-purple-200'
            ];
        }
        // 3. Rain Probability >= 70% -> SKIP
        elseif ($rainProb >= 70) {
            $decision = 'SKIP';
            $waterStatus = 'WAITING';
            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman',
                'time_window' => $fixedWindow,
                'advice' => "Kemungkinan hujan sangat tinggi ({$rainProb}% saat ini). Penyiraman dilewati untuk menghemat air.",
                'badge' => 'Penyiraman Dilewati (Peluang Hujan ≥ 70%)',
                'badge_bg' => 'bg-red-100 text-red-800 border border-red-200'
            ];
        }
        // 4. Rain Probability 50% - 69% -> REDUCE
        elseif ($rainProb >= 50) {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Kemungkinan hujan 50–69% ({$rainProb}%). Kurangi volume air penyiraman atau pertimbangkan tunda.",
                'badge' => 'Kurangi Volume (Peluang Hujan 50–69%)',
                'badge_bg' => 'bg-blue-100 text-blue-800 border border-blue-200'
            ];
        }
        // 5. Temperature >= 33°C -> NORMAL_PLUS
        elseif ($temp >= 33) {
            $decision = 'NORMAL_PLUS';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'NORMAL_PLUS',
                'title' => 'Penyiraman Normal + Ekstra',
                'time_window' => $fixedWindow,
                'advice' => "Suhu sangat panas ({$temp}°C). Lakukan penyiraman normal pada sesi Pagi/Sore dengan sedikit tambahan volume air.",
                'badge' => 'Penyiraman Normal + Extra (≥ 33°C)',
                'badge_bg' => 'bg-amber-100 text-amber-900 border border-amber-300'
            ];
        }
        // 6. Drizzle -> REDUCE
        elseif ($conditionCategory === 'DRIZZLE') {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Gerimis terdeteksi. Kurangi volume air penyiraman karena kelembapan udara meningkat.",
                'badge' => 'Kurangi Volume (Gerimis)',
                'badge_bg' => 'bg-sky-100 text-sky-800 border border-sky-200'
            ];
        }
        // 7. Fog -> REDUCE
        elseif ($conditionCategory === 'FOG') {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Kondisi berkabut. Kurangi sedikit volume penyiraman karena embun meningkatkan kelembapan media tanam.",
                'badge' => 'Kurangi Volume (Berkabut)',
                'badge_bg' => 'bg-stone-100 text-stone-800 border border-stone-200'
            ];
        }
        // 8. Default (CLEAR, PARTLY_CLOUDY, CLOUDY) -> NORMAL
        else {
            $decision = 'NORMAL';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'NORMAL',
                'title' => 'Penyiraman Normal',
                'time_window' => $fixedWindow,
                'advice' => 'Kondisi cuaca ideal. Lakukan penyiraman normal sesuai jadwal tetap (Pagi 06.00–09.00 & Sore 16.00–18.00).',
                'badge' => 'Penyiraman Normal',
                'badge_bg' => 'bg-emerald-100 text-emerald-800 border border-emerald-200'
            ];
        }

        $fertilization = [
            'allowed' => true,
            'advice' => 'Aman untuk pemupukan rutin (organik/NPK). Aplikasikan pada pagi atau sore hari.',
            'badge' => 'Pemupukan Aman',
            'badge_bg' => 'bg-emerald-100 text-emerald-800'
        ];

        if (in_array($conditionCategory, ['THUNDERSTORM', 'RAIN', 'HEAVY_RAIN'])) {
            $fertilization = [
                'allowed' => false,
                'advice' => 'TUNDA pemupukan cair/daun hari ini! Air hujan akan membilas nutrisi (leaching) sehingga pupuk terbuang sia-sia.',
                'badge' => 'Tunda Pupuk Cair',
                'badge_bg' => 'bg-amber-100 text-amber-800'
            ];
        } elseif ($temp >= 33) {
            $fertilization = [
                'allowed' => true,
                'advice' => 'Gunakan dosis pupuk encer (1/2 konsentrasi). Jangan pupuk pekat saat suhu terik untuk mencegah terbakar (fertilizer burn).',
                'badge' => 'Pupuk Dosis Encer',
                'badge_bg' => 'bg-orange-100 text-orange-800'
            ];
        }

        $pest_disease = [
            'risk_level' => 'LOW',
            'advice' => 'Risiko hama & jamur tergolong rendah. Inspeksi mingguan rutin.',
            'badge' => 'Risiko Low',
            'badge_bg' => 'bg-emerald-100 text-emerald-800'
        ];

        if (in_array($conditionCategory, ['RAIN', 'HEAVY_RAIN', 'THUNDERSTORM']) || $humidity >= 85) {
            $pest_disease = [
                'risk_level' => 'HIGH_FUNGUS',
                'advice' => 'Waspada Jamur Daun & Antraknosa! Kelembapan tinggi memicu perkembangan jamur. Pangkas daun tua yang menempel ke tanah.',
                'badge' => 'Cek Jamur Daun',
                'badge_bg' => 'bg-purple-100 text-purple-800'
            ];
        }

        $protection = [
            'needed' => false,
            'advice' => 'Kondisi angin & paparan sinar matahari stabil.',
            'badge' => 'Lingkungan Aman',
            'badge_bg' => 'bg-emerald-100 text-emerald-800'
        ];

        if ($conditionCategory === 'THUNDERSTORM' || $windSpeed >= 20) {
            $protection = [
                'needed' => true,
                'advice' => "Angin kencang ({$windSpeed} km/j) terdeteksi. Pasang/ikat ajir kayu pada tanaman tinggi & amankan pot gantung.",
                'badge' => 'Pasang Ajir / Penyangga',
                'badge_bg' => 'bg-red-100 text-red-800'
            ];
        } elseif ($temp >= 33) {
            $protection = [
                'needed' => true,
                'advice' => 'Berikan mulsa (jerami/daun kering) di atas tanah pot atau lindungi bibit muda dengan peneduh (paranet).',
                'badge' => 'Beri Mulsa / Naungan',
                'badge_bg' => 'bg-amber-100 text-amber-800'
            ];
        }

        // Dynamic Check against Custom WeatherRules in Database
        $customAlerts = [];
        try {
            $weatherRules = \App\Models\WeatherRule::where('is_active', true)->get();
            foreach ($weatherRules as $rule) {
                $val = match ($rule->weather_field) {
                    'temperature' => $temp,
                    'humidity' => $humidity,
                    'wind_speed' => $windSpeed,
                    'rain_probability' => $rainProb,
                    default => null,
                };
                if ($val !== null) {
                    $matched = match ($rule->operator) {
                        '>' => $val > $rule->threshold,
                        '<' => $val < $rule->threshold,
                        '>=' => $val >= $rule->threshold,
                        '<=' => $val <= $rule->threshold,
                        '==' => $val == $rule->threshold,
                        '!=' => $val != $rule->threshold,
                        default => false,
                    };
                    if ($matched) {
                        $customAlerts[] = [
                            'name' => $rule->name,
                            'severity' => $rule->severity,
                            'message' => $rule->message,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore DB exceptions
        }

        return [
            'status' => $conditionCategory,
            'condition_category' => $conditionCategory,
            'condition_title' => $title,
            'irrigation_decision' => $decision,
            'plant_water_status' => $waterStatus,
            'icon' => $icon,
            'badge_bg' => $badgeBg,
            'summary' => $summary,
            'temperature' => $temp,
            'rain_probability' => $rainProb,
            'wind_speed' => $windSpeed,
            'humidity' => $humidity,
            'weather_code' => $code,
            'watering' => $watering,
            'fertilization' => $fertilization,
            'pest_disease' => $pest_disease,
            'protection' => $protection,
            'custom_alerts' => $customAlerts,
        ];
    }
}

