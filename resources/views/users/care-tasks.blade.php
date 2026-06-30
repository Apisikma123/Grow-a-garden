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
                Hari Ini, 24 Mei
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
                    <div class="text-[28px] font-bold text-[#166534]">12/18</div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[16px] font-bold text-[#166534] mb-3">Tugas Selesai</h3>
                    <div class="w-full bg-[#bbf7d0] h-[6px] rounded-full overflow-hidden">
                        <div class="bg-[#166534] h-full rounded-full" style="width: 66%;"></div>
                    </div>
                </div>
            </div>

            {{-- Prioritas Tinggi --}}
            <div class="bg-[#ffedd5] rounded-[24px] p-[24px] flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="w-12 h-12 bg-[#f97316] rounded-[16px] flex items-center justify-center text-white shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">priority_high</span>
                    </div>
                    <div class="text-[28px] font-bold text-[#9a3412]">3</div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[16px] font-bold text-[#9a3412] mb-1">Prioritas Tinggi</h3>
                    <p class="text-[12px] text-[#c2410c] font-medium">Membutuhkan perhatian segera</p>
                </div>
            </div>

            {{-- Saran Hari Ini --}}
            <div class="bg-surface-container-low rounded-[24px] p-[24px] flex justify-between relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 border border-outline-variant/30">
                <div class="relative z-10 max-w-[160px]">
                    <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Saran Hari Ini</div>
                    <h3 class="text-[18px] font-bold text-on-surface leading-tight mb-2">Waktunya Pemupukan</h3>
                    <p class="text-[12px] text-on-surface-variant font-medium">Tanaman Tomat butuh nitrogen.</p>
                </div>
                <div class="relative z-10 mt-auto">
                    <div class="w-14 h-14 bg-[#d1fae5] rounded-full flex items-center justify-center text-[#059669] shadow-sm">
                        <span class="material-symbols-outlined text-[28px]">eco</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-[32px]">
            
            {{-- Left Column: Daftar Tugas (Takes up 2 columns) --}}
            <div class="lg:col-span-2 flex flex-col gap-[16px]">
                {{-- Header Filter --}}
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
                    <h2 class="text-[20px] font-bold text-on-surface">Daftar Tugas Harian</h2>
                    <div class="flex gap-2">
                        <button class="bg-[#047857] text-white px-4 py-1.5 rounded-full text-[13px] font-bold shadow-sm">Semua</button>
                        <button class="text-on-surface-variant hover:bg-surface-container-high px-4 py-1.5 rounded-full text-[13px] font-bold transition-colors">Penting</button>
                        <button class="text-on-surface-variant hover:bg-surface-container-high px-4 py-1.5 rounded-full text-[13px] font-bold transition-colors">Tertunda</button>
                    </div>
                </div>

                <div class="space-y-[16px] pt-2">
                    {{-- Task 1: Tinggi Prioritas Pending --}}
                    <div class="bg-surface rounded-[24px] p-[20px] flex items-center justify-between ambient-shadow hover:ambient-shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[16px] bg-[#ecfdf5] text-[#059669] flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">water_drop</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="text-[18px] font-bold text-on-surface">Penyiraman Rutin</h3>
                                    <span class="bg-[var(--color-status-late-bg)] text-[var(--color-status-late-text)] text-[10px] font-bold px-2 py-0.5 rounded-[4px]">HIGH</span>
                                </div>
                                <p class="text-[13px] text-on-surface-variant font-medium">Plot A1: Lidah Buaya & Basil</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-[#dc2626]">Pending</div>
                                <div class="text-[11px] text-on-surface-variant">Selesai Jam 09:00</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-[#059669] hover:bg-[#d1fae5] transition-colors"><span class="material-symbols-outlined">check</span></button>
                                <button class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-surface-container-highest transition-colors"><span class="material-symbols-outlined">fast_forward</span></button>
                            </div>
                        </div>
                    </div>

                    {{-- Task 2: Selesai --}}
                    <div class="bg-surface rounded-[24px] p-[20px] flex items-center justify-between ambient-shadow opacity-80">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[16px] bg-surface-container-highest text-on-surface-variant flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">science</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="text-[18px] font-bold text-on-surface line-through decoration-outline-variant">Pemupukan Organik</h3>
                                    <span class="bg-surface-container-high text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-[4px]">MEDIUM</span>
                                </div>
                                <p class="text-[13px] text-outline font-medium">Plot B3: Tomat Cherry</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-[#059669]">Done</div>
                                <div class="text-[11px] text-outline">07:30 AM</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-[#059669] flex items-center justify-center text-white shadow-sm"><span class="material-symbols-outlined">check_circle</span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Task 3: Tinggi Prioritas Pending --}}
                    <div class="bg-surface rounded-[24px] p-[20px] flex items-center justify-between ambient-shadow hover:ambient-shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[16px] bg-[#fff7ed] text-[#ea580c] flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">bug_report</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="text-[18px] font-bold text-on-surface">Pengendalian Hama</h3>
                                    <span class="bg-[var(--color-status-late-bg)] text-[var(--color-status-late-text)] text-[10px] font-bold px-2 py-0.5 rounded-[4px]">HIGH</span>
                                </div>
                                <p class="text-[13px] text-on-surface-variant font-medium">Plot C2: Mawar Merah</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-[#dc2626]">Pending</div>
                                <div class="text-[11px] text-on-surface-variant">Mendesak</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-[#059669] hover:bg-[#d1fae5] transition-colors"><span class="material-symbols-outlined">check</span></button>
                                <button class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-surface-container-highest transition-colors"><span class="material-symbols-outlined">fast_forward</span></button>
                            </div>
                        </div>
                    </div>

                    {{-- Task 4: Skipped --}}
                    <div class="bg-surface rounded-[24px] p-[20px] flex items-center justify-between ambient-shadow opacity-60">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[16px] bg-surface-container-highest text-on-surface-variant flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">visibility</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="text-[18px] font-bold text-on-surface">Pemeriksaan Kesehatan</h3>
                                    <span class="bg-surface-container-high text-on-surface-variant text-[10px] font-bold px-2 py-0.5 rounded-[4px]">LOW</span>
                                </div>
                                <p class="text-[13px] text-outline font-medium">Plot A2: Sayuran Hijau</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <div class="text-[13px] font-bold text-on-surface-variant">Skip</div>
                                <div class="text-[11px] text-outline">Dijadwal ulang</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-outline"><span class="material-symbols-outlined">block</span></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right Column: Sidebar (Takes up 1 column) --}}
            <div class="lg:col-span-1 flex flex-col gap-[24px]">
                
                {{-- Plot Terpopuler Card --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg">
                    <h3 class="text-[18px] font-bold text-on-surface mb-4">Plot Terpopuler</h3>
                    
                    <div class="relative h-[140px] rounded-[16px] overflow-hidden mb-4 shadow-sm group cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1615811361523-6bd03d7748e7?w=400&h=200&fit=crop&q=80" alt="Basil" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white font-bold text-[16px]">Plot A1: Kebun Herbal</div>
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
                </div>

                {{-- Autopilot (Pro Feature) --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-[18px] font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">smart_toy</span> Autopilot
                        </h3>
                        @if(in_array(Auth::user()->role ?? 'free', ['pro', 'premium', 'admin']))
                            <div class="w-10 h-6 bg-primary rounded-full relative cursor-pointer shadow-inner">
                                <div class="w-4 h-4 bg-white rounded-full absolute right-1 top-1 shadow-sm"></div>
                            </div>
                        @else
                            <div class="w-10 h-6 bg-outline-variant/50 rounded-full relative cursor-pointer" onclick="document.getElementById('pricing-modal').classList.remove('hidden')">
                                <div class="w-4 h-4 bg-white rounded-full absolute left-1 top-1 shadow-sm flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[10px] text-outline-variant">lock</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @if(in_array(Auth::user()->role ?? 'free', ['pro', 'premium', 'admin']))
                        <p class="text-[13px] text-on-surface-variant font-medium">Asisten AI sedang menyusun jadwal harian Anda secara otomatis.</p>
                        <div class="mt-4 flex items-center gap-2 text-[12px] font-bold text-primary bg-primary-container/30 px-3 py-1.5 rounded-lg inline-flex">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span> Aktif
                        </div>
                    @else
                        <p class="text-[13px] text-on-surface-variant font-medium mb-4">Otomatisasi jadwal berdasarkan kebutuhan spesifik tiap tanaman.</p>
                        <button onclick="document.getElementById('pricing-modal').classList.remove('hidden')" class="w-full bg-surface-container-high hover:bg-surface-container-highest text-primary font-bold py-2 rounded-xl text-[13px] transition-colors border border-primary/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">workspace_premium</span> Upgrade to Pro
                        </button>
                    @endif
                </div>

                {{-- Misi Mingguan Card --}}
                <div class="bg-[#67b193] rounded-[24px] p-[24px] relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 ambient-shadow-lg text-[#003823]">
                    <div class="mb-4 relative z-10">
                        <span class="material-symbols-outlined text-[28px] mb-2">military_tech</span>
                        <h3 class="text-[18px] font-bold mb-1">Misi Mingguan</h3>
                        <p class="text-[14px] font-medium leading-relaxed opacity-90">Selesaikan 5 tugas lagi untuk mendapatkan badge 'Tangan Dingin'.</p>
                    </div>
                    
                    <div class="flex justify-between items-end relative z-10">
                        <button class="bg-[#003823] text-white px-5 py-2.5 rounded-full text-[13px] font-bold hover:bg-[#025c3c] active:scale-95 transition-colors shadow-sm">Lihat Badge</button>
                        <div class="w-12 h-12 bg-[#003823] rounded-[16px] flex items-center justify-center text-white shadow-md">
                            <span class="material-symbols-outlined text-[24px]">workspace_premium</span>
                        </div>
                    </div>

                    {{-- Decorative blur --}}
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:bg-white/30 transition-colors duration-500"></div>
                </div>

            </div>

        </div>
    </div>
@endsection
