@extends('layouts.dashboard')

@section('title', 'Kalender Tanam — Grow a Garden')
@section('description', 'Pantau dan kelola tahap pertumbuhan tanaman Anda.')

@section('dashboard-content')
<div class="relative min-h-[80vh] pb-10">

    {{-- 1-Page Scoped Blur Paywall Overlay for Free Users --}}
    @if(isset($isLocked) && $isLocked)
        {{-- Background blur scoped ONLY inside page content canvas (Does NOT blur sidebar/navbar) --}}
        <div class="absolute -inset-5 md:-inset-8 z-20 bg-slate-900/60 backdrop-blur-md"></div>

        {{-- Fixed Pop-up Card centered in user screen/viewport --}}
        <div class="fixed inset-0 z-30 flex items-center justify-center p-4 pointer-events-none md:pl-64">
            <div class="bg-gradient-to-br from-[#0f172a] to-[#1e293b] text-white rounded-[32px] p-6 sm:p-10 text-center shadow-2xl border border-yellow-500/30 max-w-lg w-full pointer-events-auto flex flex-col items-center my-auto">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg mx-auto mb-5 sm:mb-6 shadow-yellow-500/30 ring-8 ring-yellow-500/10 shrink-0">
                    <span class="material-symbols-outlined text-[32px] sm:text-[40px] text-white">lock</span>
                </div>
                <div class="text-center w-full min-w-full max-w-md mx-auto self-stretch flex flex-col items-center" style="width: 100% !important; min-width: 100% !important; text-align: center !important;">
                    <h2 class="text-[22px] sm:text-[26px] md:text-[28px] font-black text-white mb-3" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">Growth Calendar Terkunci</h2>
                    <p class="text-[13px] sm:text-[14px] md:text-[15px] text-slate-300 leading-relaxed mb-6 sm:mb-8" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">
                        Fitur Growth Calendar khusus untuk pengguna Paket Subur (Pro) dan Panen Raya (Premium). Upgrade akun Anda sekarang untuk membuka grafik & estimasi fase pertumbuhan tanaman secara detail.
                    </p>
                </div>
                <button type="button" onclick="document.getElementById('pricing-modal').classList.remove('hidden')" class="w-full bg-yellow-400 text-yellow-900 font-bold text-[15px] sm:text-[16px] py-3.5 sm:py-4 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all shadow-lg flex items-center justify-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[20px] sm:text-[22px]">star</span>
                    Upgrade Sekarang
                </button>
            </div>
        </div>
    @endif

    {{-- Main Page Content (Blurred if Free user) --}}
    <div class="flex flex-col gap-[24px] {{ (isset($isLocked) && $isLocked) ? 'filter blur-md opacity-50 pointer-events-none select-none' : '' }}">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-2">
            <div>
                <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight mb-2">Kalender Tanam</h1>
                <p class="text-[16px] text-on-surface-variant max-w-xl leading-[24px]">Pantau linimasa pertumbuhan cerdas yang beradaptasi dengan kondisi kebun Anda secara real-time.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-[24px]">
            {{-- Main Timeline Container (Takes up 3 columns) --}}
            <div class="lg:col-span-3 bg-surface rounded-[24px] p-[24px] md:p-[40px] ambient-shadow-lg">
                
                @if($mainPlant)
                {{-- Premium Plant Profile Header --}}
                <div class="relative bg-[#f1f5f2] rounded-[24px] p-6 mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-outline-variant/30 overflow-hidden">
                    {{-- Decorative Ambient Glow --}}
                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex flex-col sm:flex-row items-center sm:items-start md:items-center gap-5 relative z-10 w-full md:w-auto">
                        <div class="relative group cursor-pointer shrink-0">
                            @php
                                $imgSrc = 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=200&h=200&fit=crop&q=80'; // Default
                                if(str_contains(strtolower($mainPlant->plantTemplate->name_id), 'bayam') || str_contains(strtolower($mainPlant->plantTemplate->name_id), 'selada')) {
                                    $imgSrc = 'https://images.unsplash.com/photo-1622383563227-04401ab4e5ea?w=200&h=200&fit=crop&q=80';
                                }
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $mainPlant->plantTemplate->name_id }}" class="w-24 h-24 md:w-28 md:h-28 rounded-[20px] object-cover shadow-[0_8px_24px_rgba(0,108,73,0.15)] border-[3px] border-white transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute -bottom-3 -right-3 bg-white rounded-full p-2 shadow-sm border border-outline-variant/20">
                                <span class="material-symbols-outlined text-[18px] text-primary">eco</span>
                            </div>
                        </div>
                        <div class="text-center sm:text-left mt-2 sm:mt-0">
                            <h2 class="text-[28px] md:text-[32px] font-black text-on-surface leading-tight mb-3 tracking-tight">{{ collect($timeline)->where('status', 'active')->first()['label'] ?? 'Panen' }} {{ $mainPlant->plantTemplate->name_id }}</h2>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                <span class="bg-primary/10 text-primary px-3 py-1.5 rounded-full text-[12px] font-bold tracking-wider uppercase border border-primary/20 shadow-sm backdrop-blur-md">Fase {{ collect($timeline)->where('status', 'active')->first()['label'] ?? 'Panen' }}</span>
                                <span class="bg-[#944a23]/10 text-[#944a23] px-3 py-1.5 rounded-full text-[12px] font-bold tracking-wider uppercase border border-[#944a23]/20 shadow-sm backdrop-blur-md">Umur {{ max(0, $currentHst) <= 0 ? 1 : max(0, $currentHst) }} Hari (Est. Panen {{ $mainPlant->plantTemplate->harvest_start_day }} Hari)</span>
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="document.getElementById('edit-jadwal-modal').classList.remove('hidden')" class="w-full md:w-auto bg-white border border-outline-variant/30 text-on-surface-variant font-bold px-6 py-3.5 rounded-full hover:bg-surface hover:text-primary hover:border-primary/30 hover:shadow-[0_4px_12px_rgba(0,108,73,0.05)] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm relative z-10">
                        <span class="material-symbols-outlined text-[20px]">calendar_month</span> Edit Jadwal
                    </button>
                </div>
                
                {{-- Stepper Timeline --}}
                <div class="relative pl-12 md:pl-14 space-y-[40px]">
                    {{-- Vertical dashed line --}}
                    <div class="absolute left-[20px] md:left-[24px] top-4 bottom-8 w-[2px] border-l-2 border-dashed border-primary/30"></div>

                    @foreach($timeline as $index => $stage)
                        @if($stage['status'] === 'completed')
                            <div class="relative group cursor-pointer transition-transform duration-300 hover:translate-x-1">
                                <div class="absolute -left-[45px] md:-left-[49px] top-0.5 w-8 h-8 rounded-full bg-[#006c49] text-white flex items-center justify-center shadow-xs z-10 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white stroke-[3.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-2 md:ml-4">
                                    <h3 class="text-[16px] font-bold text-[#006c49] mb-0.5 flex items-center gap-2">
                                        {{ $stage['label'] }} 
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#006c49] bg-[#e6f4ea] px-2.5 py-0.5 rounded-full border border-[#006c49]/20">
                                            (Selesai)
                                        </span>
                                    </h3>
                                    <p class="text-[13px] text-on-surface-variant font-medium">{{ $stage['date']->isoFormat('D MMM YYYY') }} • {{ $stage['desc'] }}</p>
                                </div>
                            </div>
                        @elseif($stage['status'] === 'active')
                            <div class="relative group">
                                {{-- Hero Active Node --}}
                                <div class="absolute -left-[46px] md:-left-[50px] top-1 w-9 h-9 rounded-full bg-[#e6f4ea] border border-[#006c49]/30 flex items-center justify-center shadow-xs z-10 group-hover:scale-110 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-[20px] text-[#006c49]" style="font-variation-settings: 'FILL' 1;">eco</span>
                                </div>
                                <div class="ml-4 md:ml-6 bg-gradient-to-br from-white to-[#006c49]/5 border border-primary/20 rounded-[20px] p-[24px] shadow-[0_8px_32px_rgba(0,108,73,0.08)] relative overflow-hidden transition-all duration-300 hover:shadow-[0_12px_48px_rgba(0,108,73,0.12)] hover:-translate-y-1">
                                    <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
                                    <h3 class="text-[18px] font-black text-primary mb-1 flex items-center gap-2">
                                        {{ $stage['label'] }} 
                                        <span class="text-[12px] bg-gradient-to-r from-[#006c49] to-[#10b981] text-white px-3 py-0.5 rounded-full font-bold tracking-wider shadow-xs">AKTIF</span>
                                    </h3>
                                    <p class="text-[14px] text-on-surface-variant font-medium mb-4 leading-relaxed">{{ $stage['desc'] }}</p>
                                    
                                    @if(isset($stageWeatherAdvice) && $stageWeatherAdvice)
                                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 mb-5 flex items-start gap-2.5">
                                        <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">auto_awesome</span>
                                        <p class="text-[12px] font-semibold text-primary leading-relaxed">
                                            {{ $stageWeatherAdvice['text'] }}
                                        </p>
                                    </div>
                                    @endif

                                    <div class="flex items-center gap-4">
                                        <div class="flex-1 bg-outline-variant/30 h-[10px] rounded-full overflow-hidden shadow-inner">
                                            <div class="bg-gradient-to-r from-[#006c49] to-[#10b981] h-full rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $stage['progress'] }}%;">
                                                <div class="absolute inset-0 bg-white/20 w-full h-full" style="background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent); background-size: 1rem 1rem;"></div>
                                            </div>
                                        </div>
                                        <span class="text-[12px] text-primary font-black whitespace-nowrap bg-primary/10 px-3 py-1 rounded-full border border-primary/20">
                                            @if($stage['daysLeft'] > 0)
                                                {{ $stage['daysLeft'] }} hari lagi
                                            @else
                                                Mendekati akhir fase
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="relative opacity-70 hover:opacity-100 transition-all duration-300 cursor-pointer group hover:translate-x-1">
                                <div class="absolute -left-[46px] md:-left-[50px] top-0.5 w-9 h-9 rounded-full bg-white border border-outline-variant/60 flex items-center justify-center z-10 shadow-[0_2px_8px_rgba(0,0,0,0.04)] ring-4 ring-white group-hover:border-primary group-hover:bg-primary/10 group-hover:shadow-[0_4px_16px_rgba(0,108,73,0.2)] group-hover:scale-110 transition-all duration-300">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400 group-hover:text-primary transition-colors">
                                        @if($stage['key'] === 'FLOWERING') local_florist 
                                        @elseif($stage['key'] === 'FRUITING') nutrition 
                                        @elseif($stage['key'] === 'HARVEST') shopping_basket 
                                        @else schedule @endif
                                    </span>
                                </div>
                                <div class="ml-2 md:ml-4">
                                    <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                        <h3 class="text-[16px] font-bold text-on-surface-variant group-hover:text-primary transition-colors">{{ $stage['label'] }}</h3>
                                        @if(isset($stage['weatherBadge']) && $stage['weatherBadge'])
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $stage['weatherBadgeBg'] }}">{{ $stage['weatherBadge'] }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[13px] text-outline font-medium">Est. {{ $stage['date']->isoFormat('D MMM YYYY') }} • {{ $stage['desc'] }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-[48px] text-outline-variant mb-4">grass</span>
                    <h2 class="text-[20px] font-bold text-on-surface mb-2">Belum Ada Tanaman Aktif</h2>
                    <p class="text-on-surface-variant">Tambahkan tanaman di kebun Anda untuk melihat kalender pertumbuhannya.</p>
                </div>
                @endif
            </div>

            {{-- Sidebar Context (Takes up 2 columns) --}}
            <div class="lg:col-span-2 flex flex-col gap-[24px]">
                
                @if(isset($mainPlant) && $mainPlant && \Carbon\Carbon::parse($mainPlant->planted_date)->diffInDays(now()) >= ($mainPlant->plantTemplate->harvest_start_day - 5))
                {{-- Card 1: Mendekati Waktu Panen --}}
                <div class="bg-gradient-to-br from-[#0b6e4f] to-[#044731] text-white rounded-[24px] p-[32px] shadow-[0_12px_32px_rgba(11,110,79,0.25)] hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(11,110,79,0.35)] transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors duration-700"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#6ffbbe]/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/10 backdrop-blur-md mb-6 border border-white/20 shadow-sm">
                                <span class="material-symbols-outlined text-[24px] text-[#6ffbbe]">notifications_active</span>
                            </div>
                            <h3 class="text-[28px] font-black leading-tight mb-4 tracking-tight">Mendekati<br>Waktu Panen!</h3>
                            <p class="text-[14px] text-[#e1f5fe] leading-relaxed mb-6 font-medium">
                                {{ $mainPlant->plantTemplate->name_id }} Anda diperkirakan siap dipanen dalam kurun waktu kurang dari 5 hari. Siapkan wadah dan peralatan panen Anda!
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tugas Hari Ini Card --}}
                <div class="bg-white rounded-[24px] p-[28px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-outline-variant/30">
                    <h3 class="text-[18px] font-black text-slate-800 mb-5 flex items-center justify-between">
                        Tugas Hari Ini
                        <a href="{{ route('care-tasks') }}" class="text-[13px] text-primary font-bold hover:underline">Lihat Semua</a>
                    </h3>
                    
                    @if(isset($todayTasks) && $todayTasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayTasks->take(3) as $task)
                                <div class="flex items-start gap-3 p-3 rounded-[16px] border border-outline-variant/30 bg-surface">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">
                                            {{ $task->eventType && str_contains(strtolower($task->eventType->code), 'water') ? 'water_drop' : 'eco' }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h4 class="text-[14px] font-bold text-on-surface">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h4>
                                            @if(isset($task->weather_tag))
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $task->weather_badge_bg }}">{{ $task->weather_tag }}</span>
                                            @endif
                                        </div>
                                        <p class="text-[12px] text-on-surface-variant font-medium mt-0.5">
                                            {{ $task->plant ? $task->plant->plantTemplate->name_id : 'Tanaman' }}
                                            @if(isset($task->weather_reason)) <span class="italic text-primary/80">— {{ $task->weather_reason }}</span> @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-surface border border-outline-variant/30 rounded-[16px] p-4 text-center">
                            <p class="text-[13px] text-on-surface-variant font-medium">Tidak ada tugas mendesak hari ini.</p>
                        </div>
                    @endif
                </div>

                {{-- Tanaman Lainnya Card --}}
                @if($otherPlants->count() > 0)
                <div class="bg-white rounded-[24px] p-[28px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-outline-variant/30">
                    <h3 class="text-[18px] font-black text-slate-800 mb-4">Tanaman Lainnya</h3>
                    <div class="space-y-3">
                        @foreach($otherPlants as $p)
                            <a href="{{ route('growth-calendar', ['plant_id' => $p->id]) }}" class="flex items-center justify-between p-3 rounded-[16px] border border-outline-variant/30 hover:border-primary/50 transition-all hover:bg-surface group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-primary font-bold text-sm group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">eco</span>
                                    </div>
                                    <div>
                                        <h4 class="text-[14px] font-bold text-on-surface group-hover:text-primary transition-colors">{{ $p->plantTemplate->name_id }}</h4>
                                        <p class="text-[12px] text-on-surface-variant font-medium">Kebun: {{ $p->garden->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    @if($mainPlant)
    <div id="edit-jadwal-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="document.getElementById('edit-jadwal-modal').classList.add('hidden')"></div>
        <div class="min-h-screen w-full px-4 py-8 flex items-center justify-center pointer-events-none">
            <div class="w-[90vw] max-w-[420px] bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative shrink-0">
                <button type="button" onclick="document.getElementById('edit-jadwal-modal').classList.add('hidden')" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
                
                <h3 class="text-[24px] font-bold text-on-surface mb-2">Edit Jadwal Tanam</h3>
                <p class="text-[14px] text-on-surface-variant mb-6">Ubah tanggal tanam untuk tanaman {{ $mainPlant->plantTemplate->name_id }} Anda.</p>
                
                <form action="{{ route('plants.update', $mainPlant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label for="planted_date" class="block text-sm font-bold text-on-surface mb-2">Tanggal Tanam</label>
                        <input type="date" id="planted_date" name="planted_date" value="{{ \Carbon\Carbon::parse($mainPlant->planted_date)->format('Y-m-d') }}" class="w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" required>
                    </div>
                    
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('edit-jadwal-modal').classList.add('hidden')" class="px-6 py-2.5 rounded-full font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-full font-bold bg-primary text-white shadow-sm hover:bg-[#005236] transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
