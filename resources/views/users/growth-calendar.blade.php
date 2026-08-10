@extends('layouts.dashboard')

@section('title', 'Kalender Tanam — Grow a Garden')
@section('description', 'Pantau dan kelola tahap pertumbuhan tanaman Anda.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
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
                            {{-- Placeholder image based on category or plant --}}
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
                                <span class="bg-[#944a23]/10 text-[#944a23] px-3 py-1.5 rounded-full text-[12px] font-bold tracking-wider uppercase border border-[#944a23]/20 shadow-sm backdrop-blur-md">HST {{ max(0, $currentHst) }} / {{ $mainPlant->plantTemplate->harvest_start_day }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="document.getElementById('edit-jadwal-modal').classList.remove('hidden')" class="w-full md:w-auto bg-white border border-outline-variant/30 text-on-surface-variant font-bold px-6 py-3.5 rounded-full hover:bg-surface hover:text-primary hover:border-primary/30 hover:shadow-[0_4px_12px_rgba(0,108,73,0.05)] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm relative z-10">
                        <span class="material-symbols-outlined text-[20px]">calendar_month</span> Edit Jadwal
                    </button>
                </div>
                
                {{-- Stepper Timeline --}}
                <div class="relative pl-8 md:pl-12 space-y-[40px]">
                    {{-- Vertical dashed line --}}
                    <div class="absolute left-[15px] md:left-[23px] top-4 bottom-8 w-[2px] border-l-2 border-dashed border-outline-variant/50"></div>

                    @foreach($timeline as $index => $stage)
                        @if($stage['status'] === 'completed')
                            <div class="relative group cursor-pointer transition-transform duration-300 hover:translate-x-1">
                                <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-[0_4px_12px_rgba(0,108,73,0.3)] z-10 group-hover:scale-110 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-[16px] text-white font-bold">check</span>
                                </div>
                                <div class="ml-4 md:ml-6">
                                    <h3 class="text-[16px] font-bold text-primary mb-0.5">{{ $stage['label'] }} <span class="text-[12px] opacity-70 font-semibold">(Selesai)</span></h3>
                                    <p class="text-[13px] text-on-surface-variant font-medium">{{ $stage['date']->isoFormat('D MMM YYYY') }} • {{ $stage['desc'] }}</p>
                                </div>
                            </div>
                        @elseif($stage['status'] === 'active')
                            <div class="relative group">
                                {{-- Pulsing Ring --}}
                                <div class="absolute -left-[53px] md:-left-[61px] top-3 w-12 h-12 rounded-full bg-primary/20 animate-ping"></div>
                                <div class="absolute -left-[49px] md:-left-[57px] top-4 w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-[0_8px_24px_rgba(0,108,73,0.4)] ring-4 ring-white z-10">
                                    <span class="material-symbols-outlined text-[20px] text-white">eco</span>
                                </div>
                                <div class="ml-4 md:ml-6 bg-gradient-to-br from-white to-[#006c49]/5 border border-primary/20 rounded-[20px] p-[24px] shadow-[0_8px_32px_rgba(0,108,73,0.08)] relative overflow-hidden transition-all duration-300 hover:shadow-[0_12px_48px_rgba(0,108,73,0.12)] hover:-translate-y-1">
                                    <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
                                    <h3 class="text-[18px] font-black text-primary mb-1">{{ $stage['label'] }} <span class="text-[13px] bg-primary text-white px-2 py-0.5 rounded-full ml-2 font-bold tracking-wide">AKTIF</span></h3>
                                    <p class="text-[14px] text-on-surface-variant font-medium mb-6 leading-relaxed">{{ $stage['desc'] }}</p>
                                    
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1 bg-outline-variant/30 h-[10px] rounded-full overflow-hidden shadow-inner">
                                            <div class="bg-gradient-to-r from-[#006c49] to-[#10b981] h-full rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $stage['progress'] }}%;">
                                                <div class="absolute inset-0 bg-white/20 w-full h-full" style="background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent); background-size: 1rem 1rem;"></div>
                                            </div>
                                        </div>
                                        <span class="text-[12px] text-primary font-black whitespace-nowrap bg-primary/10 px-3 py-1 rounded-full">
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
                            <div class="relative opacity-60 hover:opacity-100 transition-all duration-300 cursor-pointer group hover:translate-x-1">
                                <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-surface border-2 border-dashed border-outline-variant flex items-center justify-center z-10 group-hover:border-primary group-hover:border-solid group-hover:bg-primary/10 group-hover:shadow-[0_0_16px_rgba(0,108,73,0.2)] transition-all duration-300">
                                    <span class="material-symbols-outlined text-[16px] text-outline-variant group-hover:text-primary transition-colors">
                                        @if($stage['key'] === 'FLOWERING') local_florist 
                                        @elseif($stage['key'] === 'FRUITING') nutrition 
                                        @elseif($stage['key'] === 'HARVEST') shopping_basket 
                                        @else schedule @endif
                                    </span>
                                </div>
                                <div class="ml-4 md:ml-6">
                                    <h3 class="text-[16px] font-bold text-on-surface-variant mb-0.5 group-hover:text-primary transition-colors">{{ $stage['label'] }}</h3>
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
                    {{-- Decorative highlight --}}
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors duration-700"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#6ffbbe]/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/10 backdrop-blur-md mb-6 border border-white/20 shadow-sm">
                                <span class="material-symbols-outlined text-[24px] text-[#6ffbbe]">notifications_active</span>
                            </div>
                            <h3 class="text-[28px] font-black leading-tight mb-4 tracking-tight">Mendekati<br>Waktu Panen!</h3>
                            <div class="flex items-start gap-3 mb-8 bg-black/20 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                <span class="material-symbols-outlined text-[20px] text-[#6ffbbe] mt-0.5">schedule</span>
                                <p class="text-[14px] font-medium leading-relaxed text-white/90">Tanaman <strong class="text-white">{{ $mainPlant->plantTemplate->name_id }}</strong> butuh perhatian khusus untuk persiapan panen.</p>
                            </div>
                        </div>
                        <button class="w-full py-4 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold text-[15px] transition-all flex items-center justify-center gap-2 group/btn">
                            Lihat Detail <span class="material-symbols-outlined text-[18px] group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
                @endif

                {{-- Tugas Hari Ini Card --}}
                <div class="bg-white rounded-[24px] p-[28px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-outline-variant/30">
                    <h3 class="text-[18px] font-black text-slate-800 mb-5 flex items-center justify-between">
                        Tugas Hari Ini
                        <a href="{{ route('care-tasks') }}" class="text-[13px] text-primary font-bold hover:underline">Lihat Semua</a>
                    </h3>
                    
                    @if(isset($isLocked) && $isLocked)
                        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-[20px] p-6 text-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[24px] text-yellow-600">lock</span>
                            </div>
                            <h4 class="text-[14px] font-bold text-on-surface mb-2">Tugas Perawatan Terkunci</h4>
                            <p class="text-[12px] text-on-surface-variant mb-4">Fitur ini khusus pengguna Pro/Premium.</p>
                            <button type="button" onclick="document.getElementById('pricing-modal').classList.remove('hidden')" class="bg-primary text-white text-[12px] font-bold px-4 py-2 rounded-full shadow-sm hover:bg-primary/90 transition-all">Upgrade Sekarang</button>
                        </div>
                    @else
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
                                            <h4 class="text-[14px] font-bold text-on-surface">{{ $task->eventType->label ?? $task->message ?? 'Tugas Perawatan' }}</h4>
                                            <p class="text-[11px] text-on-surface-variant">{{ $task->priority ?? 'NORMAL' }} Priority</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($todayTasks->count() > 3)
                                <p class="text-center text-[12px] text-on-surface-variant font-medium mt-3">+{{ $todayTasks->count() - 3 }} tugas lainnya</p>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-6 text-center">
                                <span class="material-symbols-outlined text-[32px] text-outline-variant mb-2">done_all</span>
                                <p class="text-[13px] text-on-surface-variant font-medium">Tidak ada tugas tertunda untuk hari ini.</p>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Card 2: Tanaman Lainnya --}}
                <div class="bg-white rounded-[24px] p-[28px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-outline-variant/30">
                    <h3 class="text-[18px] font-black text-slate-800 mb-5 flex items-center justify-between">
                        Tanaman Lainnya
                        <a href="{{ route('gardens') }}" class="text-[13px] text-primary font-bold hover:underline">Lihat Kebun</a>
                    </h3>
                    <div class="space-y-3">
                        @forelse($otherPlants->take(4) as $plant)
                        <a href="{{ route('growth-calendar', ['plant_id' => $plant->id]) }}" class="block">
                            <div class="flex items-center justify-between p-3 rounded-[20px] border border-outline-variant/30 hover:border-primary/30 hover:shadow-[0_8px_24px_rgba(0,108,73,0.08)] cursor-pointer transition-all duration-300 group bg-white">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-[14px] overflow-hidden shrink-0 border border-outline-variant/20 bg-surface flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">psychiatry</span>
                                    </div>
                                    <div>
                                        <h4 class="text-[15px] font-bold text-slate-800 group-hover:text-primary transition-colors mb-1">{{ $plant->plantTemplate->name_id }}</h4>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ $plant->stage }}
                                        </span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-outline-variant group-hover:text-primary group-hover:translate-x-1 transition-all mr-2">chevron_right</span>
                            </div>
                        </a>
                        @empty
                        <p class="text-on-surface-variant text-[14px]">Tidak ada tanaman lain.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Card 3: Tips Hari Ini --}}
                <div class="bg-[#f1f5f2] border border-primary/20 rounded-[24px] p-[32px] shadow-sm relative overflow-hidden group hover:border-primary/40 hover:shadow-md transition-all duration-300">
                    <span class="material-symbols-outlined absolute -top-4 -right-4 text-[120px] text-primary/5 -rotate-12 group-hover:rotate-0 transition-transform duration-700 pointer-events-none" style="font-variation-settings: 'FILL' 1;">format_quote</span>
                    
                    <div class="flex items-center gap-2 mb-6 relative z-10">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px] text-primary">lightbulb</span>
                        </div>
                        <h3 class="text-[13px] text-primary font-bold uppercase tracking-widest">Tips Hari Ini</h3>
                    </div>

                    @php
                        $tips = [
                            "Saat fase vegetatif, pangkas tunas 'sucker' (tunas air) yang tumbuh di antara batang utama dan dahan agar nutrisi tanaman terfokus penuh pada pertumbuhan batang dan buah.",
                            "Siram tanaman di pagi hari sebelum matahari terlalu terik agar air tidak cepat menguap dan akar dapat menyerap kelembapan dengan optimal.",
                            "Gunakan kompos atau pupuk organik secara berkala untuk menjaga struktur tanah tetap gembur dan mikroorganisme tanah tetap aktif.",
                            "Pastikan pot atau planter bag memiliki lubang drainase yang cukup agar air tidak menggenang dan menyebabkan pembusukan akar.",
                            "Lakukan rotasi tanaman secara berkala untuk mencegah penumpukan hama dan penyakit yang spesifik pada satu jenis keluarga tanaman.",
                            "Mulsa organik seperti jerami atau potongan rumput dapat membantu menjaga kelembapan tanah dan menekan pertumbuhan gulma.",
                            "Periksa bagian bawah daun secara rutin karena biasanya telur hama dan serangga kecil bersembunyi di tempat terlindung tersebut.",
                            "Tanaman yang mendapatkan paparan sinar matahari langsung minimal 6 jam sehari akan memproduksi buah yang jauh lebih manis dan lebat."
                        ];
                        $todayTip = $tips[\Carbon\Carbon::now()->dayOfYear % count($tips)];
                    @endphp

                    <p class="text-[15px] leading-[26px] font-medium text-slate-700 italic relative z-10">
                        "{{ $todayTip }}"
                    </p>
                </div>

            </div>
        </div>
    </div>

    @if($mainPlant)
    {{-- Edit Jadwal Modal --}}
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
@endsection
