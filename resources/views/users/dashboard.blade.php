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
                        $greeting = 'Selamat pagi.';
                    } elseif ($hour < 15) {
                        $greeting = 'Selamat siang.';
                    } elseif ($hour < 18) {
                        $greeting = 'Selamat sore.';
                    } else {
                        $greeting = 'Selamat malam.';
                    }
                @endphp
                <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight mb-2">{{ $greeting }}</h1>
                <p class="text-[16px] text-on-surface-variant">Kebun Anda tumbuh dengan baik. Mari lihat apa yang perlu dirawat hari ini.</p>
            </div>
            
            {{-- Weather Widget (Dynamic) --}}
            <div id="weather-widget" class="flex items-center gap-4 transition-all duration-500">
                {{-- Default: Ask Location State --}}
                <div id="weather-ask" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-surface-container-lowest rounded-full flex items-center justify-center ambient-shadow shrink-0">
                        <span class="material-symbols-outlined text-[24px] text-on-surface-variant">location_off</span>
                    </div>
                    <div class="max-w-[260px]">
                        <h3 class="font-bold text-[14px] text-on-surface mb-1">Aktifkan Lokasi Kebun</h3>
                        <p class="text-[11px] text-on-surface-variant leading-tight mb-2">Deteksi lokasi untuk penyesuaian cuaca otomatis pada jadwal penyiraman.</p>
                        <button type="button" id="dash-detect-location" class="bg-primary text-on-primary text-[12px] font-bold px-4 py-1.5 rounded-full flex items-center justify-center gap-1.5 hover:bg-primary/90 active:scale-95 transition-all shadow-sm overflow-hidden">
                            <span class="material-symbols-outlined text-[16px] shrink-0">my_location</span>
                            <span class="truncate">Deteksi Lokasi</span>
                        </button>
                    </div>
                </div>

                {{-- Loading State --}}
                <div id="weather-loading" class="hidden flex items-center gap-3">
                    <div class="w-12 h-12 bg-surface-container-lowest rounded-full flex items-center justify-center ambient-shadow shrink-0">
                        <span class="material-symbols-outlined text-[24px] text-primary animate-spin">sync</span>
                    </div>
                    <div class="max-w-[220px]">
                        <h3 class="font-bold text-[14px] text-on-surface mb-0.5">Mendeteksi lokasi...</h3>
                        <p class="text-[11px] text-on-surface-variant leading-tight">Sedang mencari koordinat dan data cuaca regional Anda.</p>
                    </div>
                </div>

                {{-- Active Weather State --}}
                <div id="weather-active" class="hidden flex items-start sm:items-center gap-[12px]">
                    <div class="w-12 h-12 bg-surface-container-lowest rounded-full flex items-center justify-center ambient-shadow shrink-0 mt-1 sm:mt-0">
                        <span class="material-symbols-outlined text-[24px] text-primary" id="weather-icon">rainy</span>
                    </div>
                    <div class="flex-1 max-w-[280px] py-1">
                        <h3 class="font-bold text-[16px] text-on-surface whitespace-nowrap mb-0.5" id="weather-title">Berawan / Hujan</h3>
                        <div class="flex items-center gap-1 text-on-surface-variant text-[12px] mb-2.5">
                            <span class="material-symbols-outlined text-[14px] shrink-0">location_on</span>
                            <span id="weather-location" class="truncate">Lokasi</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-primary text-[10px] font-bold uppercase tracking-[0.08em] mb-1.5 leading-none">
                            <span class="material-symbols-outlined text-[14px] leading-none shrink-0">auto_awesome</span> 
                            <span class="pt-0.5">Adaptasi Pintar</span>
                        </div>
                        <p class="text-[12px] text-[#4a4a4a] leading-relaxed" id="weather-desc">Frekuensi penyiraman dikurangi 20% hari ini karena perkiraan hujan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-[16px]">
            {{-- Card 1: Gardens & Plots --}}
            <a href="/garden-plots" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[#0f766e] text-[24px] mb-2">energy_savings_leaf</span>
                <div class="flex items-baseline gap-2 mb-1">
                    <span class="text-[36px] font-black text-on-surface leading-none">{{ count($gardens) }}</span>
                    <span class="text-[16px] font-bold text-on-surface-variant">/ {{ $gardens->sum(function($g) { return count($g->plots); }) }}</span>
                </div>
                <div class="text-[14px] text-on-surface font-medium text-center">Kebun / Plot</div>
            </a>
            {{-- Card 2: Active Plants --}}
            <a href="/growth-calendar" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-status-healthy text-[24px] mb-2">potted_plant</span>
                <div class="text-[36px] font-black text-on-surface leading-none mb-1">12</div>
                <div class="text-[14px] text-on-surface font-medium text-center">Tanaman Aktif</div>
            </a>

            {{-- Card 4 --}}
            <a href="/care-tasks" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[#f97316] text-[24px] mb-2">task_alt</span>
                <div class="text-[36px] font-black text-on-surface leading-none mb-1">3</div>
                <div class="text-[14px] text-on-surface font-medium text-center">Aktivitas Hari Ini</div>
            </a>
        </div>

        {{-- Map Row --}}
        <div class="flex flex-col gap-[24px]">
            
            {{-- Garden Plots --}}
            <div class="bg-surface rounded-[24px] p-[24px] md:p-[32px] ambient-shadow">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-[20px] font-bold text-on-surface">Plot Kebun</h3>
                    <div class="flex flex-wrap gap-4 text-[12px] font-bold text-on-surface-variant">
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-healthy)]"></span> Sehat</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-attention)]"></span> Perlu Perhatian</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-late)]"></span> Terlambat Dirawat</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-new)]"></span> Baru Ditanam</div>
                    </div>
                </div>
                
                <style>
                    .dash-bg-grid {
                        background-color: #f2f6f4;
                        background-image: 
                            linear-gradient(rgba(0, 108, 73, 0.05) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(0, 108, 73, 0.05) 1px, transparent 1px);
                        background-size: 24px 24px;
                        background-position: center center;
                    }
                    .dash-zone-box {
                        position: absolute;
                        border: 2px dashed;
                        border-radius: 20px;
                        background-color: rgba(255,255,255,0.95);
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        box-shadow: inset 0 0 0 2px rgba(255,255,255,0.6), 0 8px 24px rgba(0,0,0,0.04);
                        transition: transform 0.3s ease;
                    }
                    .dash-zone-box:hover {
                        transform: scale(1.02);
                        z-index: 10;
                    }
                    .dash-zone-label {
                        background-color: rgba(255,255,255,0.95);
                        padding: 8px 16px;
                        border-radius: 30px;
                        box-shadow: 0 8px 24px rgba(0, 108, 73, 0.08), inset 0 0 0 1px rgba(255,255,255,1);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                </style>

                <div class="dash-bg-grid rounded-[16px] w-full h-[240px] md:h-[300px] relative overflow-hidden border border-outline-variant/20 cursor-pointer group hover:border-primary/30 transition-colors" onclick="window.location.href='/garden-plots'">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-white/20 backdrop-blur-[2px] z-20">
                        <span class="bg-primary text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                            <span class="material-symbols-outlined">open_in_new</span>
                            Buka Plot Kebun
                        </span>
                    </div>

                    <div class="absolute w-[1000px] h-[600px] left-1/2 top-1/2 -translate-x-[40%] -translate-y-[45%] scale-[0.4] md:scale-[0.6]">
                        @if(count($gardens) > 0 && count($gardens->first()->plots) > 0)
                            @foreach($gardens->first()->plots as $plot)
                                <div class="dash-zone-box" style="left: {{ $plot->pos_x ?? 100 }}px; top: {{ $plot->pos_y ?? 100 }}px; width: {{ $plot->width ?? 120 }}px; height: {{ $plot->length ?? 120 }}px; border-color: var(--color-primary);">
                                    <div class="dash-zone-label" style="color: var(--color-primary);">
                                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                                        <span class="text-[14px] md:text-[15px] font-bold truncate tracking-tight">{{ $plot->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-on-surface-variant/50 text-[18px] font-bold">
                                Belum ada plot di kebun Anda
                            </div>
                        @endif
                    </div>
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
                {{-- Harvest Item --}}
                <div class="bg-surface-container-low rounded-[20px] p-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[24px]">eco</span>
                    </div>
                    <div class="flex flex-col h-full w-full">
                        <div class="text-[15px] font-bold text-on-surface leading-tight mb-1">Tomat Cherry</div>
                        <div class="text-[13px] text-on-surface-variant mb-4">Plot A1</div>
                        <div class="mt-auto flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-status-healthy text-[18px]">schedule</span>
                            <span class="text-[13.5px] font-bold text-status-healthy">2 hari lagi</span>
                        </div>
                    </div>
                </div>
                {{-- Harvest Item --}}
                <div class="bg-surface-container-low rounded-[20px] p-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[24px]">eco</span>
                    </div>
                    <div class="flex flex-col h-full w-full">
                        <div class="text-[15px] font-bold text-on-surface leading-tight mb-1">Cabai Rawit</div>
                        <div class="text-[13px] text-on-surface-variant mb-4">Plot B2</div>
                        <div class="mt-auto flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
                            <span class="text-[13.5px] font-bold text-primary">5 hari lagi</span>
                        </div>
                    </div>
                </div>
            </div>
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
                    title: 'Musim Hujan',
                    desc: 'Frekuensi penyiraman dikurangi 30% karena curah hujan tinggi di wilayah Anda.',
                    modifier: '-30%',
                    color: '#006c49'
                };
            case 'dry':
                return {
                    icon: 'sunny',
                    title: 'Musim Kemarau',
                    desc: 'Frekuensi penyiraman ditambah 50% untuk mengkompensasi penguapan tinggi.',
                    modifier: '+50%',
                    color: '#944a23'
                };
            default:
                return {
                    icon: 'partly_cloudy_day',
                    title: 'Cuaca Normal',
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

        document.getElementById('weather-icon').textContent = config.icon;
        document.getElementById('weather-title').textContent = config.title;
        document.getElementById('weather-desc').textContent = config.desc;
        document.getElementById('weather-location').textContent = locationData.formatted || province;
        document.getElementById('weather-icon').style.color = config.color;

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
                alert('Browser Anda tidak mendukung Geolocation.');
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
                    alert(msg);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }
});
</script>
@endpush
