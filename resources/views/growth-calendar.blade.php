@extends('layouts.dashboard')

@section('title', 'Growth Calendar — Grow a Garden')
@section('description', 'Track and manage the growth stages of your plants.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-2">
            <div>
                <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight mb-2">Growth Calendar</h1>
                <p class="text-[16px] text-on-surface-variant max-w-xl leading-[24px]">Pantau linimasa pertumbuhan cerdas yang beradaptasi dengan kondisi kebun Anda secara real-time.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-[24px]">
            {{-- Main Timeline Container (Takes up 3 columns) --}}
            <div class="lg:col-span-3 bg-surface rounded-[24px] p-[24px] md:p-[40px] ambient-shadow-lg border border-outline-variant/20">
                
                {{-- Plant Selector Header --}}
                <div class="flex justify-between items-start mb-10 pb-6">
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=150&h=150&fit=crop&q=80" alt="Tomat Cherry" class="w-16 h-16 md:w-20 md:h-20 rounded-[16px] object-cover shadow-sm border border-outline-variant/20">
                        <div>
                            <h2 class="text-[24px] md:text-[28px] font-bold text-on-surface leading-tight mb-1">Tomat Cherry</h2>
                            <p class="text-[14px] font-bold text-secondary">Fase: Vegetatif (Hari 24/75)</p>
                        </div>
                    </div>
                    <button class="text-primary font-bold flex items-center gap-2 hover:opacity-80 transition-opacity mt-2">
                        <span class="material-symbols-outlined text-[20px]">edit</span> Edit Jadwal
                    </button>
                </div>
                
                {{-- Stepper Timeline --}}
                <div class="relative pl-8 md:pl-10 space-y-[36px]">
                    {{-- Vertical dashed line --}}
                    <div class="absolute left-[15px] md:left-[23px] top-4 bottom-8 w-[2px] border-l-2 border-dashed border-outline-variant/50"></div>

                    {{-- Step 1: Tanam --}}
                    <div class="relative group cursor-pointer">
                        <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-md z-10 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-[16px] text-on-primary font-bold">check</span>
                        </div>
                        <div class="ml-4 md:ml-6">
                            <h3 class="text-[16px] font-bold text-primary mb-0.5">Tanam (Selesai)</h3>
                            <p class="text-[13px] text-on-surface-variant font-medium">12 Jan 2024 • Benih disemai di tray kayu.</p>
                        </div>
                    </div>

                    {{-- Step 2: Germinasi --}}
                    <div class="relative group cursor-pointer">
                        <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-md z-10 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-[16px] text-on-primary font-bold">check</span>
                        </div>
                        <div class="ml-4 md:ml-6">
                            <h3 class="text-[16px] font-bold text-primary mb-0.5">Germinasi (Selesai)</h3>
                            <p class="text-[13px] text-on-surface-variant font-medium">18 Jan 2024 • Tunas pertama muncul (4-6 hari).</p>
                        </div>
                    </div>

                    {{-- Step 3: Persemaian --}}
                    <div class="relative group cursor-pointer">
                        <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-md z-10 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-[16px] text-on-primary font-bold">check</span>
                        </div>
                        <div class="ml-4 md:ml-6">
                            <h3 class="text-[16px] font-bold text-primary mb-0.5">Persemaian (Selesai)</h3>
                            <p class="text-[13px] text-on-surface-variant font-medium">02 Feb 2024 • Pindah ke pot utama, tinggi 10cm.</p>
                        </div>
                    </div>

                    {{-- Step 4: Vegetatif (Current) --}}
                    <div class="relative group">
                        <div class="absolute -left-[49px] md:-left-[57px] top-4 w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-lg ring-4 ring-surface z-10">
                            <span class="material-symbols-outlined text-[20px] text-on-primary">eco</span>
                        </div>
                        <div class="ml-4 md:ml-6 bg-surface-container-lowest border-l-4 border-primary rounded-r-[16px] rounded-tl-[4px] rounded-bl-[4px] p-[20px] shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                            <h3 class="text-[16px] font-bold text-primary mb-1">Vegetatif (Sedang Berlangsung)</h3>
                            <p class="text-[13px] text-on-surface-variant font-medium mb-5">Fokus pada pertumbuhan batang dan daun. Berikan nutrisi tinggi nitrogen.</p>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex-1 bg-outline-variant/30 h-[8px] rounded-full overflow-hidden">
                                    <div class="bg-primary h-full rounded-full transition-all duration-1000 ease-out" style="width: 70%;"></div>
                                </div>
                                <span class="text-[11px] text-on-surface-variant font-bold whitespace-nowrap">Estimasi 12 hari lagi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Step 5: Berbunga --}}
                    <div class="relative opacity-60 hover:opacity-100 transition-opacity duration-300 cursor-pointer group">
                        <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-surface border-2 border-outline-variant flex items-center justify-center z-10 group-hover:border-primary group-hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-outline-variant group-hover:text-primary transition-colors">local_florist</span>
                        </div>
                        <div class="ml-4 md:ml-6">
                            <h3 class="text-[16px] font-bold text-on-surface-variant mb-0.5 group-hover:text-primary transition-colors">Berbunga (Mendatang)</h3>
                            <p class="text-[13px] text-outline font-medium">Estimasi: 20 Feb 2024 • Munculnya bunga kuning kecil.</p>
                        </div>
                    </div>

                    {{-- Step 6: Berbuah --}}
                    <div class="relative opacity-50 hover:opacity-100 transition-opacity duration-300 cursor-pointer group">
                        <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-surface border-2 border-outline-variant flex items-center justify-center z-10 group-hover:border-primary group-hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-outline-variant group-hover:text-primary transition-colors">nutrition</span>
                        </div>
                        <div class="ml-4 md:ml-6">
                            <h3 class="text-[16px] font-bold text-on-surface-variant mb-0.5 group-hover:text-primary transition-colors">Berbuah (Mendatang)</h3>
                            <p class="text-[13px] text-outline font-medium">Estimasi: 10 Mar 2024 • Formasi buah hijau.</p>
                        </div>
                    </div>

                    {{-- Step 7: Panen --}}
                    <div class="relative opacity-40 hover:opacity-100 transition-opacity duration-300 cursor-pointer group">
                        <div class="absolute -left-[45px] md:-left-[53px] top-0 w-8 h-8 rounded-full bg-surface border-2 border-outline-variant flex items-center justify-center z-10 group-hover:border-primary group-hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-outline-variant group-hover:text-primary transition-colors">shopping_basket</span>
                        </div>
                        <div class="ml-4 md:ml-6">
                            <h3 class="text-[16px] font-bold text-on-surface-variant mb-0.5 group-hover:text-primary transition-colors">Panen (Mendatang)</h3>
                            <p class="text-[13px] text-outline font-medium">Estimasi: 01 Apr 2024 • Buah matang sempurna.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Context (Takes up 2 columns) --}}
            <div class="lg:col-span-2 flex flex-col gap-[24px]">
                
                {{-- Card 1: Mendekati Waktu Panen --}}
                <div class="bg-[#0b6e4f] text-white rounded-[24px] p-[32px] ambient-shadow-lg hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                    <h3 class="text-[28px] font-bold leading-tight mb-8 relative z-10 pr-4">Mendekati Waktu Panen!</h3>
                    <div class="flex items-center gap-3 relative z-10">
                        <span class="material-symbols-outlined text-[24px]">schedule</span>
                        <p class="text-[14px] font-bold leading-tight">3 tanaman butuh perhatian khusus besok</p>
                    </div>
                    {{-- Decorative highlight --}}
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors duration-500"></div>
                </div>

                {{-- Card 2: Tanaman Lainnya --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow border border-outline-variant/20">
                    <h3 class="text-[16px] font-bold text-on-surface mb-4">Tanaman Lainnya</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-[16px] border border-outline-variant/30 hover:border-primary/30 hover:bg-surface-container-lowest cursor-pointer transition-colors group">
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1622383563227-04401ab4e5ea?w=100&h=100&fit=crop&q=80" alt="Selada Romaine" class="w-14 h-14 rounded-[12px] object-cover shadow-sm">
                                <div>
                                    <h4 class="text-[14px] font-bold text-on-surface group-hover:text-primary transition-colors">Selada Romaine</h4>
                                    <p class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1.5 uppercase tracking-wider mt-1"><span class="w-2 h-2 rounded-full bg-primary"></span> PERSEMAIAN</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">chevron_right</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 rounded-[16px] border border-outline-variant/30 hover:border-error/30 hover:bg-error-container/10 cursor-pointer transition-colors group">
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1588252303782-cb80119abd6d?w=100&h=100&fit=crop&q=80" alt="Cabai Rawit" class="w-14 h-14 rounded-[12px] object-cover shadow-sm">
                                <div>
                                    <h4 class="text-[14px] font-bold text-on-surface group-hover:text-error transition-colors">Cabai Rawit</h4>
                                    <p class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1.5 uppercase tracking-wider mt-1"><span class="w-2 h-2 rounded-full bg-error"></span> BERBUNGA</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant group-hover:text-error transition-colors">chevron_right</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Tips Hari Ini --}}
                <div class="bg-[#67b193] text-[#003823] rounded-[24px] p-[24px] ambient-shadow-lg relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-4 relative z-10">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                        <h3 class="text-[14px] font-bold uppercase tracking-wider">Tips Hari Ini</h3>
                    </div>
                    <p class="text-[14px] leading-relaxed font-semibold italic relative z-10">
                        "Saat fase vegetatif tomat, pangkas tunas 'sucker' agar nutrisi fokus pada pertumbuhan batang utama."
                    </p>
                    <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                </div>

            </div>
        </div>
    </div>
@endsection
