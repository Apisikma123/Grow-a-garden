@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight">Katalog Tanaman</h1>
            <p class="text-[14px] text-on-surface-variant">Kelola database tanaman global termasuk taksonomi, kondisi ideal, dan status.</p>
        </div>
        <button class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-5 py-2.5 rounded-lg hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Tambah Tanaman Baru
        </button>
    </div>

    {{-- Filters Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <button class="px-5 py-2 rounded-lg bg-primary text-on-primary font-bold text-[13px] shadow-sm">
                Semua Tanaman
            </button>
            <button class="px-5 py-2 rounded-lg border border-outline-variant/40 text-on-surface-variant font-medium text-[13px] hover:bg-surface-container-lowest transition-colors bg-white">
                Sayuran
            </button>
            <button class="px-5 py-2 rounded-lg border border-outline-variant/40 text-on-surface-variant font-medium text-[13px] hover:bg-surface-container-lowest transition-colors bg-white">
                Buah-buahan
            </button>
            <button class="px-5 py-2 rounded-lg border border-outline-variant/40 text-on-surface-variant font-medium text-[13px] hover:bg-surface-container-lowest transition-colors bg-white">
                Herbal
            </button>
            <button class="flex items-center gap-2 px-5 py-2 rounded-lg bg-surface-container-low text-on-surface-variant font-bold text-[13px] hover:bg-surface-container-high transition-colors ml-2">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Advanced Filters
            </button>
        </div>
        
        <div class="px-5 py-2 rounded-lg border border-outline-variant/20 bg-surface-container-lowest text-on-surface-variant font-medium text-[13px]">
            142 items found
        </div>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-surface-container-lowest rounded-[12px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
        
        {{-- Table --}}
        <div class="overflow-x-auto w-full no-scrollbar">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/20 text-left">
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Preview</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase w-[30%]">Plant Details</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Kategori</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Color Code</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    
                    {{-- Row 1: Heirloom Tomat --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high border border-outline-variant/20">
                                <img src="https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Tomat">
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[14px] font-bold text-on-surface mb-0.5">Heirloom Tomat</div>
                            <div class="text-[12px] italic text-on-surface-variant">Solanum lycopersicum</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface text-[11px] font-semibold text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">eco</span>
                                Vegetable
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full bg-[#E53A3A] shadow-sm"></div>
                                <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">#E53A3A</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#10b981]/10 text-[#006c49]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></div>
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button class="p-2 text-on-surface-variant hover:text-error hover:bg-error/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 2: Sweet Selasih --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high border border-outline-variant/20">
                                <img src="https://images.unsplash.com/photo-1615486171448-4fdcf58611eb?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Selasih">
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[14px] font-bold text-on-surface mb-0.5">Sweet Selasih</div>
                            <div class="text-[12px] italic text-on-surface-variant">Ocimum basilicum</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface text-[11px] font-semibold text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">spa</span>
                                Herb
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full bg-[#10b981] shadow-sm"></div>
                                <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">#10B981</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#10b981]/10 text-[#006c49]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></div>
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button class="p-2 text-on-surface-variant hover:text-error hover:bg-error/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 3: Bell Pepper --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high border border-outline-variant/20 opacity-60 grayscale">
                                <img src="https://images.unsplash.com/photo-1563514995963-3f114c000fc5?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Bell Pepper">
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[14px] font-bold text-on-surface-variant opacity-70 mb-0.5">Bell Pepper</div>
                            <div class="text-[12px] italic text-on-surface-variant opacity-60">Capsicum annuum</div>
                        </td>
                        <td class="py-4 px-6 opacity-70">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface text-[11px] font-semibold text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">eco</span>
                                Vegetable
                            </span>
                        </td>
                        <td class="py-4 px-6 opacity-70">
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full bg-[#9ca3af] shadow-sm"></div>
                                <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">#9CA3AF</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-surface-container-highest text-on-surface-variant">
                                <div class="w-1.5 h-1.5 rounded-full bg-outline-variant"></div>
                                Inactive
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button class="p-2 text-on-surface-variant hover:text-error hover:bg-error/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 4: Wortel --}}
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high border border-outline-variant/20">
                                <img src="https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Wortel">
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[14px] font-bold text-on-surface mb-0.5">Wortel</div>
                            <div class="text-[12px] italic text-on-surface-variant">Daucus carota</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface text-[11px] font-semibold text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">eco</span>
                                Vegetable
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full bg-[#f59e0b] shadow-sm"></div>
                                <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">#F59E0B</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#10b981]/10 text-[#006c49]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></div>
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button class="p-2 text-on-surface-variant hover:text-error hover:bg-error/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-5 flex justify-between items-center border-t border-outline-variant/20 bg-surface-container-lowest">
            <div class="text-[13px] text-on-surface-variant font-medium">
                Showing 1 to 4 of 142 entries
            </div>
            
            <div class="flex items-center gap-2 text-on-surface-variant">
                <button class="w-8 h-8 rounded-md flex items-center justify-center hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </button>
                <button class="w-8 h-8 rounded-md bg-[#006c49] text-white text-[13px] font-bold flex items-center justify-center shadow-sm">1</button>
                <button class="w-8 h-8 rounded-md text-[13px] font-medium hover:bg-surface-container-high transition-colors flex items-center justify-center">2</button>
                <button class="w-8 h-8 rounded-md text-[13px] font-medium hover:bg-surface-container-high transition-colors flex items-center justify-center">3</button>
                <span class="px-1">...</span>
                <button class="w-8 h-8 rounded-md flex items-center justify-center hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
