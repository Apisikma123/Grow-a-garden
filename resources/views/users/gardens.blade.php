@extends('layouts.dashboard')

@section('title', 'Kebun Saya — Grow a Garden')
@section('description', 'Kelola kebun dan tanaman Anda.')

@php
    $authUser = auth()->user();
    $userRole = $authUser ? $authUser->role : 'free';
    $planName = $authUser ? $authUser->planName() : 'Bibit (Gratis)';
    $maxGardens = $authUser ? $authUser->maxGardens() : 1;
    $maxPlants = $authUser ? $authUser->maxPlants() : 10;
@endphp

@section('dashboard-content')
<div class="flex flex-col gap-6 pb-28 sm:pb-10 w-full" id="gardens-app" style="width: 100% !important;">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight leading-tight">Kebun Saya</h1>
                <span class="text-[12px] font-extrabold px-3 py-1 rounded-full bg-primary/10 text-primary border border-primary/20">
                    Paket {{ $planName }} (Maks. {{ $maxGardens }} Kebun)
                </span>
            </div>
            <p class="text-[14px] text-on-surface-variant mt-1">Kelola kebun dan tanaman Anda di satu tempat.</p>
        </div>
        <button type="button" onclick="GardenApp.openAddGardenModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-5 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Tambah Kebun
        </button>
    </div>

    {{-- Loading State --}}
    <div id="gardens-loading" class="w-full flex items-center justify-center py-20" style="width: 100% !important;">
        <div class="flex flex-col items-center gap-3 text-center">
            <span class="material-symbols-outlined text-[48px] text-primary animate-spin">progress_activity</span>
            <span class="text-[14px] text-on-surface-variant font-medium">Memuat kebun Anda...</span>
        </div>
    </div>

    {{-- Empty State --}}
    <div id="gardens-empty" style="display: none; width: 100% !important;" class="w-full bg-surface rounded-[24px] p-8 sm:p-12 ambient-shadow flex flex-col items-center justify-center gap-6 text-center border border-outline-variant/20">
        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[44px] text-primary">yard</span>
        </div>
        <div class="text-center w-full min-w-full max-w-md mx-auto self-stretch" style="width: 100% !important; min-width: 100% !important; text-align: center !important;">
            <h3 class="text-[20px] font-bold text-on-surface mb-2" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important;">Belum ada kebun</h3>
            <p class="text-[14px] text-on-surface-variant leading-relaxed" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important;">Mulai dengan membuat kebun pertama Anda, lalu tambahkan tanaman dari katalog kami.</p>
        </div>
        <button type="button" onclick="GardenApp.openAddGardenModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-6 py-3 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Buat Kebun Pertama
        </button>
    </div>

    {{-- Main Content: Garden List + Detail --}}
    <div id="gardens-content" style="display: none; width: 100% !important;" class="w-full flex flex-col lg:flex-row gap-6">

        {{-- Left: Garden List --}}
        <div class="w-full lg:w-[320px] shrink-0 flex flex-col gap-3">
            <div id="garden-list" class="flex flex-col gap-3 w-full">
                {{-- Populated by JS --}}
            </div>
        </div>

        {{-- Right: Garden Detail --}}
        <div class="flex-1 min-w-0 w-full" style="width: 100% !important;">
            {{-- No garden selected state --}}
            <div id="garden-detail-empty" style="display: none; width: 100% !important;" class="w-full flex flex-col items-center justify-center py-20 bg-surface rounded-[24px] ambient-shadow text-center">
                <span class="material-symbols-outlined text-[48px] text-outline-variant mb-4">arrow_back</span>
                <p class="text-[16px] text-on-surface-variant font-medium">Pilih kebun dari daftar di samping</p>
            </div>

            {{-- Garden detail panel --}}
            <div id="garden-detail" style="display: none; width: 100% !important;" class="w-full flex flex-col gap-6">



                {{-- Detail Header --}}
                <div class="bg-surface rounded-[24px] p-6 ambient-shadow w-full" style="width: 100% !important;">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0" id="detail-garden-icon-box">
                                <span class="material-symbols-outlined text-[28px] text-primary" id="detail-garden-icon">yard</span>
                            </div>
                            <div class="min-w-0">
                                <h2 id="detail-garden-name" class="text-[22px] font-bold text-on-surface truncate"></h2>
                                <p id="detail-garden-location" class="text-[13px] text-on-surface-variant flex items-center gap-1 mt-0.5 truncate">
                                    <span class="material-symbols-outlined text-[14px] shrink-0">location_on</span>
                                    <span class="truncate"></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" id="add-plant-btn" onclick="GardenApp.openAddPlantModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[13px] px-4 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                Tambah Tanaman
                            </button>
                            <button type="button" onclick="GardenApp.deleteCurrentGarden()" class="p-2.5 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-full transition-colors" title="Hapus Kebun">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Plant Content Section (Supports Glassmorphism Blur Overlay when garden is locked) --}}
                <div id="plants-wrapper" class="relative w-full min-h-[300px] rounded-[24px]">

                    {{-- Glassmorphism Blur Overlay for Locked Gardens --}}
                    <div id="locked-plants-overlay" style="display: none; width: 100% !important;" class="absolute inset-0 z-20 backdrop-blur-md bg-surface/90 rounded-[24px] border border-error/30 p-6 sm:p-10 flex flex-col items-center justify-center text-center shadow-xl">
                        <div class="w-16 h-16 rounded-full bg-error-container text-on-error-container flex items-center justify-center shadow-md mb-4 shrink-0 border border-error/30">
                            <span class="material-symbols-outlined text-[36px]">lock</span>
                        </div>
                        <div class="text-center w-full min-w-full max-w-md mx-auto self-stretch flex flex-col items-center" style="width: 100% !important; min-width: 100% !important; text-align: center !important;">
                            <h3 class="text-[20px] font-extrabold text-on-surface mb-2" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">Akses Kebun Terkunci</h3>
                            <p class="text-[13.5px] text-on-surface-variant leading-relaxed mb-6" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">
                                Kebun ini berada di luar kuota paket <strong class="font-bold text-error">{{ $planName }}</strong> Anda (Maksimal {{ $maxGardens }} Kebun). Upgrade paket Anda untuk membuka kembali seluruh isi kebun ini.
                            </p>
                        </div>
                        <a href="/settings#subscription" class="bg-error hover:bg-error/90 text-on-error font-extrabold text-[14px] px-7 py-3 rounded-full shadow-md active:scale-95 transition-all flex items-center gap-2 cursor-pointer shrink-0">
                            <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                            Upgrade Paket Sekarang
                        </a>
                    </div>

                    {{-- Plants Loading --}}
                    <div id="plants-loading" class="w-full flex items-center justify-center py-16" style="width: 100% !important;">
                        <span class="material-symbols-outlined text-[36px] text-primary animate-spin">progress_activity</span>
                    </div>

                    {{-- Plants Empty --}}
                    <div id="plants-empty" style="display: none; width: 100% !important;" class="w-full bg-surface rounded-[24px] p-8 sm:p-10 ambient-shadow flex flex-col items-center justify-center gap-4 text-center border border-outline-variant/20">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[32px] text-primary">potted_plant</span>
                        </div>
                        <div class="text-center w-full min-w-full max-w-md mx-auto self-stretch" style="width: 100% !important; min-width: 100% !important; text-align: center !important;">
                            <h3 class="text-[18px] font-bold text-on-surface mb-1" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">Belum ada tanaman di kebun ini</h3>
                            <p class="text-[13px] text-on-surface-variant leading-relaxed" style="width: 100% !important; text-align: center !important; display: block !important; white-space: normal !important; word-break: normal !important;">Tambahkan tanaman pertama dari katalog pustaka tanaman kami.</p>
                        </div>
                        <button type="button" onclick="GardenApp.openAddPlantModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Tambah Tanaman Pertama
                        </button>
                    </div>

                    {{-- Plants Grid --}}
                    <div id="plants-grid" style="display: none; width: 100% !important;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                        {{-- Populated by JS --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Add Garden Modal ── --}}
