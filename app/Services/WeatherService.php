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
        $cacheKey = "weather_v8_{$lat}_{$lng}_" . now()->format('Y-m-d_H') . "_{$tenMinBlock}";
        
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
                    $todayDailyCode = (int) ($daily['weather_code'][1] ?? ($daily['weather_code'][0] ?? 0));

                    // 1. Scan Past 24 Hours for Storm or Rain
                    $startPastIdx = max(0, $hourIndex - 24);
                    $past24hPrecip = 0.0;
                    $hadRecentStorm = false;

                    for ($i = $startPastIdx; $i <= $hourIndex; $i++) {
                        $p = (float) ($hourly['precipitation'][$i] ?? 0);
                        $c = (int) ($hourly['weather_code'][$i] ?? 0);
                        $past24hPrecip += $p;
                        if (in_array($c, [95, 96, 99])) {
                            $hadRecentStorm = true;
                        } elseif (in_array($c, [65, 82]) || $p >= 3.0) {
                            $hadRecentStorm = true;
                        }
                    }
                    $yesterdayCode = (int) ($daily['weather_code'][0] ?? 0);
                    $yesterdayPrecip = (float) ($daily['precipitation_sum'][0] ?? 0);
                    if (in_array($yesterdayCode, [95, 96, 99, 65, 82]) || $yesterdayPrecip >= 5.0) {
                        $hadRecentStorm = true;
                    }

                    // 2. Scan Upcoming 12 Hours for Impending Storm / Rain
                    $endFutureIdx = min(count($hourlyTimes) - 1, $hourIndex + 12);
                    $upcomingMaxProb = $currentRainProb;
                    $upcomingMaxPrecip = $currentPrecip;
                    $upcomingHasStorm = in_array($todayDailyCode, [95, 96, 99]);
                    $upcomingStormTime = null;

                    for ($i = $hourIndex; $i <= $endFutureIdx; $i++) {
                        $prob = (int) ($hourly['precipitation_probability'][$i] ?? 0);
                        $p = (float) ($hourly['precipitation'][$i] ?? 0);
                        $c = (int) ($hourly['weather_code'][$i] ?? 0);
                        if ($prob > $upcomingMaxProb) $upcomingMaxProb = $prob;
                        if ($p > $upcomingMaxPrecip) $upcomingMaxPrecip = $p;
                        if (in_array($c, [95, 96, 99, 65, 81, 82]) || $p >= 2.0) {
                            $upcomingHasStorm = true;
                            if (!$upcomingStormTime) $upcomingStormTime = $hourlyTimes[$i] ?? null;
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
                        'past_precipitation_12h' => round($past24hPrecip, 2),
                        'past_precipitation_24h' => round($past24hPrecip, 2),
                        'had_recent_storm' => $hadRecentStorm,
                        'yesterday_precip' => $yesterdayPrecip,
                        'upcoming_has_storm' => $upcomingHasStorm,
                        'upcoming_max_rain_prob' => $upcomingMaxProb,
                        'upcoming_max_precip' => round($upcomingMaxPrecip, 2),
                        'upcoming_storm_time' => $upcomingStormTime,
                        'daily_precipitation_sum' => round($dailyPrecipSum, 2),
                        'today_daily_code' => $todayDailyCode,
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
        $past24hPrecip = (float) ($weather['past_precipitation_24h'] ?? 0);
        $hadRecentStorm = (bool) ($weather['had_recent_storm'] ?? false);
        $upcomingHasStorm = (bool) ($weather['upcoming_has_storm'] ?? false);
        $upcomingMaxProb = (int) ($weather['upcoming_max_rain_prob'] ?? $rainProb);
        $dailyPrecipSum = (float) ($weather['daily_precipitation_sum'] ?? 0);
        $todayDailyCode = (int) ($weather['today_daily_code'] ?? $code);

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

        // 1. Current Weather is Active Rain or Thunderstorm -> SKIP
        if (in_array($conditionCategory, ['HEAVY_RAIN', 'THUNDERSTORM', 'RAIN', 'LIGHT_RAIN'])) {
            $decision = 'SKIP';
            $waterStatus = 'RAINED';
            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman',
                'time_window' => $fixedWindow,
                'advice' => "Sedang {$title} (Peluang Hujan {$rainProb}% saat ini). Penyiraman dilewati untuk mencegah pembusukan akar.",
                'warning' => "Sedang terjadi hujan/badai saat ini. Jangan menyiram tanaman agar akar tidak terendam air berlebih.",
                'badge' => 'Penyiraman Dilewati (Hujan)',
                'badge_bg' => 'bg-red-100 text-red-800 border border-red-200'
            ];
        }
        // 2. Recent Storm / Heavy Rain in Past 24h / Logged Rain Today -> SKIP
        elseif ($hadRecentStorm || $past24hPrecip >= 5.0 || $hasRecentRain) {
            $decision = 'SKIP';
            $waterStatus = 'RAINED';
            $stormDetail = $hadRecentStorm ? "Terjadi badai/hujan lebat dalam 24 jam terakhir" : "Akumulasi curah hujan {$past24hPrecip} mm dalam 24 jam terakhir";
            
            if ($temp >= 32) {
                $advice = "{$stormDetail} dan cuaca saat ini terik panas ({$temp}°C). Lapisan tanah bawah masih basah—HINDARI menyiram di siang terik agar akar tidak melepuh/terkukus. Cukup cek kembali kelembapan di sore hari (16.00–18.00).";
                $warning = "HINDARI MENYIRAM DI SIANG TERIK ({$temp}°C)! Tanah masih basah akibat badai semalam. Menyiram saat matahari panas terik akan merebus akar (root scalding) dan membuat tanaman layu mendadak.";
                $badge = 'Lewati (Pasca Badai + Siang Terik)';
            } else {
                $advice = "{$stormDetail}. Tanah masih sangat lembap & jenuh air, sehingga penyiraman sesi ini dilewati untuk mencegah pembusukan akar.";
                $warning = "Tanah masih jenuh air pasca hujan/badai semalam. Lewati sesi penyiraman ini untuk mencegah pembusukan akar.";
                $badge = 'Penyiraman Dilewati (Pasca Hujan/Badai)';
            }

            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman (Pasca Hujan/Badai)',
                'time_window' => $fixedWindow,
                'advice' => $advice,
                'warning' => $warning,
                'badge' => $badge,
                'badge_bg' => 'bg-purple-100 text-purple-800 border border-purple-200'
            ];
        }
        // 3. Impending Storm / Heavy Rain Today (upcoming storm or daily code 95/81 or daily prob >= 70% or daily sum >= 5mm) -> SKIP
        elseif ($upcomingHasStorm || in_array($todayDailyCode, [95, 96, 99, 65, 81, 82]) || $upcomingMaxProb >= 70 || $dailyPrecipSum >= 5.0) {
            $decision = 'SKIP';
            $waterStatus = 'WAITING';
            $watering = [
                'action' => 'SKIP',
                'title' => 'Lewati Penyiraman (Prakiraan Hujan/Badai)',
                'time_window' => $fixedWindow,
                'advice' => "Prakiraan cuaca mendeteksi potensi hujan lebat/badai hari ini (Peluang {$upcomingMaxProb}%, estimasi curah hujan {$dailyPrecipSum} mm). Penyiraman dilewati.",
                'warning' => "WASPADA BADAI/HUJAN LEBAT: Prakiraan mendeteksi potensi hujan lebat/badai (Peluang {$upcomingMaxProb}%). Jangan menyiram tanah dan amankan pot dari angin kencang.",
                'badge' => 'Penyiraman Dilewati (Prakiraan Badai/Hujan)',
                'badge_bg' => 'bg-purple-100 text-purple-800 border border-purple-200'
            ];
        }
        // 4. Moderate Past Rain (1.5mm - 5mm) in past 24h -> REDUCE
        elseif ($past24hPrecip >= 1.5) {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $warningText = ($temp >= 32)
                ? "Cuaca terik ({$temp}°C) namun tanah masih menyimpan kelembapan hujan kemarin ({$past24hPrecip} mm). Jangan menyiram di siang bolong, siram secukupnya hanya pada sore hari (16.00-18.00)."
                : "Tercatat hujan {$past24hPrecip} mm dalam 24 jam terakhir. Kurangi volume air siram agar media tanam tidak becek.";

            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Tercatat hujan {$past24hPrecip} mm dalam 24 jam terakhir. Tanah masih agak lembap, kurangi volume air penyiraman.",
                'warning' => $warningText,
                'badge' => 'Kurangi Volume (Lembap Pasca Hujan)',
                'badge_bg' => 'bg-blue-100 text-blue-800 border border-blue-200'
            ];
        }
        // 5. Rain Probability 50% - 69% -> REDUCE
        elseif ($upcomingMaxProb >= 50 || $rainProb >= 50) {
            $maxP = max($upcomingMaxProb, $rainProb);
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Kemungkinan hujan hari ini {$maxP}%. Kurangi volume air penyiraman atau pertimbangkan tunda.",
                'warning' => "Peluang hujan cukup tinggi ({$maxP}%). Kurangi volume penyiraman atau tunda sesi penyiraman jika langit mulai mendung gelap.",
                'badge' => "Kurangi Volume (Peluang Hujan {$maxP}%)",
                'badge_bg' => 'bg-blue-100 text-blue-800 border border-blue-200'
            ];
        }
        // 6. Temperature >= 33°C -> NORMAL_PLUS
        elseif ($temp >= 33) {
            $decision = 'NORMAL_PLUS';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'NORMAL_PLUS',
                'title' => 'Penyiraman Normal + Ekstra',
                'time_window' => $fixedWindow,
                'advice' => "Suhu sangat panas ({$temp}°C). Lakukan penyiraman ekstra pada sesi Pagi (06.00-09.00) atau Sore (16.00-18.00).",
                'warning' => "HINDARI MENYIRAM DI SIANG HARI ({$temp}°C)! Suhu sangat panas. Lakukan penyiraman hanya di pagi atau sore hari, dan berikan mulsa/naungan pada tanaman.",
                'badge' => 'Penyiraman Normal + Extra (≥ 33°C)',
                'badge_bg' => 'bg-amber-100 text-amber-900 border border-amber-300'
            ];
        }
        // 7. Drizzle -> REDUCE
        elseif ($conditionCategory === 'DRIZZLE') {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Gerimis terdeteksi. Kurangi volume air penyiraman karena kelembapan udara meningkat.",
                'warning' => "Gerimis meningkatkan kelembapan tanah. Kurangi takaran air agar media tidak terlalu basah.",
                'badge' => 'Kurangi Volume (Gerimis)',
                'badge_bg' => 'bg-sky-100 text-sky-800 border border-sky-200'
            ];
        }
        // 8. Fog -> REDUCE
        elseif ($conditionCategory === 'FOG') {
            $decision = 'REDUCE';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'REDUCE',
                'title' => 'Kurangi Volume Siram',
                'time_window' => $fixedWindow,
                'advice' => "Kondisi berkabut. Kurangi sedikit volume penyiraman karena embun meningkatkan kelembapan media tanam.",
                'warning' => "Kabut tebal menghasilkan embun alami. Kurangi penyiraman media tanam.",
                'badge' => 'Kurangi Volume (Berkabut)',
                'badge_bg' => 'bg-stone-100 text-stone-800 border border-stone-200'
            ];
        }
        // 9. Default (CLEAR, PARTLY_CLOUDY, CLOUDY) -> NORMAL
        else {
            $decision = 'NORMAL';
            $waterStatus = 'DRY';
            $watering = [
                'action' => 'NORMAL',
                'title' => 'Penyiraman Normal',
                'time_window' => $fixedWindow,
                'advice' => 'Kondisi cuaca ideal. Lakukan penyiraman normal sesuai jadwal tetap (Pagi 06.00–09.00 & Sore 16.00–18.00).',
                'warning' => null,
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

