@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-1 mb-2">
        <h1 class="text-[28px] font-bold text-on-surface tracking-tight">User Management</h1>
        <p class="text-[14px] text-on-surface-variant">Oversee community members, manage access, and monitor engagement.</p>
    </div>

    {{-- Main Container --}}
    <div class="bg-surface-container-lowest rounded-[12px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="p-5 flex justify-between items-center border-b border-outline-variant/20">
            {{-- Filter Dropdown --}}
            <button class="flex items-center gap-2 bg-surface-container-high hover:bg-surface-container-highest transition-colors px-4 py-2 rounded-md text-[13px] font-semibold text-on-surface">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                All Membership Levels
                <span class="material-symbols-outlined text-[18px] ml-2">expand_more</span>
            </button>

            {{-- Export Button --}}
            <button class="flex items-center gap-2 px-4 py-2 rounded-md border border-secondary text-secondary font-bold text-[13px] hover:bg-secondary/5 transition-colors">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto w-full no-scrollbar">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/20 text-left">
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase w-[35%]">User</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Role</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Plots</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    
                    {{-- Row 1: Elara Vance --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary-container/40 text-primary-container font-bold text-[13px] flex items-center justify-center shrink-0">
                                    ES
                                </div>
                                <div>
                                    <div class="text-[14px] font-bold text-on-surface">Elara Vance</div>
                                    <div class="text-[12px] text-on-surface-variant">elara.v@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#10b981] text-white">Community Lead</span>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-on-surface-variant font-medium">
                            12 Active
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2 text-[13px] font-bold text-on-surface">
                                <div class="w-2 h-2 rounded-full bg-[#10b981]"></div>
                                Active
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                            </button>
                        </td>
                    </tr>

                    {{-- Row 2: Marcus Thorne --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop" class="w-10 h-10 rounded-full object-cover shrink-0">
                                <div>
                                    <div class="text-[14px] font-bold text-on-surface">Marcus Thorne</div>
                                    <div class="text-[12px] text-on-surface-variant">m.thorne@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-surface-container-highest text-on-surface-variant">Amateur</span>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-on-surface-variant font-medium">
                            2 Active
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2 text-[13px] font-bold text-on-surface">
                                <div class="w-2 h-2 rounded-full bg-[#10b981]"></div>
                                Active
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                            </button>
                        </td>
                    </tr>

                    {{-- Row 3: Sarah Reed --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#fd9e70]/30 text-[#944a23] font-bold text-[13px] flex items-center justify-center shrink-0">
                                    SR
                                </div>
                                <div>
                                    <div class="text-[14px] font-bold text-on-surface">Sarah Reed</div>
                                    <div class="text-[12px] text-on-surface-variant">s.reed@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#fd9e70]/20 text-[#944a23]">Pro</span>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-on-surface-variant font-medium">
                            8 Active
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2 text-[13px] font-bold text-on-surface-variant">
                                <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                                Inactive
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-5 flex justify-between items-center border-t border-outline-variant/20 bg-surface-container-lowest">
            <div class="text-[13px] text-on-surface-variant font-medium">
                Showing 1 to 3 of 45 entries
            </div>
            
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 rounded-md text-[13px] font-medium text-on-surface-variant hover:bg-surface-container-high transition-colors">Prev</button>
                <button class="w-8 h-8 rounded-md bg-[#006c49] text-white text-[13px] font-bold flex items-center justify-center shadow-sm">1</button>
                <button class="w-8 h-8 rounded-md text-[13px] font-medium text-on-surface hover:bg-surface-container-high transition-colors flex items-center justify-center">2</button>
                <button class="px-3 py-1.5 rounded-md text-[13px] font-medium text-on-surface hover:bg-surface-container-high transition-colors">Next</button>
            </div>
        </div>

    </div>

</div>
@endsection
