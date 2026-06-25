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

        {{-- Active Gardens --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Active Gardens</div>
                <div class="w-8 h-8 rounded-full bg-tertiary-container/30 text-tertiary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">local_florist</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">18,204</div>
            <div class="flex items-center gap-1 text-[11px] text-tertiary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +8% this month
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-tertiary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </div>

        {{-- Community Growth --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Community Growth</div>
                <div class="w-8 h-8 rounded-full bg-secondary-container/30 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">forum</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">42.5%</div>
            <div class="flex items-center gap-1 text-[11px] text-secondary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                High engagement
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
        </div>

        {{-- Total Harvests --}}
        <div class="bg-surface-container-lowest rounded-[20px] p-5 ambient-shadow border border-outline-variant/30 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="text-[10px] font-bold text-on-surface-variant tracking-wider uppercase">Total Harvests</div>
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">eco</span>
                </div>
            </div>
            <div class="text-[32px] font-black text-on-surface leading-tight mb-2 relative z-10">1.2M</div>
            <div class="flex items-center gap-1 text-[11px] text-primary font-bold relative z-10">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                +24% YoY
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

                    {{-- Mock Line using SVG --}}
                    <svg class="absolute inset-0 w-full h-full z-10" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <defs>
                            <linearGradient id="gradientArea" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"></stop>
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.05"></stop>
                            </linearGradient>
                        </defs>
                        <path d="M 0 80 Q 20 60, 30 50 T 50 30 T 70 40 T 100 10 L 100 100 L 0 100 Z" fill="url(#gradientArea)"></path>
                        <path d="M 0 80 Q 20 60, 30 50 T 50 30 T 70 40 T 100 10" fill="none" stroke="#006c49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        
                        {{-- Data points --}}
                        <circle cx="0" cy="80" r="2" fill="white" stroke="#006c49" stroke-width="1"></circle>
                        <circle cx="30" cy="50" r="2" fill="white" stroke="#006c49" stroke-width="1"></circle>
                        <circle cx="50" cy="30" r="2" fill="white" stroke="#006c49" stroke-width="1"></circle>
                        <circle cx="70" cy="40" r="2" fill="white" stroke="#006c49" stroke-width="1"></circle>
                        <circle cx="100" cy="10" r="2" fill="white" stroke="#006c49" stroke-width="1"></circle>
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

        {{-- Top Crops Bar Chart --}}
        <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <h3 class="text-[18px] font-bold text-on-surface mb-6">Top Crops</h3>
            
            <div class="flex-1 flex flex-col justify-between gap-4 pb-6 border-b border-outline-variant/20 relative">
                
                <div class="absolute bottom-6 left-16 right-0 top-0 flex justify-between pointer-events-none z-0 border-l border-outline-variant/20">
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                    <div class="w-px h-full bg-outline-variant/10"></div>
                </div>

                {{-- Bars --}}
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Tomato</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-[#006c49] rounded-r-md" style="width: 85%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Lettuce</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-[#10b981] rounded-r-md" style="width: 65%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Chili</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-secondary-container rounded-r-md" style="width: 50%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Spinach</div>
                    <div class="flex-1 h-4">
                        <div class="h-full bg-tertiary-container rounded-r-md" style="width: 40%;"></div>
                    </div>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-14 text-[11px] font-bold text-on-surface text-right">Basil</div>
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

    {{-- Bottom Row --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Recent Community Activity --}}
        <div class="xl:col-span-2 bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-[18px] font-bold text-on-surface">Recent Community Activity</h3>
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

        {{-- System Alerts --}}
        <div class="bg-surface-container-lowest rounded-[24px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col">
            <h3 class="text-[18px] font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">warning</span>
                System Alerts
            </h3>
            
            <div class="flex-1 space-y-3 mb-6">
                {{-- Alert 1 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-[16px] p-4 flex gap-3 shadow-sm hover:border-outline-variant/40 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-secondary-container/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[16px] text-secondary">router</span>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-on-surface mb-0.5">IoT Gateway Offline</div>
                        <div class="text-[11px] text-on-surface-variant leading-relaxed">Gateway 'WestCoast-04' missed 3 heartbeats. Attempting reconnect.</div>
                    </div>
                </div>

                {{-- Alert 2 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-[16px] p-4 flex gap-3 shadow-sm hover:border-outline-variant/40 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-primary-container/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[16px] text-primary">cloud_done</span>
                    </div>
                    <div>
                        <div class="text-[13px] font-bold text-on-surface mb-0.5">Database Backup Completed</div>
                        <div class="text-[11px] text-on-surface-variant leading-relaxed">Routine snapshot finished at 04:00 AM UTC. No errors.</div>
                    </div>
                </div>
            </div>

            <button class="w-full py-2.5 rounded-full border-2 border-outline-variant/30 text-on-surface font-bold text-[13px] hover:bg-outline-variant/10 active:scale-95 transition-all">
                View All Logs
            </button>
        </div>

    </div>
</div>
@endsection