<div id="add-garden-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="GardenApp.closeAddGardenModal()"></div>
    <div class="w-full min-h-screen px-4 py-8 flex flex-col items-center justify-center pointer-events-none">
        <div class="w-full shrink-0 min-w-full sm:min-w-[400px] max-w-md mx-auto bg-surface rounded-[28px] p-6 sm:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative self-stretch" style="white-space: normal; word-break: normal;">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[22px]">yard</span>
                    </div>
                    <h3 class="text-[20px] font-bold text-on-surface">Tambah Kebun Baru</h3>
                </div>
                <button type="button" onclick="GardenApp.closeAddGardenModal()" class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="add-garden-form" onsubmit="GardenApp.submitAddGarden(event)" class="flex flex-col gap-5">
                <div>
                    <label class="block text-[13px] font-bold text-on-surface mb-2">Nama Kebun <span class="text-error">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Kebun Belakang Rumah, Balkon Apt..."
                        class="w-full px-4 py-3 rounded-xl border border-outline-variant/50 bg-surface text-on-surface text-[14px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-bold text-on-surface mb-2">Lokasi <span class="text-[12px] font-normal text-on-surface-variant">(Opsional)</span></label>
                    <input type="text" name="location" placeholder="Contoh: Bandung, Jawa Barat"
                        class="w-full px-4 py-3 rounded-xl border border-outline-variant/50 bg-surface text-on-surface text-[14px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="GardenApp.closeAddGardenModal()" class="px-5 py-2.5 rounded-full text-[14px] font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="add-garden-submit" class="bg-primary text-on-primary font-bold text-[14px] px-6 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                        Buat Kebun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Add Plant Modal (Catalog Picker) ── --}}
