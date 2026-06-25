@extends('layouts.dashboard')

@section('title', 'Koleksi Plot Kebun — Grow a Garden')
@section('description', 'Kelola dan pantau setiap petak tanaman Anda secara real-time.')

@section('dashboard-content')
    <div class="flex flex-col gap-[32px] pb-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight mb-2">Koleksi Plot Kebun</h1>
                <p class="text-[16px] text-on-surface-variant">Kelola dan pantau setiap petak tanaman Anda secara real-time.</p>
            </div>
            <div class="relative w-full md:w-[300px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" placeholder="Cari tanaman..." class="w-full bg-surface-container-high rounded-full py-3.5 pl-12 pr-4 text-[14px] text-on-surface font-medium focus:outline-none focus:ring-2 focus:ring-primary/50 transition-shadow">
            </div>
        </div>

        {{-- Grid Container --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]">
            
            {{-- Card 1: Cabai Rawit --}}
            <div class="bg-surface rounded-[24px] overflow-hidden ambient-shadow-lg border border-outline-variant/20 group hover:-translate-y-1 transition-all duration-300">
                <div class="relative h-[220px] overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1588252303782-cb80119abd6d?w=600&h=400&fit=crop&q=80" alt="Cabai Rawit" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-surface text-primary font-bold text-[14px] px-3.5 py-1.5 rounded-full shadow-sm">A1</div>
                    <div class="absolute top-4 right-4 bg-primary-container text-on-primary-container font-bold text-[12px] px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Sehat
                    </div>
                </div>
                <div class="p-[24px]">
                    <div class="flex justify-between items-start mb-1">
                        <h2 class="text-[24px] font-bold text-on-surface">Cabai Rawit</h2>
                        <button class="text-on-surface-variant hover:text-on-surface transition-colors mt-1"><span class="material-symbols-outlined">more_vert</span></button>
                    </div>
                    <div class="flex items-center gap-1.5 text-on-surface-variant text-[13px] font-medium mb-6">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span> Tanam: 12 Jan 2024
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between text-[13px] font-bold mb-2">
                            <span class="text-on-surface-variant">Progress Pertumbuhan</span>
                            <span class="text-primary">75%</span>
                        </div>
                        <div class="h-[6px] bg-surface-container-highest rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-1000 ease-out" style="width: 75%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-surface-container-lowest rounded-[16px] p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-secondary text-[24px]">water_drop</span>
                            <div>
                                <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Kelembapan</div>
                                <div class="text-[16px] font-bold text-on-surface leading-none">68%</div>
                            </div>
                        </div>
                        <div class="bg-surface-container-lowest rounded-[16px] p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B] text-[24px]">light_mode</span>
                            <div>
                                <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Cahaya</div>
                                <div class="text-[16px] font-bold text-on-surface leading-none">Optimal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Selada Mentega --}}
            <div class="bg-surface rounded-[24px] overflow-hidden ambient-shadow-lg border border-outline-variant/20 group hover:-translate-y-1 transition-all duration-300">
                <div class="relative h-[220px] overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1622383563227-04401ab4e5ea?w=600&h=400&fit=crop&q=80" alt="Selada Mentega" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-surface text-primary font-bold text-[14px] px-3.5 py-1.5 rounded-full shadow-sm">A2</div>
                    <div class="absolute top-4 right-4 bg-primary-container text-on-primary-container font-bold text-[12px] px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Sehat
                    </div>
                </div>
                <div class="p-[24px]">
                    <div class="flex justify-between items-start mb-1">
                        <h2 class="text-[24px] font-bold text-on-surface">Selada Mentega</h2>
                        <button class="text-on-surface-variant hover:text-on-surface transition-colors mt-1"><span class="material-symbols-outlined">more_vert</span></button>
                    </div>
                    <div class="flex items-center gap-1.5 text-on-surface-variant text-[13px] font-medium mb-6">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span> Tanam: 05 Feb 2024
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between text-[13px] font-bold mb-2">
                            <span class="text-on-surface-variant">Progress Pertumbuhan</span>
                            <span class="text-primary">40%</span>
                        </div>
                        <div class="h-[6px] bg-surface-container-highest rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-1000 ease-out" style="width: 40%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-surface-container-lowest rounded-[16px] p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-secondary text-[24px]">water_drop</span>
                            <div>
                                <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Kelembapan</div>
                                <div class="text-[16px] font-bold text-on-surface leading-none">72%</div>
                            </div>
                        </div>
                        <div class="bg-surface-container-lowest rounded-[16px] p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B] text-[24px]">light_mode</span>
                            <div>
                                <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Cahaya</div>
                                <div class="text-[16px] font-bold text-on-surface leading-none">Cukup</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Tomat Ceri --}}
            <div class="bg-surface rounded-[24px] overflow-hidden ambient-shadow-lg border border-outline-variant/20 group hover:-translate-y-1 transition-all duration-300">
                <div class="relative h-[220px] overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=600&h=400&fit=crop&q=80" alt="Tomat Ceri" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-surface text-primary font-bold text-[14px] px-3.5 py-1.5 rounded-full shadow-sm">B1</div>
                    <div class="absolute top-4 right-4 bg-[#FFEBEE] text-error font-bold text-[12px] px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1 animate-pulse">
                        <span class="material-symbols-outlined text-[16px]">warning</span> Kurang Air
                    </div>
                </div>
                <div class="p-[24px]">
                    <div class="flex justify-between items-start mb-1">
                        <h2 class="text-[24px] font-bold text-on-surface">Tomat Ceri</h2>
                        <button class="text-on-surface-variant hover:text-on-surface transition-colors mt-1"><span class="material-symbols-outlined">more_vert</span></button>
                    </div>
                    <div class="flex items-center gap-1.5 text-on-surface-variant text-[13px] font-medium mb-6">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span> Tanam: 28 Des 2023
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between text-[13px] font-bold mb-2">
                            <span class="text-on-surface-variant">Progress Pertumbuhan</span>
                            <span class="text-primary">92%</span>
                        </div>
                        <div class="h-[6px] bg-surface-container-highest rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-1000 ease-out" style="width: 92%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-[#FFEBEE]/50 rounded-[16px] p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-error text-[24px]">water_drop</span>
                            <div>
                                <div class="text-[10px] font-bold text-error uppercase tracking-wider mb-0.5">Kelembapan</div>
                                <div class="text-[16px] font-bold text-error leading-none">32%</div>
                            </div>
                        </div>
                        <div class="bg-surface-container-lowest rounded-[16px] p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B] text-[24px]">light_mode</span>
                            <div>
                                <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Cahaya</div>
                                <div class="text-[16px] font-bold text-on-surface leading-none">Optimal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Tambah Plot Baru --}}
            <div class="border-[2.5px] border-dashed border-outline-variant/40 rounded-[24px] flex flex-col items-center justify-center min-h-[460px] group hover:border-primary/50 hover:bg-surface-container-lowest transition-all duration-300 cursor-pointer">
                <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-primary group-hover:shadow-md transition-all duration-300">
                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant group-hover:text-on-primary transition-colors">add</span>
                </div>
                <h3 class="text-[24px] font-bold text-on-surface text-center mb-1 leading-tight group-hover:text-primary transition-colors">Tambah Plot<br>Baru</h3>
                <p class="text-[13px] text-on-surface-variant font-medium">Mulai petualangan tanam baru</p>
            </div>

        </div>
    </div>
@endsection
