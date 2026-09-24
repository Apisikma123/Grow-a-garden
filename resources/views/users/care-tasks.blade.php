@extends('layouts.dashboard')

@section('title', 'Tugas Perawatan — Grow a Garden')
@section('description', 'Kelola daftar tugas harian kebun Anda.')

@section('dashboard-content')
    <div class="flex flex-col gap-6 pb-12 w-full">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div>
                <h1 class="text-[28px] sm:text-[36px] font-bold text-on-surface tracking-tight leading-tight">Tugas Perawatan</h1>
                <p class="text-[14px] sm:text-[15px] text-on-surface-variant mt-0.5">Kelola rutinitas perawatan untuk hasil panen optimal kebun Anda.</p>
            </div>
            <div class="bg-surface-container-low border border-outline-variant/30 text-on-surface-variant px-4 py-2 rounded-full flex items-center gap-2 font-bold text-[13px] shadow-xs shrink-0">
                <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                <span>Hari Ini, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM') }}</span>
            </div>
        </div>

        {{-- Top Summary Stats (Clean 2-card metrics row without duplicate weather card) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full items-stretch">
            {{-- Card 1: Tugas Selesai --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-outline-variant/20 flex flex-col justify-between shadow-xs">
                <div class="flex justify-between items-start mb-2.5">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">task_alt</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[24px] font-black text-on-surface leading-none">{{ $totalCompleted }}</span>
                        <span class="text-[15px] font-bold text-on-surface-variant">/{{ $totalTasks }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-[12px] font-bold text-on-surface mb-1.5">
                        <span>Tugas Selesai</span>
                        <span class="text-[12px] font-bold text-primary">{{ $totalTasks > 0 ? round(($totalCompleted / $totalTasks) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
                        <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $totalTasks > 0 ? ($totalCompleted / $totalTasks) * 100 : 0 }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Prioritas Tinggi --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-outline-variant/20 flex flex-col justify-between shadow-xs">
                <div class="flex justify-between items-start mb-2.5">
                    <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">priority_high</span>
                    </div>
                    <div class="text-[24px] font-black text-secondary leading-none">{{ $highPriorityCount }}</div>
                </div>
                <div>
                    <h3 class="text-[13px] font-bold text-on-surface mb-0.5">Prioritas Tinggi</h3>
                    <p class="text-[11px] text-on-surface-variant font-medium">
                        {{ $highPriorityCount > 0 ? 'Membutuhkan perhatian segera hari ini' : 'Semua tugas prioritas terkendali' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Layout: Left Task List (2 cols) & Right Insights/Mission (1 col) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full items-start">
            
            {{-- Left Column: Daftar Tugas --}}
            <div class="lg:col-span-2 flex flex-col gap-4 w-full">
                {{-- Header Filter Tabs --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-outline-variant/30 gap-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-[18px] sm:text-[20px] font-bold text-on-surface">Daftar Tugas</h2>
                        <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">{{ $pendingTasks->count() }} tertunda</span>
                    </div>
                    <div class="flex gap-1.5 overflow-x-auto no-scrollbar pb-1">
                        <a href="{{ route('care-tasks') }}" class="{{ !request('priority') ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} px-3.5 py-1.5 rounded-full text-[12px] font-bold transition-colors whitespace-nowrap">Semua</a>
                        <a href="{{ route('care-tasks', ['priority' => 'HIGH']) }}" class="{{ request('priority') == 'HIGH' ? 'bg-secondary text-on-secondary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} px-3.5 py-1.5 rounded-full text-[12px] font-bold transition-colors whitespace-nowrap">Tinggi</a>
                        <a href="{{ route('care-tasks', ['priority' => 'MEDIUM']) }}" class="{{ request('priority') == 'MEDIUM' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} px-3.5 py-1.5 rounded-full text-[12px] font-bold transition-colors whitespace-nowrap">Sedang</a>
                        <a href="{{ route('care-tasks', ['priority' => 'LOW']) }}" class="{{ request('priority') == 'LOW' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} px-3.5 py-1.5 rounded-full text-[12px] font-bold transition-colors whitespace-nowrap">Rendah</a>
                    </div>
                </div>

                {{-- Status Session Alerts --}}
                @if(session('success'))
                    <div class="bg-primary/10 text-primary border border-primary/20 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="bg-surface-container-high text-on-surface px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                {{-- Tasks Container --}}
                <div class="space-y-3 w-full">
                    @forelse($pendingTasks as $task)
                        @php
                            $bgClass = 'bg-primary/10';
                            $textClass = 'text-primary';
                            $icon = 'eco';
                            $code = strtolower($task->eventType->code ?? '');
                            
                            if(str_contains($code, 'water')) {
                                $icon = 'water_drop';
                                $bgClass = 'bg-sky-50';
                                $textClass = 'text-sky-700';
                            } elseif(str_contains($code, 'pest')) {
                                $bgClass = 'bg-amber-50';
                                $textClass = 'text-amber-800';
                                $icon = 'bug_report';
                            } elseif(str_contains($code, 'fertiliz')) {
                                $bgClass = 'bg-emerald-50';
                                $textClass = 'text-emerald-800';
                                $icon = 'psychiatry';
                            } elseif(str_contains($code, 'drain')) {
                                $bgClass = 'bg-purple-50';
                                $textClass = 'text-purple-800';
                                $icon = 'water_damage';
                            } elseif(str_contains($code, 'weather_protect') || str_contains($code, 'heat')) {
                                $bgClass = 'bg-rose-50';
                                $textClass = 'text-rose-800';
                                $icon = 'air';
                            } elseif(str_contains($code, 'fungus')) {
                                $bgClass = 'bg-teal-50';
                                $textClass = 'text-teal-800';
                                $icon = 'sanitizer';
                            } elseif(str_contains($code, 'weed')) {
                                $bgClass = 'bg-lime-50';
                                $textClass = 'text-lime-800';
                                $icon = 'grass';
                            } elseif(str_contains($code, 'stak')) {
                                $bgClass = 'bg-orange-50';
                                $textClass = 'text-orange-800';
                                $icon = 'yard';
                            } elseif(str_contains($code, 'prun')) {
                                $bgClass = 'bg-rose-50';
                                $textClass = 'text-rose-800';
                                $icon = 'content_cut';
                            }

                            // Extract task title
                            $taskDisplayTitle = $task->eventType->label ?? 'Tugas Perawatan';
                            if (!empty($task->message)) {
                                if (str_contains($task->message, '—')) {
                                    $mainPart = trim(explode('—', $task->message)[0]);
                                    if (str_contains($mainPart, ':')) {
                                        $taskDisplayTitle = trim(explode(':', $mainPart, 2)[1]);
                                    } else {
                                        $taskDisplayTitle = $mainPart;
                                    }
                                } elseif (str_contains($task->message, ':')) {
                                    $taskDisplayTitle = trim(explode(':', $task->message, 2)[1]);
                                } else {
                                    $taskDisplayTitle = $task->message;
                                }
                            }

                            // Clean duplicate weather tag check
                            $isWeatherAdapted = isset($task->weather_tag) || isset($task->weather_reason);
                            $showDistinctWeatherTag = false;
                            if (isset($task->weather_tag)) {
                                $normTag = strtolower(preg_replace('/[^a-z0-9]/', '', $task->weather_tag));
                                $normTitle = strtolower(preg_replace('/[^a-z0-9]/', '', $taskDisplayTitle));
                                $showDistinctWeatherTag = !str_contains($normTitle, $normTag) && !str_contains($normTag, $normTitle);
                            }
                        @endphp
                        
                        <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-outline-variant/20 hover:border-outline-variant/40 hover:shadow-xs transition-all duration-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 w-full">
                            <div class="flex items-start sm:items-center gap-3 min-w-0 w-full sm:w-auto flex-1">
                                <div class="w-10 h-10 rounded-xl {{ $bgClass }} {{ $textClass }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 mb-0.5 flex-wrap">
                                        <h3 class="text-[14px] sm:text-[15px] font-bold text-on-surface">{{ $taskDisplayTitle }}</h3>
                                        
                                        {{-- Priority Badge --}}
                                        @if($task->priority == 'HIGH' || $task->priority == 'CRITICAL')
                                            <span class="bg-secondary/10 text-secondary text-[10px] font-bold px-2 py-0.5 rounded-full border border-secondary/20">Tinggi</span>
                                        @elseif($task->priority == 'MEDIUM')
                                            <span class="bg-surface-container-high text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-full">Sedang</span>
                                        @else
                                            <span class="bg-surface-container text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-full">Rendah</span>
                                        @endif

                                        {{-- Weather Adaptation Badge --}}
                                        @if($isWeatherAdapted)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                                                <span class="material-symbols-outlined text-[11px]">auto_awesome</span>
                                                {{ $showDistinctWeatherTag ? $task->weather_tag : 'Adaptasi Cuaca' }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Subline: Plant & Details --}}
                                    <div class="flex items-center gap-1.5 text-[12px] text-on-surface-variant flex-wrap">
                                        <span class="font-bold text-on-surface">{{ $task->plant->plantTemplate->name_id }}</span>
                                        <span class="text-outline-variant">•</span>
                                        <span class="text-on-surface-variant">{{ $task->plant->garden->name ?? 'Kebun' }}</span>
                                        
                                        @if(isset($task->weather_reason))
                                            <span class="text-outline-variant hidden md:inline">•</span>
                                            <span class="text-on-surface-variant/80 text-[11px] truncate max-w-xs hidden md:inline">{{ $task->weather_reason }}</span>
                                        @endif

                                        <a href="{{ route('growth-calendar', ['plant_id' => $task->plant->id]) }}" class="text-primary hover:underline flex items-center gap-0.5 ml-0.5" title="Lihat di Kalender">
                                            <span class="material-symbols-outlined text-[15px]">calendar_month</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions & Status on Right --}}
                            <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-outline-variant/15 shrink-0">
                                <div class="text-left sm:text-right">
                                    <div class="text-[11px] font-bold text-secondary">Tertunda</div>
                                    <div class="text-[10px] text-on-surface-variant">{{ $task->scheduled_date ? \Carbon\Carbon::parse($task->scheduled_date)->isoFormat('D MMM') : 'Hari Ini' }}</div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <form action="{{ route('care-tasks.complete', $task->id) }}" method="POST" class="inline" onsubmit="return preventDoubleSubmit(this)">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all duration-200 flex items-center justify-center cursor-pointer shadow-2xs active:scale-95" title="Tandai Selesai">
                                            <span class="material-symbols-outlined text-[17px]">check</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('care-tasks.skip', $task->id) }}" method="POST" class="inline" onsubmit="return preventDoubleSubmit(this)">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-error-container hover:text-on-error-container transition-all duration-200 flex items-center justify-center cursor-pointer active:scale-95" title="Lewati">
                                            <span class="material-symbols-outlined text-[17px]">close</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full bg-surface rounded-2xl p-8 text-center text-on-surface-variant border border-outline-variant/20 flex flex-col items-center justify-center gap-2">
                            <div class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-1">
                                <span class="material-symbols-outlined text-[28px]">done_all</span>
                            </div>
                            <div class="w-full self-stretch max-w-md mx-auto text-center" style="white-space: normal; word-break: normal;">
                                <h3 class="font-bold text-on-surface text-[16px]">Semua Tugas Beres!</h3>
                                <p class="text-[13px] text-on-surface-variant mt-0.5">Tidak ada tugas perawatan tertunda untuk filter ini. Tanaman Anda terawat dengan baik.</p>
                            </div>
                        </div>
                    @endforelse

                    {{-- Completed & Skipped Tasks Section --}}
                    @if($completedTasks->count() > 0 || $skippedTasks->count() > 0)
                        <div class="pt-4 border-t border-outline-variant/20 space-y-2.5">
                            <h3 class="text-[13px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Riwayat Tugas Hari Ini</h3>

                            @foreach($completedTasks as $task)
                                <div class="bg-surface/80 rounded-xl p-3.5 flex items-center justify-between border border-outline-variant/15 opacity-80 hover:opacity-100 transition-opacity">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[14px] font-semibold text-on-surface line-through decoration-outline-variant block truncate">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</span>
                                            <span class="text-[11px] text-on-surface-variant">{{ $task->plant->plantTemplate->name_id }} • {{ $task->plant->garden->name ?? 'Kebun' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-bold text-primary bg-primary/10 px-2.5 py-0.5 rounded-full shrink-0">Selesai {{ $task->completed_at ? $task->completed_at->format('H:i') : '' }}</span>
                                </div>
                            @endforeach

                            @foreach($skippedTasks as $task)
                                <div class="bg-surface/60 rounded-xl p-3.5 flex items-center justify-between border border-outline-variant/15 opacity-60">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-highest text-on-surface-variant flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[18px]">block</span>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[14px] font-semibold text-on-surface-variant block truncate">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</span>
                                            <span class="text-[11px] text-outline">{{ $task->plant->plantTemplate->name_id }} • {{ $task->plant->garden->name ?? 'Kebun' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-on-surface-variant bg-surface-container px-2.5 py-0.5 rounded-full shrink-0">Dilewati</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Assistant Sidebar (Smart Irrigation & Weekly Mission) --}}
            <div class="lg:col-span-1 flex flex-col gap-5 w-full">
                
                {{-- Smart Irrigation Assistant Widget --}}
                @if(isset($agronomic))
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-outline-variant/20 shadow-xs flex flex-col gap-3.5 w-full">
                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-outline-variant/15">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[19px]">{{ $agronomic['icon'] ?? 'thermostat' }}</span>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-extrabold text-primary uppercase tracking-wider block">Smart Irrigation</span>
                                <h3 class="text-[13px] font-bold text-on-surface leading-tight">Evaluasi Cuaca</h3>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold bg-primary/10 text-primary px-2.5 py-0.5 rounded-full shrink-0 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                            Live Sync
                        </span>
                    </div>

                    {{-- Decision Pill --}}
                    <div class="bg-primary/5 rounded-xl p-3 border border-primary/15">
                        <div class="text-[10px] font-extrabold text-primary uppercase tracking-wider mb-0.5">Keputusan Hari Ini</div>
                        <div class="text-[14px] font-black text-on-surface leading-snug">{{ $agronomic['watering']['title'] ?? 'Penyiraman Normal' }}</div>
                        <div class="text-[11px] font-semibold text-primary mt-0.5">
                            {{ $agronomic['watering']['badge'] ?? 'Normal' }} • {{ (int) round($agronomic['temperature'] ?? 29) }}°C
                        </div>
                    </div>

                    {{-- Advice & Schedule Text --}}
                    <div class="space-y-2 text-[12px] text-on-surface-variant">
                        <div>
                            <span class="font-bold text-on-surface block mb-0.5">Rekomendasi Cuaca:</span>
                            <p class="leading-relaxed">{{ $agronomic['watering']['advice'] ?? $agronomic['summary'] }}</p>
                        </div>

                        <div class="pt-2 border-t border-outline-variant/15 flex items-center gap-1.5 text-[11px]">
                            <span class="material-symbols-outlined text-[15px] text-primary shrink-0">schedule</span>
                            <span class="font-medium">Jadwal Pantau: Pagi (06–09) • Sore (16–18)</span>
                        </div>
                    </div>

                    {{-- Agronomic Warning --}}
                    @if(!empty($agronomic['watering']['warning']))
                    <div class="bg-secondary/5 border border-secondary/20 rounded-xl p-2.5 flex items-start gap-2 text-[11px]">
                        <span class="material-symbols-outlined text-[16px] text-secondary shrink-0 mt-0.5">warning</span>
                        <div class="min-w-0">
                            <span class="font-bold text-secondary block mb-0.5">Peringatan Agronomi</span>
                            <p class="text-on-surface-variant leading-relaxed">{{ $agronomic['watering']['warning'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── Badge Unlock from session ──────────────────────────────
    @if(session('new_badge'))
        setTimeout(() => {
            Alert.modal.badge({!! json_encode(session('new_badge')) !!});
        }, 600);
    @endif

    // ── Daily Quest Completion Celebration ────────────────────
    const totalTasks   = {{ $totalTasks }};
    const totalCompleted = {{ $totalCompleted }};
    const pendingCount = {{ $pendingTasks->count() }};

    if (totalTasks > 1 && pendingCount === 0 && totalCompleted > 0) {
        const questKey = 'quest_celebrated_' + new Date().toISOString().slice(0, 10);
        if (!sessionStorage.getItem(questKey)) {
            sessionStorage.setItem(questKey, '1');
            setTimeout(() => {
                Alert.quest.complete(totalCompleted);
            }, 800);
        }
    }
});
</script>
<script>
    function preventDoubleSubmit(form) {
        if (form.dataset.submitted) return false;
        form.dataset.submitted = 'true';
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.style.opacity = '0.5';
            btn.style.pointerEvents = 'none';
        }
        return true;
    }
</script>
@endpush
