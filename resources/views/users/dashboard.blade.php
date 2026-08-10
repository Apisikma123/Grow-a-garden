@extends('layouts.dashboard')

@section('title', 'Beranda — Grow a Garden')
@section('description', 'Ringkasan kebun Anda dan tugas harian.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                @php
                    $hour = now()->format('H');
                    if ($hour < 11) {
                        $greeting = 'Selamat pagi';
                    } elseif ($hour < 15) {
                        $greeting = 'Selamat siang';
                    } elseif ($hour < 18) {
                        $greeting = 'Selamat sore';
                    } else {
                        $greeting = 'Selamat malam';
                    }
                    $userName = auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Guest';
                @endphp
                <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight mb-2">{{ $greeting }}, {{ $userName }}!</h1>
                <p class="text-[16px] text-on-surface-variant">Kebun Anda tumbuh dengan baik. Mari lihat apa yang perlu dirawat hari ini.</p>
            </div>
            
            {{-- Weather Widget (Dynamic - Premium Card Style) --}}
            <div id="weather-widget" class="bg-white rounded-3xl p-6 md:p-8 premium-shadow max-w-[480px] w-full transition-all duration-500 shrink-0">
                
                {{-- Default: Ask Location State --}}
                <div id="weather-ask" class="flex flex-col gap-4">
                    <div class="flex items-center justify-between mb-2 border-b border-outline-variant/30 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 24px;">location_off</span>
                            <span class="text-sm font-bold text-on-surface">Adaptasi Pintar Aktif?</span>
                        </div>
                        <span class="text-[11px] font-semibold text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full">Offline</span>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <h3 class="font-bold text-[14px] text-on-surface mb-2">Aktifkan Lokasi Kebun</h3>
                        <p class="text-sm text-on-surface-variant leading-relaxed mb-4">
                            Deteksi lokasi untuk penyesuaian cuaca otomatis pada jadwal penyiraman harian.
                        </p>
                        <button type="button" id="dash-detect-location" class="w-full bg-primary text-on-primary text-sm font-bold py-2.5 rounded-xl hover:bg-primary-container hover:text-on-primary-container active:scale-[0.98] transition-all shadow-sm flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">my_location</span>
                            Deteksi Lokasi
                        </button>
                    </div>
                </div>

                {{-- Loading State --}}
                <div id="weather-loading" class="hidden flex flex-col gap-4">
                    <div class="flex items-center justify-between mb-2 border-b border-outline-variant/30 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary animate-spin" style="font-size: 24px;">sync</span>
                            <span class="text-sm font-bold text-on-surface">Mendeteksi...</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4 flex flex-col items-center text-center justify-center min-h-[140px]">
                        <h3 class="font-bold text-[14px] text-on-surface mb-2">Mencari Koordinat</h3>
                        <p class="text-sm text-on-surface-variant leading-relaxed">
                            Sedang mencari data cuaca regional Anda.
                        </p>
                    </div>
                </div>

                {{-- Active Weather State --}}
                <div id="weather-active" class="hidden flex flex-col w-full">
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant hidden sm:inline-block" style="font-size: 22px;" id="weather-icon-1">cloud</span>
                            <span class="material-symbols-outlined text-on-surface-variant hidden sm:inline-block" style="font-size: 22px;" id="weather-icon-2">water_drop</span>
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 22px;" id="weather-icon-main">thermostat</span>
                            <span class="text-sm font-bold text-on-surface truncate" id="weather-title">Prediksi Cuaca: Hujan</span>
                        </div>
                        <span class="text-[11px] font-semibold px-3 py-1 rounded-full whitespace-nowrap" id="weather-badge">Hujan Ringan</span>
                    </div>

                    {{-- Card Body --}}
                    <div class="bg-surface-container-low rounded-xl p-4 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary flex-shrink-0 mt-0.5" style="font-size: 20px;">info</span>
                        <div>
                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-primary uppercase tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[14px]">auto_awesome</span> Adaptasi Pintar
                            </div>
                            <p class="text-[13px] text-on-surface-variant leading-relaxed mb-3" id="weather-desc">
                                Jadwal penyiraman otomatis ditunda hari ini karena curah hujan yang cukup.
                            </p>
                            <div class="flex items-center gap-1.5 text-[11px] text-on-surface-variant/80 border-t border-outline-variant/30 pt-3">
                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                <span id="weather-location" class="truncate">Lokasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($activeAlerts) && $activeAlerts->count() > 0)
        <div class="flex flex-col gap-3">
            @foreach($activeAlerts as $alert)
            @php
                $alertColor = match($alert->severity) {
                    'CRITICAL' => 'bg-error/10 border-error/30 text-error',
                    'HIGH' => 'bg-orange-50 border-orange-200 text-orange-800',
                    'MEDIUM' => 'bg-warning/10 border-warning/30 text-warning',
                    default => 'bg-primary/10 border-primary/30 text-primary'
                };
                $icon = match($alert->severity) {
                    'CRITICAL' => 'warning',
                    'HIGH' => 'priority_high',
                    default => 'info'
                };
            @endphp
            <div class="rounded-xl border p-4 flex items-start sm:items-center gap-4 {{ $alertColor }}">
                <div class="shrink-0 p-2 rounded-full bg-white/50 backdrop-blur-sm">
                    <span class="material-symbols-outlined block text-[24px]">{{ $icon }}</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-[14px] leading-tight mb-1">
                        Peringatan Cuaca Ekstrem
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/50 uppercase ml-2">{{ $alert->severity }}</span>
                    </h4>
                    <p class="text-[13px] leading-relaxed opacity-90">{{ $alert->message }}</p>
                </div>
                <a href="/gardens" class="shrink-0 mt-3 sm:mt-0 font-bold text-[13px] px-4 py-2 bg-white/50 hover:bg-white/80 rounded-lg transition-colors">
                    Lihat Kebun
                </a>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-[16px]">
            {{-- Card 1: Gardens --}}
            <a href="/gardens" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[#0f766e] text-[24px] mb-2">energy_savings_leaf</span>
                <div class="flex items-baseline gap-2 mb-1">
                    <span class="text-[36px] font-black text-on-surface leading-none">{{ count($gardens) }}</span>
                </div>
                <div class="text-[14px] text-on-surface font-medium text-center">Kebun</div>
            </a>
            {{-- Card 2: Active Plants --}}
            <a href="/growth-calendar" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-status-healthy text-[24px] mb-2">potted_plant</span>
                <div class="text-[36px] font-black text-on-surface leading-none mb-1">{{ $activePlants }}</div>
                <div class="text-[14px] text-on-surface font-medium text-center">Tanaman Aktif</div>
            </a>

            {{-- Card 4 --}}
            <a href="/care-tasks" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[#f97316] text-[24px] mb-2">task_alt</span>
                <div class="text-[36px] font-black text-on-surface leading-none mb-1">{{ $todayTasks->count() }}</div>
                <div class="text-[14px] text-on-surface font-medium text-center">Aktivitas Hari Ini</div>
            </a>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
            
            {{-- Plant Distribution --}}
            <div class="bg-surface rounded-[24px] p-[32px] ambient-shadow">
                <h3 class="text-[20px] font-bold text-on-surface mb-8">Distribusi Tanaman</h3>
                <div class="flex justify-center mb-8">
                    {{-- CSS Conic Gradient Donut Chart --}}
                    <div class="w-56 h-56 rounded-full flex items-center justify-center" style="background: conic-gradient(#10b981 0% 55%, #78a994 55% 75%, #fb923c 75% 100%);">
                        <div class="w-36 h-36 bg-surface rounded-full shadow-inner"></div>
                    </div>
                </div>
                <div class="flex justify-center gap-6 text-[13px] font-bold text-on-surface-variant">
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-status-healthy"></span> Sayuran</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#78a994]"></span> Herbal</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#fb923c]"></span> Buah-buahan</div>
                </div>
            </div>

            {{-- Weekly Care Activity --}}
            <div class="bg-surface rounded-[24px] p-[32px] ambient-shadow">
                <h3 class="text-[20px] font-bold text-on-surface mb-8">Aktivitas Perawatan Mingguan</h3>
                
                {{-- Mock Bar Chart --}}
                <div class="h-48 flex items-end justify-between gap-3 mb-6 border-b-2 border-outline-variant/20 pb-2 relative">
                    {{-- Y Axis Grid Lines --}}
                    <div class="absolute inset-0 flex flex-col justify-between z-0 pointer-events-none">
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                    </div>

                    {{-- Bars --}}
                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 60%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>
                    
                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 50%">
                        <div class="bg-[#fb923c] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 25%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 75%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 85%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 35%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 65%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 30%">
                        <div class="bg-status-healthy w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 100%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 65%">
                        <div class="bg-[#fb923c] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 50%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 65%">
                        <div class="bg-status-healthy w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 100%"></div>
                    </div>
                </div>

                <div class="flex justify-between px-2 text-[12px] text-on-surface-variant font-bold mb-6">
                    <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                </div>

                <div class="flex justify-center gap-6 text-[13px] font-bold text-on-surface-variant">
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-status-healthy"></span> Menyiram</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#fb923c]"></span> Memupuk</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#78a994]"></span> Memangkas</div>
                </div>
            </div>
        </div>

        {{-- Upcoming Harvest Row --}}
        <div class="bg-surface rounded-[24px] p-[32px] ambient-shadow mb-[24px]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[20px] font-bold text-on-surface">Panen Mendatang</h3>
                <a href="/growth-calendar" class="text-[14px] font-bold text-primary hover:underline">Lihat Kalender</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse($upcomingHarvests as $plant)
                {{-- Harvest Item --}}
                <div class="bg-surface-container-low rounded-[20px] p-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[24px]">eco</span>
                    </div>
                    <div class="flex flex-col h-full w-full">
                        <div class="text-[15px] font-bold text-on-surface leading-tight mb-1">{{ $plant->plantTemplate->name_id ?? 'Unknown' }}</div>
                        <div class="text-[13px] text-on-surface-variant mb-4">{{ $plant->garden->name ?? 'Kebun' }}</div>
                        <div class="mt-auto flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-status-healthy text-[18px]">schedule</span>
                            <span class="text-[13.5px] font-bold text-status-healthy">{{ $plant->estimated_harvest_days === 0 ? 'Hari ini' : $plant->estimated_harvest_days . ' hari lagi' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10">
                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[32px] text-on-surface-variant">eco</span>
                    </div>
                    <p class="text-on-surface-variant">Belum ada tanaman yang mendekati masa panen.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── Indonesian Regional Season Map ──
    // Maps provinces to their rainy months (1-indexed). Months NOT listed = dry.
    // Based on BMKG general patterns. "Normal" returned for transition months.
    const RAINY_MONTHS = {
        'Aceh':               [9,10,11,12,1,2],
        'Sumatera Utara':     [9,10,11,12,1,2],
        'Sumatera Barat':     [9,10,11,12,1,2,3],
        'Riau':               [9,10,11,12,1,2],
        'Kepulauan Riau':     [10,11,12,1,2,3],
        'Jambi':              [10,11,12,1,2,3],
        'Sumatera Selatan':   [10,11,12,1,2,3],
        'Bangka Belitung':    [10,11,12,1,2,3],
        'Bengkulu':           [10,11,12,1,2,3],
        'Lampung':            [10,11,12,1,2,3],
        'DKI Jakarta':        [10,11,12,1,2,3],
        'Jawa Barat':         [10,11,12,1,2,3],
        'Banten':             [10,11,12,1,2,3],
        'Jawa Tengah':        [10,11,12,1,2,3],
        'DI Yogyakarta':      [10,11,12,1,2,3],
        'Jawa Timur':         [10,11,12,1,2,3],
        'Bali':               [10,11,12,1,2,3],
        'Nusa Tenggara Barat':[11,12,1,2,3],
        'Nusa Tenggara Timur':[11,12,1,2,3],
        'Kalimantan Barat':   [9,10,11,12,1,2,3,4],
        'Kalimantan Tengah':  [9,10,11,12,1,2,3],
        'Kalimantan Selatan': [10,11,12,1,2,3],
        'Kalimantan Timur':   [10,11,12,1,2,3],
        'Kalimantan Utara':   [10,11,12,1,2,3],
        'Sulawesi Utara':     [10,11,12,1,2,3],
        'Gorontalo':          [10,11,12,1,2],
        'Sulawesi Tengah':    [10,11,12,1,2,3],
        'Sulawesi Barat':     [10,11,12,1,2,3],
        'Sulawesi Selatan':   [10,11,12,1,2,3],
        'Sulawesi Tenggara':  [10,11,12,1,2,3],
        'Maluku':             [4,5,6,7,8],
        'Maluku Utara':       [10,11,12,1,2,3],
        'Papua Barat':        [10,11,12,1,2,3,4,5],
        'Papua':              [10,11,12,1,2,3,4,5],
    };

    function getSeason(province) {
        const month = new Date().getMonth() + 1; // 1-12
        const rainyMonths = RAINY_MONTHS[province];
        if (!rainyMonths) return 'normal';
        if (rainyMonths.includes(month)) return 'rainy';
        // Transition month check (1 month before/after rainy block)
        const prevMonth = month === 1 ? 12 : month - 1;
        const nextMonth = month === 12 ? 1 : month + 1;
        if (rainyMonths.includes(prevMonth) || rainyMonths.includes(nextMonth)) {
            // Could be transition — check if it's right on the edge
            if (!rainyMonths.includes(month)) return 'dry';
        }
        return 'dry';
    }

    function getWeatherConfig(season) {
        switch (season) {
            case 'rainy':
                return {
                    icon: 'rainy',
                    icons: ['cloud', 'water_drop'],
                    title: 'Cuaca: Hujan',
                    badge: 'Hujan Tinggi',
                    badgeBg: 'bg-primary-container',
                    badgeText: 'text-on-primary-container',
                    desc: 'Frekuensi penyiraman dikurangi 30% karena curah hujan tinggi di wilayah Anda.',
                    modifier: '-30%',
                    color: '#006c49'
                };
            case 'dry':
                return {
                    icon: 'thermostat',
                    icons: ['sunny', 'wb_sunny'],
                    title: 'Cuaca: Kemarau',
                    badge: 'Suhu Tinggi',
                    badgeBg: 'bg-orange-100',
                    badgeText: 'text-orange-800',
                    desc: 'Frekuensi penyiraman ditambah 50% untuk mengkompensasi penguapan tinggi.',
                    modifier: '+50%',
                    color: '#944a23'
                };
            default:
                return {
                    icon: 'partly_cloudy_day',
                    icons: ['cloud', 'wb_sunny'],
                    title: 'Cuaca: Normal',
                    badge: 'Normal',
                    badgeBg: 'bg-surface-container-highest',
                    badgeText: 'text-on-surface-variant',
                    desc: 'Kondisi cuaca normal. Jadwal penyiraman berjalan sesuai standar.',
                    modifier: '0%',
                    color: '#006c49'
                };
        }
    }

    function showWeatherState(state) {
        document.getElementById('weather-ask').classList.add('hidden');
        document.getElementById('weather-loading').classList.add('hidden');
        document.getElementById('weather-active').classList.add('hidden');
        document.getElementById('weather-' + state).classList.remove('hidden');
    }

    function applyWeather(locationData) {
        const province = locationData.region || '';
        const season = getSeason(province);
        const config = getWeatherConfig(season);

        document.getElementById('weather-icon-main').textContent = config.icon;
        document.getElementById('weather-icon-1').textContent = config.icons[0];
        document.getElementById('weather-icon-2').textContent = config.icons[1];
        document.getElementById('weather-title').textContent = config.title;
        document.getElementById('weather-desc').textContent = config.desc;
        document.getElementById('weather-location').textContent = locationData.formatted || province;
        
        const badge = document.getElementById('weather-badge');
        badge.textContent = config.badge;
        badge.className = `text-[11px] font-semibold px-3 py-1 rounded-full whitespace-nowrap ${config.badgeBg} ${config.badgeText}`;

        showWeatherState('active');
    }

    // ── Init: check localStorage ──
    const saved = localStorage.getItem('garden_location');
    if (saved) {
        try {
            applyWeather(JSON.parse(saved));
        } catch(e) {
            showWeatherState('ask');
        }
    } else {
        showWeatherState('ask');
    }

    // ── Detect Location from Dashboard ──
    const detectBtn = document.getElementById('dash-detect-location');
    if (detectBtn) {
        detectBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                Alert.toast.error('Browser Anda tidak mendukung Geolocation.');
                return;
            }

            showWeatherState('loading');

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    try {
                        const resp = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10`,
                            { headers: { 'Accept-Language': 'id, en' } }
                        );
                        if (!resp.ok) throw new Error('API error');

                        const data = await resp.json();
                        const addr = data.address || {};
                        const city = addr.city || addr.town || addr.municipality || addr.city_district || addr.county || 'Lokasi Terdeteksi';
                        const state = addr.state || addr.region || '';
                        const formatted = state ? `${city}, ${state}` : city;

                        const locationData = {
                            lat, lon, city,
                            region: state || city,
                            country: addr.country || 'Indonesia',
                            formatted: `${formatted}, Indonesia`
                        };

                        localStorage.setItem('garden_location', JSON.stringify(locationData));
                        applyWeather(locationData);

                    } catch (err) {
                        console.error('Reverse geocoding error:', err);
                        const fallback = {
                            lat, lon,
                            city: `${lat.toFixed(4)}, ${lon.toFixed(4)}`,
                            region: '',
                            country: 'Indonesia',
                            formatted: `Koordinat: ${lat.toFixed(4)}, ${lon.toFixed(4)}`
                        };
                        localStorage.setItem('garden_location', JSON.stringify(fallback));
                        applyWeather(fallback);
                    }
                },
                (error) => {
                    showWeatherState('ask');
                    let msg = 'Gagal mendeteksi lokasi.';
                    if (error.code === error.PERMISSION_DENIED)
                        msg = 'Izin lokasi ditolak. Anda bisa mengatur lokasi secara manual di halaman Settings.';
                    else if (error.code === error.POSITION_UNAVAILABLE)
                        msg = 'Informasi lokasi tidak tersedia.';
                    else if (error.code === error.TIMEOUT)
                        msg = 'Waktu permintaan lokasi habis. Coba lagi.';
                    Alert.modal.error('Gagal Mendeteksi', msg);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }
});
</script>
@endpush
