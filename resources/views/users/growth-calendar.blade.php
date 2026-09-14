@extends('layouts.dashboard')

@section('title', 'Kalender Tanam — Grow a Garden')
@section('description', 'Pantau dan kelola tahap pertumbuhan tanaman Anda dengan kalender bulanan interaktif.')

@section('dashboard-content')
<div class="relative min-h-[80vh] pb-10 w-full min-w-0" style="width: 100% !important;">

    {{-- Main Container --}}
    <div class="flex flex-col gap-[24px] w-full min-w-0" style="width: 100% !important;">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-2 w-full">
            <div>
                <h1 class="text-[30px] md:text-[42px] font-bold text-on-surface tracking-tight leading-tight mb-1">Kalender Tanam</h1>
                <p class="text-[15px] md:text-[16px] text-on-surface-variant max-w-xl leading-relaxed">Pantau linimasa pertumbuhan cerdas, jadwalkan perawatan baru, dan kelola kegiatan kebun Anda secara fleksibel.</p>
            </div>
            @if($mainPlant)
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="document.getElementById('edit-jadwal-modal').classList.remove('hidden')" class="bg-white border border-outline-variant/40 text-on-surface-variant font-bold px-4 py-2.5 rounded-xl hover:bg-surface hover:text-primary hover:border-primary/30 transition-all flex items-center gap-1.5 text-sm shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">edit_calendar</span>
                    <span>Ubah Tgl Tanam</span>
                </button>
            </div>
            @endif
        </div>

        @if(!$mainPlant)
        {{-- Empty State (Layout Anti-Collapse Safe) --}}
        <div class="w-full min-w-full self-stretch bg-white rounded-[24px] p-8 md:p-14 text-center border border-outline-variant/30 ambient-shadow-lg flex flex-col items-center justify-center gap-5" style="width: 100% !important; min-width: 100% !important; box-sizing: border-box !important;">
            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-1 shadow-inner shrink-0">
                <span class="material-symbols-outlined text-[42px]">yard</span>
            </div>
            <div class="text-center w-full min-w-full max-w-md mx-auto self-stretch flex flex-col items-center" style="width: 100% !important; min-width: 100% !important; max-width: 28rem !important; text-align: center !important;">
                <h2 class="text-[22px] md:text-[26px] font-black text-on-surface mb-2 w-full self-stretch" style="width: 100% !important; min-width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">Belum Ada Tanaman Aktif</h2>
                <p class="text-[14px] md:text-[15px] text-on-surface-variant mb-6 w-full self-stretch leading-relaxed" style="width: 100% !important; min-width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">Tambahkan tanaman di kebun Anda untuk melihat kalender bulanan dan rekomendasi perawatan cerdas.</p>
                <a href="{{ route('gardens') }}" class="inline-flex items-center justify-center gap-2 bg-primary text-white font-bold px-6 py-3.5 rounded-full hover:bg-[#005236] transition-all shadow-md active:scale-95 shrink-0 whitespace-nowrap" style="white-space: nowrap !important; text-decoration: none !important;">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>Buka Kebun & Tambah Tanaman</span>
                </a>
            </div>
        </div>
        @else

        {{-- 1. Compact Stage Tracker (Fase Pertumbuhan Tanaman Aktif) --}}
        <div class="w-full bg-white rounded-[24px] p-5 md:p-7 border border-outline-variant/30 ambient-shadow-lg relative overflow-hidden">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 pb-5 border-b border-outline-variant/20">
                {{-- Plant Overview --}}
                <div class="flex items-center gap-4">
                    @php
                        $imgSrc = 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=160&h=160&fit=crop&q=80';
                        $plantNameLower = strtolower($mainPlant->plantTemplate->name_id ?? '');
                        if(str_contains($plantNameLower, 'bayam') || str_contains($plantNameLower, 'selada')) {
                            $imgSrc = 'https://images.unsplash.com/photo-1622383563227-04401ab4e5ea?w=160&h=160&fit=crop&q=80';
                        } elseif(str_contains($plantNameLower, 'cabai') || str_contains($plantNameLower, 'tomat')) {
                            $imgSrc = 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=160&h=160&fit=crop&q=80';
                        }
                        $activeStage = collect($timeline)->firstWhere('status', 'active') ?? collect($timeline)->last();
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $mainPlant->plantTemplate->name_id }}" class="w-16 h-16 md:w-20 md:h-20 rounded-[18px] object-cover border-2 border-primary/20 shadow-sm shrink-0">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-[20px] md:text-[24px] font-black text-on-surface leading-tight tracking-tight">{{ $mainPlant->plantTemplate->name_id }}</h2>
                            <span class="bg-primary/10 text-primary px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider border border-primary/20">
                                Fase {{ $activeStage['label'] ?? 'Tumbuh' }}
                            </span>
                        </div>
                        <p class="text-[13px] text-on-surface-variant font-medium mt-1">
                            Kebun: <span class="text-on-surface font-bold">{{ $mainPlant->garden->name ?? 'Kebun Utama' }}</span> • 
                            Umur Tanaman: <span class="text-primary font-black">{{ max(1, $currentHst) }} Hari</span> • 
                            Perkiraan Panen: <span class="text-[#944a23] font-black">Hari ke-{{ $mainPlant->plantTemplate->harvest_start_day ?? 30 }}</span>
                        </p>
                    </div>
                </div>

                {{-- Quick Stage Stats / Days Left --}}
                <div class="flex items-center gap-3 bg-surface-container-low px-4 py-2.5 rounded-2xl border border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[24px]">timelapse</span>
                    <div>
                        <p class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider">Status Fase Saat Ini</p>
                        <p class="text-[13px] font-black text-on-surface">
                            @if(isset($activeStage['daysLeft']) && $activeStage['daysLeft'] > 0)
                                {{ $activeStage['daysLeft'] }} hari lagi menuju fase berikutnya
                            @else
                                Mendekati / di fase akhir
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Compact Stepper / Horizontal Progress --}}
            <div class="mt-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-{{ count($timeline) }} gap-3">
                    @foreach($timeline as $idx => $stage)
                        @php
                            $isDone = ($stage['status'] === 'completed');
                            $isActive = ($stage['status'] === 'active');
                        @endphp
                        <div class="p-3 rounded-2xl border transition-all relative {{ $isActive ? 'bg-primary/5 border-primary shadow-xs' : ($isDone ? 'bg-surface-container-low/60 border-outline-variant/30' : 'bg-surface border-outline-variant/20 opacity-70') }}">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[11px] font-extrabold tracking-wider uppercase {{ $isActive ? 'text-primary' : ($isDone ? 'text-slate-600' : 'text-slate-400') }}">
                                    Langkah {{ $idx + 1 }}
                                </span>
                                @if($isDone)
                                    <span class="material-symbols-outlined text-[16px] text-[#006c49] font-black">check_circle</span>
                                @elseif($isActive)
                                    <span class="w-2.5 h-2.5 rounded-full bg-primary animate-ping"></span>
                                @else
                                    <span class="material-symbols-outlined text-[16px] text-slate-300">radio_button_unchecked</span>
                                @endif
                            </div>
                            <h4 class="text-[13px] md:text-[14px] font-black text-on-surface leading-snug">{{ $stage['label'] }}</h4>
                            <p class="text-[11px] text-on-surface-variant font-medium mt-0.5">{{ $stage['date']->isoFormat('D MMM') }}</p>

                            @if(!empty($stage['weatherBadge']))
                            <div class="mt-1">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md {{ $stage['weatherBadgeBg'] }} inline-block">
                                    {{ $stage['weatherBadge'] }}
                                </span>
                            </div>
                            @endif

                            @if($isActive)
                            <div class="mt-2.5 w-full bg-outline-variant/30 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $stage['progress'] ?? 50 }}%;"></div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Weather Advice Alert Banner --}}
                @if(isset($stageWeatherAdvice['text']) && $stageWeatherAdvice['text'])
                <div class="mt-4 bg-primary/5 border border-primary/20 rounded-2xl p-3.5 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">auto_awesome</span>
                    </div>
                    <div class="text-[12px] md:text-[13px] text-on-surface leading-relaxed">
                        <span class="font-bold text-primary">Saran Agronomis Cuaca:</span> {{ $stageWeatherAdvice['text'] }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- 2. Main Grid & Sidebar Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-[24px]">

            {{-- Monthly Calendar Card (3 Columns) --}}
            <div class="lg:col-span-3 bg-white rounded-[24px] p-5 md:p-8 border border-outline-variant/30 ambient-shadow-lg flex flex-col">
                
                {{-- Calendar Navigation Bar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-outline-variant/20">
                    {{-- Month Title & Year --}}
                    <div class="flex items-center gap-3">
                        <h2 id="calendar-month-year" class="text-[22px] md:text-[26px] font-black text-on-surface tracking-tight">
                            September 2026
                        </h2>
                        <div id="calendar-spinner" class="hidden items-center text-xs font-bold text-primary gap-1">
                            <span class="material-symbols-outlined text-[16px] animate-spin">sync</span>
                        </div>
                    </div>

                    {{-- Controls: Plant Filter, Add Task, Today, Prev/Next --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- Plant Filter --}}
                        <div class="relative">
                            <select id="calendar-plant-filter" onchange="onPlantFilterChange(this.value)" class="text-[13px] font-bold text-on-surface bg-surface border border-outline-variant/50 rounded-xl px-3 py-2 pr-8 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary appearance-none cursor-pointer">
                                <option value="all" {{ request('plant_id') === 'all' ? 'selected' : '' }}>Semua Tanaman</option>
                                @foreach($plants as $p)
                                    <option value="{{ $p->id }}" {{ $mainPlant && $mainPlant->id == $p->id && request('plant_id') !== 'all' ? 'selected' : '' }}>
                                        {{ $p->plantTemplate->name_id }} ({{ $p->garden->name ?? 'Kebun' }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant absolute right-2.5 top-2.5 pointer-events-none">expand_more</span>
                        </div>

                        {{-- Today Button --}}
                        <button type="button" onclick="jumpToToday()" class="text-[12px] font-extrabold text-on-surface border border-outline-variant/50 hover:bg-surface-container-high active:scale-95 px-3 py-2 rounded-xl transition-all shadow-2xs">
                            Hari Ini
                        </button>

                        {{-- Prev & Next Month Buttons --}}
                        <div class="inline-flex items-center bg-surface rounded-xl border border-outline-variant/40 p-0.5">
                            <button type="button" onclick="prevMonth()" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors active:scale-95" title="Bulan Sebelumnya">
                                <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                            </button>
                            <button type="button" onclick="nextMonth()" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors active:scale-95" title="Bulan Berikutnya">
                                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Weekdays Header (Senin - Minggu) --}}
                <div class="grid grid-cols-7 gap-1 md:gap-2 mb-2 text-center text-[12px] font-extrabold text-on-surface-variant uppercase tracking-wider">
                    <div class="py-1">Sen</div>
                    <div class="py-1">Sel</div>
                    <div class="py-1">Rab</div>
                    <div class="py-1">Kam</div>
                    <div class="py-1">Jum</div>
                    <div class="py-1 text-[#006c49]">Sab</div>
                    <div class="py-1 text-[#ba1a1a]">Min</div>
                </div>

                {{-- Calendar Days Grid --}}
                <div id="calendar-grid" class="grid grid-cols-7 gap-1 md:gap-2 auto-rows-fr flex-1">
                    {{-- Dynamically populated via JavaScript --}}
                </div>

                {{-- Calendar Footer Legend --}}
                <div class="mt-6 pt-4 border-t border-outline-variant/20 flex flex-wrap items-center justify-between gap-3 text-[11px] md:text-[12px] text-on-surface-variant font-medium">
                    <div class="flex items-center gap-4 flex-wrap">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-primary/20 border border-primary"></span>
                            <span>Hari Ini</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-cyan-100 border border-cyan-400"></span>
                            <span>Pending</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-[#ffdad6] border border-[#ba1a1a]"></span>
                            <span>Terlewat (Missed)</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-emerald-100 border border-emerald-500"></span>
                            <span>Selesai (Completed)</span>
                        </span>
                    </div>
                    <div class="text-[11px] text-on-surface-variant italic">
                        * Klik tanggal untuk melihat daftar kegiatan, menambah, atau mengelola jadwal
                    </div>
                </div>

            </div>

            {{-- Sidebar Supporting Cards (2 Columns) --}}
            <div class="lg:col-span-2 flex flex-col gap-[24px]">

                {{-- Card: Penyesuaian Cuaca & Kalender (Agronomic Weather Intelligence) --}}
                @if(isset($agronomic))
                <div class="w-full bg-white rounded-[24px] p-6 border border-outline-variant/30 ambient-shadow-lg flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">{{ $agronomic['icon'] ?? 'partly_cloudy_day' }}</span>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-black text-on-surface leading-snug">Penyesuaian Cuaca</h3>
                                <p class="text-[11px] text-on-surface-variant font-medium">{{ $agronomic['condition_title'] ?? 'Kondisi Hari Ini' }}</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full {{ $agronomic['watering']['badge_bg'] ?? 'bg-primary/10 text-primary' }}">
                            {{ $agronomic['watering']['badge'] ?? 'Normal' }}
                        </span>
                    </div>

                    {{-- Metrics grid --}}
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-surface-container-low rounded-xl p-2.5 border border-outline-variant/15">
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold block">Suhu</span>
                            <span class="text-[14px] font-black text-on-surface">{{ (int) round($agronomic['temperature'] ?? 29) }}°C</span>
                        </div>
                        <div class="bg-surface-container-low rounded-xl p-2.5 border border-outline-variant/15">
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold block">Lembap</span>
                            <span class="text-[14px] font-black text-on-surface">{{ $agronomic['humidity'] ?? 75 }}%</span>
                        </div>
                        <div class="bg-surface-container-low rounded-xl p-2.5 border border-outline-variant/15">
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold block">Hujan</span>
                            <span class="text-[14px] font-black text-on-surface">{{ $agronomic['rain_probability'] ?? 0 }}%</span>
                        </div>
                    </div>

                    {{-- Impact & Advice on Calendar --}}
                    <div class="bg-primary/5 rounded-xl p-3 border border-primary/20 space-y-1.5">
                        <div class="flex items-center gap-1.5 text-xs font-black text-primary">
                            <span class="material-symbols-outlined text-[16px]">info</span>
                            <span>Pengaruh Terhadap Jadwal:</span>
                        </div>
                        <p class="text-[12px] text-on-surface leading-relaxed font-medium">
                            {{ $agronomic['watering']['advice'] ?? 'Kondisi cuaca normal. Jadwal perawatan berjalan sesuai kalender.' }}
                        </p>
                    </div>
                </div>
                @endif

                {{-- Card: Tugas Hari Ini --}}
                <div class="w-full bg-white rounded-[24px] p-6 border border-outline-variant/30 ambient-shadow-lg flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[22px]">checklist</span>
                            <h3 class="text-[17px] font-black text-on-surface">Tugas Hari Ini</h3>
                        </div>
                        <a href="{{ route('care-tasks') }}" class="text-[12px] text-primary font-extrabold hover:underline">Lihat Semua</a>
                    </div>

                    @if(isset($todayTasks) && $todayTasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayTasks->take(4) as $task)
                                @php
                                    $code = strtolower($task->eventType->code ?? '');
                                    $tIcon = 'eco';
                                    if(str_contains($code, 'water')) $tIcon = 'water_drop';
                                    elseif(str_contains($code, 'fertiliz')) $tIcon = 'science';
                                    elseif(str_contains($code, 'pest')) $tIcon = 'pest_control';
                                @endphp
                                <div class="p-3 rounded-2xl border border-outline-variant/30 bg-surface-container-lowest hover:border-primary/40 transition-colors flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined text-[18px]">{{ $tIcon }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2 flex-wrap">
                                            <h4 class="text-[13px] font-bold text-on-surface truncate">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h4>
                                            <div class="flex items-center gap-1 shrink-0">
                                                @if(!empty($task->weather_tag))
                                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-full {{ $task->weather_badge_bg ?? 'bg-primary/10 text-primary' }}">
                                                        {{ $task->weather_tag }}
                                                    </span>
                                                @endif
                                                @if($task->status === 'MISSED')
                                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-[#ffdad6] text-[#ba1a1a] uppercase">Terlewat</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-[11px] text-on-surface-variant font-medium truncate mt-0.5">
                                            {{ $task->plant ? $task->plant->plantTemplate->name_id : 'Tanaman' }}
                                        </p>
                                        @if(!empty($task->weather_reason))
                                            <p class="text-[10px] text-primary/85 font-semibold truncate mt-0.5 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[12px]">schedule</span>
                                                <span>{{ $task->weather_reason }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <button type="button" onclick="quickRescheduleById({{ $task->id }})" class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-surface-container-high rounded-lg transition-colors shrink-0" title="Kelola / Reschedule Jadwal">
                                        <span class="material-symbols-outlined text-[18px]">edit_calendar</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="w-full py-8 text-center bg-surface-container-low rounded-2xl border border-outline-variant/20 flex flex-col items-center justify-center self-stretch" style="width: 100% !important;">
                            <span class="material-symbols-outlined text-[32px] text-primary/50 mb-1 shrink-0">task_alt</span>
                            <p class="text-[13px] font-bold text-on-surface w-full self-stretch" style="white-space: normal !important; word-break: normal !important;">Semua Beres!</p>
                            <p class="text-[11px] text-on-surface-variant w-full self-stretch" style="white-space: normal !important; word-break: normal !important;">Tidak ada tugas mendesak hari ini.</p>
                        </div>
                    @endif
                </div>

                {{-- Card: Tanaman Lainnya --}}
                @if(isset($otherPlants) && $otherPlants->count() > 0)
                <div class="w-full bg-white rounded-[24px] p-6 border border-outline-variant/30 ambient-shadow-lg flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#944a23] text-[22px]">potted_plant</span>
                            <h3 class="text-[17px] font-black text-on-surface">Tanaman Kebun Anda</h3>
                        </div>
                        <span class="text-[12px] font-bold text-on-surface-variant">{{ $plants->count() }} Total</span>
                    </div>

                    <div class="space-y-2.5 max-h-[320px] overflow-y-auto pr-1">
                        @foreach($plants as $p)
                            @php $isCurrent = ($mainPlant && $mainPlant->id == $p->id); @endphp
                            <a href="{{ route('growth-calendar', ['plant_id' => $p->id]) }}" class="flex items-center justify-between p-2.5 rounded-2xl border transition-all {{ $isCurrent ? 'bg-primary/10 border-primary font-bold' : 'bg-surface border-outline-variant/30 hover:border-primary/50 hover:bg-surface-container-low' }}">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl {{ $isCurrent ? 'bg-primary text-white' : 'bg-surface-container-high text-primary' }} flex items-center justify-center font-bold text-sm shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">eco</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-[13px] font-bold text-on-surface truncate">{{ $p->plantTemplate->name_id }}</h4>
                                        <p class="text-[11px] text-on-surface-variant truncate">Kebun: {{ $p->garden->name ?? '-' }} • Umur {{ max(1, $p->hst) }} Hari</p>
                                    </div>
                                </div>
                                @if($isCurrent)
                                    <span class="text-[10px] font-black uppercase text-primary bg-white px-2 py-0.5 rounded-full shadow-2xs border border-primary/20 shrink-0">Aktif</span>
                                @else
                                    <span class="material-symbols-outlined text-[18px] text-outline-variant shrink-0">chevron_right</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Card: Edukasi & Bantuan --}}
                <div class="w-full bg-gradient-to-br from-[#006c49]/10 via-surface to-[#944a23]/10 rounded-[24px] p-6 border border-primary/20 ambient-shadow-lg flex flex-col">
                    <div class="flex items-center gap-2 mb-2 text-primary font-black text-[14px]">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                        <span>Fleksibilitas Kalender</span>
                    </div>
                    <p class="text-[12px] text-on-surface-variant leading-relaxed mb-3">
                        Anda dapat menambah jadwal perawatan baru kapan saja serta memindahkan tanggal jadwal maju/mundur atau menghapusnya jika sudah tidak diperlukan.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] font-bold text-[#006c49] bg-white/80 p-2.5 rounded-xl border border-primary/20">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        <span>Klik tombol (+) di tanggal atau klik tombol Tambah Kegiatan untuk menambahkan jadwal baru!</span>
                    </div>
                </div>

            </div>

        </div>
        @endif

    </div>

    {{-- ============================================================
         2.5. Date Activities & Management Modal (Teken Tanggal -> List Kegiatan & Tambah & Hapus)
         ============================================================ --}}
    <div id="date-activities-modal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="date-modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeDateModal()"></div>

        <div class="min-h-screen w-full px-4 py-8 flex items-center justify-center pointer-events-none">
            <div class="w-full max-w-[540px] bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative shrink-0 max-h-[90vh] flex flex-col">
                
                {{-- Close Button (Only X, NO redundant 'Batal') --}}
                <button type="button" onclick="closeDateModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors" aria-label="Tutup">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>

                {{-- Header with Selected Date --}}
                <div class="flex items-center gap-3.5 mb-4 pr-10">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[26px]">calendar_month</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 id="date-modal-title" class="text-[18px] md:text-[22px] font-black text-on-surface leading-tight">Tanggal</h3>
                            <span id="date-modal-today-badge" class="hidden text-[10px] font-black bg-primary text-white px-2 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">Hari Ini</span>
                        </div>
                        <p id="date-modal-subtitle" class="text-[12px] text-on-surface-variant font-medium mt-0.5">Kelola seluruh jadwal kegiatan perawatan</p>
                    </div>
                </div>

                {{-- Action Bar: Counter & + Tambah Kegiatan Button --}}
                <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-outline-variant/20">
                    <span id="date-modal-count" class="text-xs font-bold text-on-surface-variant">0 Kegiatan Terjadwal</span>
                    <button type="button" id="btn-toggle-date-add" onclick="toggleDateAddForm()" class="px-3.5 py-2 rounded-xl font-bold text-xs bg-primary text-white hover:bg-[#005236] transition-all flex items-center gap-1.5 shadow-2xs active:scale-95">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span id="btn-toggle-date-add-label">Tambah Kegiatan</span>
                    </button>
                </div>

                {{-- Scrollable Activities & Form Container --}}
                <div class="overflow-y-auto flex-1 pr-1 space-y-3.5" id="date-modal-scrollable">
                    
                    {{-- Today Weather Intelligence in Date Modal --}}
                    <div id="date-modal-weather-card" class="hidden bg-primary/5 rounded-2xl p-3.5 border border-primary/20 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                            <span id="date-modal-weather-icon" class="material-symbols-outlined text-[18px]">wb_sunny</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <span id="date-modal-weather-title" class="text-xs font-black text-on-surface">Cuaca: -</span>
                                <span id="date-modal-weather-badge" class="text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-primary/10 text-primary">Normal</span>
                            </div>
                            <p id="date-modal-weather-advice" class="text-[11px] text-on-surface-variant font-medium leading-relaxed">-</p>
                        </div>
                    </div>

                    {{-- Collapsible Add Task Form --}}
                    <div id="date-add-form-wrapper" class="hidden bg-surface-container-low rounded-2xl p-4 border border-primary/25 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-black uppercase tracking-wider text-primary flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">add_task</span>
                                Tambah Kegiatan Baru
                            </h4>
                            <button type="button" onclick="toggleDateAddForm(false)" class="text-slate-400 hover:text-slate-600" aria-label="Tutup Form">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>

                        {{-- Error Alert --}}
                        <div id="date-add-error" class="hidden bg-[#ffdad6] border border-[#ba1a1a]/30 text-[#ba1a1a] rounded-xl p-2.5 mb-3 text-[11px] font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] shrink-0">error</span>
                            <span id="date-add-error-text">Terjadi kesalahan.</span>
                        </div>

                        <form id="date-add-form" onsubmit="event.preventDefault(); submitAddTaskFromDateModal();" class="space-y-3">
                            <div>
                                <label for="date-add-plant-id" class="block text-[11px] font-black uppercase tracking-wider text-on-surface mb-1">Tanaman <span class="text-error">*</span></label>
                                <select id="date-add-plant-id" class="w-full bg-white border border-outline-variant/60 rounded-xl px-3 py-2 text-xs font-bold text-on-surface focus:outline-none focus:border-primary" required>
                                    @foreach($plants as $p)
                                        <option value="{{ $p->id }}" {{ $mainPlant && $mainPlant->id == $p->id ? 'selected' : '' }}>
                                            {{ $p->plantTemplate->name_id }} ({{ $p->garden->name ?? 'Kebun' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date-add-event-type-id" class="block text-[11px] font-black uppercase tracking-wider text-on-surface mb-1">Jenis Kegiatan <span class="text-error">*</span></label>
                                <select id="date-add-event-type-id" class="w-full bg-white border border-outline-variant/60 rounded-xl px-3 py-2 text-xs font-bold text-on-surface focus:outline-none focus:border-primary" required>
                                    @if(isset($eventTypes) && $eventTypes->count() > 0)
                                        @foreach($eventTypes as $et)
                                            <option value="{{ $et->id }}">{{ $et->label }}</option>
                                        @endforeach
                                    @else
                                        <option value="9">Pengingat Penyiraman</option>
                                        <option value="10">Pengingat Pemupukan</option>
                                        <option value="11">Inspeksi Hama</option>
                                        <option value="12">Perempelan / Pemangkasan</option>
                                    @endif
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label for="date-add-priority" class="block text-[11px] font-black uppercase tracking-wider text-on-surface mb-1">Prioritas</label>
                                    <select id="date-add-priority" class="w-full bg-white border border-outline-variant/60 rounded-xl px-2.5 py-2 text-xs font-bold text-on-surface focus:outline-none focus:border-primary">
                                        <option value="MEDIUM">Sedang</option>
                                        <option value="HIGH">Tinggi</option>
                                        <option value="LOW">Rendah</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="date-add-message" class="block text-[11px] font-black uppercase tracking-wider text-on-surface mb-1">Catatan</label>
                                    <input type="text" id="date-add-message" placeholder="Opsional" class="w-full bg-white border border-outline-variant/60 rounded-xl px-2.5 py-2 text-xs text-on-surface focus:outline-none focus:border-primary">
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" onclick="toggleDateAddForm(false)" class="px-3 py-1.5 rounded-xl font-bold text-xs text-on-surface-variant hover:bg-surface-container-high transition-colors">
                                    Batal
                                </button>
                                <button type="submit" id="btn-submit-date-add" class="px-4 py-1.5 rounded-xl font-bold text-xs bg-primary text-white hover:bg-[#005236] transition-colors flex items-center gap-1.5 shadow-2xs">
                                    <span id="btn-date-add-label">Simpan Kegiatan</span>
                                    <span id="btn-date-add-spinner" class="hidden material-symbols-outlined text-[14px] animate-spin">sync</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Empty State --}}
                    <div id="date-activities-empty" class="hidden py-8 text-center bg-surface-container-low rounded-2xl border border-outline-variant/20 flex flex-col items-center justify-center w-full self-stretch" style="width: 100% !important;">
                        <span class="material-symbols-outlined text-[36px] text-primary/40 mb-1.5 shrink-0">event_available</span>
                        <p class="text-[13px] font-bold text-on-surface w-full self-stretch" style="white-space: normal !important; word-break: normal !important;">Belum Ada Kegiatan</p>
                        <p class="text-[11px] text-on-surface-variant max-w-[260px] mx-auto mt-0.5 mb-3 w-full self-stretch" style="white-space: normal !important; word-break: normal !important;">Tidak ada jadwal kegiatan perawatan pada tanggal ini.</p>
                        <button type="button" onclick="toggleDateAddForm(true)" class="px-3.5 py-1.5 rounded-xl font-bold text-xs bg-primary text-white hover:bg-[#005236] transition-all flex items-center gap-1 shadow-2xs shrink-0 whitespace-nowrap">
                            <span class="material-symbols-outlined text-[15px]">add</span>
                            Tambah Kegiatan Baru
                        </button>
                    </div>

                    {{-- Activities List --}}
                    <div id="date-activities-list" class="space-y-2.5">
                        {{-- Populated via JS --}}
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- ============================================================
         3. Interactive Reschedule & Delete Modal
         ============================================================ --}}
    <div id="reschedule-modal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="modal-reschedule-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeRescheduleModal()"></div>

        <div class="min-h-screen w-full px-4 py-8 flex items-center justify-center pointer-events-none">
            <div class="w-full max-w-[460px] bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative shrink-0">
                
                {{-- Close Button --}}
                <button type="button" onclick="closeRescheduleModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors" aria-label="Tutup">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[22px]">calendar_clock</span>
                    </div>
                    <div>
                        <h3 id="modal-reschedule-title" class="text-[18px] md:text-[20px] font-black text-on-surface leading-tight">Kelola Jadwal Perawatan</h3>
                        <p class="text-[12px] text-on-surface-variant">Ubah tanggal pelaksanaan atau hapus kegiatan dari jadwal.</p>
                    </div>
                </div>

                {{-- Task Summary Box --}}
                <div class="bg-surface-container-low rounded-2xl p-4 border border-outline-variant/30 mb-5">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2">
                            <span id="modal-task-icon" class="material-symbols-outlined text-primary text-[20px]">eco</span>
                            <h4 id="modal-task-title" class="text-[14px] font-black text-on-surface leading-snug">Penyiraman Rutin</h4>
                        </div>
                        <span id="modal-task-status" class="text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-primary/10 text-primary border border-primary/20 shrink-0">PENDING</span>
                    </div>
                    <div class="text-[12px] text-on-surface-variant space-y-1 font-medium">
                        <p>Tanaman: <span id="modal-task-plant" class="text-on-surface font-bold">-</span> (<span id="modal-task-garden">-</span>)</p>
                        <p>Jadwal Saat Ini: <span id="modal-task-current-date" class="text-primary font-bold">-</span></p>
                    </div>
                </div>

                {{-- Alert for Completed Tasks --}}
                <div id="modal-completed-notice" class="hidden bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 mb-5 text-[13px]">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>Tugas Telah Selesai</span>
                    </div>
                    <p class="text-[12px]">Tugas ini telah ditandai selesai dan tidak dapat diubah atau dihapus.</p>
                </div>

                {{-- Reschedule Form --}}
                <div id="modal-form-body">
                    {{-- Error Notice Container --}}
                    <div id="modal-error-notice" class="hidden bg-[#ffdad6] border border-[#ba1a1a]/30 text-[#ba1a1a] rounded-2xl p-3 mb-4 text-[12px] font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                        <span id="modal-error-text">Terjadi kesalahan.</span>
                    </div>

                    {{-- Inline Delete Confirmation Box --}}
                    <div id="modal-delete-confirm-box" class="hidden bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-2xl p-3.5 mb-4 text-[12px]">
                        <p class="font-bold text-[#ba1a1a] mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">warning</span>
                            <span>Hapus kegiatan ini dari kalender?</span>
                        </p>
                        <p class="text-on-surface-variant mb-3">Tindakan ini akan menghapus jadwal kegiatan ini secara permanen.</p>
                        <div class="flex items-center gap-2 justify-end">
                            <button type="button" onclick="cancelDeleteEvent()" class="px-3 py-1.5 rounded-xl font-bold text-xs bg-white text-on-surface-variant border border-outline-variant/30 hover:bg-surface transition-colors">
                                Batal
                            </button>
                            <button type="button" id="btn-confirm-delete" onclick="submitDeleteEvent()" class="px-3 py-1.5 rounded-xl font-bold text-xs bg-[#ba1a1a] text-white hover:bg-[#93000a] transition-colors flex items-center gap-1 shadow-xs">
                                <span id="btn-delete-text">Ya, Hapus Kegiatan</span>
                                <span id="btn-delete-spinner" class="hidden material-symbols-outlined text-[14px] animate-spin">sync</span>
                            </button>
                        </div>
                    </div>

                    <div id="reschedule-input-container" class="mb-5">
                        <label for="reschedule-new-date" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-2">
                            Pilih Tanggal Baru
                        </label>
                        <input type="date" id="reschedule-new-date" min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/60 rounded-2xl px-4 py-3 text-on-surface text-[14px] font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        <p class="text-[11px] text-on-surface-variant flex items-center gap-1.5 mt-2 leading-tight">
                            <span class="material-symbols-outlined text-[15px] text-primary shrink-0">info</span>
                            <span>Bisa maju/mundur selama tanggal baru minimal <strong>hari ini ({{ date('d M Y') }})</strong>.</span>
                        </p>
                    </div>

                    <div class="flex gap-2 justify-between items-center pt-2 flex-wrap">
                        {{-- Delete Button --}}
                        <button type="button" id="btn-trigger-delete" onclick="showDeleteConfirm()" class="px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm text-[#ba1a1a] hover:bg-[#ffdad6]/60 border border-[#ba1a1a]/30 transition-all flex items-center gap-1 active:scale-95">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                            <span>Hapus</span>
                        </button>

                        <div class="flex gap-2 items-center ml-auto">
                            <button type="button" id="btn-submit-reschedule" onclick="submitReschedule()" class="px-5 py-2.5 rounded-full font-bold text-sm bg-primary text-white shadow-sm hover:bg-[#005236] transition-colors flex items-center gap-2 active:scale-95">
                                <span id="btn-reschedule-text">Simpan Jadwal Baru</span>
                                <span id="btn-reschedule-spinner" class="hidden material-symbols-outlined text-[16px] animate-spin">sync</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="modal-completed-footer" class="hidden justify-end pt-2">
                    <button type="button" onclick="closeRescheduleModal()" class="px-6 py-2.5 rounded-full font-bold text-sm bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-colors">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================================
         4. Add Task Modal (Tambah Kegiatan Baru)
         ============================================================ --}}
    <div id="add-task-modal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="modal-add-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeAddTaskModal()"></div>

        <div class="min-h-screen w-full px-4 py-8 flex items-center justify-center pointer-events-none">
            <div class="w-full max-w-[480px] bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative shrink-0">
                
                <button type="button" onclick="closeAddTaskModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors" aria-label="Tutup">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>

                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[22px]">add_task</span>
                    </div>
                    <div>
                        <h3 id="modal-add-title" class="text-[18px] md:text-[20px] font-black text-on-surface leading-tight">Tambah Kegiatan Perawatan</h3>
                        <p class="text-[12px] text-on-surface-variant">Buat jadwal tugas baru untuk tanaman di kebun Anda.</p>
                    </div>
                </div>

                {{-- Error Container --}}
                <div id="add-task-error" class="hidden bg-[#ffdad6] border border-[#ba1a1a]/30 text-[#ba1a1a] rounded-2xl p-3 mb-4 text-[12px] font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                    <span id="add-task-error-text">Terjadi kesalahan.</span>
                </div>

                <form id="add-task-form" onsubmit="event.preventDefault(); submitAddTask();">
                    {{-- 1. Plant Selector --}}
                    <div class="mb-4">
                        <label for="add-task-plant-id" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-1.5">
                            Pilih Tanaman <span class="text-error">*</span>
                        </label>
                        <select id="add-task-plant-id" class="w-full bg-surface border border-outline-variant/60 rounded-xl px-3.5 py-2.5 text-on-surface text-[14px] font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" required>
                            @foreach($plants as $p)
                                <option value="{{ $p->id }}" {{ $mainPlant && $mainPlant->id == $p->id ? 'selected' : '' }}>
                                    {{ $p->plantTemplate->name_id }} ({{ $p->garden->name ?? 'Kebun' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Event Type Selector --}}
                    <div class="mb-4">
                        <label for="add-task-event-type-id" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-1.5">
                            Jenis Kegiatan Perawatan <span class="text-error">*</span>
                        </label>
                        <select id="add-task-event-type-id" class="w-full bg-surface border border-outline-variant/60 rounded-xl px-3.5 py-2.5 text-on-surface text-[14px] font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" required>
                            @if(isset($eventTypes) && $eventTypes->count() > 0)
                                @foreach($eventTypes as $et)
                                    <option value="{{ $et->id }}">{{ $et->label }}</option>
                                @endforeach
                            @else
                                <option value="9">Pengingat Penyiraman</option>
                                <option value="10">Pengingat Pemupukan</option>
                                <option value="11">Inspeksi Hama</option>
                                <option value="12">Perempelan / Pemangkasan</option>
                                <option value="13">Pemasangan Ajir</option>
                                <option value="21">Pemeriksaan Drainase Pot</option>
                                <option value="22">Sanitasi Jamur Daun</option>
                            @endif
                        </select>
                    </div>

                    {{-- 3. Scheduled Date & Priority --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <div>
                            <label for="add-task-date" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-1.5">
                                Tanggal Pelaksanaan <span class="text-error">*</span>
                            </label>
                            <input type="date" id="add-task-date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/60 rounded-xl px-3 py-2 text-on-surface text-[13px] font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" required>
                        </div>
                        <div>
                            <label for="add-task-priority" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-1.5">
                                Prioritas
                            </label>
                            <select id="add-task-priority" class="w-full bg-surface border border-outline-variant/60 rounded-xl px-3 py-2 text-on-surface text-[13px] font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                <option value="MEDIUM">Sedang (Normal)</option>
                                <option value="HIGH">Tinggi (Penting)</option>
                                <option value="LOW">Rendah</option>
                            </select>
                        </div>
                    </div>

                    {{-- 4. Custom Message / Notes --}}
                    <div class="mb-5">
                        <label for="add-task-message" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-1.5">
                            Catatan Tambahan <span class="text-[11px] text-on-surface-variant font-normal">(Opsional)</span>
                        </label>
                        <input type="text" id="add-task-message" placeholder="Contoh: Berikan pupuk NPK 5 gram / Cek daun bawah" class="w-full bg-surface border border-outline-variant/60 rounded-xl px-3.5 py-2.5 text-on-surface text-[13px] focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 justify-end items-center pt-2">
                        <button type="submit" id="btn-submit-add-task" class="px-6 py-2.5 rounded-full font-bold text-sm bg-primary text-white shadow-sm hover:bg-[#005236] transition-colors flex items-center gap-2 active:scale-95">
                            <span id="btn-add-text">Simpan Kegiatan</span>
                            <span id="btn-add-spinner" class="hidden material-symbols-outlined text-[16px] animate-spin">sync</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ============================================================
         5. Edit Planted Date Modal (Original)
         ============================================================ --}}
    @if($mainPlant)
    <div id="edit-jadwal-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="document.getElementById('edit-jadwal-modal').classList.add('hidden')"></div>
        <div class="min-h-screen w-full px-4 py-8 flex items-center justify-center pointer-events-none">
            <div class="w-[90vw] max-w-[420px] bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative shrink-0">
                <button type="button" onclick="document.getElementById('edit-jadwal-modal').classList.add('hidden')" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                
                <h3 class="text-[20px] font-black text-on-surface mb-1">Edit Tanggal Tanam</h3>
                <p class="text-[13px] text-on-surface-variant mb-5">Ubah tanggal semai/tanam untuk memperbarui perhitungan umur tanaman {{ $mainPlant->plantTemplate->name_id }}.</p>
                
                <form action="{{ route('plants.update', $mainPlant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-5">
                        <label for="planted_date" class="block text-xs font-extrabold uppercase tracking-wider text-on-surface mb-2">Tanggal Tanam</label>
                        <input type="date" id="planted_date" name="planted_date" value="{{ \Carbon\Carbon::parse($mainPlant->planted_date)->format('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all font-bold" required>
                    </div>
                    
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('edit-jadwal-modal').classList.add('hidden')" class="px-5 py-2 rounded-full font-bold text-sm text-on-surface-variant hover:bg-surface-container-high transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-full font-bold text-sm bg-primary text-white shadow-sm hover:bg-[#005236] transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Toast Notification --}}
    <div id="calendar-toast" class="fixed bottom-6 right-6 z-[120] hidden transform transition-all duration-300 translate-y-4 opacity-0 pointer-events-none">
        <div class="bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-xl border border-white/10 flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-400 text-[20px]">check_circle</span>
            <span id="calendar-toast-msg" class="text-sm font-semibold">Jadwal berhasil diperbarui.</span>
        </div>
    </div>

</div>

{{-- Calendar Client Script --}}
<script>
    // State
    const MONTH_NAMES = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    let currentYear = {{ date('Y') }};
    let currentMonth = {{ date('n') }}; // 1 - 12
    let activePlantFilter = "{{ request('plant_id', ($mainPlant ? $mainPlant->id : 'all')) }}";
    let eventsData = [];
    let activeEvent = null;
    let todayWeather = null;

    const todayDate = new Date();
    const todayStr = `${todayDate.getFullYear()}-${String(todayDate.getMonth() + 1).padStart(2, '0')}-${String(todayDate.getDate()).padStart(2, '0')}`;

    document.addEventListener('DOMContentLoaded', () => {
        fetchEvents();
    });

    function showToast(message) {
        const toast = document.getElementById('calendar-toast');
        const msgEl = document.getElementById('calendar-toast-msg');
        if (!toast || !msgEl) return;

        msgEl.textContent = message;
        toast.classList.remove('hidden', 'translate-y-4', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 3500);
    }

    function onPlantFilterChange(plantId) {
        activePlantFilter = plantId;
        fetchEvents();
    }

    function prevMonth() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        fetchEvents();
    }

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        fetchEvents();
    }

    function jumpToToday() {
        const now = new Date();
        currentYear = now.getFullYear();
        currentMonth = now.getMonth() + 1;
        fetchEvents();
    }

    async function fetchEvents() {
        const spinner = document.getElementById('calendar-spinner');
        if (spinner) spinner.classList.remove('hidden');

        // Update Title
        const titleEl = document.getElementById('calendar-month-year');
        if (titleEl) {
            titleEl.textContent = `${MONTH_NAMES[currentMonth - 1]} ${currentYear}`;
        }

        try {
            const url = `/api/growth-calendar/events?plant_id=${encodeURIComponent(activePlantFilter)}&month=${currentMonth}&year=${currentYear}`;
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data && data.success) {
                eventsData = data.events || [];
                todayWeather = data.today_weather || null;
                renderGrid();
            }
        } catch (err) {
            console.error('Failed to fetch calendar events:', err);
        } finally {
            if (spinner) spinner.classList.add('hidden');
        }
    }

    function renderGrid() {
        const grid = document.getElementById('calendar-grid');
        if (!grid) return;
        grid.innerHTML = '';

        // Calculate days in month and start offset
        // JS getDay(): 0 = Sun, 1 = Mon ... 6 = Sat
        // We want Monday-based (0 = Mon, 6 = Sun)
        const firstDayObj = new Date(currentYear, currentMonth - 1, 1);
        const dayOfWeek = firstDayObj.getDay();
        const startOffset = (dayOfWeek + 6) % 7;

        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const daysInPrevMonth = new Date(currentYear, currentMonth - 1, 0).getDate();

        // 1. Previous Month Overflow Days
        for (let i = startOffset - 1; i >= 0; i--) {
            const dayNum = daysInPrevMonth - i;
            const cell = document.createElement('div');
            cell.className = 'min-h-[85px] md:min-h-[105px] p-2 rounded-xl bg-slate-50/40 border border-outline-variant/10 flex flex-col justify-between opacity-40 select-none';
            cell.innerHTML = `<span class="text-[11px] font-bold text-slate-400">${dayNum}</span>`;
            grid.appendChild(cell);
        }

        // 2. Current Month Days
        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const isToday = (dateStr === todayStr);
            const isPast = (dateStr < todayStr);

            const dayEvents = eventsData.filter(e => e.scheduled_date === dateStr);

            const cell = document.createElement('div');
            let cellClass = 'min-h-[85px] md:min-h-[105px] p-1.5 md:p-2 rounded-xl border flex flex-col justify-between transition-all relative overflow-hidden group cursor-pointer';

            if (isToday) {
                cellClass += ' bg-[#006c49]/5 border-primary ring-1.5 ring-primary/40 shadow-xs';
            } else if (isPast) {
                cellClass += ' bg-surface/50 border-outline-variant/20 hover:border-outline-variant/40';
            } else {
                cellClass += ' bg-white border-outline-variant/25 hover:border-primary/40 hover:shadow-xs';
            }
            cell.className = cellClass;
            cell.title = `Klik untuk kelola kegiatan tanggal ${dateStr}`;
            cell.onclick = (e) => {
                if (e.target.closest('button')) return;
                openDateModal(dateStr);
            };

            // Day Header (Date number + badge)
            let headerHtml = `
                <div class="flex items-center justify-between gap-1 mb-1">
                    <div class="flex items-center gap-1">
                        <span class="text-[12px] md:text-[13px] font-black ${isToday ? 'text-primary' : (isPast ? 'text-slate-500' : 'text-on-surface')}">
                            ${d}
                        </span>
                        ${isToday ? '<span class="text-[9px] font-black bg-primary text-white px-1.5 py-0.2 rounded-full uppercase tracking-tighter">Hari Ini</span>' : ''}
                    </div>
                    ${isToday && todayWeather ? `
                        <span class="text-[9px] font-bold text-primary flex items-center gap-0.5 bg-primary/10 px-1.5 py-0.5 rounded-md" title="Cuaca Hari Ini: ${todayWeather.condition_title} (${todayWeather.temperature}°C)">
                            <span class="material-symbols-outlined text-[12px]">${todayWeather.icon || 'wb_sunny'}</span>
                            <span>${todayWeather.temperature}°C</span>
                        </span>
                    ` : ''}
                </div>
            `;

            // Tasks List
            let tasksHtml = '<div class="flex flex-col gap-1 w-full overflow-hidden">';
            const visibleEvents = dayEvents.slice(0, 3);
            const remaining = dayEvents.length - visibleEvents.length;

            visibleEvents.forEach(evt => {
                const isCompleted = (evt.status === 'COMPLETED');
                const isMissed = (evt.status === 'MISSED');

                let badgeColor = 'bg-primary/10 text-primary border-primary/20 hover:bg-primary/20';
                if (isCompleted) {
                    badgeColor = 'bg-emerald-50 text-emerald-800 border-emerald-200 line-through opacity-75';
                } else if (isMissed) {
                    badgeColor = 'bg-[#ffdad6] text-[#ba1a1a] border-[#ba1a1a]/30 font-bold';
                } else {
                    const code = (evt.code || '').toLowerCase();
                    if (code.includes('water')) {
                        badgeColor = 'bg-cyan-50 text-cyan-800 border-cyan-200 hover:bg-cyan-100';
                    } else if (code.includes('fertiliz')) {
                        badgeColor = 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100';
                    } else if (code.includes('pest')) {
                        badgeColor = 'bg-[#944a23]/10 text-[#944a23] border-[#944a23]/30 hover:bg-[#944a23]/20';
                    }
                }

                tasksHtml += `
                    <button type="button" onclick="openDateModal('${dateStr}')" class="w-full text-left px-1.5 py-1 rounded-md text-[10px] md:text-[11px] font-extrabold border ${badgeColor} transition-transform active:scale-95 flex items-center gap-1 truncate shadow-2xs" title="${evt.title} (${evt.plant_name}) - Klik untuk kelola kegiatan">
                        <span class="material-symbols-outlined text-[13px] shrink-0">${evt.icon || 'eco'}</span>
                        <span class="truncate">${evt.title}</span>
                    </button>
                `;
            });

            if (remaining > 0) {
                tasksHtml += `
                    <button type="button" onclick="openDateModal('${dateStr}')" class="text-[9px] font-bold text-on-surface-variant bg-surface-container-high px-1.5 py-0.5 rounded text-center block w-full hover:bg-surface-container-highest transition-colors">
                        +${remaining} lainnya
                    </button>
                `;
            }

            tasksHtml += '</div>';

            cell.innerHTML = headerHtml + tasksHtml;
            grid.appendChild(cell);
        }

        // 3. Next Month Overflow Days
        const totalRendered = startOffset + daysInMonth;
        const trailing = (7 - (totalRendered % 7)) % 7;
        for (let j = 1; j <= trailing; j++) {
            const cell = document.createElement('div');
            cell.className = 'min-h-[85px] md:min-h-[105px] p-2 rounded-xl bg-slate-50/40 border border-outline-variant/10 flex flex-col justify-between opacity-40 select-none';
            cell.innerHTML = `<span class="text-[11px] font-bold text-slate-400">${j}</span>`;
            grid.appendChild(cell);
        }
    }

    // ============================================================
    // Date Activities Modal Functions (Teken Tanggal -> List Kegiatan, Tambah, Ubah, Hapus)
    // ============================================================
    let activeDateStr = null;
    const INDO_DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    function formatDateIndo(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const year = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const day = parseInt(parts[2], 10);
        const d = new Date(year, month, day);
        const dayName = INDO_DAYS[d.getDay()] || '';
        const monthName = MONTH_NAMES[month] || '';
        return `${dayName}, ${day} ${monthName} ${year}`;
    }

    function openDateModal(dateStr, autoOpenAdd = false) {
        activeDateStr = dateStr;
        const modal = document.getElementById('date-activities-modal');
        const titleEl = document.getElementById('date-modal-title');
        const todayBadge = document.getElementById('date-modal-today-badge');
        
        if (titleEl) titleEl.textContent = formatDateIndo(dateStr);
        if (todayBadge) {
            todayBadge.classList.toggle('hidden', dateStr !== todayStr);
        }

        // Today Weather Card in Date Modal
        const weatherCard = document.getElementById('date-modal-weather-card');
        if (weatherCard) {
            if (dateStr === todayStr && todayWeather) {
                weatherCard.classList.remove('hidden');
                const iconEl = document.getElementById('date-modal-weather-icon');
                if (iconEl) iconEl.textContent = todayWeather.icon || 'wb_sunny';
                const titleEl = document.getElementById('date-modal-weather-title');
                if (titleEl) titleEl.textContent = `Cuaca Hari Ini: ${todayWeather.condition_title} (${todayWeather.temperature}°C)`;
                const badgeEl = document.getElementById('date-modal-weather-badge');
                if (badgeEl) {
                    badgeEl.textContent = todayWeather.watering_badge || 'Normal';
                    badgeEl.className = `text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase ${todayWeather.watering_badge_bg || 'bg-primary/10 text-primary'}`;
                }
                const adviceEl = document.getElementById('date-modal-weather-advice');
                if (adviceEl) adviceEl.textContent = todayWeather.watering_advice || todayWeather.summary || 'Kondisi cuaca ideal.';
            } else {
                weatherCard.classList.add('hidden');
            }
        }

        toggleDateAddForm(autoOpenAdd);
        renderDateActivitiesList();

        if (modal) modal.classList.remove('hidden');
    }

    function closeDateModal() {
        const modal = document.getElementById('date-activities-modal');
        if (modal) modal.classList.add('hidden');
        toggleDateAddForm(false);
        activeDateStr = null;
    }

    function toggleDateAddForm(forceState = null) {
        const wrapper = document.getElementById('date-add-form-wrapper');
        const btnLabel = document.getElementById('btn-toggle-date-add-label');
        const errBox = document.getElementById('date-add-error');
        const plantSelect = document.getElementById('date-add-plant-id');
        const msgInput = document.getElementById('date-add-message');

        if (!wrapper) return;
        const isHidden = (forceState !== null) ? !forceState : !wrapper.classList.contains('hidden');

        if (isHidden) {
            wrapper.classList.add('hidden');
            if (btnLabel) btnLabel.textContent = 'Tambah Kegiatan';
        } else {
            wrapper.classList.remove('hidden');
            if (btnLabel) btnLabel.textContent = 'Tutup Form';
            if (errBox) errBox.classList.add('hidden');
            if (msgInput) msgInput.value = '';
            if (plantSelect && activePlantFilter && activePlantFilter !== 'all') {
                plantSelect.value = activePlantFilter;
            }
        }
    }

    async function submitAddTaskFromDateModal() {
        if (!activeDateStr) return;

        const plantSelect = document.getElementById('date-add-plant-id');
        const typeSelect = document.getElementById('date-add-event-type-id');
        const prioritySelect = document.getElementById('date-add-priority');
        const msgInput = document.getElementById('date-add-message');

        const errBox = document.getElementById('date-add-error');
        const errText = document.getElementById('date-add-error-text');
        const btnText = document.getElementById('btn-date-add-label');
        const btnSpinner = document.getElementById('btn-date-add-spinner');
        const submitBtn = document.getElementById('btn-submit-date-add');

        const plantId = plantSelect ? plantSelect.value : '';
        const eventTypeId = typeSelect ? typeSelect.value : '';
        const priority = prioritySelect ? prioritySelect.value : 'MEDIUM';
        const message = msgInput ? msgInput.value : '';

        if (!plantId || !eventTypeId) {
            if (errText) errText.textContent = 'Harap pilih tanaman dan jenis kegiatan.';
            if (errBox) errBox.classList.remove('hidden');
            return;
        }

        if (activeDateStr < todayStr) {
            if (errText) errText.textContent = 'Kegiatan baru tidak boleh dijadwalkan pada tanggal sebelum hari ini.';
            if (errBox) errBox.classList.remove('hidden');
            return;
        }

        if (btnText) btnText.textContent = 'Menyimpan...';
        if (btnSpinner) btnSpinner.classList.remove('hidden');
        if (submitBtn) submitBtn.disabled = true;
        if (errBox) errBox.classList.add('hidden');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/api/growth-calendar/events', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    plant_id: plantId,
                    event_type_id: eventTypeId,
                    scheduled_date: activeDateStr,
                    priority: priority,
                    message: message
                })
            });

            const result = await res.json();
            if (res.ok && result.success) {
                toggleDateAddForm(false);
                showToast(result.message || 'Kegiatan berhasil ditambahkan.');
                await fetchEvents();
                renderDateActivitiesList();
            } else {
                const msg = result.message || (result.errors && Object.values(result.errors)[0][0]) || 'Gagal menambahkan kegiatan.';
                if (errText) errText.textContent = msg;
                if (errBox) errBox.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Submit error:', err);
            if (errText) errText.textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
            if (errBox) errBox.classList.remove('hidden');
        } finally {
            if (btnText) btnText.textContent = 'Simpan Kegiatan';
            if (btnSpinner) btnSpinner.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    function renderDateActivitiesList() {
        const listEl = document.getElementById('date-activities-list');
        const emptyEl = document.getElementById('date-activities-empty');
        const countEl = document.getElementById('date-modal-count');
        if (!listEl || !activeDateStr) return;

        const dayEvents = eventsData.filter(e => e.scheduled_date === activeDateStr);
        if (countEl) {
            countEl.textContent = `${dayEvents.length} Kegiatan Terjadwal`;
        }

        if (dayEvents.length === 0) {
            listEl.innerHTML = '';
            if (emptyEl) emptyEl.classList.remove('hidden');
            return;
        }

        if (emptyEl) emptyEl.classList.add('hidden');
        listEl.innerHTML = '';

        dayEvents.forEach(evt => {
            const isCompleted = (evt.status === 'COMPLETED');
            const isMissed = (evt.status === 'MISSED');

            let statusBadge = '<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-primary/10 text-primary border border-primary/20 shrink-0">PENDING</span>';
            if (isCompleted) {
                statusBadge = '<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">SELESAI</span>';
            } else if (isMissed) {
                statusBadge = '<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-[#ffdad6] text-[#ba1a1a] border border-[#ba1a1a]/30 shrink-0">TERLEWAT</span>';
            }

            let weatherBadgeHtml = '';
            if (evt.weather_tag) {
                weatherBadgeHtml = `<span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-full ${evt.weather_badge_bg || 'bg-primary/10 text-primary'} shrink-0">${evt.weather_tag}</span>`;
            }

            let weatherReasonHtml = '';
            if (evt.weather_reason) {
                weatherReasonHtml = `<p class="text-[10px] text-primary/85 font-semibold truncate mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">schedule</span><span>${evt.weather_reason}</span></p>`;
            }

            const item = document.createElement('div');
            item.className = 'bg-white rounded-2xl p-3.5 border border-outline-variant/30 shadow-2xs space-y-2.5 transition-all';
            item.id = `date-event-item-${evt.id}`;

            item.innerHTML = `
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">${evt.icon || 'eco'}</span>
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-[13px] font-black text-on-surface leading-tight truncate">${evt.title}</h5>
                            <p class="text-[11px] text-on-surface-variant truncate">Tanaman: <span class="font-bold text-on-surface">${evt.plant_name}</span> (${evt.garden_name})</p>
                            ${weatherReasonHtml}
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        ${weatherBadgeHtml}
                        ${statusBadge}
                    </div>
                </div>

                ${!isCompleted ? `
                <div class="flex items-center justify-between gap-2 pt-1 border-t border-outline-variant/15 flex-wrap">
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="toggleInlineReschedule(${evt.id})" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-primary hover:bg-primary/10 transition-colors flex items-center gap-1 active:scale-95" title="Pindahkan tanggal kegiatan">
                            <span class="material-symbols-outlined text-[15px]">edit_calendar</span>
                            <span>Pindah Tanggal</span>
                        </button>
                        <button type="button" onclick="toggleInlineDelete(${evt.id})" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#ba1a1a] hover:bg-[#ffdad6]/60 transition-colors flex items-center gap-1 active:scale-95" title="Hapus kegiatan">
                            <span class="material-symbols-outlined text-[15px]">delete</span>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>

                <div id="inline-reschedule-box-${evt.id}" class="hidden bg-surface-container-low rounded-xl p-3 border border-outline-variant/30 space-y-2 mt-2">
                    <div class="flex items-center justify-between">
                        <label for="inline-reschedule-input-${evt.id}" class="text-[11px] font-black uppercase text-on-surface">Pilih Tanggal Baru</label>
                        <button type="button" onclick="toggleInlineReschedule(${evt.id})" class="text-slate-400 hover:text-slate-600">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="date" id="inline-reschedule-input-${evt.id}" min="${todayStr}" value="${(evt.scheduled_date >= todayStr ? evt.scheduled_date : todayStr)}" class="flex-1 bg-white border border-outline-variant/60 rounded-xl px-2.5 py-1.5 text-xs font-bold text-on-surface focus:outline-none focus:border-primary">
                        <button type="button" id="btn-save-inline-${evt.id}" onclick="submitInlineReschedule(${evt.id})" class="px-3 py-1.5 rounded-xl font-bold text-xs bg-primary text-white hover:bg-[#005236] transition-colors flex items-center gap-1 shrink-0 active:scale-95">
                            <span>Simpan</span>
                        </button>
                    </div>
                    <p class="text-[10px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px] text-primary">info</span>
                        <span>Bisa maju/mundur minimal <strong>hari ini (${todayStr})</strong>.</span>
                    </p>
                </div>

                <div id="inline-delete-box-${evt.id}" class="hidden bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-xl p-2.5 space-y-2 mt-2">
                    <p class="text-[11px] font-bold text-[#ba1a1a] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        <span>Hapus kegiatan '${evt.title}'?</span>
                    </p>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleInlineDelete(${evt.id})" class="px-2.5 py-1 rounded-lg text-xs font-bold text-on-surface-variant bg-white border border-outline-variant/30 hover:bg-surface">
                            Batal
                        </button>
                        <button type="button" id="btn-del-inline-${evt.id}" onclick="submitInlineDelete(${evt.id})" class="px-2.5 py-1 rounded-lg text-xs font-bold text-white bg-[#ba1a1a] hover:bg-[#93000a] transition-colors shadow-2xs">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
                ` : `
                <div class="flex items-center gap-1.5 pt-1 text-[11px] font-semibold text-emerald-700">
                    <span class="material-symbols-outlined text-[15px]">check_circle</span>
                    <span>Kegiatan ini telah selesai dikerjakan.</span>
                </div>
                `}
            `;

            listEl.appendChild(item);
        });
    }

    function toggleInlineReschedule(eventId) {
        const box = document.getElementById(`inline-reschedule-box-${eventId}`);
        const deleteBox = document.getElementById(`inline-delete-box-${eventId}`);
        if (deleteBox) deleteBox.classList.add('hidden');
        if (box) box.classList.toggle('hidden');
    }

    function toggleInlineDelete(eventId) {
        const box = document.getElementById(`inline-delete-box-${eventId}`);
        const reschedBox = document.getElementById(`inline-reschedule-box-${eventId}`);
        if (reschedBox) reschedBox.classList.add('hidden');
        if (box) box.classList.toggle('hidden');
    }

    async function submitInlineReschedule(eventId) {
        const input = document.getElementById(`inline-reschedule-input-${eventId}`);
        const btn = document.getElementById(`btn-save-inline-${eventId}`);
        const newDate = input ? input.value : '';
        if (!newDate) {
            alert('Pilih tanggal baru.');
            return;
        }

        if (newDate < todayStr) {
            alert('Jadwal tidak boleh dipindahkan ke tanggal sebelum hari ini.');
            return;
        }

        if (btn) {
            btn.disabled = true;
            btn.textContent = '...';
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/api/events/${eventId}/reschedule`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    new_date: newDate
                })
            });

            const result = await res.json();
            if (res.ok && result.success) {
                showToast(result.message || 'Jadwal berhasil dipindahkan.');
                await fetchEvents();
                renderDateActivitiesList();
            } else {
                alert(result.message || 'Gagal mengubah jadwal.');
            }
        } catch (err) {
            console.error('Reschedule error:', err);
            alert('Terjadi kesalahan koneksi.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        }
    }

    async function submitInlineDelete(eventId) {
        const btn = document.getElementById(`btn-del-inline-${eventId}`);
        if (btn) {
            btn.disabled = true;
            btn.textContent = '...';
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/api/events/${eventId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await res.json();
            if (res.ok && result.success) {
                showToast(result.message || 'Kegiatan berhasil dihapus.');
                await fetchEvents();
                renderDateActivitiesList();
            } else {
                alert(result.message || 'Gagal menghapus kegiatan.');
            }
        } catch (err) {
            console.error('Delete error:', err);
            alert('Terjadi kesalahan koneksi.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Ya, Hapus';
            }
        }
    }

    // ============================================================
    // Add Task Modal Functions
    // ============================================================
    function openAddTaskModal(defaultDate = null) {
        const modal = document.getElementById('add-task-modal');
        const errBox = document.getElementById('add-task-error');
        const dateInput = document.getElementById('add-task-date');
        const plantSelect = document.getElementById('add-task-plant-id');
        const msgInput = document.getElementById('add-task-message');

        if (errBox) errBox.classList.add('hidden');
        if (msgInput) msgInput.value = '';

        if (dateInput) {
            dateInput.min = todayStr;
            dateInput.value = (defaultDate && defaultDate >= todayStr) ? defaultDate : todayStr;
        }

        // Pre-select plant if activePlantFilter is a specific plant
        if (plantSelect && activePlantFilter && activePlantFilter !== 'all') {
            plantSelect.value = activePlantFilter;
        }

        if (modal) modal.classList.remove('hidden');
    }

    function openAddTaskModalForDate(dateStr) {
        openAddTaskModal(dateStr);
    }

    function closeAddTaskModal() {
        const modal = document.getElementById('add-task-modal');
        if (modal) modal.classList.add('hidden');
    }

    async function submitAddTask() {
        const plantSelect = document.getElementById('add-task-plant-id');
        const typeSelect = document.getElementById('add-task-event-type-id');
        const dateInput = document.getElementById('add-task-date');
        const prioritySelect = document.getElementById('add-task-priority');
        const msgInput = document.getElementById('add-task-message');

        const errBox = document.getElementById('add-task-error');
        const errText = document.getElementById('add-task-error-text');
        const btnText = document.getElementById('btn-add-text');
        const btnSpinner = document.getElementById('btn-add-spinner');
        const submitBtn = document.getElementById('btn-submit-add-task');

        const plantId = plantSelect ? plantSelect.value : '';
        const eventTypeId = typeSelect ? typeSelect.value : '';
        const schedDate = dateInput ? dateInput.value : '';
        const priority = prioritySelect ? prioritySelect.value : 'MEDIUM';
        const message = msgInput ? msgInput.value : '';

        if (!plantId || !eventTypeId || !schedDate) {
            if (errText) errText.textContent = 'Harap lengkapi semua kolom yang wajib diisi.';
            if (errBox) errBox.classList.remove('hidden');
            return;
        }

        if (schedDate < todayStr) {
            if (errText) errText.textContent = 'Tanggal kegiatan tidak boleh sebelum hari ini.';
            if (errBox) errBox.classList.remove('hidden');
            return;
        }

        // Show loading state
        if (btnText) btnText.textContent = 'Menyimpan...';
        if (btnSpinner) btnSpinner.classList.remove('hidden');
        if (submitBtn) submitBtn.disabled = true;
        if (errBox) errBox.classList.add('hidden');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/api/growth-calendar/events', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    plant_id: plantId,
                    event_type_id: eventTypeId,
                    scheduled_date: schedDate,
                    priority: priority,
                    message: message
                })
            });

            const result = await res.json();
            if (res.ok && result.success) {
                closeAddTaskModal();
                showToast(result.message || 'Kegiatan berhasil ditambahkan.');
                // Refresh calendar events
                fetchEvents();
            } else {
                const message = result.message || (result.errors && Object.values(result.errors)[0][0]) || 'Gagal menambahkan kegiatan.';
                if (errText) errText.textContent = message;
                if (errBox) errBox.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Add task error:', err);
            if (errText) errText.textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
            if (errBox) errBox.classList.remove('hidden');
        } finally {
            if (btnText) btnText.textContent = 'Simpan Kegiatan';
            if (btnSpinner) btnSpinner.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    // ============================================================
    // Reschedule & Delete Modal Functions
    // ============================================================
    function openRescheduleModalById(eventId) {
        const evt = eventsData.find(e => e.id === eventId);
        if (!evt) return;
        activeEvent = evt;

        const modal = document.getElementById('reschedule-modal');
        const iconEl = document.getElementById('modal-task-icon');
        const titleEl = document.getElementById('modal-task-title');
        const statusEl = document.getElementById('modal-task-status');
        const plantEl = document.getElementById('modal-task-plant');
        const gardenEl = document.getElementById('modal-task-garden');
        const dateEl = document.getElementById('modal-task-current-date');
        const dateInput = document.getElementById('reschedule-new-date');
        const formBody = document.getElementById('modal-form-body');
        const completedNotice = document.getElementById('modal-completed-notice');
        const completedFooter = document.getElementById('modal-completed-footer');
        const errNotice = document.getElementById('modal-error-notice');
        const deleteConfirmBox = document.getElementById('modal-delete-confirm-box');
        const triggerDeleteBtn = document.getElementById('btn-trigger-delete');

        if (errNotice) errNotice.classList.add('hidden');
        if (deleteConfirmBox) deleteConfirmBox.classList.add('hidden');

        if (iconEl) iconEl.textContent = evt.icon || 'eco';
        if (titleEl) titleEl.textContent = evt.title;
        if (plantEl) plantEl.textContent = evt.plant_name;
        if (gardenEl) gardenEl.textContent = evt.garden_name;
        if (dateEl) dateEl.textContent = evt.scheduled_date_formatted || evt.scheduled_date;

        if (statusEl) {
            statusEl.textContent = evt.status;
            if (evt.status === 'MISSED') {
                statusEl.className = 'text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-[#ffdad6] text-[#ba1a1a] border border-[#ba1a1a]/30 shrink-0';
            } else if (evt.status === 'COMPLETED') {
                statusEl.className = 'text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0';
            } else {
                statusEl.className = 'text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase bg-primary/10 text-primary border border-primary/20 shrink-0';
            }
        }

        if (evt.status === 'COMPLETED') {
            if (formBody) formBody.classList.add('hidden');
            if (completedNotice) completedNotice.classList.remove('hidden');
            if (completedFooter) completedFooter.classList.remove('hidden');
            if (completedFooter) completedFooter.classList.add('flex');
            if (triggerDeleteBtn) triggerDeleteBtn.classList.add('hidden');
        } else {
            if (formBody) formBody.classList.remove('hidden');
            if (completedNotice) completedNotice.classList.add('hidden');
            if (completedFooter) completedFooter.classList.add('hidden');
            if (completedFooter) completedFooter.classList.remove('flex');
            if (triggerDeleteBtn) triggerDeleteBtn.classList.remove('hidden');

            if (dateInput) {
                const currentSched = evt.scheduled_date;
                dateInput.value = (currentSched && currentSched >= todayStr) ? currentSched : todayStr;
                dateInput.min = todayStr;
            }
        }

        if (modal) modal.classList.remove('hidden');
    }

    function quickRescheduleById(eventId) {
        openRescheduleModalById(eventId);
    }

    function closeRescheduleModal() {
        const modal = document.getElementById('reschedule-modal');
        if (modal) modal.classList.add('hidden');
        cancelDeleteEvent();
        activeEvent = null;
    }

    function showDeleteConfirm() {
        const deleteConfirmBox = document.getElementById('modal-delete-confirm-box');
        if (deleteConfirmBox) deleteConfirmBox.classList.remove('hidden');
    }

    function cancelDeleteEvent() {
        const deleteConfirmBox = document.getElementById('modal-delete-confirm-box');
        if (deleteConfirmBox) deleteConfirmBox.classList.add('hidden');
    }

    async function submitDeleteEvent() {
        if (!activeEvent) return;

        const btnText = document.getElementById('btn-delete-text');
        const btnSpinner = document.getElementById('btn-delete-spinner');
        const submitBtn = document.getElementById('btn-confirm-delete');
        const errNotice = document.getElementById('modal-error-notice');
        const errText = document.getElementById('modal-error-text');

        // Show loading state
        if (btnText) btnText.textContent = 'Menghapus...';
        if (btnSpinner) btnSpinner.classList.remove('hidden');
        if (submitBtn) submitBtn.disabled = true;
        if (errNotice) errNotice.classList.add('hidden');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/api/events/${activeEvent.id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await res.json();
            if (res.ok && result.success) {
                closeRescheduleModal();
                showToast(result.message || 'Kegiatan berhasil dihapus.');
                // Refresh calendar events
                fetchEvents();
            } else {
                const message = result.message || 'Gagal menghapus kegiatan.';
                if (errText) errText.textContent = message;
                if (errNotice) errNotice.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Delete error:', err);
            if (errText) errText.textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
            if (errNotice) errNotice.classList.remove('hidden');
        } finally {
            if (btnText) btnText.textContent = 'Ya, Hapus Kegiatan';
            if (btnSpinner) btnSpinner.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    async function submitReschedule() {
        if (!activeEvent) return;

        const dateInput = document.getElementById('reschedule-new-date');
        const errNotice = document.getElementById('modal-error-notice');
        const errText = document.getElementById('modal-error-text');
        const btnText = document.getElementById('btn-reschedule-text');
        const btnSpinner = document.getElementById('btn-reschedule-spinner');
        const submitBtn = document.getElementById('btn-submit-reschedule');

        const newDate = dateInput ? dateInput.value : '';
        if (!newDate) {
            if (errText) errText.textContent = 'Harap pilih tanggal baru.';
            if (errNotice) errNotice.classList.remove('hidden');
            return;
        }

        if (newDate < todayStr) {
            if (errText) errText.textContent = 'Jadwal tidak boleh dipindahkan ke tanggal sebelum hari ini.';
            if (errNotice) errNotice.classList.remove('hidden');
            return;
        }

        // Show loading state
        if (btnText) btnText.textContent = 'Menyimpan...';
        if (btnSpinner) btnSpinner.classList.remove('hidden');
        if (submitBtn) submitBtn.disabled = true;
        if (errNotice) errNotice.classList.add('hidden');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/api/events/${activeEvent.id}/reschedule`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    new_date: newDate
                })
            });

            const result = await res.json();
            if (res.ok && result.success) {
                closeRescheduleModal();
                showToast(result.message || 'Jadwal berhasil dipindahkan.');
                // Refresh calendar events
                fetchEvents();
            } else {
                const message = result.message || (result.errors && Object.values(result.errors)[0][0]) || 'Gagal mengubah jadwal.';
                if (errText) errText.textContent = message;
                if (errNotice) errNotice.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Reschedule error:', err);
            if (errText) errText.textContent = 'Terjadi gangguan koneksi. Silakan coba lagi.';
            if (errNotice) errNotice.classList.remove('hidden');
        } finally {
            if (btnText) btnText.textContent = 'Simpan Jadwal Baru';
            if (btnSpinner) btnSpinner.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;
        }
    }
</script>
@endsection
