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
        // Round lat/long to 4 decimal places for ~11m micro-location precision
        $lat = round($latitude, 4);
        $lng = round($longitude, 4);
        
        // Cache in 10-minute blocks for fresh & fast updates
        $tenMinBlock = floor((int) now()->format('i') / 10);
        $cacheKey = "weather_v6_{$lat}_{$lng}_" . now()->format('Y-m-d_H') . "_{$tenMinBlock}";
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($lat, $lng) {
            try {
                $response = Http::withoutVerifying()->timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,showers,weather_code,cloud_cover,wind_speed_10m',
                    'hourly' => 'temperature_2m,relative_humidity_2m,precipitation_probability,precipitation,weather_code,wind_speed_10m,cloud_cover',
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max,precipitation_sum',
                    'timezone' => 'auto',
                    'past_days' => 1,
                    'forecast_days' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    $current = $data['current'] ?? [];
                    $hourly = $data['hourly'] ?? [];
                    $daily = $data['daily'] ?? [];

                    // Match current hour index in forecast
                    $currentHour = (int) now()->format('H');
                    $hourlyTimes = $hourly['time'] ?? [];
                    $nowIsoHour = now()->format('Y-m-d\TH:00');
                    $hourIndex = array_search($nowIsoHour, $hourlyTimes);
                    if ($hourIndex === false) {
                        $hourIndex = count($hourlyTimes) > 0 ? count($hourlyTimes) - 1 : $currentHour;
                    }

                    $hourlyCode = isset($hourly['weather_code'][$hourIndex]) ? (int) $hourly['weather_code'][$hourIndex] : null;
                    $currentCode = (int) ($current['weather_code'] ?? ($hourlyCode ?? ($daily['weather_code'][1] ?? 0)));
                    $currentTemp = (float) ($current['temperature_2m'] ?? ($hourly['temperature_2m'][$hourIndex] ?? ($daily['temperature_2m_max'][1] ?? 29)));
                    $apparentTemp = (float) ($current['apparent_temperature'] ?? $currentTemp);
                    $currentHumidity = (int) ($current['relative_humidity_2m'] ?? ($hourly['relative_humidity_2m'][$hourIndex] ?? 70));
                    $currentWind = (float) ($current['wind_speed_10m'] ?? ($hourly['wind_speed_10m'][$hourIndex] ?? 10));
                    $currentPrecip = (float) ($current['precipitation'] ?? ($hourly['precipitation'][$hourIndex] ?? 0));
                    $currentCloud = (int) ($current['cloud_cover'] ?? ($hourly['cloud_cover'][$hourIndex] ?? 0));

                    $hourlyProbs = $hourly['precipitation_probability'] ?? [];
                    $currentRainProb = isset($hourlyProbs[$hourIndex]) ? (int) $hourlyProbs[$hourIndex] : (int) ($daily['precipitation_probability_max'][1] ?? 0);
                    $rainProb24h = (int) ($daily['precipitation_probability_max'][1] ?? ($daily['precipitation_probability_max'][0] ?? 0));
                    $dailyPrecipSum = (float) ($daily['precipitation_sum'][1] ?? ($daily['precipitation_sum'][0] ?? 0));

                    // Calculate precipitation & storm occurrence in the past 12-24 hours
                    $startPastIdx = max(0, $hourIndex - 12);
                    $past12hPrecip = 0.0;
                    $hadRecentStorm = false;
                    $recentStormCode = null;

                    for ($i = $startPastIdx; $i <= $hourIndex; $i++) {
                        $p = (float) ($hourly['precipitation'][$i] ?? 0);
                        $c = (int) ($hourly['weather_code'][$i] ?? 0);
                        $past12hPrecip += $p;
                        if (in_array($c, [95, 96, 99])) {
                            $hadRecentStorm = true;
                            $recentStormCode = $c;
                        } elseif (in_array($c, [65, 82]) || $p >= 3.0) {
                            $hadRecentStorm = true;
                        }
                    }

                    return [
                        'weather_code' => $currentCode,
                        'temperature' => round($currentTemp, 1),
                        'apparent_temperature' => round($apparentTemp, 1),
                        'rain_probability' => $currentRainProb,
                        'rain_probability_24h' => $rainProb24h,
                        'wind_speed' => round($currentWind, 1),
                        'humidity' => $currentHumidity,
                        'precipitation' => round($currentPrecip, 2),
                        'is_day' => (int) ($current['is_day'] ?? 1),
                        'cloud_cover' => $currentCloud,
                        'past_precipitation_12h' => round($past12hPrecip, 2),
                        'had_recent_storm' => $hadRecentStorm,
                        'recent_storm_code' => $recentStormCode,
                        'daily_precipitation_sum' => round($dailyPrecipSum, 2),
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
     */
    public function analyzeAgronomicConditions(?array $weather, bool $hasRecentRain = false): array
    {
        $temp = $weather['temperature'] ?? 29;
        $rainProb = $weather['rain_probability'] ?? 0;
        $windSpeed = $weather['wind_speed'] ?? 10;
        $humidity = $weather['humidity'] ?? 70;
        $code = $weather['weather_code'] ?? 0;
        $precip = $weather['precipitation'] ?? 0;
        $cloud = $weather['cloud_cover'] ?? 0;
        $past12hPrecip = (float) ($weather['past_precipitation_12h'] ?? 0);
        $hadRecentStorm = (bool) ($weather['had_recent_storm'] ?? false);
        $dailyPrecipSum = (float) ($weather['daily_precipitation_sum'] ?? 0);

        // Enhanced WMO Weather Condition Categories
        $conditionCategory = 'PARTLY_CLOUDY';
        $title = 'Cerah Berawan';
        $icon = 'partly_cloudy_day';
        $badgeBg = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
        $summary = 'Cuaca sejuk & sinar matahari cukup. Kondisi ideal untuk pertumbuhan tanaman.';

        if (in_array($code, [95, 96, 99])) {
            $conditionCategory = 'THUNDERSTORM';
            $title = 'Hujan Petir & Badai';
            $icon = 'thunderstorm';
            $badgeBg = 'bg-purple-100 text-purple-900 border border-purple-300';
            $summary = "WASPADA Hujan Badai & Angin Kencang ({$windSpeed} km/j)! Amankan pot gantung dan bibit muda.";
        } elseif (in_array($code, [65, 82]) || $precip > 5.0) {
            $conditionCategory = 'HEAVY_RAIN';
            $title = 'Hujan Lebat';
            $icon = 'rainy';
            $badgeBg = 'bg-blue-200 text-blue-900 border border-blue-300';
            $summary = "Hujan lebat terdeteksi saat ini (Curah hujan {$precip} mm, Peluang {$rainProb}%). Penyiraman otomatis dilewati.";
        } elseif (in_array($code, [63, 81])) {
            $conditionCategory = 'RAIN';
            $title = 'Hujan Sedang';
            $icon = 'rainy';
            $badgeBg = 'bg-blue-100 text-blue-800 border border-blue-200';
            $summary = "Hujan sedang terdeteksi saat ini (Peluang hujan {$rainProb}%). Tunda penyiraman & pemupukan cair.";
        } elseif (in_array($code, [61, 80]) || $precip > 0.2 || $rainProb >= 70) {
            $conditionCategory = 'LIGHT_RAIN';
            $title = 'Hujan Ringan';
            $icon = 'rainy';
            $badgeBg = 'bg-sky-100 text-sky-900 border border-sky-300';
            $summary = "Hujan ringan terdeteksi (Peluang hujan {$rainProb}%). Penyiraman dapat dilewati atau dikurangi.";
        } elseif (in_array($code, [51, 53, 55, 56, 57])) {
            $conditionCategory = 'DRIZZLE';
            $title = 'Gerimis';
            $icon = 'water_drop';
            $badgeBg = 'bg-sky-100 text-sky-800 border border-sky-200';
            $summary = "Gerimis terdeteksi di sekitar kebun. Kurangi volume air penyiraman.";
        } elseif (in_array($code, [45, 48])) {
            $conditionCategory = 'FOG';
            $title = 'Berkabut';
            $icon = 'foggy';
            $badgeBg = 'bg-stone-100 text-stone-800 border border-stone-200';
            $summary = "Kondisi berkabut saat ini. Kelembapan udara tinggi, pertimbangkan pengurangan penyiraman.";
        } elseif ($code === 3 || $cloud >= 80) {
            $conditionCategory = 'OVERCAST';
            $title = 'Mendung';
            $icon = 'cloud';
            $badgeBg = 'bg-slate-200 text-slate-900 border border-slate-300';
            $summary = "Langit mendung berawan tebal. Kelembapan tanah terawat, siram secukupnya.";
        } elseif ($code === 2 || ($cloud >= 40 && $cloud < 80)) {
            $conditionCategory = 'CLOUDY';
            $title = 'Berawan';
            $icon = 'cloud';
            $badgeBg = 'bg-slate-100 text-slate-800 border border-slate-200';
            $summary = "Cuaca berawan saat ini. Kelembapan tanah stabil & sejuk, waktu ideal untuk perawatan harian.";
        } elseif ($temp >= 33 && $code <= 1) {
            $conditionCategory = 'VERY_HOT';
            $title = 'Sangat Panas';
            $icon = 'wb_sunny';
            $badgeBg = 'bg-amber-100 text-amber-900 border border-amber-300';
            $summary = "Cuaca terik sangat panas dengan suhu {$temp}°C. Risiko penguapan tinggi, lakukan penyiraman ekstra.";
        } else {
            $conditionCategory = 'PARTLY_CLOUDY';
            $title = 'Cerah Berawan';
            $icon = 'partly_cloudy_day';
            $badgeBg = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
            $summary = 'Cuaca sejuk & sinar matahari cukup. Kondisi ideal untuk pertumbuhan tanaman.';
        }

        // Priority Evaluation Chain for Smart Irrigation
        $fixedWindow = 'Pagi (06.00 - 09.00) & Sore (16.00 - 18.00)';
        $decision = 'NORMAL';
        $waterStatus = 'WAITING';

        // 1. Current Weather is HEAVY_RAIN, THUNDERSTORM, RAIN, or LIGHT_RAIN -> SKIP
        if (in_array($conditionCategory, ['HEAVY_RAIN', 'THUNDERSTORM', 'RAIN', 'LIGHT_RAIN'])) {
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
        // 2. Recent Storm or Heavy Rain in past 12-24h / Logged Rain Today -> SKIP
        elseif ($hadRecentStorm || $past12hPrecip >= 5.0 || $hasRecentRain) {
            $decision = 'SKIP';
            $waterStatus = 'RAINED';
            $stormDetail = $hadRecentStorm ? "Terjadi badai/hujan lebat beberapa jam lalu" : "Akumulasi curah hujan {$past12hPrecip} mm dalam 12 jam terakhir";
            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman (Pasca Hujan/Badai)',
                'time_window' => $fixedWindow,
                'advice' => "{$stormDetail}. Tanah masih sangat lembap & jenuh air, sehingga penyiraman sesi ini dilewati untuk mencegah pembusukan akar.",
                'badge' => 'Penyiraman Dilewati (Pasca Hujan/Badai)',
                'badge_bg' => 'bg-purple-100 text-purple-800 border border-purple-200'
            ];
        }
        // 3. Moderate Past Rain (1.5mm - 5mm) in past 12h -> REDUCE
        elseif ($past12hPrecip >= 1.5) {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Tercatat hujan {$past12hPrecip} mm beberapa jam lalu. Tanah masih agak lembap, kurangi volume air penyiraman.",
                'badge' => 'Kurangi Volume (Lembap Pasca Hujan)',
                'badge_bg' => 'bg-blue-100 text-blue-800 border border-blue-200'
            ];
        }
        // 4. Rain Probability >= 70% -> SKIP
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
        // 5. Rain Probability 50% - 69% -> REDUCE
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

