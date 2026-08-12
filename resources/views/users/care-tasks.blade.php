@extends('layouts.dashboard')

@section('title', 'Tugas Perawatan — Grow a Garden')
@section('description', 'Kelola daftar tugas harian kebun Anda.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
            <div>
                <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight mb-1">Tugas Perawatan</h1>
                <p class="text-[16px] text-on-surface-variant">Tetap konsisten untuk hasil panen yang maksimal.</p>
            </div>
            <div class="bg-surface-container-high text-on-surface-variant px-5 py-2.5 rounded-full flex items-center gap-2 font-bold text-[14px] shadow-sm">
                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                Hari Ini, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM') }}
            </div>
        </div>

        {{-- Top Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px] mb-4">
            {{-- Tugas Selesai --}}
            <div class="bg-[#dcfce7] rounded-[24px] p-[24px] flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="w-12 h-12 bg-[#16a34a] rounded-[16px] flex items-center justify-center text-white shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">task_alt</span>
                    </div>
                    <div class="text-[28px] font-bold text-[#166534]">{{ $totalCompleted }}/{{ $totalTasks }}</div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[16px] font-bold text-[#166534] mb-3">Tugas Selesai</h3>
                    <div class="w-full bg-[#bbf7d0] h-[6px] rounded-full overflow-hidden">
                        <div class="bg-[#166534] h-full rounded-full" style="width: {{ $totalTasks > 0 ? ($totalCompleted/$totalTasks)*100 : 0 }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Prioritas Tinggi --}}
            <div class="bg-[#ffedd5] rounded-[24px] p-[24px] flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="w-12 h-12 bg-[#f97316] rounded-[16px] flex items-center justify-center text-white shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">priority_high</span>
                    </div>
                    <div class="text-[28px] font-bold text-[#9a3412]">{{ $highPriorityCount }}</div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[16px] font-bold text-[#9a3412] mb-1">Prioritas Tinggi</h3>
                    <p class="text-[12px] text-[#c2410c] font-medium">Membutuhkan perhatian segera</p>
                </div>
            </div>

            {{-- Saran Hari Ini / Adaptasi Cuaca --}}
            @if(isset($weatherAdvice) && $weatherAdvice)
            <div class="bg-surface-container-low rounded-[24px] p-[24px] flex justify-between relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 border border-outline-variant/30">
                <div class="relative z-10 max-w-[210px]">
                    <div class="text-[10px] font-bold text-primary uppercase tracking-wider mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">auto_awesome</span> Adaptasi Cuaca Real-Time
                    </div>
                    <h3 class="text-[16px] font-bold text-on-surface leading-tight mb-1.5">{{ $weatherAdvice['title'] }}</h3>
                    <p class="text-[12px] text-on-surface-variant font-medium leading-relaxed">{{ $weatherAdvice['desc'] }}</p>
                </div>
                <div class="relative z-10 mt-auto shrink-0">
                    <div class="w-12 h-12 bg-primary-container rounded-full flex items-center justify-center text-on-primary-container shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">{{ $weatherAdvice['icon'] }}</span>
                    </div>
                </div>
            </div>
            @else
            @php
                $dailyAdviceList = [
                    ['title' => 'Periksa Kebun', 'desc' => 'Luangkan waktu 10 menit untuk observasi daun & tanah.', 'icon' => 'eco'],
                    ['title' => 'Cek Kelembapan', 'desc' => 'Pastikan media tanam tidak terlalu kering atau menggenang.', 'icon' => 'water_drop'],
                    ['title' => 'Pangkas Daun Tua', 'desc' => 'Bersihkan daun kuning untuk menghemat nutrisi tanaman.', 'icon' => 'content_cut'],
                    ['title' => 'Cek Hama Daun', 'desc' => 'Periksa balik daun untuk mencegah serangga berkembang biak.', 'icon' => 'search'],
                    ['title' => 'Beri Sinar Matahari', 'desc' => 'Geser pot ke area cerah untuk fotosintesis maksimal.', 'icon' => 'light_mode'],
                ];
                $todayAdvice = $dailyAdviceList[\Carbon\Carbon::now()->dayOfYear % count($dailyAdviceList)];
            @endphp
            <div class="bg-surface-container-low rounded-[24px] p-[24px] flex justify-between relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 border border-outline-variant/30">
                <div class="relative z-10 max-w-[160px]">
                    <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Saran Hari Ini</div>
                    <h3 class="text-[18px] font-bold text-on-surface leading-tight mb-2">{{ $todayAdvice['title'] }}</h3>
                    <p class="text-[12px] text-on-surface-variant font-medium">{{ $todayAdvice['desc'] }}</p>
                </div>
                <div class="relative z-10 mt-auto">
                    <div class="w-14 h-14 bg-[#d1fae5] rounded-full flex items-center justify-center text-[#059669] shadow-sm">
                        <span class="material-symbols-outlined text-[28px]">{{ $todayAdvice['icon'] }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-[32px]">
            
            {{-- Left Column: Daftar Tugas (Takes up 2 columns) --}}
            <div class="lg:col-span-2 flex flex-col gap-[16px]">
                {{-- Header Filter --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-outline-variant/30 gap-3">
                    <h2 class="text-[20px] font-bold text-on-surface">Daftar Tugas Harian</h2>
                    <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                        <a href="{{ route('care-tasks') }}" class="{{ !request('priority') ? 'bg-[#047857] text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }} px-4 py-1.5 rounded-full text-[13px] font-bold shadow-sm transition-colors whitespace-nowrap">Semua</a>
                        <a href="{{ route('care-tasks', ['priority' => 'HIGH']) }}" class="{{ request('priority') == 'HIGH' ? 'bg-[#f97316] text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }} px-4 py-1.5 rounded-full text-[13px] font-bold shadow-sm transition-colors whitespace-nowrap">Tinggi</a>
                        <a href="{{ route('care-tasks', ['priority' => 'MEDIUM']) }}" class="{{ request('priority') == 'MEDIUM' ? 'bg-[#047857] text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }} px-4 py-1.5 rounded-full text-[13px] font-bold shadow-sm transition-colors whitespace-nowrap">Sedang</a>
                        <a href="{{ route('care-tasks', ['priority' => 'LOW']) }}" class="{{ request('priority') == 'LOW' ? 'bg-[#047857] text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }} px-4 py-1.5 rounded-full text-[13px] font-bold shadow-sm transition-colors whitespace-nowrap">Rendah</a>
                    </div>
                </div>

                <div class="space-y-[16px] pt-2 relative">
                    
                    @if(isset($isLocked) && $isLocked)
                        {{-- Locked State (Paywall) --}}
                        <div class="bg-surface rounded-[24px] p-8 md:p-12 text-center ambient-shadow-lg border border-primary/20 relative overflow-hidden flex flex-col items-center justify-center min-h-[400px]">
                            {{-- Decorative Background --}}
                            <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>
                            
                            <div class="relative z-10 flex flex-col items-center w-full max-w-2xl mx-auto">
                                <div class="w-20 h-20 bg-secondary rounded-full flex items-center justify-center shadow-lg mb-6 shadow-secondary/30 ring-8 ring-secondary/10 shrink-0">
                                    <span class="material-symbols-outlined text-[40px] text-on-secondary">lock</span>
                                </div>
                                <h3 class="text-[24px] font-black text-on-surface mb-3 text-center">Tugas Perawatan Terkunci</h3>
                                <p class="text-[15px] text-on-surface-variant leading-relaxed mb-8 text-center max-w-lg">Tingkatkan ke paket <span class="font-bold text-primary">Panen Raya</span> atau <span class="font-bold text-secondary">Subur (Pro)</span> untuk membuka asisten perawatan pintar, daftar tugas harian, dan notifikasi kebun real-time.</p>
                                
                                <button type="button" onclick="document.getElementById('pricing-modal').classList.remove('hidden')" class="bg-primary text-white font-bold text-[15px] px-8 py-3.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-md flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                                    Upgrade Sekarang
                                </button>
                            </div>
                        </div>
                    @else
                    
                    @if(session('success'))
                        <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-xl text-sm font-bold border border-[#bbf7d0]">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="bg-surface-container-high text-on-surface px-4 py-3 rounded-xl text-sm font-bold">
                            {{ session('info') }}
                        </div>
                    @endif

                    @forelse($pendingTasks as $task)
                    <div class="bg-surface rounded-[24px] p-[20px] flex flex-col sm:flex-row items-start sm:items-center justify-between ambient-shadow hover:ambient-shadow-lg hover:-translate-y-0.5 transition-all duration-300 gap-4 sm:gap-0">
                        <div class="flex items-center gap-4">
                            @php
                                $bgClass = 'bg-[#ecfdf5]';
                                $textClass = 'text-[#059669]';
                                $icon = 'eco';
                                
                                if($task->eventType && str_contains(strtolower($task->eventType->code), 'water')) {
                                    $icon = 'water_drop';
                                } elseif($task->eventType && str_contains(strtolower($task->eventType->code), 'pest')) {
                                    $bgClass = 'bg-[#fff7ed]';
                                    $textClass = 'text-[#ea580c]';
                                    $icon = 'bug_report';
                                }
                            @endphp
                            <div class="w-14 h-14 rounded-[16px] {{ $bgClass }} {{ $textClass }} flex items-center justify-center shadow-sm shrink-0">
                                <span class="material-symbols-outlined text-[28px]">{{ $icon }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                    <h3 class="text-[18px] font-bold text-on-surface">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h3>
                                    
                                    @if(isset($task->weather_tag))
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-[4px] {{ $task->weather_badge_bg }}">{{ $task->weather_tag }}</span>
                                    @endif

                                    @if($task->priority == 'HIGH' || $task->priority == 'CRITICAL')
                                    <span class="bg-[var(--color-status-late-bg)] text-[var(--color-status-late-text)] text-[10px] font-bold px-2 py-0.5 rounded-[4px]">{{ $task->priority }}</span>
                                    @elseif($task->priority == 'MEDIUM')
                                    <span class="bg-surface-container-high text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-[4px]">{{ $task->priority }}</span>
                                    @else
                                    <span class="bg-surface-container text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-[4px]">{{ $task->priority ?? 'LOW' }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-[13px] text-on-surface-variant font-medium">{{ $task->plant->garden->name ?? 'Kebun' }}: {{ $task->plant->plantTemplate->name_id }}</p>
                                    @if(isset($task->weather_reason))
                                    <span class="text-[11px] font-semibold text-primary/80 italic">— {{ $task->weather_reason }}</span>
                                    @endif
                                    <a href="{{ route('growth-calendar', ['plant_id' => $task->plant->id]) }}" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-0.5" title="Lihat di Kalender"><span class="material-symbols-outlined text-[14px]">calendar_month</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 w-full sm:w-auto justify-end">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-[#dc2626]">Pending</div>
                                <div class="text-[11px] text-on-surface-variant">{{ $task->scheduled_date->isoFormat('D MMM') }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('care-tasks.complete', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-[#059669] hover:bg-[#d1fae5] transition-colors" title="Tandai Selesai"><span class="material-symbols-outlined">check</span></button>
                                </form>
                                <form action="{{ route('care-tasks.skip', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-surface-container-highest transition-colors" title="Lewati / Skip"><span class="material-symbols-outlined">fast_forward</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-surface rounded-[24px] p-8 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">done_all</span>
                        <p class="font-bold">Yeay! Semua tugas hari ini sudah selesai.</p>
                    </div>
                    @endforelse

                    {{-- Menampilkan Tugas Selesai --}}
                    @foreach($completedTasks as $task)
                    <div class="bg-surface rounded-[24px] p-[20px] flex items-center justify-between ambient-shadow opacity-80">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[16px] bg-surface-container-highest text-on-surface-variant flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">done</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="text-[18px] font-bold text-on-surface line-through decoration-outline-variant">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h3>
                                </div>
                                <p class="text-[13px] text-outline font-medium">{{ $task->plant->plantTemplate->name_id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-[#059669]">Done</div>
                                <div class="text-[11px] text-outline">{{ $task->completed_at ? $task->completed_at->format('H:i') : '' }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-[#059669] flex items-center justify-center text-white shadow-sm"><span class="material-symbols-outlined">check_circle</span></div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @foreach($skippedTasks as $task)
                    <div class="bg-surface rounded-[24px] p-[20px] flex items-center justify-between ambient-shadow opacity-60">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[16px] bg-surface-container-highest text-on-surface-variant flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">visibility_off</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="text-[18px] font-bold text-on-surface">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h3>
                                </div>
                                <p class="text-[13px] text-outline font-medium">{{ $task->plant->plantTemplate->name_id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-on-surface-variant">Skip</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-outline"><span class="material-symbols-outlined">block</span></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @endif
                </div>
            </div>

            {{-- Right Column: Sidebar (Takes up 1 column) --}}
            <div class="lg:col-span-1 flex flex-col gap-[24px]">
                
                {{-- Kebun Terpopuler Card --}}
                <div class="bg-surface-container-low rounded-3xl p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:border-primary/30 transition-all group shrink-0">
                    <h3 class="text-[18px] font-bold text-on-surface mb-4">Kebun Utama Anda</h3>
                    
                    @php $firstGarden = Auth::user() ? Auth::user()->gardens()->first() : null; @endphp
                    @if($firstGarden)
                    <div class="relative h-[140px] rounded-[16px] overflow-hidden mb-4 shadow-sm group cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1615811361523-6bd03d7748e7?w=400&h=200&fit=crop&q=80" alt="Garden" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white font-bold text-[16px]">{{ $firstGarden->name }}</div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3.5 bg-surface-container-lowest rounded-[16px] border border-outline-variant/30">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#059669] text-[20px]">water_drop</span>
                                <span class="text-[14px] font-bold text-on-surface-variant">Kelembapan</span>
                            </div>
                            <span class="text-[16px] font-bold text-[#059669]">82%</span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 bg-surface-container-lowest rounded-[16px] border border-outline-variant/30">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-status-attention text-[20px]">light_mode</span>
                                <span class="text-[14px] font-bold text-on-surface-variant">Paparan Sinar</span>
                            </div>
                            <span class="text-[16px] font-bold text-[#ea580c]">6 jam</span>
                        </div>
                    </div>
                    @else
                    <p class="text-[14px] text-on-surface-variant">Belum ada kebun. Tambahkan kebun untuk memantau metrik kebun.</p>
                    @endif
                </div>


                {{-- Misi Mingguan Card --}}
                @if(isset($closestBadge) && $closestBadge)
                    <div class="bg-[#67b193] rounded-[24px] p-[24px] relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 ambient-shadow-lg text-[#003823] shrink-0">
                        <div class="mb-4 relative z-10">
                            <span class="material-symbols-outlined text-[28px] mb-2">{{ $closestBadge->icon_url ?? 'military_tech' }}</span>
                            <h3 class="text-[18px] font-bold mb-1">Misi Mingguan</h3>
                            <p class="text-[14px] font-medium leading-relaxed opacity-90">Selesaikan {{ $closestTarget - $closestCurrent }} tugas lagi untuk mendapatkan badge '{{ $closestBadge->name }}'.</p>
                        </div>
                        
                        <div class="flex justify-between items-end relative z-10">
                            <a href="{{ route('badges') }}" class="inline-block bg-[#003823] text-white px-5 py-2.5 rounded-full text-[13px] font-bold hover:bg-[#025c3c] active:scale-95 transition-colors shadow-sm">Lihat Badge</a>
                            <div class="w-12 h-12 bg-[#003823] rounded-[16px] flex items-center justify-center text-white shadow-md">
                                <span class="material-symbols-outlined text-[24px]">{{ $closestBadge->icon_url ?? 'military_tech' }}</span>
                            </div>
                        </div>

                        {{-- Decorative blur --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:bg-white/30 transition-colors duration-500"></div>
                    </div>
                @else
                    <div class="bg-[#67b193] rounded-[24px] p-[24px] relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 ambient-shadow-lg text-[#003823] shrink-0">
                        <div class="mb-4 relative z-10">
                            <span class="material-symbols-outlined text-[28px] mb-2">military_tech</span>
                            <h3 class="text-[18px] font-bold mb-1">Misi Mingguan</h3>
                            <p class="text-[14px] font-medium leading-relaxed opacity-90">Selesaikan 5 tugas lagi untuk mendapatkan badge 'Tangan Dingin'.</p>
                        </div>
                        
                        <div class="flex justify-between items-end relative z-10">
                            <a href="{{ route('badges') }}" class="inline-block bg-[#003823] text-white px-5 py-2.5 rounded-full text-[13px] font-bold hover:bg-[#025c3c] active:scale-95 transition-colors shadow-sm">Lihat Badge</a>
                            <div class="w-12 h-12 bg-[#003823] rounded-[16px] flex items-center justify-center text-white shadow-md">
                                <span class="material-symbols-outlined text-[24px]">military_tech</span>
                            </div>
                        </div>

                        {{-- Decorative blur --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:bg-white/30 transition-colors duration-500"></div>
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
    // Detects: all tasks for today are done (no pending tasks left)
    const totalTasks   = {{ $totalTasks }};
    const totalCompleted = {{ $totalCompleted }};
    const pendingCount = {{ $pendingTasks->count() }};

    // Only celebrate when: there are real tasks today AND all pending = 0 AND NOT already shown this session
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
@endpush
