@extends('layouts.dashboard')

@section('title', 'Dashboard — Grow a Garden')
@section('description', 'Your garden overview and daily tasks.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-[32px] md:text-[40px] font-bold text-[#1e293b] tracking-tight leading-tight mb-2">Good morning.</h1>
                <p class="text-[16px] text-on-surface-variant">Your garden is thriving. Let's see what needs tending today.</p>
            </div>
            
            {{-- Weather Widget --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-surface-container-lowest rounded-full flex items-center justify-center ambient-shadow shrink-0">
                    <span class="material-symbols-outlined text-[24px] text-primary">rainy</span>
                </div>
                <div class="max-w-[220px]">
                    <h3 class="font-bold text-[16px] text-on-surface mb-0.5">Cloudy / Rainy</h3>
                    <div class="flex items-center gap-1 text-primary text-[10px] font-bold uppercase tracking-wider mb-1">
                        <span class="material-symbols-outlined text-[14px]">auto_awesome</span> Smart Adaptation
                    </div>
                    <p class="text-[11px] text-on-surface-variant leading-tight">Watering frequency reduced by 20% today due to expected rainfall.</p>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-[16px]">
            {{-- Card 1 --}}
            <div class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center border border-outline-variant/20 ambient-shadow hover:-translate-y-1 transition-transform">
                <span class="material-symbols-outlined text-[#0f766e] text-[24px] mb-2">energy_savings_leaf</span>
                <div class="text-[36px] font-black text-[#1e293b] leading-none mb-1">1</div>
                <div class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider">Total Gardens</div>
            </div>
            {{-- Card 2 --}}
            <div class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center border border-outline-variant/20 ambient-shadow hover:-translate-y-1 transition-transform">
                <span class="material-symbols-outlined text-[#10b981] text-[24px] mb-2">potted_plant</span>
                <div class="text-[36px] font-black text-[#1e293b] leading-none mb-1">12</div>
                <div class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider">Active Plants</div>
            </div>
            {{-- Card 3 --}}
            <div class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center border border-outline-variant/20 ambient-shadow hover:-translate-y-1 transition-transform">
                <span class="material-symbols-outlined text-[#0ea5e9] text-[24px] mb-2">grid_view</span>
                <div class="text-[36px] font-black text-[#1e293b] leading-none mb-1">16</div>
                <div class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider">Total Plots</div>
            </div>
            {{-- Card 4 --}}
            <div class="bg-surface rounded-[24px] p-6 flex flex-col items-center justify-center border border-outline-variant/20 ambient-shadow hover:-translate-y-1 transition-transform">
                <span class="material-symbols-outlined text-[#f97316] text-[24px] mb-2">task_alt</span>
                <div class="text-[36px] font-black text-[#1e293b] leading-none mb-1">3</div>
                <div class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider">Today's Activities</div>
            </div>
        </div>

        {{-- Map & Schedule Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-[24px]">
            
            {{-- Garden Plots --}}
            <div class="lg:col-span-2 bg-surface rounded-[24px] p-[24px] md:p-[32px] border border-outline-variant/20 ambient-shadow">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-[20px] font-bold text-[#1e293b]">Garden Plots</h3>
                    <div class="flex flex-wrap gap-4 text-[12px] font-bold text-on-surface-variant">
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#10b981]"></span> Healthy</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#f59e0b]"></span> Attention</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#ef4444]"></span> Late Care</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#0ea5e9]"></span> Newly Planted</div>
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
                        background-color: rgba(255,255,255,0.85);
                        backdrop-filter: blur(12px);
                        -webkit-backdrop-filter: blur(12px);
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

                <div class="dash-bg-grid rounded-[16px] w-full h-[240px] md:h-[300px] relative overflow-hidden border border-outline-variant/20 cursor-pointer group hover:border-[#006c49]/30 transition-colors" onclick="window.location.href='/garden-plots'">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-white/20 backdrop-blur-[2px] z-20">
                        <span class="bg-[#006c49] text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                            <span class="material-symbols-outlined">open_in_new</span>
                            Open Garden Plots
                        </span>
                    </div>

                    <div class="absolute w-[1000px] h-[600px] left-1/2 top-1/2 -translate-x-[40%] -translate-y-[45%] scale-[0.4] md:scale-[0.6]">
                        <!-- Zone 1 -->
                        <div class="dash-zone-box" style="left: 240px; top: 144px; width: 264px; height: 168px; border-color: #006c49;">
                            <div class="dash-zone-label" style="color: #006c49;">
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

            {{-- Today's Schedule --}}
            <div class="bg-surface rounded-[24px] p-[32px] border border-outline-variant/20 ambient-shadow flex flex-col">
                <h3 class="text-[20px] font-bold text-[#1e293b] mb-8">Today's Schedule</h3>
                
                <div class="flex-1 space-y-6">
                    {{-- Task 1 --}}
                    <div class="flex items-start gap-4">
                        <div class="w-6 h-6 rounded-[6px] border-2 border-outline-variant mt-0.5 cursor-pointer hover:border-primary transition-colors flex items-center justify-center"></div>
                        <div>
                            <div class="text-[15px] font-bold text-[#1e293b] mb-0.5">Watering - Plot A1</div>
                            <div class="text-[13px] text-on-surface-variant">Tomato • Soil moisture low</div>
                        </div>
                    </div>
                    
                    {{-- Task 2 --}}
                    <div class="flex items-start gap-4">
                        <div class="w-6 h-6 rounded-[6px] border-2 border-outline-variant mt-0.5 cursor-pointer hover:border-primary transition-colors flex items-center justify-center"></div>
                        <div>
                            <div class="text-[15px] font-bold text-[#1e293b] mb-0.5">Fertilizing - Plot B2</div>
                            <div class="text-[13px] text-on-surface-variant">Chili • Scheduled feed</div>
                        </div>
                    </div>

                    {{-- Task 3 --}}
                    <div class="flex items-start gap-4">
                        <div class="w-6 h-6 rounded-[6px] border-2 border-outline-variant mt-0.5 cursor-pointer hover:border-primary transition-colors flex items-center justify-center"></div>
                        <div>
                            <div class="text-[15px] font-bold text-[#1e293b] mb-0.5">Check health - Plot C3</div>
                            <div class="text-[13px] text-on-surface-variant">Spinach • Potential pests spotted</div>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-8 py-3 rounded-full border-[2px] border-[#b45309] text-[#b45309] font-bold text-[14px] hover:bg-[#b45309]/5 active:scale-95 transition-all">
                    View All Tasks
                </button>
            </div>

        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
            
            {{-- Plant Distribution --}}
            <div class="bg-surface rounded-[24px] p-[32px] border border-outline-variant/20 ambient-shadow">
                <h3 class="text-[20px] font-bold text-[#1e293b] mb-8">Plant Distribution</h3>
                <div class="flex justify-center mb-8">
                    {{-- CSS Conic Gradient Donut Chart --}}
                    <div class="w-56 h-56 rounded-full flex items-center justify-center" style="background: conic-gradient(#10b981 0% 55%, #78a994 55% 75%, #fb923c 75% 100%);">
                        <div class="w-36 h-36 bg-surface rounded-full shadow-inner"></div>
                    </div>
                </div>
                <div class="flex justify-center gap-6 text-[13px] font-bold text-on-surface-variant">
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#10b981]"></span> Vegetables</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#78a994]"></span> Herbs</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#fb923c]"></span> Fruits</div>
                </div>
            </div>

            {{-- Weekly Care Activity --}}
            <div class="bg-surface rounded-[24px] p-[32px] border border-outline-variant/20 ambient-shadow">
                <h3 class="text-[20px] font-bold text-[#1e293b] mb-8">Weekly Care Activity</h3>
                
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
                        <div class="bg-[#10b981] w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>
                    
                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 50%">
                        <div class="bg-[#fb923c] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 25%"></div>
                        <div class="bg-[#10b981] w-full hover:opacity-80 transition-opacity" style="height: 75%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 85%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 35%"></div>
                        <div class="bg-[#10b981] w-full hover:opacity-80 transition-opacity" style="height: 65%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 30%">
                        <div class="bg-[#10b981] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 100%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 65%">
                        <div class="bg-[#fb923c] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-[#10b981] w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 50%">
                        <div class="bg-[#78a994] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 20%"></div>
                        <div class="bg-[#10b981] w-full hover:opacity-80 transition-opacity" style="height: 80%"></div>
                    </div>

                    <div class="flex flex-col justify-end w-full gap-0.5 relative z-10" style="height: 65%">
                        <div class="bg-[#10b981] w-full rounded-t-sm hover:opacity-80 transition-opacity" style="height: 100%"></div>
                    </div>
                </div>

                <div class="flex justify-between px-2 text-[12px] text-on-surface-variant font-bold mb-6">
                    <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                </div>

                <div class="flex justify-center gap-6 text-[13px] font-bold text-on-surface-variant">
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#10b981]"></span> Watering</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#fb923c]"></span> Fertilizing</div>
                    <div class="flex items-center gap-2"><span class="w-6 h-2 rounded-full bg-[#78a994]"></span> Pruning</div>
                </div>
            </div>

        </div>

        {{-- Bottom Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-[24px]">
            
            {{-- Upcoming Harvest --}}
            <div class="bg-surface rounded-[24px] p-[32px] border border-outline-variant/20 ambient-shadow">
                <h3 class="text-[20px] font-bold text-[#1e293b] mb-6">Upcoming Harvest</h3>
                
                <div class="space-y-5">
                    {{-- Item 1 --}}
                    <div class="flex items-center justify-between group hover:bg-surface-container-lowest p-2 -m-2 rounded-[12px] transition-colors cursor-pointer">
                        <div class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=100&h=100&fit=crop&q=80" class="w-14 h-14 rounded-[12px] object-cover shadow-sm group-hover:scale-105 transition-transform">
                            <div>
                                <div class="text-[15px] font-bold text-[#1e293b]">Tomato Cherry</div>
                                <div class="text-[13px] text-on-surface-variant">Plot A1, A2</div>
                            </div>
                        </div>
                        <span class="bg-[#10b981] text-white text-[12px] font-bold px-4 py-1.5 rounded-full shadow-sm">5 days</span>
                    </div>
                    
                    {{-- Item 2 --}}
                    <div class="flex items-center justify-between group hover:bg-surface-container-lowest p-2 -m-2 rounded-[12px] transition-colors cursor-pointer">
                        <div class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1588252303782-cb80119abd6d?w=100&h=100&fit=crop&q=80" class="w-14 h-14 rounded-[12px] object-cover shadow-sm group-hover:scale-105 transition-transform">
                            <div>
                                <div class="text-[15px] font-bold text-[#1e293b]">Chili</div>
                                <div class="text-[13px] text-on-surface-variant">Plot B2</div>
                            </div>
                        </div>
                        <span class="border-2 border-outline-variant/50 text-on-surface-variant text-[12px] font-bold px-4 py-1.5 rounded-full bg-surface-container-lowest">12 days</span>
                    </div>
                </div>
            </div>

            {{-- Growth Calendar Overview --}}
            <div class="lg:col-span-2 bg-surface rounded-[24px] p-[32px] border border-outline-variant/20 ambient-shadow">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-[20px] font-bold text-[#1e293b]">Growth Calendar Overview</h3>
                    <button class="text-on-surface-variant hover:text-[#1e293b] transition-colors"><span class="material-symbols-outlined">more_horiz</span></button>
                </div>
                
                <div class="relative pl-6 space-y-8">
                    {{-- Connecting Line --}}
                    <div class="absolute left-[7px] top-2 bottom-2 w-[2px] bg-outline-variant/30"></div>
                    
                    {{-- Step 1 --}}
                    <div class="relative">
                        <div class="absolute -left-[30px] top-1 w-4 h-4 rounded-full bg-[#0f766e] ring-4 ring-surface"></div>
                        <h4 class="text-[15px] font-bold text-[#0f766e] mb-1">Current Stage: Vegetative</h4>
                        <p class="text-[13px] text-on-surface-variant font-medium leading-relaxed">Majority of plots are experiencing rapid leaf and stem growth. Ensure consistent watering.</p>
                    </div>
                    
                    {{-- Step 2 --}}
                    <div class="relative opacity-60 hover:opacity-100 transition-opacity cursor-pointer group">
                        <div class="absolute -left-[30px] top-1 w-4 h-4 rounded-full bg-outline-variant ring-4 ring-surface group-hover:bg-[#1e293b] transition-colors"></div>
                        <h4 class="text-[15px] font-bold text-[#1e293b] mb-1">Flowering (Est. 2 weeks)</h4>
                        <p class="text-[13px] text-on-surface-variant font-medium leading-relaxed">Transition to bloom fertilizers recommended for fruiting plots.</p>
                    </div>
                    
                    {{-- Step 3 --}}
                    <div class="relative opacity-60 hover:opacity-100 transition-opacity cursor-pointer group">
                        <div class="absolute -left-[30px] top-1 w-4 h-4 rounded-full bg-outline-variant ring-4 ring-surface group-hover:bg-[#1e293b] transition-colors"></div>
                        <h4 class="text-[15px] font-bold text-[#1e293b] mb-1">Peak Harvest (Est. 4 weeks)</h4>
                        <p class="text-[13px] text-on-surface-variant font-medium leading-relaxed">Prepare for major yield collection across all primary crops.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
