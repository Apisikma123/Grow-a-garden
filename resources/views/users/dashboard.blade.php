@extends('layouts.dashboard')

@section('title', 'Beranda — Grow a Garden')
@section('description', 'Ringkasan kebun Anda dan tugas harian.')

@section('dashboard-content')
    <div class="flex flex-col gap-6 pb-10 w-full overflow-x-hidden">
        
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 w-full">
            <div class="w-full lg:w-auto min-w-0">
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
                <div class="flex items-center gap-3 flex-wrap mb-1">
                    <h1 class="text-[26px] sm:text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight break-words">{{ $greeting }}, {{ $userName }}!</h1>
                    @if(auth()->check())
                        <a href="/settings#subscription" class="text-[12px] font-extrabold px-3.5 py-1 rounded-full bg-primary/10 text-primary border border-primary/20 hover:bg-primary/20 transition-all flex items-center gap-1.5 shrink-0 cursor-pointer" title="Kelola Paket Langganan">
                            <span class="material-symbols-outlined text-[14px]">
                                {{ auth()->user()->role === 'premium' ? 'workspace_premium' : (auth()->user()->role === 'pro' ? 'star' : 'eco') }}
                            </span>
                            Paket {{ auth()->user()->planName() }}
                        </a>
                    @endif
                </div>
                <p class="text-[14px] sm:text-[16px] text-on-surface-variant leading-relaxed">Kebun Anda tumbuh dengan baik. Mari lihat apa yang perlu dirawat hari ini.</p>
            </div>
            
            {{-- Weather Widget (Dynamic - Premium Card Style) --}}
            <div id="weather-widget" class="bg-white rounded-3xl p-5 sm:p-6 md:p-8 ambient-shadow max-w-[480px] w-full transition-all duration-500 shrink-0 border border-outline-variant/20">
                
                {{-- Default: Ask Location State --}}
                <div id="weather-ask" class="flex flex-col gap-4">
                    <div class="flex items-center justify-between mb-2 border-b border-outline-variant/30 pb-4 gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="material-symbols-outlined text-on-surface-variant shrink-0" style="font-size: 24px;">location_off</span>
                            <span class="text-xs sm:text-sm font-bold text-on-surface truncate">Adaptasi Pintar Aktif?</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-semibold text-on-surface-variant bg-surface-container-high px-2.5 py-1 rounded-full shrink-0">Offline</span>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <h3 class="font-bold text-[13px] sm:text-[14px] text-on-surface mb-2">Aktifkan Lokasi Kebun</h3>
                        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-4">
                            Deteksi lokasi untuk penyesuaian cuaca otomatis pada jadwal penyiraman harian.
                        </p>
                        <button type="button" id="dash-detect-location" class="w-full bg-primary text-on-primary text-xs sm:text-sm font-bold py-2.5 rounded-xl hover:bg-primary-container hover:text-on-primary-container active:scale-[0.98] transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
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
                            <span class="text-xs sm:text-sm font-bold text-on-surface">Mendeteksi...</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4 flex flex-col items-center text-center justify-center min-h-[140px]">
                        <h3 class="font-bold text-[13px] sm:text-[14px] text-on-surface mb-2">Mencari Koordinat</h3>
                        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                            Sedang mencari data cuaca regional Anda.
                        </p>
                    </div>
                </div>

                {{-- Active Weather State --}}
                <div id="weather-active" class="hidden flex flex-col w-full min-w-0">
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between mb-5 gap-2">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <span class="material-symbols-outlined text-on-surface-variant hidden sm:inline-block shrink-0" style="font-size: 22px;" id="weather-icon-1">cloud</span>
                            <span class="material-symbols-outlined text-on-surface-variant hidden sm:inline-block shrink-0" style="font-size: 22px;" id="weather-icon-2">water_drop</span>
                            <span class="material-symbols-outlined text-on-surface-variant shrink-0" style="font-size: 22px;" id="weather-icon-main">thermostat</span>
                            <span class="text-xs sm:text-sm font-bold text-on-surface truncate min-w-0" id="weather-title">Prediksi Cuaca: Hujan</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-semibold px-2.5 py-1 rounded-full whitespace-nowrap shrink-0" id="weather-badge">Hujan Ringan</span>
                    </div>

                    {{-- Card Body --}}
                    <div class="bg-surface-container-low rounded-xl p-4 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary flex-shrink-0 mt-0.5" style="font-size: 20px;">info</span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-primary uppercase tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[14px]">auto_awesome</span> Adaptasi Pintar
                            </div>
                            <p class="text-[12px] sm:text-[13px] text-on-surface-variant leading-relaxed mb-3 break-words" id="weather-desc">
                                Jadwal penyiraman otomatis ditunda hari ini karena curah hujan yang cukup.
                            </p>
                            <div class="flex items-center gap-1.5 text-[11px] text-on-surface-variant/80 border-t border-outline-variant/30 pt-3 min-w-0">
                                <span class="material-symbols-outlined text-[14px] shrink-0">location_on</span>
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
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 w-full">
            {{-- Card 1: Gardens --}}
            <a href="/gardens" class="bg-surface rounded-[24px] p-5 sm:p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer border border-outline-variant/20 group">
                <span class="material-symbols-outlined text-[#0f766e] text-[28px] mb-2 group-hover:scale-110 transition-transform">energy_savings_leaf</span>
                <div class="text-[32px] sm:text-[36px] font-black text-on-surface leading-none mb-1">{{ count($gardens) }}</div>
                <div class="text-[13px] sm:text-[14px] text-on-surface font-medium text-center truncate w-full">Kebun</div>
            </a>
            {{-- Card 2: Active Plants --}}
            <a href="/gardens" class="bg-surface rounded-[24px] p-5 sm:p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer border border-outline-variant/20 group">
                <span class="material-symbols-outlined text-status-healthy text-[28px] mb-2 group-hover:scale-110 transition-transform">potted_plant</span>
                <div class="text-[32px] sm:text-[36px] font-black text-on-surface leading-none mb-1">{{ $activePlants }}</div>
                <div class="text-[13px] sm:text-[14px] text-on-surface font-medium text-center truncate w-full">Tanaman Aktif</div>
            </a>

            {{-- Card 3: Today Tasks --}}
            <a href="/care-tasks" class="bg-surface rounded-[24px] p-5 sm:p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer border border-outline-variant/20 group">
                <span class="material-symbols-outlined text-[#f97316] text-[28px] mb-2 group-hover:scale-110 transition-transform">task_alt</span>
                <div class="text-[32px] sm:text-[36px] font-black text-on-surface leading-none mb-1">{{ $todayTasks->count() }}</div>
                <div class="text-[13px] sm:text-[14px] text-on-surface font-medium text-center truncate w-full">Aktivitas Hari Ini</div>
            </a>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
            
            {{-- Plant Distribution --}}
            <div class="bg-surface rounded-[24px] p-5 sm:p-6 md:p-8 ambient-shadow flex flex-col justify-between border border-outline-variant/20">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-6 w-full">
                    <h3 class="text-[18px] sm:text-[20px] font-bold text-on-surface truncate">Distribusi Tanaman</h3>
                    <span class="text-[11px] sm:text-[12px] bg-primary/10 text-primary font-bold px-3 py-1 rounded-full whitespace-nowrap">
                        {{ $activePlants }} Tanaman Aktif
                    </span>
                </div>
                
                <div class="flex justify-center mb-8 relative">
                    {{-- CSS Conic Gradient Donut Chart --}}
                    <div class="w-44 h-44 sm:w-52 sm:h-52 md:w-56 md:h-56 rounded-full flex items-center justify-center transition-all duration-500 shadow-md relative shrink-0" style="background: {{ $conicGradient }};">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 md:w-36 md:h-36 bg-surface rounded-full shadow-inner flex flex-col items-center justify-center text-center p-2 z-10">
                            <span class="text-[28px] sm:text-[32px] md:text-[36px] font-black text-on-surface leading-none mb-1">{{ $activePlants }}</span>
                            <span class="text-[10px] sm:text-[12px] font-semibold text-on-surface-variant">Total Tanaman</span>
                        </div>
                    </div>
                </div>

                {{-- Categories Breakdown with exact numbers & percentages --}}
                <div class="flex flex-wrap justify-center gap-2 sm:gap-3 text-[12px] sm:text-[13px] font-bold text-on-surface-variant border-t border-outline-variant/20 pt-4 w-full">
                    @forelse($plantDistribution as $item)
                        <div class="flex items-center gap-1.5 sm:gap-2 bg-surface-container-low px-2.5 py-1.5 rounded-xl max-w-full">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $item['color'] }}"></span>
                            <span class="text-on-surface font-semibold truncate">{{ $item['name'] }}:</span>
                            <span class="font-extrabold text-on-surface shrink-0">{{ $item['count'] }}</span>
                            <span class="text-[10px] sm:text-[11px] text-on-surface-variant font-normal shrink-0">({{ $item['percentage'] }}%)</span>
                        </div>
                    @empty
                        <div class="text-center text-on-surface-variant text-[12px] sm:text-[13px]">Belum ada data tanaman aktif.</div>
                    @endforelse
                </div>
            </div>

            {{-- Weekly Care Activity --}}
            <div class="bg-surface rounded-[24px] p-5 sm:p-6 md:p-8 ambient-shadow flex flex-col justify-between border border-outline-variant/20">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-6 w-full">
                    <h3 class="text-[18px] sm:text-[20px] font-bold text-on-surface truncate">Aktivitas Perawatan Mingguan</h3>
                    <span class="text-[11px] sm:text-[12px] bg-primary/10 text-primary font-bold px-3 py-1 rounded-full whitespace-nowrap">
                        {{ $weeklyTotals['total'] }} Perawatan Minggu Ini
                    </span>
                </div>
                
                {{-- Dynamic Bar Chart --}}
                <div class="h-48 sm:h-56 flex items-end justify-between gap-1 sm:gap-2 md:gap-2.5 mb-2 border-b border-outline-variant/20 pb-2 relative pt-8 w-full">
                    {{-- Y Axis Grid Lines --}}
                    <div class="absolute inset-0 flex flex-col justify-between z-0 pointer-events-none pt-8 pb-2">
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                    </div>

                    {{-- Bars for each day of the week --}}
                    @foreach($weeklyDays as $day)
                        <div class="flex flex-col items-center justify-end w-full h-full relative z-10 group">
                            {{-- Number badge above the bar --}}
                            <div class="mb-1 text-[9px] sm:text-[11px] font-black text-on-surface transition-all group-hover:scale-110 {{ $day['isToday'] ? 'text-primary scale-105' : '' }}">
                                {{ $day['total'] }}
                            </div>

                            {{-- Stacked Bar Column --}}
                            <div class="w-full flex flex-col justify-end gap-0.5 rounded-t-md overflow-hidden bg-surface-container-low transition-all duration-300" style="height: {{ max($day['heightPct'], $day['total'] > 0 ? 12 : 4) }}%;">
                                @if($day['prune'] > 0)
                                    <div class="bg-[#78a994] w-full flex items-center justify-center text-white text-[9px] sm:text-[10px] font-bold transition-opacity hover:opacity-90" 
                                         style="height: {{ $day['prunePct'] }}%" 
                                         title="Memangkas / Perawatan lain: {{ $day['prune'] }}">
                                        @if($day['prunePct'] >= 25) {{ $day['prune'] }} @endif
                                    </div>
                                @endif

                                @if($day['fertilize'] > 0)
                                    <div class="bg-[#944a23] w-full flex items-center justify-center text-white text-[9px] sm:text-[10px] font-bold transition-opacity hover:opacity-90" 
                                         style="height: {{ $day['fertilizePct'] }}%" 
                                         title="Memupuk: {{ $day['fertilize'] }}">
                                        @if($day['fertilizePct'] >= 25) {{ $day['fertilize'] }} @endif
                                    </div>
                                @endif

                                @if($day['water'] > 0)
                                    <div class="bg-status-healthy w-full flex items-center justify-center text-white text-[9px] sm:text-[10px] font-bold transition-opacity hover:opacity-90" 
                                         style="height: {{ $day['waterPct'] }}%" 
                                         title="Menyiram: {{ $day['water'] }}">
                                        @if($day['waterPct'] >= 25) {{ $day['water'] }} @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Day Names --}}
                <div class="flex justify-between px-0.5 sm:px-1 text-[10px] sm:text-[12px] font-bold mb-6 w-full">
                    @foreach($weeklyDays as $day)
                        <div class="w-full text-center">
                            <span class="inline-block px-1.5 sm:px-2 py-0.5 rounded-full {{ $day['isToday'] ? 'bg-primary text-on-primary text-[10px] sm:text-[11px] font-extrabold shadow-sm' : 'text-on-surface-variant' }}">
                                {{ $day['day'] }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Legend with exact counts --}}
                <div class="flex justify-center gap-2 sm:gap-4 text-[11px] sm:text-[13px] font-bold text-on-surface-variant border-t border-outline-variant/20 pt-4 flex-wrap w-full">
                    <div class="flex items-center gap-1.5 bg-surface-container-low px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl">
                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-status-healthy"></span>
                        <span class="text-on-surface font-medium">Menyiram:</span>
                        <span class="text-on-surface font-extrabold">{{ $weeklyTotals['water'] }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-surface-container-low px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl">
                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#944a23]"></span>
                        <span class="text-on-surface font-medium">Memupuk:</span>
                        <span class="text-on-surface font-extrabold">{{ $weeklyTotals['fertilize'] }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-surface-container-low px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl">
                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#78a994]"></span>
                        <span class="text-on-surface font-medium">Memangkas:</span>
                        <span class="text-on-surface font-extrabold">{{ $weeklyTotals['prune'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Harvest Row --}}
        <div class="bg-surface rounded-[24px] p-5 sm:p-6 md:p-8 ambient-shadow mb-6 border border-outline-variant/20 w-full">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
                <h3 class="text-[18px] sm:text-[20px] font-bold text-on-surface">Panen Mendatang</h3>
                <a href="/growth-calendar" class="text-[13px] sm:text-[14px] font-bold text-primary hover:underline whitespace-nowrap">Lihat Kalender</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
                @forelse($upcomingHarvests as $plant)
                {{-- Harvest Item --}}
                <div class="bg-surface-container-low rounded-[20px] p-4 sm:p-5 flex items-start gap-3 sm:gap-4 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[20px] sm:text-[24px]">eco</span>
                    </div>
                    <div class="flex flex-col h-full w-full min-w-0">
                        <div class="text-[14px] sm:text-[15px] font-bold text-on-surface leading-tight mb-1 truncate">{{ $plant->plantTemplate->name_id ?? 'Unknown' }}</div>
                        <div class="text-[12px] sm:text-[13px] text-on-surface-variant mb-4 truncate">{{ $plant->garden->name ?? 'Kebun' }}</div>
                        <div class="mt-auto flex items-center gap-1.5 shrink-0">
                            <span class="material-symbols-outlined text-status-healthy text-[16px] sm:text-[18px]">schedule</span>
                            <span class="text-[12.5px] sm:text-[13.5px] font-bold text-status-healthy">{{ $plant->estimated_harvest_days === 0 ? 'Hari ini' : $plant->estimated_harvest_days . ' hari lagi' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8 sm:py-10">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[28px] sm:text-[32px] text-on-surface-variant">eco</span>
                    </div>
                    <p class="text-sm text-on-surface-variant">Belum ada tanaman yang mendekati masa panen.</p>
                </div>
                @endforelse
            </div>
        </div>
</div>
@endsection

@push('scripts')
<script>
function initDashboard() {
    // ── Indonesian Regional Season Map ──
    const RAINY_MONTHS = {
        'Aceh':               [9,10,11,12,1,2],
        'Sumatera Utara':     [9,10,11,12,1,2],
        'Sumatera Barat':     [9,10,11,12,1,2,3],
        'Riau':               [10,11,12,1],
        'Kepulauan Riau':     [10,11,12,1],
        'Jambi':              [10,11,12,1,2,3],
        'Sumatera Selatan':   [10,11,12,1,2,3,4],
        'Bangka Belitung':    [11,12,1,2,3],
        'Bengkulu':           [10,11,12,1,2,3],
        'Lampung':            [11,12,1,2,3,4],
        'DKI Jakarta':        [11,12,1,2,3,4],
        'Jawa Barat':         [10,11,12,1,2,3,4,5],
        'Banten':             [11,12,1,2,3,4],
        'Jawa Tengah':        [11,12,1,2,3,4],
        'DI Yogyakarta':      [11,12,1,2,3,4],
        'Jawa Timur':         [12,1,2,3,4],
        'Bali':               [12,1,2,3,4],
        'Nusa Tenggara Barat': [12,1,2,3],
        'Nusa Tenggara Timur': [12,1,2,3],
        'Kalimantan Barat':   [9,10,11,12,1,2,3],
        'Kalimantan Tengah':  [10,11,12,1,2,3,4],
        'Kalimantan Selatan': [11,12,1,2,3,4],
        'Kalimantan Timur':   [10,11,12,1,2,3],
        'Kalimantan Utara':   [9,10,11,12,1,2,3],
        'Sulawesi Utara':     [11,12,1,2,3,4,5],
        'Gorontalo':          [5,6,7,11,12,1],
        'Sulawesi Tengah':    [4,5,6,7,12,1],
        'Sulawesi Barat':     [11,12,1,2,3,4],
        'Sulawesi Selatan':   [12,1,2,3,4,5],
        'Sulawesi Tenggara':  [4,5,6,7,12,1],
        'Maluku':             [5,6,7,8],
        'Maluku Utara':       [5,6,7,11,12,1],
        'Papua Barat':        [1,2,3,4,5,6],
        'Papua':              [1,2,3,4,5,6]
    };

    function getSeason(province) {
        if (!province) return 'normal';
        const month = new Date().getMonth() + 1;
        const rainyMonths = RAINY_MONTHS[province] || [11, 12, 1, 2, 3];
        return rainyMonths.includes(month) ? 'rainy' : 'dry';
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

    async function fetchLiveWeather(lat, lon) {
        if (!lat || !lon) return null;
        try {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,weather_code,rain,showers,precipitation&daily=weather_code,temperature_2m_max,precipitation_probability_max&timezone=Asia/Jakarta`;
            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), 3500);
            const resp = await fetch(url, { signal: controller.signal });
            clearTimeout(timer);
            if (resp.ok) {
                const data = await resp.json();
                const current = data.current || {};
                const daily = data.daily || {};
                
                const temp = Math.round(current.temperature_2m ?? daily.temperature_2m_max?.[0] ?? 29);
                const rainProb = daily.precipitation_probability_max?.[0] ?? 0;
                const windSpeed = Math.round(current.windspeed ?? daily.wind_speed_10m_max?.[0] ?? 10);
                const wCode = current.weathercode ?? daily.weather_code?.[0] ?? 0;

                return { temp, rainProb, windSpeed, wCode };
            }
        } catch(e) {
            console.warn('Open-Meteo live weather fetch failed', e);
        }
        return null;
    }

    function cleanLocationName(name) {
        if (!name) return 'Lokasi Kebun';
        let cleaned = String(name).replace(/\s*\([\d\.\,\s\-]+\)/gi, '').trim();
        if (cleaned === 'Lokasi Terdeteksi' || cleaned === 'Kota Terdeteksi' || !cleaned) {
            return 'Lokasi Kebun';
        }
        return cleaned;
    }

    async function applyWeather(locationData = null) {
        showWeatherState('loading');
        let queryStr = '';
        if (locationData && locationData.lat && locationData.lon) {
            queryStr = `?lat=${locationData.lat}&lng=${locationData.lon}`;
        }

        try {
            const resp = await fetch(`/api/weather/live${queryStr}`);
            if (resp.ok) {
                const apiData = await resp.json();
                if (apiData.success && apiData.agronomic) {
                    const agro = apiData.agronomic;
                    const temp = agro.temperature || 29;
                    const rawName = (apiData.location && apiData.location.name) 
                        ? apiData.location.name 
                        : (locationData ? (locationData.formatted || locationData.name || locationData.city) : 'Lokasi Kebun');
                    const locName = cleanLocationName(rawName);

                    document.getElementById('weather-icon-main').textContent = agro.icon || 'partly_cloudy_day';
                    document.getElementById('weather-title').textContent = `Cuaca: ${agro.condition_title || 'Cerah Berawan'}`;
                    document.getElementById('weather-desc').textContent = `${agro.summary} ${agro.watering ? agro.watering.advice : ''}`;
                    document.getElementById('weather-location').textContent = locName;

                    const badgeElem = document.getElementById('weather-badge');
                    badgeElem.textContent = `${agro.condition_title} (${temp}°C)`;
                    badgeElem.className = `text-[10px] sm:text-[11px] font-semibold px-2.5 py-1 rounded-full whitespace-nowrap ${agro.badge_bg}`;

                    showWeatherState('active');

                    // Sync to local storage for consistency
                    if (!locationData && apiData.location) {
                        const syncLoc = {
                            lat: apiData.location.latitude,
                            lon: apiData.location.longitude,
                            name: apiData.location.name || 'Lokasi Kebun',
                            formatted: apiData.location.name || 'Lokasi Kebun'
                        };
                        localStorage.setItem('garden_location', JSON.stringify(syncLoc));
                    }
                    return;
                }
            }
        } catch(e) {
            console.warn('Backend live weather fetch error:', e);
        }

        showWeatherState('ask');
    }

    // Auto-load on startup: load garden weather from DB or local storage or auto-detect
    async function loadInitialWeather() {
        const saved = localStorage.getItem('garden_location');
        if (saved) {
            try {
                await applyWeather(JSON.parse(saved));
                return;
            } catch(e){}
        }

        // Try DB live weather directly
        try {
            const resp = await fetch('/api/weather/live');
            if (resp.ok) {
                const apiData = await resp.json();
                if (apiData.success && apiData.agronomic) {
                    await applyWeather(null);
                    return;
                }
            }
        } catch(e){}

        // Auto-detect fast location fallback
        try {
            const locData = await getFastLocation();
            localStorage.setItem('garden_location', JSON.stringify(locData));
            await applyWeather(locData);
        } catch(e) {
            showWeatherState('ask');
        }
    }

    loadInitialWeather();

    // Listen to real-time location changes across tabs or pages
    window.addEventListener('storage', (e) => {
        if (e.key === 'garden_location' && e.newValue) {
            try { applyWeather(JSON.parse(e.newValue)); } catch(err){}
        }
    });

    window.addEventListener('garden_location_updated', (e) => {
        if (e.detail) {
            try { applyWeather(e.detail); } catch(err){}
        }
    });

    // Automatic Live Weather Background Sync (Every 2 minutes)
    setInterval(async () => {
        const currentLoc = localStorage.getItem('garden_location');
        if (currentLoc) {
            try {
                await applyWeather(JSON.parse(currentLoc));
            } catch(e){}
        } else {
            await applyWeather(null);
        }
    }, 120000);

    const INDONESIA_PROVINCES = [
        'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi', 
        'Sumatera Selatan', 'Bangka Belitung', 'Bengkulu', 'Lampung', 'DKI Jakarta', 
        'Jawa Barat', 'Banten', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Bali', 
        'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah', 
        'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara', 
        'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara', 
        'Maluku', 'Maluku Utara', 'Papua Barat', 'Papua'
    ];

    const PROVINCE_MAP = {
        'north sumatra': 'Sumatera Utara',
        'north sumatera': 'Sumatera Utara',
        'sumatra utara': 'Sumatera Utara',
        'sumatra': 'Sumatera Utara',
        'medan': 'Sumatera Utara',
        'kota medan': 'Sumatera Utara',
        'percut': 'Sumatera Utara',
        'deli serdang': 'Sumatera Utara',
        'west java': 'Jawa Barat',
        'bandung': 'Jawa Barat',
        'central java': 'Jawa Tengah',
        'semarang': 'Jawa Tengah',
        'east java': 'Jawa Timur',
        'surabaya': 'Jawa Timur',
        'jakarta': 'DKI Jakarta',
        'dki jakarta': 'DKI Jakarta',
        'yogyakarta': 'DI Yogyakarta',
        'jogja': 'DI Yogyakarta',
        'west sumatra': 'Sumatera Barat',
        'padang': 'Sumatera Barat',
        'south sumatra': 'Sumatera Selatan',
        'palembang': 'Sumatera Selatan',
        'west kalimantan': 'Kalimantan Barat',
        'pontianak': 'Kalimantan Barat',
        'central kalimantan': 'Kalimantan Tengah',
        'south kalimantan': 'Kalimantan Selatan',
        'east kalimantan': 'Kalimantan Timur',
        'samarinda': 'Kalimantan Timur',
        'balikpapan': 'Kalimantan Timur',
        'north kalimantan': 'Kalimantan Utara',
        'north sulawesi': 'Sulawesi Utara',
        'manado': 'Sulawesi Utara',
        'central sulawesi': 'Sulawesi Tengah',
        'west sulawesi': 'Sulawesi Barat',
        'south sulawesi': 'Sulawesi Selatan',
        'makassar': 'Sulawesi Selatan',
        'southeast sulawesi': 'Sulawesi Tenggara',
        'west nusa tenggara': 'Nusa Tenggara Barat',
        'mataram': 'Nusa Tenggara Barat',
        'east nusa tenggara': 'Nusa Tenggara Timur',
        'kupang': 'Nusa Tenggara Timur',
        'north maluku': 'Maluku Utara',
        'west papua': 'Papua Barat',
        'papua': 'Papua'
    };

    function normalizeProvinceName(str) {
        if (!str) return 'Sumatera Utara';
        const lower = str.toLowerCase().trim();
        for (const p of INDONESIA_PROVINCES) {
            if (p.toLowerCase() === lower) return p;
        }
        for (const [k, v] of Object.entries(PROVINCE_MAP)) {
            if (lower.includes(k) || k.includes(lower)) return v;
        }
        for (const p of INDONESIA_PROVINCES) {
            if (lower.includes(p.toLowerCase()) || p.toLowerCase().includes(lower)) return p;
        }
        return 'Sumatera Utara';
    }

    // Fast & Reliable Location Detector with GPS & IP Fallback
    async function getFastLocation() {
        let coords = null;
        if (navigator.geolocation) {
            coords = await new Promise((resolve) => {
                let done = false;
                const timer = setTimeout(() => { if (!done) { done = true; resolve(null); } }, 3000);
                navigator.geolocation.getCurrentPosition(
                    (pos) => { if (!done) { done = true; clearTimeout(timer); resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude }); } },
                    () => { if (!done) { done = true; clearTimeout(timer); resolve(null); } },
                    { enableHighAccuracy: false, timeout: 2500, maximumAge: 60000 }
                );
            });
        }

        if (coords) {
            try {
                const controller = new AbortController();
                const fetchTimer = setTimeout(() => controller.abort(), 3000);
                const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${coords.lat}&lon=${coords.lon}&zoom=10`, {
                    headers: { 'Accept-Language': 'id, en' },
                    signal: controller.signal
                });
                clearTimeout(fetchTimer);
                if (resp.ok) {
                    const data = await resp.json();
                    const addr = data.address || {};
                    const city = addr.city || addr.town || addr.municipality || addr.city_district || addr.county || 'Kota Terdeteksi';
                    const state = addr.state || addr.region || city;
                    const normProv = normalizeProvinceName(state || city);
                    return {
                        lat: coords.lat,
                        lon: coords.lon,
                        city: city,
                        region: normProv,
                        country: addr.country || 'Indonesia',
                        formatted: `${city}, ${normProv}, Indonesia`
                    };
                }
            } catch (e) {
                console.warn('Reverse geocode timeout/fail, using IP fallback', e);
            }
        }

        // IP Geolocation fallback via ipwho.is
        try {
            const controller = new AbortController();
            const fetchTimer = setTimeout(() => controller.abort(), 3000);
            const resp = await fetch('https://ipwho.is/', { signal: controller.signal });
            clearTimeout(fetchTimer);
            if (resp.ok) {
                const ipData = await resp.json();
                if (ipData.success) {
                    const city = ipData.city || 'Kota Terdeteksi';
                    const rawState = ipData.region || ipData.city || '';
                    const normProv = normalizeProvinceName(rawState);
                    return {
                        lat: ipData.latitude || 0,
                        lon: ipData.longitude || 0,
                        city: city,
                        region: normProv,
                        country: 'Indonesia',
                        formatted: `${city}, ${normProv}, Indonesia`
                    };
                }
            }
        } catch (e) {
            console.warn('ipwho.is fallback failed', e);
        }

        // Secondary fallback to ip-api.com
        try {
            const controller = new AbortController();
            const fetchTimer = setTimeout(() => controller.abort(), 3000);
            const resp = await fetch('http://ip-api.com/json', { signal: controller.signal });
            clearTimeout(fetchTimer);
            if (resp.ok) {
                const data = await resp.json();
                if (data.status === 'success') {
                    const city = data.city || 'Kota Terdeteksi';
                    const rawState = data.regionName || data.region || '';
                    const normProv = normalizeProvinceName(rawState);
                    return {
                        lat: data.lat || 0,
                        lon: data.lon || 0,
                        city: city,
                        region: normProv,
                        country: 'Indonesia',
                        formatted: `${city}, ${normProv}, Indonesia`
                    };
                }
            }
        } catch (e) {
            console.warn('ip-api.com fallback failed', e);
        }

        return {
            lat: 3.58,
            lon: 98.67,
            city: 'Kota Medan',
            region: 'Sumatera Utara',
            country: 'Indonesia',
            formatted: 'Kota Medan, Sumatera Utara, Indonesia'
        };
    }

    const detectBtn = document.getElementById('dash-detect-location');
    if (detectBtn) {
        detectBtn.addEventListener('click', async () => {
            showWeatherState('loading');
            try {
                const locationData = await getFastLocation();
                localStorage.setItem('garden_location', JSON.stringify(locationData));
                applyWeather(locationData);
            } catch (err) {
                console.error('Dash detect location error:', err);
                showWeatherState('ask');
            }
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}
</script>
@endpush
