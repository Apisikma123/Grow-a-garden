@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[32px] font-bold text-on-surface tracking-tight">Weather Rules Engine</h1>
            <p class="text-[14px] text-on-surface-variant">Automated watering adjustments based on macro climate conditions.</p>
        </div>
        <button class="flex items-center gap-2 bg-[#006c49] text-white font-bold text-[14px] px-5 py-2.5 rounded-full hover:bg-[#005236] active:scale-[0.98] transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Create Rule
        </button>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- Left Column: Active Adjustment Rules --}}
        <div class="xl:col-span-2 flex flex-col gap-5">
            {{-- Section Header --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant">tune</span>
                    <h2 class="text-[18px] font-bold text-on-surface">Active Adjustment Rules</h2>
                </div>
                <div class="px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[11px] font-bold">
                    3 Rules Running
                </div>
            </div>

            {{-- Rules Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Rule 1: Rainy Season Mode --}}
                <div class="bg-surface-container-lowest rounded-[20px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:ambient-shadow-lg transition-shadow">
                    {{-- Top --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-surface text-[24px]">cloud</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#10b981]/10 text-[#006c49] text-[11px] font-bold">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></div>
                            Active
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <h3 class="text-[20px] font-bold text-on-surface mb-2">Rainy Season Mode</h3>
                    <p class="text-[13px] text-on-surface-variant leading-relaxed flex-1 mb-6">
                        Reduces overall watering schedules globally to prevent root rot during monsoon periods.
                    </p>

                    {{-- Modifier --}}
                    <div class="bg-surface-container-low rounded-[12px] p-4 flex justify-between items-center">
                        <div class="text-[11px] font-bold text-on-surface-variant tracking-wider uppercase leading-tight">
                            Watering<br>Modifier
                        </div>
                        <div class="text-[40px] font-black text-[#006c49] leading-none tracking-tighter">
                            -30%
                        </div>
                    </div>
                </div>

                {{-- Rule 2: Dry Season Surge --}}
                <div class="bg-surface-container-lowest rounded-[20px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:ambient-shadow-lg transition-shadow">
                    {{-- Top --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-surface text-[24px]">sunny</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#10b981]/10 text-[#006c49] text-[11px] font-bold">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></div>
                            Active
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <h3 class="text-[20px] font-bold text-on-surface mb-2">Dry Season Surge</h3>
                    <p class="text-[13px] text-on-surface-variant leading-relaxed flex-1 mb-6">
                        Increases baseline hydration reminders to compensate for high evaporation rates.
                    </p>

                    {{-- Modifier --}}
                    <div class="bg-surface-container-low rounded-[12px] p-4 flex justify-between items-center">
                        <div class="text-[11px] font-bold text-on-surface-variant tracking-wider uppercase leading-tight">
                            Watering<br>Modifier
                        </div>
                        <div class="text-[40px] font-black text-[#944a23] leading-none tracking-tighter">
                            +50%
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Right Column: Regional Data Map --}}
        <div class="xl:col-span-1 flex flex-col gap-5">
            {{-- Section Header --}}
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-on-surface-variant">calendar_today</span>
                <h2 class="text-[18px] font-bold text-on-surface">Regional Data Map</h2>
            </div>

            {{-- Map Card --}}
            <div class="bg-surface-container-lowest rounded-[20px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
                
                {{-- Top Image (Topographic placeholder) --}}
                <div class="h-32 w-full bg-[#d2d6cd] relative overflow-hidden">
                    {{-- We simulate a topo map with CSS radial gradients if an image isn't perfect, but a placeholder image is better --}}
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=500&h=300&fit=crop" class="w-full h-full object-cover mix-blend-luminosity opacity-40" alt="Map">
                </div>

                {{-- Content --}}
                <div class="p-6 flex flex-col gap-5">
                    <p class="text-[13px] text-on-surface-variant leading-relaxed">
                        Calendar rules applied automatically based on user's selected province.
                    </p>

                    {{-- Region List --}}
                    <div class="flex flex-col gap-4">
                        {{-- Region 1 --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-[13px] font-bold text-on-surface">Northern Highlands</div>
                                <div class="text-[11px] text-on-surface-variant">Jun - Aug</div>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#10b981]/10 text-[#006c49] text-[11px] font-bold">
                                <span class="material-symbols-outlined text-[14px]">water_drop</span>
                                Rainy
                            </div>
                        </div>

                        {{-- Region 2 --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-[13px] font-bold text-on-surface">Central Plains</div>
                                <div class="text-[11px] text-on-surface-variant">Mar - May</div>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#944a23]/10 text-[#944a23] text-[11px] font-bold">
                                <span class="material-symbols-outlined text-[14px]">sunny</span>
                                Dry
                            </div>
                        </div>

                        {{-- Region 3 --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-[13px] font-bold text-on-surface">Coastal Belt</div>
                                <div class="text-[11px] text-on-surface-variant">Sep - Nov</div>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#006c49]/10 text-[#006c49] text-[11px] font-bold">
                                <span class="material-symbols-outlined text-[14px]">air</span>
                                Moderate
                            </div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <button class="mt-2 w-full py-2.5 rounded-[12px] border border-outline-variant/50 text-[#006c49] font-bold text-[13px] hover:bg-[#006c49]/5 transition-colors flex items-center justify-center gap-2">
                        View Full Calendar
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
