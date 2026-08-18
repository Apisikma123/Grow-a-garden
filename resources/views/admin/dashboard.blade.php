@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Ringkasan Beranda Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div>
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight mb-1">Ringkasan Beranda</h1>
            <p class="text-[14px] text-on-surface-variant">Berikut ringkasan aktivitas kebun hari ini.</p>
        </div>
        <button class="px-5 py-2 rounded-full border-2 border-secondary/40 text-secondary font-bold text-[14px] hover:bg-secondary/5 transition-colors">
            Ekspor Laporan
        </button>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Pengguna --}}
        <a href="/admin/users" class="block bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Pengguna</div>
                <div class="w-8 h-8 rounded-full bg-primary-container/30 text-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px] text-primary">group</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">{{ number_format($totalUsers) }}</div>
            <div class="flex items-center gap-1 text-[11px] text-primary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +12% bulan ini
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </a>

        {{-- Total Kebun --}}
        <a href="#" class="block bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Kebun</div>
                <div class="w-8 h-8 rounded-full bg-tertiary-container/30 text-tertiary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">yard</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">{{ number_format($totalGardens) }}</div>
            <div class="flex items-center gap-1 text-[11px] text-tertiary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +8% bulan ini
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-tertiary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </a>

        {{-- Total Active Plants --}}
        <a href="/admin/plants" class="block bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Tanaman Aktif</div>
                <div class="w-8 h-8 rounded-full bg-secondary-container/30 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">potted_plant</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">{{ number_format($totalPlants) }}</div>
            <div class="flex items-center gap-1 text-[11px] text-secondary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +15% this month
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </a>

        {{-- Panen Berhasil --}}
        <a href="#" class="block bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Panen Berhasil</div>
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">shopping_basket</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">{{ number_format($successfulHarvests) }}</div>
            <div class="flex items-center gap-1 text-[11px] text-primary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">eco</span>
                Total panen pengguna
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary/5 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </a>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Growth Line Chart --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                <h3 class="text-[18px] font-bold text-on-surface">User Growth</h3>
                <form method="GET" action="{{ route('admin.dashboard') }}" id="growthPeriodForm">
                    <select name="growth_period" onchange="document.getElementById('growthPeriodForm').submit()" class="text-[12px] font-bold text-on-surface bg-surface border border-outline-variant/50 rounded-xl px-3 py-1.5 focus:outline-none focus:border-primary cursor-pointer shadow-sm">
                        <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>Bulan Ini (This Month)</option>
                        <option value="last_6_months" {{ $period === 'last_6_months' ? 'selected' : '' }}>6 Bulan Terakhir (Last 6 Months)</option>
                        <option value="last_12_months" {{ $period === 'last_12_months' ? 'selected' : '' }}>12 Bulan Terakhir (Last 12 Months)</option>
                    </select>
                </form>
            </div>

            @php
                $rawMax = max($userGrowth);
                if ($rawMax == 0) {
                    $yMax = 5;
                } elseif ($rawMax <= 5) {
                    $yMax = 5;
                } elseif ($rawMax <= 10) {
                    $yMax = 10;
                } elseif ($rawMax <= 50) {
                    $yMax = ceil($rawMax / 10) * 10;
                } elseif ($rawMax <= 100) {
                    $yMax = ceil($rawMax / 20) * 20;
                } else {
                    $yMax = ceil($rawMax / 100) * 100;
                }

                $yTicks = [
                    $yMax,
                    round($yMax * 0.8),
                    round($yMax * 0.6),
                    round($yMax * 0.4),
                    round($yMax * 0.2),
                    0
                ];

                $count = count($userGrowth);
                $stepX = $count > 1 ? 1000 / ($count - 1) : 1000;
                $points = [];
                foreach($userGrowth as $i => $val) {
                    $x = $i * $stepX;
                    // Leave padding at top
                    $y = 230 - (($val / $yMax) * 200);
                    $points[] = ['x' => $x, 'y' => $y, 'val' => $val];
                }
                
                $pathLine = "";
                $pathArea = "M 0 230 ";
                foreach($points as $i => $pt) {
                    $prefix = $i === 0 ? "M" : "L";
                    $pathLine .= "$prefix {$pt['x']} {$pt['y']} ";
                    $pathArea .= "L {$pt['x']} {$pt['y']} ";
                }
                $lastX = end($points)['x'] ?? 1000;
                $pathArea .= "L {$lastX} 230 Z";
            @endphp
            
            {{-- Dynamic Area Chart --}}
            <div class="relative h-[240px] w-full flex items-end">
                {{-- Y-Axis Labels --}}
                <div class="absolute left-0 top-0 bottom-6 flex flex-col justify-between text-[10px] text-on-surface-variant font-medium z-10 bg-surface-container-lowest pr-2 min-w-[30px] text-right">
                    @foreach($yTicks as $tick)
                    <span>{{ number_format($tick) }}</span>
                    @endforeach
                </div>

                {{-- Chart Area --}}
                <div class="ml-10 flex-1 h-[216px] relative border-b border-outline-variant/20 mb-6">
                    {{-- Grid Lines --}}
                    <div class="absolute inset-0 flex flex-col justify-between z-0">
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full"></div>
                    </div>

                    {{-- Dynamic Line Chart --}}
                    <svg class="absolute inset-0 w-full h-full z-10" preserveAspectRatio="none" viewBox="0 0 1000 250">
                        <defs>
                            <linearGradient id="gradientArea" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"></stop>
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.05"></stop>
                            </linearGradient>
                        </defs>
                        <path d="{{ $pathArea }}" fill="url(#gradientArea)"></path>
                        <path d="{{ $pathLine }}" fill="none" stroke="#006c49" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"></path>
                        
                        {{-- Data points --}}
                        @foreach($points as $pt)
                        <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke">
                            <title>Pengguna: {{ $pt['val'] }}</title>
                        </circle>
                        @endforeach
                    </svg>
                </div>

                {{-- X-Axis Labels --}}
                <div class="absolute bottom-0 left-10 right-0 flex justify-between text-[10px] text-on-surface-variant font-medium">
                    @foreach($growthLabels as $m)
                    <span>{{ $m }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tanaman Populer Bar Chart --}}
        <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <h3 class="text-[18px] font-bold text-on-surface mb-6">Tanaman Populer</h3>
            
            <div class="flex-1 flex flex-col justify-between gap-4 pb-6 border-b border-outline-variant/20 relative">
                
                <div class="absolute bottom-6 left-16 right-0 top-0 flex justify-between pointer-events-none z-0 border-l border-outline-variant/20">
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                </div>

                {{-- Bars --}}
                @foreach($popularPlants as $index => $plantStats)
                @php
                    // Hitung persentase lebar bar (maksimal dari tanaman paling populer)
                    $maxTotal = $popularPlants->max('total');
                    $width = $maxTotal > 0 ? ($plantStats->total / $maxTotal) * 100 : 0;
                    
                    // Warna berdasarkan index
                    $colors = ['bg-[#006c49]', 'bg-[#10b981]', 'bg-secondary-container', 'bg-tertiary-container', 'bg-inverse-primary'];
                    $color = $colors[$index % count($colors)];
                @endphp
                <div class="flex items-center gap-3 relative z-10" title="Total: {{ $plantStats->total }}">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right truncate">{{ $plantStats->plantTemplate->name_id ?? 'Unknown' }}</div>
                    <div class="flex-1 h-4">
                        <div class="h-full {{ $color }} rounded-r-md" style="width: {{ $width }}%;"></div>
                    </div>
                    <div class="text-[10px] text-on-surface-variant font-bold">{{ $plantStats->total }}</div>
                </div>
                @endforeach
            </div>
            
            <div class="flex justify-between pl-16 pt-2 text-[10px] text-on-surface-variant font-medium">
                <span>0</span>
                <span>5,000</span>
                <span>10,000</span>
            </div>
        </div>
    </div>
</div>
@endsection
