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
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-[18px] font-bold text-on-surface">User Growth</h3>
                <div class="text-[12px] font-bold text-on-surface-variant border border-outline-variant/40 rounded-md px-3 py-1">Last 6 Months</div>
            </div>
            
            {{-- Mock Area Chart --}}
            <div class="relative h-[240px] w-full flex items-end">
                {{-- Y-Axis Labels --}}
                <div class="absolute left-0 top-0 bottom-6 flex flex-col justify-between text-[10px] text-on-surface-variant font-medium z-10 bg-surface-container-lowest pr-2">
                    <span>1,500</span>
                    <span>1,200</span>
                    <span>900</span>
                    <span>600</span>
                    <span>300</span>
                    <span>0</span>
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

                    {{-- Mock Line using SVG (Optimized Aspect Ratio) --}}
                    <svg class="absolute inset-0 w-full h-full z-10" preserveAspectRatio="none" viewBox="0 0 1000 250">
                        <defs>
                            <linearGradient id="gradientArea" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"></stop>
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.05"></stop>
                            </linearGradient>
                        </defs>
                        <path d="M 0 200 Q 200 150, 300 125 T 500 75 T 700 100 T 1000 25 L 1000 250 L 0 250 Z" fill="url(#gradientArea)"></path>
                        <path d="M 0 200 Q 200 150, 300 125 T 500 75 T 700 100 T 1000 25" fill="none" stroke="#006c49" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"></path>
                        
                        {{-- Data points --}}
                        <circle cx="0" cy="200" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="300" cy="125" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="500" cy="75" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="700" cy="100" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="1000" cy="25" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                    </svg>
                </div>

                {{-- X-Axis Labels --}}
                <div class="absolute bottom-0 left-10 right-0 flex justify-between text-[10px] text-on-surface-variant font-medium">
                    <span>Jan</span>
                    <span>Feb</span>
                    <span>Mar</span>
                    <span>Apr</span>
                    <span>May</span>
                    <span>Jun</span>
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

    {{-- Row 3: Aktivitas & Rata-rata Umur --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Aktivitas Terbanyak --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <h3 class="text-[18px] font-bold text-on-surface mb-6">Aktivitas Terbanyak</h3>
            
            <div class="flex flex-col justify-center gap-5 flex-1">
                @forelse($topActivities as $index => $activityStats)
                @php
                    $percentage = $totalCompletedEvents > 0 ? round(($activityStats->total / $totalCompletedEvents) * 100) : 0;
                    
                    // Style config
                    $styles = [
                        ['text' => 'text-primary', 'bg' => 'bg-primary'],
                        ['text' => 'text-primary-container', 'bg' => 'bg-primary-container'],
                        ['text' => 'text-secondary', 'bg' => 'bg-secondary'],
                        ['text' => 'text-outline-variant', 'bg' => 'bg-outline-variant'],
                    ];
                    $style = $styles[$index % count($styles)];
                @endphp
                <div>
                    <div class="flex justify-between text-[13px] font-bold text-on-surface mb-2">
                        <span>{{ $activityStats->eventType->name ?? 'Aktivitas' }}</span>
                        <span class="{{ $style['text'] }}">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full {{ $style['bg'] }} rounded-full" style="width: {{ $percentage }}%;"></div>
                    </div>
                </div>
                @empty
                <div class="text-[13px] text-on-surface-variant text-center my-auto">Belum ada aktivitas selesai.</div>
                @endforelse
            </div>
        </div>

        {{-- Rata-rata Umur Panen --}}
        <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col items-center justify-center text-center">
            <h3 class="text-[18px] font-bold text-on-surface mb-6 w-full text-left">Rata-rata Umur Panen</h3>
            
            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="text-[64px] font-black text-primary leading-none mb-2">{{ $avgHarvestAge }}</div>
                <div class="text-[20px] font-bold text-on-surface mb-4">Hari</div>
                
                <div class="flex items-center gap-1 text-[12px] text-primary font-bold">
                    <span class="material-symbols-outlined text-[16px]">info</span>
                    Rata-rata estimasi panen seluruh tanaman
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Aktivitas Hari Ini --}}
    <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-[18px] font-bold text-on-surface">Aktivitas Hari Ini</h3>
            <a href="#" class="text-[13px] font-bold text-primary hover:text-primary/80 transition-colors">View All</a>
        </div>

        <div class="overflow-x-auto w-full no-scrollbar">
            <table class="w-full min-w-[500px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 text-left">
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase px-2 w-1/2">User / Event</th>
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase px-2">Type</th>
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase px-2">Status</th>
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right px-2">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($todayActivities as $event)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                @php
                                    $initial = substr($event->plant->garden->user->name ?? 'U', 0, 1);
                                @endphp
                                <div class="w-8 h-8 rounded-full bg-primary-container/30 text-primary-container font-bold text-[12px] flex items-center justify-center shrink-0">
                                    {{ strtoupper($initial) }}
                                </div>
                                <div>
                                    <div class="text-[13px] font-bold text-on-surface truncate max-w-[200px]">{{ $event->plant->garden->user->name ?? 'User' }}</div>
                                    <div class="text-[11px] text-on-surface-variant truncate max-w-[200px]">{{ $event->plant->plantTemplate->name_id ?? 'Tanaman' }} ({{ $event->plant->garden->name ?? 'Kebun' }})</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-[13px] text-on-surface-variant">
                            {{ $event->eventType->name ?? 'Aktivitas' }}
                        </td>
                        <td class="py-3 px-2">
                            @if($event->status === 'COMPLETED')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#10b981]/10 text-[#10b981]">{{ $event->status }}</span>
                            @elseif($event->status === 'SKIPPED')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800">{{ $event->status }}</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-surface-container-highest text-on-surface-variant">{{ $event->status }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-2 text-[12px] text-on-surface-variant text-right">
                            {{ $event->updated_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-[13px] text-on-surface-variant">
                            Belum ada aktivitas tercatat hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
