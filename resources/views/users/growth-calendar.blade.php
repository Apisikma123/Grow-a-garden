@extends('layouts.dashboard')

@section('title', 'Kalender Tanam — Grow a Garden')
@section('description', 'Pantau dan kelola tahap pertumbuhan tanaman Anda dengan kalender bulanan interaktif.')

@section('dashboard-content')
<div class="relative min-h-[80vh] pb-10">

    {{-- Main Container --}}
    <div class="flex flex-col gap-[24px]">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-2">
            <div>
                <h1 class="text-[30px] md:text-[42px] font-bold text-on-surface tracking-tight leading-tight mb-1">Kalender Tanam</h1>
                <p class="text-[15px] md:text-[16px] text-on-surface-variant max-w-xl leading-relaxed">Pantau linimasa pertumbuhan cerdas dan kelola jadwal perawatan tanaman Anda secara interaktif.</p>
            </div>
            @if($mainPlant)
            <div class="flex items-center gap-2">
                <button type="button" onclick="document.getElementById('edit-jadwal-modal').classList.remove('hidden')" class="bg-white border border-outline-variant/40 text-on-surface-variant font-bold px-4 py-2.5 rounded-xl hover:bg-surface hover:text-primary hover:border-primary/30 transition-all flex items-center gap-2 text-sm shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">edit_calendar</span>
                    <span>Ubah Tgl Tanam</span>
                </button>
            </div>
            @endif
        </div>

        @if(!$mainPlant)
        {{-- Empty State (Layout Anti-Collapse Safe) --}}
        <div class="w-full bg-surface rounded-[24px] p-8 md:p-14 text-center border border-outline-variant/30 ambient-shadow-lg flex flex-col items-center justify-center">
            <div class="w-full max-w-md mx-auto flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-5 shadow-inner">
                    <span class="material-symbols-outlined text-[42px]">yard</span>
                </div>
                <h2 class="text-[22px] font-black text-on-surface mb-2 w-full">Belum Ada Tanaman Aktif</h2>
                <p class="text-[14px] text-on-surface-variant mb-6 w-full leading-relaxed">Tambahkan tanaman di kebun Anda untuk melihat kalender bulanan dan rekomendasi perawatan cerdas.</p>
                <a href="{{ route('gardens') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-full hover:bg-[#005236] transition-all shadow-md active:scale-95">
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
                            Umur <span class="text-primary font-black">{{ max(1, $currentHst) }} HST</span> • 
                            Est. Panen <span class="text-[#944a23] font-black">{{ $mainPlant->plantTemplate->harvest_start_day ?? 30 }} HST</span>
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

                    {{-- Controls: Plant Filter, Today, Prev/Next --}}
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
                        <button type="button" onclick="jumpToToday()" class="text-[12px] font-extrabold text-primary border border-primary/30 hover:bg-primary/10 active:scale-95 px-3 py-2 rounded-xl transition-all shadow-2xs">
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
                        * Klik pada tugas untuk mereschedule ke tanggal baru
                    </div>
                </div>

            </div>

            {{-- Sidebar Supporting Cards (2 Columns) --}}
            <div class="lg:col-span-2 flex flex-col gap-[24px]">

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
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-[13px] font-bold text-on-surface truncate">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h4>
                                            @if($task->status === 'MISSED')
                                                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-[#ffdad6] text-[#ba1a1a] shrink-0 uppercase">Terlewat</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-on-surface-variant font-medium truncate mt-0.5">
                                            {{ $task->plant ? $task->plant->plantTemplate->name_id : 'Tanaman' }}
                                        </p>
                                    </div>
                                    <button type="button" onclick="quickRescheduleById({{ $task->id }})" class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-surface-container-high rounded-lg transition-colors shrink-0" title="Reschedule Jadwal">
                                        <span class="material-symbols-outlined text-[18px]">update</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="w-full py-8 text-center bg-surface-container-low rounded-2xl border border-outline-variant/20 flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-[32px] text-primary/50 mb-1">task_alt</span>
                            <p class="text-[13px] font-bold text-on-surface">Semua Beres!</p>
                            <p class="text-[11px] text-on-surface-variant">Tidak ada tugas mendesak hari ini.</p>
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
                                        <p class="text-[11px] text-on-surface-variant truncate">Kebun: {{ $p->garden->name ?? '-' }} • {{ max(1, $p->hst) }} HST</p>
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

                {{-- Card: Edukasi Reschedule & Tips --}}
                <div class="w-full bg-gradient-to-br from-[#006c49]/10 via-surface to-[#944a23]/10 rounded-[24px] p-6 border border-primary/20 ambient-shadow-lg flex flex-col">
                    <div class="flex items-center gap-2 mb-2 text-primary font-black text-[14px]">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                        <span>Fleksibilitas Reschedule</span>
                    </div>
                    <p class="text-[12px] text-on-surface-variant leading-relaxed mb-3">
                        Kondisi cuaca atau kesibukan mendadak? Anda dapat memindahkan tanggal jadwal perawatan maju ke masa depan maupun mundur mendekat, asalkan tidak sebelum hari ini.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] font-bold text-[#006c49] bg-white/80 p-2.5 rounded-xl border border-primary/20">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        <span>Tugas yang terlewat (Missed) akan aktif kembali saat dijadwal ulang ke hari ini atau ke depan!</span>
                    </div>
                </div>

            </div>

        </div>
        @endif

    </div>

    {{-- ============================================================
         3. Interactive Reschedule Modal
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
                        <h3 id="modal-reschedule-title" class="text-[18px] md:text-[20px] font-black text-on-surface leading-tight">Reschedule Jadwal Perawatan</h3>
                        <p class="text-[12px] text-on-surface-variant">Pindahkan waktu pelaksanaan tugas ke tanggal baru.</p>
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
                    <p class="text-[12px]">Tugas ini telah ditandai selesai dan tidak dapat di-reschedule lagi.</p>
                </div>

                {{-- Reschedule Form --}}
                <div id="modal-form-body">
                    {{-- Error Notice Container --}}
                    <div id="modal-error-notice" class="hidden bg-[#ffdad6] border border-[#ba1a1a]/30 text-[#ba1a1a] rounded-2xl p-3 mb-4 text-[12px] font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                        <span id="modal-error-text">Terjadi kesalahan.</span>
                    </div>

                    <div class="mb-5">
                        <label for="reschedule-new-date" class="block text-xs font-black uppercase tracking-wider text-on-surface mb-2">
                            Pilih Tanggal Baru
                        </label>
                        <input type="date" id="reschedule-new-date" min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/60 rounded-2xl px-4 py-3 text-on-surface text-[14px] font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        <p class="text-[11px] text-on-surface-variant flex items-center gap-1.5 mt-2 leading-tight">
                            <span class="material-symbols-outlined text-[15px] text-primary shrink-0">info</span>
                            <span>Bisa maju/mundur selama tanggal baru minimal <strong>hari ini ({{ date('d M Y') }})</strong>.</span>
                        </p>
                    </div>

                    <div class="flex gap-3 justify-end items-center pt-2">
                        <button type="button" onclick="closeRescheduleModal()" class="px-5 py-2.5 rounded-full font-bold text-sm text-on-surface-variant hover:bg-surface-container-high transition-colors">
                            Batal
                        </button>
                        <button type="button" id="btn-submit-reschedule" onclick="submitReschedule()" class="px-6 py-2.5 rounded-full font-bold text-sm bg-primary text-white shadow-sm hover:bg-[#005236] transition-colors flex items-center gap-2 active:scale-95">
                            <span id="btn-reschedule-text">Simpan Jadwal Baru</span>
                            <span id="btn-reschedule-spinner" class="hidden material-symbols-outlined text-[16px] animate-spin">sync</span>
                        </button>
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
         4. Edit Planted Date Modal (Original)
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
            let cellClass = 'min-h-[85px] md:min-h-[105px] p-1.5 md:p-2 rounded-xl border flex flex-col justify-between transition-all relative overflow-hidden group';

            if (isToday) {
                cellClass += ' bg-[#006c49]/5 border-primary ring-1.5 ring-primary/40 shadow-xs';
            } else if (isPast) {
                cellClass += ' bg-surface/50 border-outline-variant/20 hover:border-outline-variant/40';
            } else {
                cellClass += ' bg-white border-outline-variant/25 hover:border-primary/40 hover:shadow-xs';
            }
            cell.className = cellClass;

            // Day Header (Date number + badge)
            let headerHtml = `
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[12px] md:text-[13px] font-black ${isToday ? 'text-primary' : (isPast ? 'text-slate-500' : 'text-on-surface')}">
                        ${d}
                    </span>
                    ${isToday ? '<span class="text-[9px] font-black bg-primary text-white px-1.5 py-0.2 rounded-full uppercase tracking-tighter">Hari Ini</span>' : ''}
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
                    <button type="button" onclick="openRescheduleModalById(${evt.id})" class="w-full text-left px-1.5 py-1 rounded-md text-[10px] md:text-[11px] font-extrabold border ${badgeColor} transition-transform active:scale-95 flex items-center gap-1 truncate shadow-2xs" title="${evt.title} (${evt.plant_name}) - Klik untuk kelola">
                        <span class="material-symbols-outlined text-[13px] shrink-0">${evt.icon || 'eco'}</span>
                        <span class="truncate">${evt.title}</span>
                    </button>
                `;
            });

            if (remaining > 0) {
                tasksHtml += `
                    <span class="text-[9px] font-bold text-on-surface-variant bg-surface-container-high px-1.5 py-0.5 rounded text-center block">
                        +${remaining} lainnya
                    </span>
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

        if (errNotice) errNotice.classList.add('hidden');

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
        } else {
            if (formBody) formBody.classList.remove('hidden');
            if (completedNotice) completedNotice.classList.add('hidden');
            if (completedFooter) completedFooter.classList.add('hidden');
            if (completedFooter) completedFooter.classList.remove('flex');

            if (dateInput) {
                // Set default value to event scheduled_date or today if scheduled_date is in the past
                const currentSched = evt.scheduled_date;
                dateInput.value = (currentSched && currentSched >= todayStr) ? currentSched : todayStr;
                dateInput.min = todayStr;
            }
        }

        if (modal) modal.classList.remove('hidden');
    }

    function quickRescheduleById(eventId) {
        // Quick hook for sidebar tasks
        openRescheduleModalById(eventId);
    }

    function closeRescheduleModal() {
        const modal = document.getElementById('reschedule-modal');
        if (modal) modal.classList.add('hidden');
        activeEvent = null;
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
                // Refresh calendar events to reflect updated date and status
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