<div id="add-plant-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="GardenApp.closeAddPlantModal()"></div>
    
    {{-- Scrollable Wrapper --}}
    <div class="fixed inset-0 z-10 overflow-y-auto overscroll-contain">
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 pointer-events-none">
            
            {{-- Modal Card --}}
            <div class="w-full max-w-2xl bg-surface rounded-t-[28px] sm:rounded-[28px] flex flex-col pointer-events-auto relative shadow-2xl sm:ambient-shadow-lg sm:border border-outline-variant/30" style="white-space: normal; word-break: normal;">
                
                <div class="p-5 pb-24 sm:p-7 sm:pb-7 flex flex-col w-full">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4 sm:mb-6 shrink-0 w-full">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[22px]">potted_plant</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-[18px] sm:text-[20px] font-bold text-on-surface leading-tight truncate">Pilih Tanaman</h3>
                                <p class="text-[11px] sm:text-[12px] text-on-surface-variant truncate">Pilih tanaman dari katalog.</p>
                            </div>
                        </div>
                        <button type="button" onclick="GardenApp.closeAddPlantModal()" class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors shrink-0">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    {{-- Search & Filters --}}
                    <div class="flex flex-col gap-3 sm:gap-4 mb-3 sm:mb-4 shrink-0 w-full">
                        <div class="relative w-full">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                            <input type="text" id="template-search" oninput="GardenApp.filterTemplates(this.value)" placeholder="Cari nama tanaman (contoh: Cabai, Tomat...)"
                                class="w-full pl-10 pr-4 py-2.5 rounded-full border border-outline-variant/50 bg-surface text-on-surface text-[13px] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                        <div id="category-tabs" class="flex gap-2 overflow-x-auto no-scrollbar pb-1 touch-pan-x w-full">
                            {{-- Category tabs injected by JS --}}
                        </div>
                    </div>

                    {{-- Template Grid (Grows naturally, no scroll bar here) --}}
                    <div id="template-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full pb-4">
                        {{-- Templates injected by JS --}}
                    </div>

                    {{-- Form Actions --}}
                    <form id="add-plant-form" onsubmit="GardenApp.submitAddPlant(event)" class="mt-2 pt-4 border-t border-outline-variant/30 shrink-0 flex flex-col gap-3 w-full">
                        <div class="flex items-center justify-between gap-3.5 w-full">
                            {{-- Summary Badge --}}
                            <span id="batch-summary-badge" class="text-[12px] font-bold px-3 py-1.5 rounded-full bg-primary/10 text-primary border border-primary/20 whitespace-nowrap shrink-0">
                                Terpilih: 0
                            </span>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" onclick="GardenApp.closeAddPlantModal()" class="px-4 py-2 rounded-full text-[13px] font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors whitespace-nowrap shrink-0">
                                    Batal
                                </button>
                                <button type="submit" id="add-plant-submit" disabled class="bg-primary text-on-primary font-bold text-[13px] px-6 py-2 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-xs disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap shrink-0">
                                    Tanam
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Plant Detail Modal ── --}}
<div id="plant-detail-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="GardenApp.closePlantDetailModal()"></div>
    <div class="w-full min-h-screen px-4 py-8 flex flex-col items-center justify-center pointer-events-none">
        <div class="w-full shrink-0 min-w-full sm:min-w-[500px] max-w-lg mx-auto bg-surface rounded-[28px] p-6 sm:p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative flex flex-col gap-6 self-stretch" style="white-space: normal; word-break: normal;">
            {{-- Modal Header --}}
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div id="detail-plant-icon-box" class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0">
                        <span id="detail-plant-icon" class="material-symbols-outlined text-[32px]"></span>
                    </div>
                    <div>
                        <h3 id="detail-plant-name" class="text-[22px] font-bold text-on-surface leading-tight"></h3>
                        <p id="detail-plant-scientific" class="text-[13px] text-on-surface-variant italic"></p>
                    </div>
                </div>
                <button type="button" onclick="GardenApp.closePlantDetailModal()" class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            {{-- Badges Row --}}
            <div class="flex items-center gap-2 flex-wrap">
                <span id="detail-plant-stage-badge" class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full"></span>
                <span id="detail-plant-status-badge" class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full"></span>
                <span id="detail-plant-category-badge" class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant"></span>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-surface-container-low p-4 rounded-2xl flex flex-col">
                    <span class="text-[11px] text-on-surface-variant font-medium">Hari Setelah Tanam</span>
                    <span id="detail-plant-hst" class="text-[24px] font-black text-on-surface mt-1"></span>
                </div>
                <div class="bg-surface-container-low p-4 rounded-2xl flex flex-col">
                    <span class="text-[11px] text-on-surface-variant font-medium">Estimasi Panen</span>
                    <span id="detail-plant-harvest" class="text-[18px] font-bold text-primary mt-2"></span>
                </div>
            </div>

            {{-- Requirements --}}
            <div id="detail-plant-reqs" class="bg-surface-container-low p-4 rounded-2xl flex flex-col gap-3">
                <h4 class="text-[13px] font-bold text-on-surface uppercase tracking-wider">Kebutuhan Perawatan</h4>
                <div class="flex items-center gap-3 text-[13px] text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">water_drop</span>
                    <span id="detail-plant-water"></span>
                </div>
                <div class="flex items-center gap-3 text-[13px] text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-secondary">wb_sunny</span>
                    <span id="detail-plant-sunlight"></span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-2 border-t border-outline-variant/30">
                <a id="detail-plant-calendar-link" href="/growth-calendar" class="flex items-center gap-2 text-primary font-bold text-[13px] hover:underline">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    Lihat di Kalender
                </a>
                <button type="button" onclick="GardenApp.deleteCurrentPlant()" class="flex items-center gap-2 text-error font-bold text-[13px] px-4 py-2 rounded-xl hover:bg-error/10 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Hapus Tanaman
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.USER_PLAN_CONFIG = {
    role: @json($userRole),
    planName: @json($planName),
    maxGardens: {{ $maxGardens }},
    maxPlants: {{ $maxPlants >= 99999 ? 999999 : $maxPlants }}
};

