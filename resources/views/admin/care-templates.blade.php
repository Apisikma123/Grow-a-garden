@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-8">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
        <div class="flex flex-col gap-2">
            <h1 class="text-[32px] font-black text-on-surface tracking-tight">Growth Templates</h1>
            <p class="text-[15px] text-on-surface-variant max-w-[600px] leading-relaxed">Configure automated lifecycles and milestone alerts for botanical precision.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <button class="flex items-center gap-2 bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-[#10b981]">filter_list</span>
                Filter View
            </button>
            <button class="flex items-center gap-2 bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-[#10b981]">sort</span>
                Sort
            </button>
        </div>
    </div>

    {{-- Grid of Templates --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8">
        
        {{-- Card 1: Heirloom Tomato Mastery --}}
        <div class="bg-white rounded-[32px] p-7 ambient-shadow border border-outline-variant/20 flex flex-col gap-6 hover:ambient-shadow-lg transition-shadow">
            
            {{-- Top Info --}}
            <div class="flex gap-5 relative">
                <div class="w-20 h-20 shrink-0 rounded-[20px] overflow-hidden bg-surface-container-high shadow-sm border border-outline-variant/20">
                    <img src="https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Tomato">
                </div>
                <div class="flex-1 flex flex-col justify-center pt-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-[#10b981]/10 text-[#006c49]">NIGHTSHADE</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-surface-container-high text-on-surface-variant">85-100 DAYS</span>
                    </div>
                    <h3 class="text-[22px] font-bold text-on-surface leading-tight mb-1">Heirloom Tomato Mastery</h3>
                    <div class="flex items-center gap-1.5 text-[12px] text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-[14px]">history</span>
                        Last edited 2 days ago
                    </div>
                </div>
                <button class="absolute top-0 right-0 text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[24px]">more_horiz</span>
                </button>
            </div>

            {{-- Middle: Lifecycle Milestones --}}
            <div class="bg-surface-container-low/50 rounded-[24px] p-6 flex flex-col">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-[18px] text-[#10b981]">insights</span>
                    <span class="text-[11px] font-black tracking-widest text-on-surface uppercase">LIFECYCLE MILESTONES</span>
                </div>
                
                <div class="relative flex justify-between items-start w-full px-4">
                    {{-- Connecting Lines --}}
                    <div class="absolute top-[20px] left-[15%] right-[50%] h-[2px] bg-[#10b981] z-0"></div>
                    <div class="absolute top-[20px] left-[50%] right-[15%] h-[2px] bg-outline-variant/30 z-0"></div>
                    
                    {{-- Milestone 1 (Completed/Active) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3">
                        <div class="w-10 h-10 rounded-full bg-[#10b981] text-white flex items-center justify-center shadow-md ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">eco</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Germination</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 0-7</div>
                        </div>
                    </div>
                    
                    {{-- Milestone 2 (Current) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-[#10b981] text-[#10b981] flex items-center justify-center shadow-sm ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">psychiatry</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Seedling</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 7-21</div>
                        </div>
                    </div>
                    
                    {{-- Milestone 3 (Future) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3 opacity-50">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-outline-variant/40 text-on-surface-variant flex items-center justify-center shadow-sm ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">yard</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Vegetative</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 21+</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Actions --}}
            <div class="flex items-center justify-between pt-2">
                {{-- Avatars --}}
                <div class="flex items-center -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-primary-container text-on-primary-container text-[10px] font-bold flex items-center justify-center">TJ</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-200 text-blue-800 text-[10px] font-bold flex items-center justify-center">AM</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-surface-container-high text-on-surface-variant text-[10px] font-bold flex items-center justify-center">+4</div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    <button class="p-2 text-outline hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-surface-container-highest hover:bg-outline-variant/40 text-on-surface font-bold text-[13px] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                        Duplicate
                    </button>
                    <button class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#10b981]/15 text-[#006c49] hover:bg-[#10b981]/25 font-bold text-[13px] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Edit
                    </button>
                </div>
            </div>

        </div>

        {{-- Card 2: Thai Bird's Eye Chili --}}
        <div class="bg-white rounded-[32px] p-7 ambient-shadow border border-outline-variant/20 flex flex-col gap-6 hover:ambient-shadow-lg transition-shadow">
            
            {{-- Top Info --}}
            <div class="flex gap-5 relative">
                <div class="w-20 h-20 shrink-0 rounded-[20px] overflow-hidden bg-surface-container-high shadow-sm border border-outline-variant/20">
                    <img src="https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Chili">
                </div>
                <div class="flex-1 flex flex-col justify-center pt-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-[#fd9e70]/20 text-[#944a23]">CAPSICUM</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-surface-container-high text-on-surface-variant">90-120 DAYS</span>
                    </div>
                    <h3 class="text-[22px] font-bold text-on-surface leading-tight mb-1">Thai Bird's Eye Chili</h3>
                    <div class="flex items-center gap-1.5 text-[12px] text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-[14px]">history</span>
                        Last edited 1 week ago
                    </div>
                </div>
                <button class="absolute top-0 right-0 text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[24px]">more_horiz</span>
                </button>
            </div>

            {{-- Middle: Lifecycle Milestones --}}
            <div class="bg-surface-container-low/50 rounded-[24px] p-6 flex flex-col">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-[18px] text-[#10b981]">insights</span>
                    <span class="text-[11px] font-black tracking-widest text-on-surface uppercase">LIFECYCLE MILESTONES</span>
                </div>
                
                <div class="relative flex justify-between items-start w-full px-4">
                    {{-- Connecting Lines --}}
                    <div class="absolute top-[20px] left-[15%] right-[50%] h-[2px] bg-[#10b981] z-0"></div>
                    <div class="absolute top-[20px] left-[50%] right-[15%] h-[2px] bg-outline-variant/30 z-0"></div>
                    
                    {{-- Milestone 1 (Completed/Active) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3">
                        <div class="w-10 h-10 rounded-full bg-[#10b981] text-white flex items-center justify-center shadow-md ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">eco</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Germination</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 0-10</div>
                        </div>
                    </div>
                    
                    {{-- Milestone 2 (Current) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-[#10b981] text-[#10b981] flex items-center justify-center shadow-sm ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">psychiatry</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Seedling</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 10-28</div>
                        </div>
                    </div>
                    
                    {{-- Milestone 3 (Future) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3 opacity-50">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-outline-variant/40 text-on-surface-variant flex items-center justify-center shadow-sm ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">yard</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Vegetative</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 28+</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Actions --}}
            <div class="flex items-center justify-between pt-2">
                {{-- Avatars --}}
                <div class="flex items-center -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-200 text-blue-800 text-[10px] font-bold flex items-center justify-center">SL</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-pink-200 text-pink-800 text-[10px] font-bold flex items-center justify-center">KR</div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    <button class="p-2 text-outline hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-surface-container-highest hover:bg-outline-variant/40 text-on-surface font-bold text-[13px] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                        Duplicate
                    </button>
                    <button class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#10b981]/15 text-[#006c49] hover:bg-[#10b981]/25 font-bold text-[13px] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Edit
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

