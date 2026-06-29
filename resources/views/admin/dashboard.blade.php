@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Dashboard Overview Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div>
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight mb-1">Dashboard Overview</h1>
            <p class="text-[14px] text-on-surface-variant">Here's what's happening in the garden today.</p>
        </div>
        <button class="px-5 py-2 rounded-full border-2 border-secondary/40 text-secondary font-bold text-[14px] hover:bg-secondary/5 transition-colors">
            Export Report
        </button>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Users --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Users</div>
                <div class="w-8 h-8 rounded-full bg-primary-container/30 text-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px] text-primary">group</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">24,592</div>
            <div class="flex items-center gap-1 text-[11px] text-primary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +12% this month
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </div>

        {{-- Total Gardens --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Gardens (Kebun)</div>
                <div class="w-8 h-8 rounded-full bg-tertiary-container/30 text-tertiary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">yard</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">18,204</div>
            <div class="flex items-center gap-1 text-[11px] text-tertiary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +8% this month
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-tertiary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </div>

        {{-- Total Active Plants --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Active Plants</div>
                <div class="w-8 h-8 rounded-full bg-secondary-container/30 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">potted_plant</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">89,431</div>
            <div class="flex items-center gap-1 text-[11px] text-secondary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +15% this month
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </div>

        {{-- Today's Activities --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Today's Activities</div>
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">bolt</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">4,281</div>
            <div class="flex items-center gap-1 text-[11px] text-primary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +5% from yesterday
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary/5 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Growth Line Chart --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-[18px] font-bold text-on-surface">User Growth</h3>
                <div class="text-[12px] font-bold text-on-surface-variant border border-outline-variant/40 rounded-md px-3 py-1">Last 6 Months</div>
            </div>
            
            {{-- Mock Area Chart --}}
            <div class="relative h-[240px] w-full flex items-end">
                {{-- Y-Axis Labels --}}
                <div class="absolute left-0 top-0 bottom-6 flex flex-col justify-between text-[10px] text-on-surface-variant font-medium z-10 bg-surface-container-lowest pr-2">
                    <span>1,500</span>
                    <span>1,200</span>
                    <span>900</span>
                    <span>600</span>
                    <span>300</span>
                    <span>0</span>
                </div>

                {{-- Chart Area --}}
                <div class="ml-10 flex-1 h-[216px] relative border-b border-outline-variant/20 mb-6">
                    {{-- Grid Lines --}}
                    <div class="absolute inset-0 flex flex-col justify-between z-0">
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full border-t border-outline-variant/10"></div>
                        <div class="w-full"></div>
                    </div>

                    {{-- Mock Line using SVG (Optimized Aspect Ratio) --}}
                    <svg class="absolute inset-0 w-full h-full z-10" preserveAspectRatio="none" viewBox="0 0 1000 250">
                        <defs>
                            <linearGradient id="gradientArea" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"></stop>
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.05"></stop>
                            </linearGradient>
                        </defs>
                        <path d="M 0 200 Q 200 150, 300 125 T 500 75 T 700 100 T 1000 25 L 1000 250 L 0 250 Z" fill="url(#gradientArea)"></path>
                        <path d="M 0 200 Q 200 150, 300 125 T 500 75 T 700 100 T 1000 25" fill="none" stroke="#006c49" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"></path>
                        
                        {{-- Data points --}}
                        <circle cx="0" cy="200" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="300" cy="125" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="500" cy="75" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="700" cy="100" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                        <circle cx="1000" cy="25" r="6" fill="white" stroke="#006c49" stroke-width="3" vector-effect="non-scaling-stroke"></circle>
                    </svg>
                </div>

                {{-- X-Axis Labels --}}
                <div class="absolute bottom-0 left-10 right-0 flex justify-between text-[10px] text-on-surface-variant font-medium">
                    <span>Jan</span>
                    <span>Feb</span>
                    <span>Mar</span>
                    <span>Apr</span>
                    <span>May</span>
                    <span>Jun</span>
                </div>
            </div>
        </div>

        {{-- Tanaman Populer Bar Chart --}}
        <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <h3 class="text-[18px] font-bold text-on-surface mb-6">Tanaman Populer</h3>
            
            <div class="flex-1 flex flex-col justify-between gap-4 pb-6 border-b border-outline-variant/20 relative">
                
                <div class="absolute bottom-6 left-16 right-0 top-0 flex justify-between pointer-events-none z-0 border-l border-outline-variant/20">
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                </div>

                {{-- Bars --}}
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Tomat</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-[#006c49] rounded-r-md" style="width: 85%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Cabai</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-[#10b981] rounded-r-md" style="width: 65%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Bayam</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-secondary-container rounded-r-md" style="width: 50%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Kangkung</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-tertiary-container rounded-r-md" style="width: 40%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Sawi</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-inverse-primary rounded-r-md" style="width: 30%;"></div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between pl-16 pt-2 text-[10px] text-on-surface-variant font-medium">
                <span>0</span>
                <span>5,000</span>
                <span>10,000</span>
            </div>
        </div>
    </div>

    {{-- Row 3: Aktivitas & Rata-rata Umur --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Aktivitas Terbanyak --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <h3 class="text-[18px] font-bold text-on-surface mb-6">Aktivitas Terbanyak</h3>
            
            <div class="flex flex-col justify-center gap-5 flex-1">
                {{-- Item 1 --}}
                <div>
                    <div class="flex justify-between text-[13px] font-bold text-on-surface mb-2">
                        <span>Menyiram Tanaman</span>
                        <span class="text-primary">45%</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: 45%;"></div>
                    </div>
                </div>

                {{-- Item 2 --}}
                <div>
                    <div class="flex justify-between text-[13px] font-bold text-on-surface mb-2">
                        <span>Memberi Pupuk</span>
                        <span class="text-primary-container">30%</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full bg-primary-container rounded-full" style="width: 30%;"></div>
                    </div>
                </div>

                {{-- Item 3 --}}
                <div>
                    <div class="flex justify-between text-[13px] font-bold text-on-surface mb-2">
                        <span>Memanen</span>
                        <span class="text-secondary">15%</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full" style="width: 15%;"></div>
                    </div>
                </div>

                {{-- Item 4 --}}
                <div>
                    <div class="flex justify-between text-[13px] font-bold text-on-surface mb-2">
                        <span>Lainnya</span>
                        <span class="text-outline-variant">10%</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full bg-outline-variant rounded-full" style="width: 10%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rata-rata Umur Panen --}}
        <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col items-center justify-center text-center">
            <h3 class="text-[18px] font-bold text-on-surface mb-6 w-full text-left">Rata-rata Umur Panen</h3>
            
            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="text-[64px] font-black text-primary leading-none mb-2">42</div>
                <div class="text-[20px] font-bold text-on-surface mb-4">Hari</div>
                
                <div class="flex items-center gap-1 text-[12px] text-primary font-bold">
                    <span class="material-symbols-outlined text-[16px]">trending_down</span>
                    - 2 hari dari bulan lalu
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Aktivitas Hari Ini --}}
    <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-[18px] font-bold text-on-surface">Aktivitas Hari Ini</h3>
            <a href="#" class="text-[13px] font-bold text-primary hover:text-primary/80 transition-colors">View All</a>
        </div>

        <div class="overflow-x-auto w-full no-scrollbar">
            <table class="w-full min-w-[500px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 text-left">
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase px-2 w-1/2">User / Event</th>
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase px-2">Type</th>
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase px-2">Status</th>
                        <th class="pb-3 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right px-2">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    {{-- Row 1 --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <div class="text-[13px] font-bold text-on-surface">Sarah J. logged a harvest</div>
                                    <div class="text-[11px] text-on-surface-variant">Heirloom Tomatoes - 2.5kg</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-[13px] text-on-surface-variant">Milestone</td>
                        <td class="py-3 px-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#10b981]/10 text-[#10b981]">Verified</span>
                        </td>
                        <td class="py-3 px-2 text-[12px] text-on-surface-variant text-right">10 mins ago</td>
                    </tr>

                    {{-- Row 2 --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?w=100&h=100&fit=crop" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <div class="text-[13px] font-bold text-on-surface">UrbanRoots created a post</div>
                                    <div class="text-[11px] text-on-surface-variant">"Tips for balcony composting"</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-[13px] text-on-surface-variant">Community Post</td>
                        <td class="py-3 px-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-secondary-container/30 text-on-secondary-container">Trending</span>
                        </td>
                        <td class="py-3 px-2 text-[12px] text-on-surface-variant text-right">1 hr ago</td>
                    </tr>

                    {{-- Row 3 --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-outline-variant/30 text-on-surface font-bold text-[12px] flex items-center justify-center">M</div>
                                <div>
                                    <div class="text-[13px] font-bold text-on-surface">New Garden Registration</div>
                                    <div class="text-[11px] text-on-surface-variant">Community Plot #402, Portland</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-[13px] text-on-surface-variant">System</td>
                        <td class="py-3 px-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#10b981]/10 text-[#10b981]">Active</span>
                        </td>
                        <td class="py-3 px-2 text-[12px] text-on-surface-variant text-right">2 hrs ago</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