window.GardenApp = (() => {
    let gardens = [];
    let selectedGardenId = null;
    let plants = [];
    let templateCategories = [];
    let selectedTemplateId = null;
    let currentPlantDetail = null;

    const STAGE_CONFIG = {
        'SEED':        { label: 'Benih',       color: '#78a994', icon: 'grain' },
        'GERMINATION': { label: 'Germinasi',    color: '#10b981', icon: 'spa' },
        'SEEDLING':    { label: 'Persemaian',   color: '#059669', icon: 'grass' },
        'VEGETATIVE':  { label: 'Vegetatif',    color: '#047857', icon: 'eco' },
        'FLOWERING':   { label: 'Berbunga',     color: '#944a23', icon: 'local_florist' },
        'FRUITING':    { label: 'Berbuah',      color: '#1b6b51', icon: 'nutrition' },
        'HARVEST':     { label: 'Panen',        color: '#006c49', icon: 'agriculture' },
        'FINISHED':    { label: 'Selesai',      color: '#6b7280', icon: 'check_circle' },
        'DEAD':        { label: 'Mati',         color: '#ba1a1a', icon: 'dangerous' },
    };

    const STATUS_CONFIG = {
        'ACTIVE':     { label: 'Aktif',       bg: 'bg-[#10b981]/10', text: 'text-[#006c49]' },
        'PRODUCTIVE': { label: 'Produktif',   bg: 'bg-[#944a23]/10', text: 'text-[#944a23]' },
        'HARVESTING': { label: 'Panen',       bg: 'bg-[#006c49]/10', text: 'text-[#006c49]' },
        'FINISHED':   { label: 'Selesai',     bg: 'bg-[#6b7280]/10', text: 'text-[#374151]' },
        'DEAD':       { label: 'Mati',        bg: 'bg-[#ba1a1a]/10', text: 'text-[#ba1a1a]' },
    };

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
    function escAttr(str) {
        return escHtml(str);
    }

    // ── Init ──
    async function init() {
        // Absolute fallback: guarantee spinner hides after 2.5s regardless of network delays
        setTimeout(() => {
            const loading = document.getElementById('gardens-loading');
            if (loading && loading.style.display !== 'none') {
                loading.style.display = 'none';
                const empty = document.getElementById('gardens-empty');
                const content = document.getElementById('gardens-content');
                if (gardens && gardens.length > 0 && content) {
                    content.style.display = 'flex';
                } else if (empty) {
                    empty.style.display = 'flex';
                }
            }
        }, 2500);

        await loadGardens();
        const dateInput = document.getElementById('planted-date-input');
        if (dateInput) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
    }

    // ── API Helpers ──
    async function api(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000);

        const defaults = {
            signal: controller.signal,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        };
        try {
            const resp = await fetch(url, { ...defaults, ...options });
            clearTimeout(timeoutId);
            if (!resp.ok) {
                const err = await resp.json().catch(() => ({}));
                throw new Error(err.error || err.message || `HTTP ${resp.status}`);
            }
            return await resp.json();
        } catch (err) {
            clearTimeout(timeoutId);
            if (err.name === 'AbortError') {
                throw new Error('Waktu tunggu koneksi habis. Silakan muat ulang.');
            }
            throw err;
        }
    }

    // ── Gardens ──
    async function loadGardens() {
        const loading = document.getElementById('gardens-loading');
        const empty = document.getElementById('gardens-empty');
        try {
            gardens = await api('/api/gardens');
            renderGardens();
        } catch (e) {
            console.error('Failed to load gardens:', e);
            if (loading) loading.style.display = 'none';
            if (empty) {
                empty.style.display = 'flex';
                empty.style.width = '100%';
            }
            if (window.Alert && Alert.modal) {
                Alert.modal.error('Gagal Memuat Halaman', e.message || 'Terjadi kesalahan jaringan.');
            }
        }
    }

    function renderGardens() {
        const loading = document.getElementById('gardens-loading');
        const empty = document.getElementById('gardens-empty');
        const content = document.getElementById('gardens-content');
        const list = document.getElementById('garden-list');

        loading.style.display = 'none';

        if (gardens.length === 0) {
            empty.style.display = 'flex';
            empty.style.width = '100%';
            content.style.display = 'none';
            return;
        }

        empty.style.display = 'none';
        content.style.display = 'flex';
        content.style.width = '100%';

        list.innerHTML = gardens.map((g, idx) => {
            const isLocked = idx >= USER_PLAN_CONFIG.maxGardens;
            return `
            <button type="button" onclick="GardenApp.selectGarden(${g.id})"
                class="garden-card w-full text-left bg-surface rounded-[20px] p-4 sm:p-5 ambient-shadow hover:-translate-y-0.5 hover:ambient-shadow-lg transition-all duration-200 border-2 ${selectedGardenId === g.id ? (isLocked ? 'border-error bg-error-container/20' : 'border-[#006c49]') : 'border-transparent'} ${isLocked ? 'bg-surface-container-low opacity-80' : ''}" data-garden-id="${g.id}">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl ${selectedGardenId === g.id ? (isLocked ? 'bg-error text-on-error' : 'bg-primary text-on-primary') : (isLocked ? 'bg-error/15 text-error' : 'bg-primary/10 text-primary')} flex items-center justify-center shrink-0 transition-colors">
                        <span class="material-symbols-outlined text-[22px] sm:text-[24px]">${isLocked ? 'lock' : 'yard'}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[14px] sm:text-[15px] font-bold ${isLocked ? 'text-on-surface-variant line-through opacity-70' : 'text-on-surface'} truncate">${escHtml(g.name)}</h3>
                        ${g.location_name ? `<p class="text-[11px] sm:text-[12px] text-on-surface-variant truncate flex items-center gap-1 mt-0.5"><span class="material-symbols-outlined text-[12px]">location_on</span>${escHtml(g.location_name)}</p>` : ''}
                        ${isLocked ? `<div class="mt-1"><span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container border border-error/30 whitespace-nowrap inline-block">Terkunci</span></div>` : ''}
                    </div>
                    <span class="material-symbols-outlined text-[20px] ${isLocked ? 'text-error' : 'text-on-surface-variant'}">chevron_right</span>
                </div>
            </button>
            `;
        }).join('');

        if (selectedGardenId && gardens.find(g => g.id === selectedGardenId)) {
            selectGarden(selectedGardenId);
        } else if (gardens.length > 0) {
            selectGarden(gardens[0].id);
        }
    }

    async function selectGarden(gardenId) {
        selectedGardenId = gardenId;
        const gardenIdx = gardens.findIndex(g => g.id === gardenId);
        const garden = gardens[gardenIdx];
        if (!garden) return;

        const isGardenLocked = gardenIdx >= USER_PLAN_CONFIG.maxGardens;

        // Show/hide blur overlay for locked garden
        const lockOverlay = document.getElementById('locked-plants-overlay');
        if (lockOverlay) {
            lockOverlay.style.display = isGardenLocked ? 'flex' : 'none';
        }

        // Toggle header action button
        const addBtn = document.getElementById('add-plant-btn');
        if (addBtn) {
            if (isGardenLocked) {
                addBtn.className = 'flex items-center gap-2 bg-error text-on-error font-bold text-[13px] px-4 py-2.5 rounded-full hover:bg-error/90 active:scale-95 transition-all shadow-sm cursor-pointer';
                addBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">workspace_premium</span> Upgrade Paket';
                addBtn.onclick = () => window.location.href = '/settings#subscription';
            } else {
                addBtn.className = 'flex items-center gap-2 bg-primary text-on-primary font-bold text-[13px] px-4 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm cursor-pointer';
                addBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">add</span> Tambah Tanaman';
                addBtn.onclick = () => GardenApp.openAddPlantModal();
            }
        }

        // Update card highlights
        document.querySelectorAll('.garden-card').forEach(card => {
            const id = parseInt(card.dataset.gardenId);
            const cardIdx = gardens.findIndex(g => g.id === id);
            const cardIsLocked = cardIdx >= USER_PLAN_CONFIG.maxGardens;
            const icon = card.querySelector('.w-11, .w-12');
            
            if (id === gardenId) {
                card.classList.remove('border-transparent');
                card.classList.add(cardIsLocked ? 'border-error' : 'border-[#006c49]');
                if (icon) {
                    icon.className = `w-11 h-11 sm:w-12 sm:h-12 rounded-xl ${cardIsLocked ? 'bg-error text-on-error' : 'bg-primary text-on-primary'} flex items-center justify-center shrink-0 transition-colors relative`;
                }
            } else {
                card.classList.add('border-transparent');
                card.classList.remove('border-[#006c49]', 'border-error');
                if (icon) {
                    icon.className = `w-11 h-11 sm:w-12 sm:h-12 rounded-xl ${cardIsLocked ? 'bg-error/15 text-error' : 'bg-primary/10 text-primary'} flex items-center justify-center shrink-0 transition-colors relative`;
                }
            }
        });

        // Show detail panel
        document.getElementById('garden-detail-empty').style.display = 'none';
        const detailPanel = document.getElementById('garden-detail');
        detailPanel.style.display = 'flex';
        detailPanel.style.width = '100%';

        document.getElementById('detail-garden-name').textContent = garden.name;
        
        const iconBox = document.getElementById('detail-garden-icon-box');
        const iconEl = document.getElementById('detail-garden-icon');
        if (iconBox && iconEl) {
            if (isGardenLocked) {
                iconBox.className = 'w-14 h-14 rounded-2xl bg-error/15 text-error flex items-center justify-center shrink-0';
                iconEl.textContent = 'lock';
            } else {
                iconBox.className = 'w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0';
                iconEl.textContent = 'yard';
            }
        }

        const locEl = document.getElementById('detail-garden-location');
        if (garden.location_name) {
            locEl.classList.remove('hidden');
            locEl.querySelector('span:last-child').textContent = garden.location_name;
        } else {
            locEl.classList.add('hidden');
        }

        // Load plants for this garden
        await loadPlants(gardenId, isGardenLocked);
    }

    // ── Plants ──
    async function loadPlants(gardenId, isGardenLocked = false) {
        const loading = document.getElementById('plants-loading');
        const empty = document.getElementById('plants-empty');
        const grid = document.getElementById('plants-grid');

        loading.style.display = 'flex';
        loading.style.width = '100%';
        empty.style.display = 'none';
        grid.style.display = 'none';

        try {
            plants = await api(`/api/gardens/${gardenId}/plants`);
            loading.style.display = 'none';

            if (plants.length === 0) {
                empty.style.display = 'flex';
                empty.style.width = '100%';
                return;
            }

            grid.style.display = 'grid';
            grid.style.width = '100%';
            grid.innerHTML = plants.map((p, pIdx) => {
                const isPlantLocked = isGardenLocked || (pIdx >= USER_PLAN_CONFIG.maxPlants);
                const stage = STAGE_CONFIG[p.stage] || STAGE_CONFIG['SEED'];
                const status = STATUS_CONFIG[p.status] || STATUS_CONFIG['ACTIVE'];
                const harvestText = p.estimated_harvest_days !== null
                    ? (p.estimated_harvest_days <= 0 ? 'Siap panen!' : `${p.estimated_harvest_days} hari lagi`)
                    : '-';
                const harvestColor = p.estimated_harvest_days !== null && p.estimated_harvest_days <= 0 ? 'text-[#006c49]' : 'text-on-surface-variant';

                const onclickAttr = isPlantLocked
                    ? `GardenApp.showPlantLockedAlert('${escAttr(p.template_name)}')`
                    : `GardenApp.openPlantDetail(${p.id})`;

                return `
                    <button type="button" onclick="${onclickAttr}"
                        class="bg-surface rounded-[20px] p-5 ambient-shadow text-left hover:-translate-y-1 hover:ambient-shadow-lg transition-all duration-200 flex flex-col gap-3 group relative ${isPlantLocked ? 'opacity-75 bg-surface-container-low border border-error/30' : ''}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 relative" style="background: ${isPlantLocked ? '#ffdad6' : stage.color + '15'};">
                                    <span class="material-symbols-outlined text-[22px]" style="color: ${isPlantLocked ? '#ba1a1a' : stage.color};">${isPlantLocked ? 'lock' : stage.icon}</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-[14px] font-bold ${isPlantLocked ? 'text-on-surface-variant line-through opacity-70' : 'text-on-surface'} truncate">${escHtml(p.template_name)}</h4>
                                    <p class="text-[11px] text-on-surface-variant italic truncate">${escHtml(p.scientific_name)}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-outline-variant group-hover:text-primary transition-colors">open_in_new</span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            ${isPlantLocked 
                                ? `<span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container border border-error/30 whitespace-nowrap inline-block">Terkunci</span>`
                                : `<span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full" style="background: ${stage.color}15; color: ${stage.color};">${stage.label}</span>
                                   <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full ${status.bg} ${status.text}">${status.label}</span>`
                            }
                        </div>
                        <div class="flex items-center justify-between text-[12px] text-on-surface-variant pt-1 border-t border-outline-variant/20">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                <span class="font-bold text-on-surface">${p.hst}</span> HST
                            </div>
                            <div class="flex items-center gap-1 ${harvestColor}">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                <span class="font-medium">${harvestText}</span>
                            </div>
                        </div>
                    </button>
                `;
            }).join('');
        } catch (e) {
            loading.style.display = 'none';
            console.error('Failed to load plants:', e);
        }
    }

    function showPlantLockedAlert(plantName) {
        Alert.modal.confirm(
            'Tanaman Terkunci',
            `Tanaman "${plantName}" terkunci karena melebihi kuota ${USER_PLAN_CONFIG.maxPlants} tanaman paket ${USER_PLAN_CONFIG.planName}. Upgrade ke paket Pro atau Premium untuk membuka kembali seluruh tanaman Anda.`,
            'Upgrade Paket',
            false
        ).then(res => {
            if (res && res.isConfirmed) {
                window.location.href = '/settings#subscription';
            }
        });
    }

    // ── Add Garden ──
    function openAddGardenModal() {
        if (gardens.length >= USER_PLAN_CONFIG.maxGardens) {
            Alert.modal.confirm(
                'Batas Kebun Tercapai',
                `Paket ${USER_PLAN_CONFIG.planName} Anda dibatasi maksimal ${USER_PLAN_CONFIG.maxGardens} kebun. Tingkatkan paket Anda ke Pro (10 Kebun) atau Premium (Tanpa Batas) untuk menambah kebun baru.`,
                'Upgrade Paket',
                false
            ).then(res => {
                if (res && res.isConfirmed) {
                    window.location.href = '/settings#subscription';
                }
            });
            return;
        }
        document.getElementById('add-garden-modal').classList.remove('hidden');
        document.getElementById('add-garden-form').reset();
    }

    function closeAddGardenModal() {
        document.getElementById('add-garden-modal').classList.add('hidden');
    }

    async function submitAddGarden(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('add-garden-submit');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const data = {
                name: form.name.value,
                location: form.location.value || null,
            };
            const garden = await api('/api/gardens', { method: 'POST', body: JSON.stringify(data) });
            gardens.push(garden);
            closeAddGardenModal();
            selectedGardenId = garden.id;
            renderGardens();
            if (window.AppState) window.AppState.usage.gardens++;

            if (garden.new_badge) {
                Alert.modal.badge(garden.new_badge);
            }
        } catch (e) {
            Alert.modal.error('Gagal Membuat Kebun', e.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Buat Kebun';
        }
    }

    // ── Delete Garden ──
    async function deleteCurrentGarden() {
        if (!selectedGardenId) return;
        const result = await Alert.modal.confirm('Hapus Kebun?', 'Hapus kebun ini beserta seluruh tanamannya?', 'Ya, Hapus', true);
        if (!result.isConfirmed) return;

        try {
            await api(`/api/gardens/${selectedGardenId}`, { method: 'DELETE' });
            gardens = gardens.filter(g => g.id !== selectedGardenId);
            selectedGardenId = null;
            renderGardens();
            if (window.AppState) window.AppState.usage.gardens--;
            Alert.toast.success('Kebun berhasil dihapus');
        } catch (e) {
            Alert.modal.error('Gagal menghapus kebun', e.message);
        }
    }

    // ── Add Plant ──
    async function openAddPlantModal() {
        if (!selectedGardenId) return;

        const gardenIdx = gardens.findIndex(g => g.id === selectedGardenId);
        if (gardenIdx >= USER_PLAN_CONFIG.maxGardens) {
            Alert.modal.confirm(
                'Kebun Terkunci',
                `Kebun ini terkunci karena melebihi batas kuota ${USER_PLAN_CONFIG.maxGardens} kebun paket ${USER_PLAN_CONFIG.planName}. Tingkatkan paket Anda untuk mengelola kebun ini.`,
                'Upgrade Paket',
                false
            ).then(res => {
                if (res && res.isConfirmed) {
                    window.location.href = '/settings#subscription';
                }
            });
            return;
        }

        if (plants.length >= USER_PLAN_CONFIG.maxPlants) {
            Alert.modal.confirm(
                'Batas Tanaman Terlampaui',
                `Paket ${USER_PLAN_CONFIG.planName} Anda dibatasi maksimal ${USER_PLAN_CONFIG.maxPlants} tanaman per kebun. Tingkatkan paket Anda untuk menambah tanaman baru.`,
                'Upgrade Paket',
                false
            ).then(res => {
                if (res && res.isConfirmed) {
                    window.location.href = '/settings#subscription';
                }
            });
            return;
        }

        document.getElementById('add-plant-modal').classList.remove('hidden');
        document.getElementById('template-search').value = '';
        resetBatchSelection();

        if (templateCategories.length === 0) {
            try {
                templateCategories = await api('/api/plant-templates');
                renderCategoryTabs();
                renderTemplateGrid();
            } catch (e) {
                console.error('Failed to load templates:', e);
            }
        } else {
            renderTemplateGrid();
        }
    }

    function closeAddPlantModal() {
        document.getElementById('add-plant-modal').classList.add('hidden');
    }

    function renderCategoryTabs() {
        const tabs = document.getElementById('category-tabs');
        let html = `<button type="button" onclick="GardenApp.filterByCategory(null)" class="category-tab active-tab px-4 py-2 rounded-full text-[12px] font-bold whitespace-nowrap transition-all" data-category="all">Semua</button>`;
        templateCategories.forEach(cat => {
            html += `<button type="button" onclick="GardenApp.filterByCategory(${cat.id})" class="category-tab px-4 py-2 rounded-full text-[12px] font-bold whitespace-nowrap transition-all border border-outline-variant/40 text-on-surface-variant hover:bg-surface-container-high" data-category="${cat.id}">${escHtml(cat.name)}</button>`;
        });
        tabs.innerHTML = html;
    }

    let activeCategoryId = null;

    function filterByCategory(catId) {
        activeCategoryId = catId;
        document.querySelectorAll('.category-tab').forEach(tab => {
            const tabCat = tab.dataset.category;
            const isActive = (catId === null && tabCat === 'all') || (catId !== null && parseInt(tabCat) === catId);
            if (isActive) {
                tab.classList.add('active-tab');
                tab.classList.remove('border', 'border-outline-variant/40', 'text-on-surface-variant', 'hover:bg-surface-container-high');
            } else {
                tab.classList.remove('active-tab');
                tab.classList.add('border', 'border-outline-variant/40', 'text-on-surface-variant', 'hover:bg-surface-container-high');
            }
        });
        renderTemplateGrid();
    }

    // ── Batch Plant Selector Logic ──
    let selectedBatch = {}; // { templateId: quantity }

    function resetBatchSelection() {
        selectedBatch = {};
        updateBatchUI();
    }

    function adjustBatchQty(templateId, delta, event) {
        if (event) event.stopPropagation();
        const current = selectedBatch[templateId] || 0;
        const next = Math.max(0, current + delta);
        if (next === 0) {
            delete selectedBatch[templateId];
        } else {
            selectedBatch[templateId] = next;
        }
        renderTemplateGrid(document.getElementById('template-search')?.value?.toLowerCase() || '');
        updateBatchUI();
    }

    function updateBatchUI() {
        const totalCount = Object.values(selectedBatch).reduce((sum, qty) => sum + qty, 0);
        const submitBtn = document.getElementById('add-plant-submit');
        const badge = document.getElementById('batch-summary-badge');

        if (badge) {
            badge.textContent = `Terpilih: ${totalCount}`;
        }

        if (submitBtn) {
            submitBtn.disabled = totalCount === 0;
            submitBtn.textContent = 'Tanam';
        }
    }

    function filterTemplates(query = '') {
        renderTemplateGrid((query || '').toLowerCase());
    }

    function renderTemplateGrid(searchQuery = '') {
        const grid = document.getElementById('template-grid');
        let templates = [];

        templateCategories.forEach(cat => {
            if (activeCategoryId !== null && cat.id !== activeCategoryId) return;
            (cat.templates || []).forEach(t => {
                if (searchQuery && !t.name_id.toLowerCase().includes(searchQuery) && !(t.scientific_name || '').toLowerCase().includes(searchQuery)) return;
                templates.push({ ...t, categoryName: cat.name });
            });
        });

        if (templates.length === 0) {
            grid.innerHTML = `<div class="col-span-2 text-center py-10 text-on-surface-variant text-[14px]">Tidak ada tanaman ditemukan.</div>`;
            return;
        }

        grid.innerHTML = templates.map(t => {
            const qty = selectedBatch[t.id] || 0;
            const isSelected = qty > 0;

            return `
            <div class="template-card p-4 rounded-xl border-2 transition-all duration-200 flex items-center justify-between gap-3 ${isSelected ? 'border-primary bg-primary/5 shadow-sm' : 'border-outline-variant/30 bg-surface hover:border-primary/40'}" data-template-id="${t.id}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl ${isSelected ? 'bg-primary text-on-primary' : 'bg-primary/10 text-primary'} flex items-center justify-center shrink-0 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">eco</span>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-[14px] font-bold text-on-surface truncate">${escHtml(t.name_id)}</h4>
                        <p class="text-[11px] text-on-surface-variant italic truncate">${escHtml(t.scientific_name || '')}</p>
                    </div>
                </div>

                <!-- Quantity Controller -->
                <div class="flex items-center gap-1.5 shrink-0 bg-surface-container-high/80 p-1 rounded-lg border border-outline-variant/30">
                    ${isSelected ? `
                        <button type="button" onclick="GardenApp.adjustBatchQty(${t.id}, -1, event)" class="w-7 h-7 rounded-md bg-surface hover:bg-error/10 hover:text-error text-on-surface-variant flex items-center justify-center font-bold text-[14px] transition-colors">
                            <span class="material-symbols-outlined text-[16px]">remove</span>
                        </button>
                        <span class="text-[13px] font-extrabold text-primary w-6 text-center">${qty}</span>
                        <button type="button" onclick="GardenApp.adjustBatchQty(${t.id}, 1, event)" class="w-7 h-7 rounded-md bg-primary text-on-primary flex items-center justify-center font-bold text-[14px] hover:bg-primary/90 transition-colors shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                        </button>
                    ` : `
                        <button type="button" onclick="GardenApp.adjustBatchQty(${t.id}, 1, event)" class="px-3 py-1.5 rounded-md bg-primary/10 text-primary hover:bg-primary hover:text-on-primary font-bold text-[12px] flex items-center gap-1 transition-all">
                            <span class="material-symbols-outlined text-[14px]">add</span> Tambah
                        </button>
                    `}
                </div>
            </div>
            `;
        }).join('');
    }

    function selectTemplate(id, name, scientific) {
        adjustBatchQty(id, 1);
    }

    function deselectTemplate() {
        resetBatchSelection();
    }

    async function submitAddPlant(e) {
        e.preventDefault();
        const items = Object.keys(selectedBatch).map(id => ({
            plant_template_id: parseInt(id),
            quantity: selectedBatch[id]
        })).filter(item => item.quantity > 0);

        if (items.length === 0 || !selectedGardenId) return;

        const btn = document.getElementById('add-plant-submit');
        btn.disabled = true;
        btn.textContent = 'Menanam...';

        try {
            const dateVal = new Date().toISOString().split('T')[0];
            const response = await api(`/api/gardens/${selectedGardenId}/plants`, {
                method: 'POST',
                body: JSON.stringify({
                    items: items,
                    planted_date: dateVal
                }),
            });

            closeAddPlantModal();
            const gardenIdx = gardens.findIndex(g => g.id === selectedGardenId);
            if (gardenIdx > -1) {
                gardens[gardenIdx].plants_count = (gardens[gardenIdx].plants_count || 0) + (response.count || 1);
                renderGardens();
            }
            const isGardenLocked = gardenIdx >= USER_PLAN_CONFIG.maxGardens;
            await loadPlants(selectedGardenId, isGardenLocked);
            if (window.AppState) window.AppState.usage.plants += (response.count || 1);
            Alert.toast.success(`${response.count || 1} Tanaman berhasil ditambahkan!`);

            if (response.new_badge) {
                Alert.modal.badge(response.new_badge);
            }
        } catch (err) {
            Alert.modal.error('Gagal Menambah Tanaman', err.message);
        } finally {
            btn.disabled = false;
            updateBatchUI();
        }
    }

    // ── Plant Detail Modal ──
    function openPlantDetail(plantOrId) {
        let plant = plantOrId;
        if (typeof plantOrId === 'number' || typeof plantOrId === 'string') {
            plant = plants.find(p => p.id == plantOrId);
        }
        if (!plant) return;

        currentPlantDetail = plant;
        const modal = document.getElementById('plant-detail-modal');

        const stage = STAGE_CONFIG[plant.stage] || STAGE_CONFIG['SEED'];
        const status = STATUS_CONFIG[plant.status] || STATUS_CONFIG['ACTIVE'];

        document.getElementById('detail-plant-name').textContent = plant.template_name;
        document.getElementById('detail-plant-scientific').textContent = plant.scientific_name || '';

        const iconBox = document.getElementById('detail-plant-icon-box');
        const icon = document.getElementById('detail-plant-icon');
        iconBox.style.background = `${stage.color}15`;
        icon.style.color = stage.color;
        icon.textContent = stage.icon;

        const stageBadge = document.getElementById('detail-plant-stage-badge');
        stageBadge.textContent = stage.label;
        stageBadge.style.background = `${stage.color}15`;
        stageBadge.style.color = stage.color;

        const statusBadge = document.getElementById('detail-plant-status-badge');
        statusBadge.textContent = status.label;
        statusBadge.className = `text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full ${status.bg} ${status.text}`;

        document.getElementById('detail-plant-category-badge').textContent = plant.category || 'Sayuran';
        document.getElementById('detail-plant-hst').textContent = `${plant.hst} HST`;

        const harvestText = plant.estimated_harvest_days !== null
            ? (plant.estimated_harvest_days <= 0 ? 'Siap Panen!' : `${plant.estimated_harvest_days} Hari`)
            : 'Belum ditentukan';
        document.getElementById('detail-plant-harvest').textContent = harvestText;

        const t = plant.template || {};
        document.getElementById('detail-plant-water').textContent = t.water_requirement || 'Secukupnya';
        document.getElementById('detail-plant-sunlight').textContent = t.sunlight || 'Full Sun';

        modal.classList.remove('hidden');
    }

    function closePlantDetailModal() {
        document.getElementById('plant-detail-modal').classList.add('hidden');
        currentPlantDetail = null;
    }

    async function deleteCurrentPlant() {
        if (!currentPlantDetail) return;
        const result = await Alert.modal.confirm('Hapus Tanaman?', `Hapus ${currentPlantDetail.template_name} dari kebun ini?`, 'Ya, Hapus', true);
        if (!result.isConfirmed) return;

        try {
            await api(`/api/plants/${currentPlantDetail.id}`, { method: 'DELETE' });
            closePlantDetailModal();
            const gardenIdx = gardens.findIndex(g => g.id === selectedGardenId);
            await loadPlants(selectedGardenId, gardenIdx >= USER_PLAN_CONFIG.maxGardens);
            Alert.toast.success('Tanaman berhasil dihapus.');
        } catch (e) {
            Alert.modal.error('Gagal menghapus tanaman', e.message);
        }
    }

    return {
        init,
        openAddGardenModal,
        closeAddGardenModal,
        submitAddGarden,
        deleteCurrentGarden,
        selectGarden,
        openAddPlantModal,
        closeAddPlantModal,
        filterCategoryTabs: renderCategoryTabs,
        filterByCategory,
        filterTemplates,
        selectTemplate,
        adjustBatchQty,
        resetBatchSelection,
        submitAddPlant,
        openPlantDetail,
        closePlantDetailModal,
        deleteCurrentPlant,
        showPlantLockedAlert,
    };
})();

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => GardenApp.init());
} else {
    GardenApp.init();
}
</script>
@endsection
