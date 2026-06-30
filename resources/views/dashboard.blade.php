@extends('layouts.dashboard')

@section('title', 'Beranda — Grow a Garden')
@section('description', 'Ringkasan kebun Anda dan tugas harian.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight mb-2">Selamat pagi.</h1>
                <p class="text-[16px] text-on-surface-variant">Kebun Anda tumbuh dengan baik. Mari lihat apa yang perlu dirawat hari ini.</p>
            </div>
            
            {{-- Weather Widget --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-surface-container-lowest rounded-full flex items-center justify-center ambient-shadow shrink-0">
                    <span class="material-symbols-outlined text-[24px] text-primary">rainy</span>
                </div>
                <div class="max-w-[220px]">
                    <h3 class="font-bold text-[16px] text-on-surface mb-0.5">Berawan / Hujan</h3>
                    <div class="flex items-center gap-1 text-primary text-[10px] font-bold uppercase tracking-wider mb-1">
                        <span class="material-symbols-outlined text-[14px]">auto_awesome</span> Adaptasi Pintar
                    </div>
                    <p class="text-[11px] text-on-surface-variant leading-tight">Frekuensi penyiraman dikurangi 20% hari ini karena perkiraan hujan.</p>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-[16px]">
            {{-- Card 1: Gardens & Plots --}}
            <a href="/garden-plots" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[#0f766e] text-[24px] mb-2">energy_savings_leaf</span>
                <div class="flex items-baseline gap-2 mb-1">
                    <span class="text-[36px] font-black text-on-surface leading-none">1</span>
                    <span class="text-[16px] font-bold text-on-surface-variant">/ 16</span>
                </div>
                <div class="text-[14px] text-on-surface font-medium text-center">Kebun / Plot</div>
            </a>
            {{-- Card 2: Active Plants --}}
            <a href="/growth-calendar" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-status-healthy text-[24px] mb-2">potted_plant</span>
                <div class="text-[36px] font-black text-on-surface leading-none mb-1">12</div>
                <div class="text-[14px] text-on-surface font-medium text-center">Tanaman Aktif</div>
            </a>

            {{-- Card 4 --}}
            <a href="/care-tasks" class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center ambient-shadow hover:-translate-y-1 hover:ambient-shadow-lg transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[#f97316] text-[24px] mb-2">task_alt</span>
                <div class="text-[36px] font-black text-on-surface leading-none mb-1">3</div>
                <div class="text-[14px] text-on-surface font-medium text-center">Aktivitas Hari Ini</div>
            </a>
        </div>

        {{-- Map Row --}}
        <div class="flex flex-col gap-[24px]">
            
            {{-- Garden Plots --}}
            <div class="bg-surface rounded-[24px] p-[24px] md:p-[32px] ambient-shadow">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-[20px] font-bold text-on-surface">Plot Kebun</h3>
                    <div class="flex flex-wrap gap-4 text-[12px] font-bold text-on-surface-variant">
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-healthy)]"></span> Sehat</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-attention)]"></span> Perlu Perhatian</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-late)]"></span> Terlambat Dirawat</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[var(--color-status-new)]"></span> Baru Ditanam</div>
                    </div>
                </div>
                
                <style>
                    .dash-bg-grid {
                        background-color: #f2f6f4;
                        background-image: 
                            linear-gradient(rgba(0, 108, 73, 0.05) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(0, 108, 73, 0.05) 1px, transparent 1px);
                        background-size: 24px 24px;
                        background-position: center center;
                    }
                    .dash-zone-box {
                        position: absolute;
                        border: 2px dashed;
                        border-radius: 20px;
                        background-color: rgba(255,255,255,0.95);
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        box-shadow: inset 0 0 0 2px rgba(255,255,255,0.6), 0 8px 24px rgba(0,0,0,0.04);
                        transition: transform 0.3s ease;
                    }
                    .dash-zone-box:hover {
                        transform: scale(1.02);
                        z-index: 10;
                    }
                    .dash-zone-label {
                        background-color: rgba(255,255,255,0.95);
                        padding: 8px 16px;
                        border-radius: 30px;
                        box-shadow: 0 8px 24px rgba(0, 108, 73, 0.08), inset 0 0 0 1px rgba(255,255,255,1);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                </style>

                <div class="dash-bg-grid rounded-[16px] w-full h-[240px] md:h-[300px] relative overflow-hidden border border-outline-variant/20 cursor-pointer group hover:border-primary/30 transition-colors" onclick="window.location.href='/garden-plots'">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-white/20 backdrop-blur-[2px] z-20">
                        <span class="bg-primary text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                            <span class="material-symbols-outlined">open_in_new</span>
                            Buka Plot Kebun
                        </span>
                    </div>

                    <div class="absolute w-[1000px] h-[600px] left-1/2 top-1/2 -translate-x-[40%] -translate-y-[45%] scale-[0.4] md:scale-[0.6]">
                        <!-- Zone 1 -->
                        <div class="dash-zone-box" style="left: 240px; top: 144px; width: 264px; height: 168px; border-color: var(--color-primary);">
                            <div class="dash-zone-label" style="color: var(--color-primary);">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                                <span class="text-[14px] md:text-[15px] font-bold truncate tracking-tight">Tomato Plot A1</span>
                            </div>
                        </div>
                        
                        <!-- Zone 2 (Attention) -->
                        <div class="dash-zone-box" style="left: 552px; top: 192px; width: 216px; height: 120px; border-color: #f59e0b;">
                            <div class="dash-zone-label" style="color: #b45309;">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                                <span class="text-[14px] md:text-[15px] font-bold truncate tracking-tight">Chili Field B2</span>
                            </div>
                        </div>

                        <!-- Zone 3 (Late Care) -->
                        <div class="dash-zone-box" style="left: 240px; top: 360px; width: 384px; height: 168px; border-color: #ef4444;">
                            <div class="dash-zone-label" style="color: #b91c1c;">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                                <span class="text-[14px] md:text-[15px] font-bold truncate tracking-tight">Carrot Patch C1</span>
                            </div>
                        </div>
                        
                        <!-- Zone 4 (Newly Planted) -->
                        <div class="dash-zone-box" style="left: 672px; top: 360px; width: 240px; height: 168px; border-color: #0ea5e9;">
                            <div class="dash-zone-label" style="color: #0369a1;">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                                <span class="text-[14px] md:text-[15px] font-bold truncate tracking-tight">Lettuce Bed D4</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Upcoming Harvest Row --}}
        <div class="bg-surface rounded-[24px] p-[32px] ambient-shadow mb-[24px]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[20px] font-bold text-on-surface">Panen Mendatang (Upcoming Harvest)</h3>
                <a href="/growth-calendar" class="text-[14px] font-bold text-primary hover:underline">Lihat Kalender</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Harvest Item --}}
                <div class="bg-surface-container-low rounded-[16px] p-4 flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">shopping_basket</span>
                        </div>
                        <div>
                            <div class="text-[14px] font-bold text-on-surface">Tomat Cherry</div>
                            <div class="text-[12px] text-on-surface-variant">Plot A1</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-status-healthy text-[16px]">schedule</span>
                        <span class="text-[13px] font-bold text-status-healthy">2 hari lagi</span>
                    </div>
                </div>
                {{-- Harvest Item --}}
                <div class="bg-surface-container-low rounded-[16px] p-4 flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">shopping_basket</span>
                        </div>
                        <div>
                            <div class="text-[14px] font-bold text-on-surface">Cabai Rawit</div>
                            <div class="text-[12px] text-on-surface-variant">Field B2</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[16px]">schedule</span>
                        <span class="text-[13px] font-bold text-primary">5 hari lagi</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
            
            {{-- Plant Distribution --}}
            <div class="bg-surface rounded-[24px] p-[32px] ambient-shadow">
                <h3 class="text-[20px] font-bold text-on-surface mb-8">Distribusi Tanaman</h3>
                <div class="flex justify-center mb-8">
                    {{-- CSS Conic Gradient Donut Chart --}}
                    <div class="w-56 h-56 rounded-full flex items-center justify-center" style="background: conic-gradient(#10b981 0% 55%, #78a994 55% 75%, #fb923c 75% 100%);">
                        <div class="w-36 h-36 bg-surface rounded-full shadow-inner"></div>
                    </div>
                </div>
                <div class="flex justify-center gap-6 text-[13px] font-bold text-on-surface-variant">
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-status-healthy"></span> Sayuran</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#78a994]"></span> Herbal</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#fb923c]"></span> Buah-buahan</div>
                </div>
            </div>

            {{-- Weekly Care Activity --}}
            <div class="bg-surface rounded-[24px] p-[32px] ambient-shadow">
                <h3 class="text-[20px] font-bold text-on-surface mb-8">Aktivitas Perawatan Mingguan</h3>
                
                {{-- Mock Bar Chart --}}
                <div class="h-48 flex items-end justify-between gap-3 mb-6 border-b-2 border-outline-variant/20 pb-2 relative">
                    {{-- Y Axis Grid Lines --}}
                    <div class="absolute inset-0 flex flex-col justify-between z-0 pointer-events-none">
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                        <div class="border-t border-outline-variant/10 w-full"></div>
                    </div>

                    {{-- Bars --}}
                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 60%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>
                    
                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 50%">
                        <div class="bg-[#fb923c] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 25%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 75%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 85%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 35%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 65%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 30%">
                        <div class="bg-status-healthy w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 100%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 65%">
                        <div class="bg-[#fb923c] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 50%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-status-healthy w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 65%">
                        <div class="bg-status-healthy w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 100%"></div>
                    </div>
                </div>

                <div class="flex justify-between px-2 text-[12px] text-on-surface-variant font-bold mb-6">
                    <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                </div>

                <div class="flex justify-center gap-6 text-[13px] font-bold text-on-surface-variant">
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-status-healthy"></span> Menyiram</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#fb923c]"></span> Memupuk</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#78a994]"></span> Memangkas</div>
                </div>
            </div>

        </div>



    </div>
@endsection
