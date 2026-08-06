@extends('layouts.dashboard')

@section('title', 'Kebun Saya — Grow a Garden')
@section('description', 'Kelola kebun dan tanaman Anda.')

@section('dashboard-content')
<div class="flex flex-col gap-6 pb-10" id="gardens-app">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight leading-tight">Kebun Saya</h1>
            <p class="text-[14px] text-on-surface-variant mt-1">Kelola kebun dan tanaman Anda di satu tempat.</p>
        </div>
        <button type="button" onclick="GardenApp.openAddGardenModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-5 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Tambah Kebun
        </button>
    </div>

    {{-- Loading State --}}
    <div id="gardens-loading" class="flex items-center justify-center py-20">
        <div class="flex flex-col items-center gap-3">
            <span class="material-symbols-outlined text-[48px] text-primary animate-spin">progress_activity</span>
            <span class="text-[14px] text-on-surface-variant font-medium">Memuat kebun Anda...</span>
        </div>
    </div>

    {{-- Empty State --}}
    <div id="gardens-empty" class="hidden flex-col items-center justify-center py-20 gap-6 w-full">
        <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-[48px] text-primary">yard</span>
        </div>
        <div class="text-center max-w-sm w-full px-4 mx-auto" style="min-width: 300px; text-wrap: normal;">
            <h3 class="text-[20px] font-bold text-on-surface mb-2">Belum ada kebun</h3>
            <p class="text-[14px] text-on-surface-variant leading-relaxed">Mulai dengan membuat kebun pertama Anda, lalu tambahkan tanaman dari katalog kami.</p>
        </div>
        <button type="button" onclick="GardenApp.openAddGardenModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-6 py-3 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Buat Kebun Pertama
        </button>
    </div>

    {{-- Main Content: Garden List + Detail --}}
    <div id="gardens-content" style="display: none;" class="flex-col lg:flex-row gap-6">

        {{-- Left: Garden List --}}
        <div class="w-full lg:w-[320px] shrink-0 flex flex-col gap-3">
            <div id="garden-list" class="flex flex-col gap-3">
                {{-- Populated by JS --}}
            </div>
        </div>

        {{-- Right: Garden Detail --}}
        <div class="flex-1 min-w-0">
            {{-- No garden selected state --}}
            <div id="garden-detail-empty" style="display: none;" class="flex-col items-center justify-center py-20 bg-surface rounded-[24px] ambient-shadow">
                <span class="material-symbols-outlined text-[48px] text-outline-variant mb-4">arrow_back</span>
                <p class="text-[16px] text-on-surface-variant font-medium">Pilih kebun dari daftar di samping</p>
            </div>

            {{-- Garden detail panel --}}
            <div id="garden-detail" style="display: none;" class="flex-col gap-6">
                {{-- Detail Header --}}
                <div class="bg-surface rounded-[24px] p-6 ambient-shadow">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[28px] text-primary">yard</span>
                            </div>
                            <div>
                                <h2 id="detail-garden-name" class="text-[22px] font-bold text-on-surface"></h2>
                                <p id="detail-garden-location" class="text-[13px] text-on-surface-variant flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    <span></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="GardenApp.openAddPlantModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[13px] px-4 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                Tambah Tanaman
                            </button>
                            <button type="button" onclick="GardenApp.deleteCurrentGarden()" class="p-2.5 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-full transition-colors" title="Hapus Kebun">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Plants Loading --}}
                <div id="plants-loading" class="flex items-center justify-center py-12">
                    <span class="material-symbols-outlined text-[36px] text-primary animate-spin">progress_activity</span>
                </div>

                {{-- Plants Empty --}}
                <div id="plants-empty" style="display: none;" class="bg-surface rounded-[24px] p-10 ambient-shadow flex-col items-center justify-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[32px] text-primary">potted_plant</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-[18px] font-bold text-on-surface mb-1">Kebun masih kosong</h3>
                        <p class="text-[13px] text-on-surface-variant">Tambahkan tanaman pertama dari katalog kami.</p>
                    </div>
                    <button type="button" onclick="GardenApp.openAddPlantModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Tanaman
                    </button>
                </div>

                {{-- Plants Grid --}}
                <div id="plants-grid" style="display: none;" class="grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    {{-- Populated by JS --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================
     MODAL: Add Garden
     ============================================ --}}
<div id="add-garden-modal" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="GardenApp.closeAddGardenModal()"></div>
    <div class="w-full min-h-screen px-4 py-8 flex items-center justify-center pointer-events-none">
        <div class="w-full max-w-md bg-surface-container-lowest rounded-3xl p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative" style="min-width: 350px; text-wrap: normal;">
            <button onclick="GardenApp.closeAddGardenModal()" class="absolute top-5 right-5 w-9 h-9 bg-surface-container-high rounded-full flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            <h3 class="text-[22px] font-bold text-on-surface mb-6">Buat Kebun Baru</h3>
            <form id="add-garden-form" onsubmit="GardenApp.submitAddGarden(event)">
                <div class="flex flex-col gap-5">
                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider mb-2 block">Nama Kebun *</label>
                        <input type="text" name="name" required placeholder="contoh: Kebun Belakang Rumah" class="w-full px-4 py-3 rounded-xl border border-outline-variant/40 bg-surface-container-lowest text-on-surface text-[14px] focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider mb-2 block">Lokasi (opsional)</label>
                        <input type="text" name="location" placeholder="contoh: Bandung, Jawa Barat" class="w-full px-4 py-3 rounded-xl border border-outline-variant/40 bg-surface-container-lowest text-on-surface text-[14px] focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    <button type="submit" id="add-garden-submit" class="w-full bg-primary text-on-primary font-bold text-[14px] py-3 rounded-xl hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm mt-2">
                        Buat Kebun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================
     MODAL: Add Plant (Template Picker)
     ============================================ --}}
<div id="add-plant-modal" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="GardenApp.closeAddPlantModal()"></div>
    <div class="min-h-screen px-4 py-8 flex items-center justify-center pointer-events-none">
        <div class="w-full max-w-2xl bg-surface-container-lowest rounded-3xl p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative max-h-[90vh] flex flex-col" style="min-width: 350px; text-wrap: normal;">
            <button onclick="GardenApp.closeAddPlantModal()" class="absolute top-5 right-5 w-9 h-9 bg-surface-container-high rounded-full flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors z-10">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            <h3 class="text-[22px] font-bold text-on-surface mb-2">Tambah Tanaman</h3>
            <p class="text-[13px] text-on-surface-variant mb-5">Pilih dari katalog tanaman, lalu tentukan tanggal tanam.</p>

            {{-- Search --}}
            <div class="relative mb-4">
                <span class="material-symbols-outlined text-[20px] text-on-surface-variant absolute left-4 top-1/2 -translate-y-1/2">search</span>
                <input type="text" id="template-search" placeholder="Cari tanaman..." oninput="GardenApp.filterTemplates(this.value)" class="w-full pl-12 pr-4 py-3 rounded-xl border border-outline-variant/40 bg-surface-container-lowest text-on-surface text-[14px] focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
            </div>

            {{-- Category Tabs --}}
            <div id="category-tabs" class="flex items-center gap-2 mb-5 overflow-x-auto no-scrollbar pb-1">
                <button type="button" onclick="GardenApp.filterByCategory(null)" class="category-tab active-tab px-4 py-2 rounded-full text-[12px] font-bold whitespace-nowrap transition-all" data-category="all">Semua</button>
            </div>

            {{-- Template Grid --}}
            <div id="template-grid" class="flex-1 overflow-y-auto no-scrollbar grid grid-cols-1 sm:grid-cols-2 gap-3 pr-1 mb-5" style="max-height: 40vh;">
                {{-- Populated by JS --}}
            </div>

            {{-- Selected Plant + Date --}}
            <div id="selected-plant-section" class="hidden border-t border-outline-variant/30 pt-5 mt-auto">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px] text-primary">eco</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-bold text-on-surface truncate" id="selected-plant-name"></p>
                        <p class="text-[12px] text-on-surface-variant italic truncate" id="selected-plant-scientific"></p>
                    </div>
                    <button type="button" onclick="GardenApp.deselectTemplate()" class="text-on-surface-variant hover:text-error transition-colors p-1">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1.5 block">Tanggal Tanam</label>
                        <input type="date" id="planted-date-input" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/40 bg-surface-container-lowest text-on-surface text-[14px] focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    <button type="button" onclick="GardenApp.submitAddPlant()" id="add-plant-submit" class="bg-primary text-on-primary font-bold text-[13px] px-6 py-2.5 rounded-xl hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm sm:self-end">
                        Tanam Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================
     MODAL: Plant Detail
     ============================================ --}}
<div id="plant-detail-modal" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="GardenApp.closePlantDetail()"></div>
    <div class="w-full min-h-screen px-4 py-8 flex items-center justify-center pointer-events-none">
        <div class="w-full max-w-lg bg-surface-container-lowest rounded-3xl p-8 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative" style="min-width: 350px; text-wrap: normal;">
            <button onclick="GardenApp.closePlantDetail()" class="absolute top-5 right-5 w-9 h-9 bg-surface-container-high rounded-full flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            {{-- Plant Info Header --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[32px] text-primary">eco</span>
                </div>
                <div>
                    <h3 id="pd-name" class="text-[22px] font-bold text-on-surface"></h3>
                    <p id="pd-scientific" class="text-[13px] text-on-surface-variant italic"></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span id="pd-category-badge" class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-primary/10 text-primary"></span>
                        <span id="pd-status-badge" class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full"></span>
                    </div>
                </div>
            </div>

            {{-- Key Info Grid --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-surface-container-low rounded-2xl p-4 text-center">
                    <span class="material-symbols-outlined text-[20px] text-primary mb-1">calendar_today</span>
                    <div id="pd-hst" class="text-[24px] font-black text-on-surface leading-none mb-1"></div>
                    <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">HST</div>
                </div>
                <div class="bg-surface-container-low rounded-2xl p-4 text-center">
                    <span class="material-symbols-outlined text-[20px] text-status-healthy mb-1">eco</span>
                    <div id="pd-stage-label" class="text-[13px] font-black text-on-surface leading-tight mb-1"></div>
                    <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Fase</div>
                </div>
                <div class="bg-surface-container-low rounded-2xl p-4 text-center">
                    <span class="material-symbols-outlined text-[20px] text-[#f97316] mb-1">schedule</span>
                    <div id="pd-harvest-eta" class="text-[13px] font-black text-on-surface leading-tight mb-1"></div>
                    <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Panen</div>
                </div>
            </div>

            {{-- Growth Timeline --}}
            <div class="mb-6">
                <h4 class="text-[13px] font-bold text-on-surface-variant uppercase tracking-wider mb-4">Timeline Pertumbuhan</h4>
                <div id="pd-timeline" class="flex flex-col gap-0">
                    {{-- Populated by JS --}}
                </div>
            </div>

            {{-- Plant Details --}}
            <div class="mb-6">
                <h4 class="text-[13px] font-bold text-on-surface-variant uppercase tracking-wider mb-3">Informasi Perawatan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex items-start gap-3 bg-surface-container-low rounded-xl p-3">
                        <span class="material-symbols-outlined text-[18px] text-primary mt-0.5">water_drop</span>
                        <div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Kebutuhan Air</div>
                            <div id="pd-water" class="text-[13px] text-on-surface font-medium"></div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-surface-container-low rounded-xl p-3">
                        <span class="material-symbols-outlined text-[18px] text-[#f97316] mt-0.5">sunny</span>
                        <div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Cahaya Matahari</div>
                            <div id="pd-sunlight" class="text-[13px] text-on-surface font-medium"></div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-surface-container-low rounded-xl p-3">
                        <span class="material-symbols-outlined text-[18px] text-[#8b5cf6] mt-0.5">science</span>
                        <div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">pH Tanah</div>
                            <div id="pd-ph" class="text-[13px] text-on-surface font-medium"></div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-surface-container-low rounded-xl p-3">
                        <span class="material-symbols-outlined text-[18px] text-status-healthy mt-0.5">event</span>
                        <div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Tanggal Tanam</div>
                            <div id="pd-planted" class="text-[13px] text-on-surface font-medium"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end">
                <button type="button" onclick="GardenApp.deleteCurrentPlant()" class="flex items-center gap-2 text-error font-bold text-[13px] px-4 py-2 rounded-xl hover:bg-error/10 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Hapus Tanaman
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
        'FLOWERING':   { label: 'Berbunga',     color: '#f59e0b', icon: 'local_florist' },
        'FRUITING':    { label: 'Berbuah',      color: '#f97316', icon: 'nutrition' },
        'HARVEST':     { label: 'Panen',        color: '#006c49', icon: 'agriculture' },
        'FINISHED':    { label: 'Selesai',      color: '#6b7280', icon: 'check_circle' },
        'DEAD':        { label: 'Mati',         color: '#ef4444', icon: 'dangerous' },
    };

    const STATUS_CONFIG = {
        'ACTIVE':     { label: 'Aktif',       bg: 'bg-[#10b981]/10', text: 'text-[#006c49]' },
        'PRODUCTIVE': { label: 'Produktif',   bg: 'bg-[#f59e0b]/10', text: 'text-[#92400e]' },
        'HARVESTING': { label: 'Panen',       bg: 'bg-[#006c49]/10', text: 'text-[#006c49]' },
        'FINISHED':   { label: 'Selesai',     bg: 'bg-[#6b7280]/10', text: 'text-[#374151]' },
        'DEAD':       { label: 'Mati',        bg: 'bg-[#ef4444]/10', text: 'text-[#b91c1c]' },
    };

    // ── Init ──
    async function init() {
        await loadGardens();
        document.getElementById('planted-date-input').value = new Date().toISOString().split('T')[0];
    }

    // ── API Helpers ──
    async function api(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const defaults = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        };
        const resp = await fetch(url, { ...defaults, ...options });
        if (!resp.ok) {
            const err = await resp.json().catch(() => ({}));
            throw new Error(err.error || err.message || `HTTP ${resp.status}`);
        }
        return resp.json();
    }

    // ── Gardens ──
    async function loadGardens() {
        try {
            gardens = await api('/api/gardens');
            renderGardens();
        } catch (e) {
            console.error('Failed to load gardens:', e);
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
            content.style.display = 'none';
            return;
        }

        empty.style.display = 'none';
        content.style.display = 'flex';

        list.innerHTML = gardens.map(g => `
            <button type="button" onclick="GardenApp.selectGarden(${g.id})"
                class="garden-card w-full text-left bg-surface rounded-[20px] p-5 ambient-shadow hover:-translate-y-0.5 hover:ambient-shadow-lg transition-all duration-200 border-2 ${selectedGardenId === g.id ? 'border-[#006c49]' : 'border-transparent'}" data-garden-id="${g.id}">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl ${selectedGardenId === g.id ? 'bg-primary text-on-primary' : 'bg-primary/10 text-primary'} flex items-center justify-center shrink-0 transition-colors">
                        <span class="material-symbols-outlined text-[24px]">yard</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[15px] font-bold text-on-surface truncate">${escHtml(g.name)}</h3>
                        ${g.location_name ? `<p class="text-[12px] text-on-surface-variant truncate flex items-center gap-1 mt-0.5"><span class="material-symbols-outlined text-[12px]">location_on</span>${escHtml(g.location_name)}</p>` : ''}
                    </div>
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant">chevron_right</span>
                </div>
            </button>
        `).join('');

        // Auto-select first or previously selected
        if (selectedGardenId && gardens.find(g => g.id === selectedGardenId)) {
            selectGarden(selectedGardenId);
        } else if (gardens.length > 0) {
            selectGarden(gardens[0].id);
        }
    }

    async function selectGarden(gardenId) {
        selectedGardenId = gardenId;
        const garden = gardens.find(g => g.id === gardenId);
        if (!garden) return;

        // Update card highlights
        document.querySelectorAll('.garden-card').forEach(card => {
            const id = parseInt(card.dataset.gardenId);
            const icon = card.querySelector('.w-12');
            if (id === gardenId) {
                card.classList.remove('border-transparent');
                card.classList.add('border-[#006c49]');
                icon.classList.remove('bg-primary/10', 'text-primary');
                icon.classList.add('bg-primary', 'text-on-primary');
            } else {
                card.classList.add('border-transparent');
                card.classList.remove('border-[#006c49]');
                icon.classList.add('bg-primary/10', 'text-primary');
                icon.classList.remove('bg-primary', 'text-on-primary');
            }
        });

        // Show detail panel
        document.getElementById('garden-detail-empty').style.display = 'none';
        document.getElementById('garden-detail').style.display = 'flex';
        document.getElementById('detail-garden-name').textContent = garden.name;
        const locEl = document.getElementById('detail-garden-location');
        if (garden.location_name) {
            locEl.classList.remove('hidden');
            locEl.querySelector('span:last-child').textContent = garden.location_name;
        } else {
            locEl.classList.add('hidden');
        }

        // Load plants
        await loadPlants(gardenId);
    }

    // ── Plants ──
    async function loadPlants(gardenId) {
        const loading = document.getElementById('plants-loading');
        const empty = document.getElementById('plants-empty');
        const grid = document.getElementById('plants-grid');

        loading.style.display = 'flex';
        empty.style.display = 'none';
        grid.style.display = 'none';

        try {
            plants = await api(`/api/gardens/${gardenId}/plants`);
            loading.style.display = 'none';

            if (plants.length === 0) {
                empty.style.display = 'flex';
                return;
            }

            grid.style.display = 'grid';
            grid.innerHTML = plants.map(p => {
                const stage = STAGE_CONFIG[p.stage] || STAGE_CONFIG['SEED'];
                const status = STATUS_CONFIG[p.status] || STATUS_CONFIG['ACTIVE'];
                const harvestText = p.estimated_harvest_days !== null
                    ? (p.estimated_harvest_days <= 0 ? 'Siap panen!' : `${p.estimated_harvest_days} hari lagi`)
                    : '-';
                const harvestColor = p.estimated_harvest_days !== null && p.estimated_harvest_days <= 0 ? 'text-[#006c49]' : 'text-on-surface-variant';

                return `
                    <button type="button" onclick='GardenApp.openPlantDetail(${JSON.stringify(p).replace(/'/g, "&#39;")})'
                        class="bg-surface rounded-[20px] p-5 ambient-shadow text-left hover:-translate-y-1 hover:ambient-shadow-lg transition-all duration-200 flex flex-col gap-3 group">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: ${stage.color}15;">
                                    <span class="material-symbols-outlined text-[22px]" style="color: ${stage.color};">${stage.icon}</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-[14px] font-bold text-on-surface truncate">${escHtml(p.template_name)}</h4>
                                    <p class="text-[11px] text-on-surface-variant italic truncate">${escHtml(p.scientific_name)}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-outline-variant group-hover:text-primary transition-colors">open_in_new</span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full" style="background: ${stage.color}15; color: ${stage.color};">${stage.label}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full ${status.bg} ${status.text}">${status.label}</span>
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

    // ── Add Garden ──
    function openAddGardenModal() {
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
            // Update AppState
            if (window.AppState) window.AppState.usage.gardens++;
        } catch (e) {
            alert(e.message);
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
        document.getElementById('add-plant-modal').classList.remove('hidden');
        document.getElementById('template-search').value = '';
        deselectTemplate();

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
        // Update tab styles
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

    function filterTemplates(query) {
        renderTemplateGrid(query.toLowerCase());
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

        grid.innerHTML = templates.map(t => `
            <button type="button" onclick="GardenApp.selectTemplate(${t.id}, '${escAttr(t.name_id)}', '${escAttr(t.scientific_name || '')}')"
                class="template-card text-left p-4 rounded-xl border-2 transition-all duration-200 hover:shadow-md ${selectedTemplateId === t.id ? 'border-primary bg-primary/5' : 'border-outline-variant/30 bg-surface hover:border-primary/50'}" data-template-id="${t.id}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-primary">eco</span>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-[13px] font-bold text-on-surface truncate">${escHtml(t.name_id)}</h4>
                        <p class="text-[11px] text-on-surface-variant italic truncate">${escHtml(t.scientific_name || '')}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2.5">
                    <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant">${escHtml(t.categoryName)}</span>
                    <span class="text-[9px] font-medium text-on-surface-variant">${t.harvest_start_day}–${t.harvest_end_day} hari</span>
                </div>
            </button>
        `).join('');
    }

    function selectTemplate(id, name, scientific) {
        selectedTemplateId = id;
        document.getElementById('selected-plant-section').classList.remove('hidden');
        document.getElementById('selected-plant-name').textContent = name;
        document.getElementById('selected-plant-scientific').textContent = scientific;

        // Highlight card
        document.querySelectorAll('.template-card').forEach(card => {
            if (parseInt(card.dataset.templateId) === id) {
                card.classList.remove('border-outline-variant/30', 'bg-surface', 'hover:border-primary/50');
                card.classList.add('border-primary', 'bg-primary/5');
            } else {
                card.classList.add('border-outline-variant/30', 'bg-surface', 'hover:border-primary/50');
                card.classList.remove('border-primary', 'bg-primary/5');
            }
        });
    }

    function deselectTemplate() {
        selectedTemplateId = null;
        document.getElementById('selected-plant-section').classList.add('hidden');
        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.add('border-outline-variant/30', 'bg-surface', 'hover:border-primary/50');
            card.classList.remove('border-primary', 'bg-primary/5');
        });
    }

    async function submitAddPlant() {
        if (!selectedTemplateId || !selectedGardenId) return;
        const plantedDate = document.getElementById('planted-date-input').value;
        if (!plantedDate) { alert('Pilih tanggal tanam.'); return; }

        const btn = document.getElementById('add-plant-submit');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            await api(`/api/gardens/${selectedGardenId}/plants`, {
                method: 'POST',
                body: JSON.stringify({
                    plant_template_id: selectedTemplateId,
                    planted_date: plantedDate,
                }),
            });
            closeAddPlantModal();
            deselectTemplate();
            await loadPlants(selectedGardenId);
            if (window.AppState) window.AppState.usage.plants++;
        } catch (e) {
            alert(e.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Tanam Sekarang';
        }
    }

    // ── Plant Detail ──
    function openPlantDetail(plant) {
        currentPlantDetail = plant;
        const modal = document.getElementById('plant-detail-modal');
        modal.classList.remove('hidden');

        const stage = STAGE_CONFIG[plant.stage] || STAGE_CONFIG['SEED'];
        const status = STATUS_CONFIG[plant.status] || STATUS_CONFIG['ACTIVE'];

        document.getElementById('pd-name').textContent = plant.template_name;
        document.getElementById('pd-scientific').textContent = plant.scientific_name;
        document.getElementById('pd-category-badge').textContent = plant.category;
        const statusBadge = document.getElementById('pd-status-badge');
        statusBadge.textContent = status.label;
        statusBadge.className = `text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full ${status.bg} ${status.text}`;

        document.getElementById('pd-hst').textContent = plant.hst;
        document.getElementById('pd-stage-label').textContent = stage.label;

        const harvestEta = document.getElementById('pd-harvest-eta');
        if (plant.estimated_harvest_days !== null) {
            harvestEta.textContent = plant.estimated_harvest_days <= 0 ? 'Siap!' : `${plant.estimated_harvest_days}h`;
        } else {
            harvestEta.textContent = '-';
        }

        // Planted date
        document.getElementById('pd-planted').textContent = plant.planted_date
            ? new Date(plant.planted_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
            : '-';

        // Care info
        document.getElementById('pd-water').textContent = plant.template?.water_requirement || '-';
        document.getElementById('pd-sunlight').textContent = plant.template?.sunlight || '-';
        document.getElementById('pd-ph').textContent = plant.template ? `${plant.template.soil_ph_min} - ${plant.template.soil_ph_max}` : '-';

        // Growth Timeline
        renderTimeline(plant);
    }

    function renderTimeline(plant) {
        const t = plant.template;
        if (!t) return;

        const stages = [
            { key: 'SEED', label: 'Benih', day: 0 },
            { key: 'GERMINATION', label: 'Germinasi', day: t.germination_day },
            { key: 'SEEDLING', label: 'Persemaian', day: t.seedling_day },
            { key: 'VEGETATIVE', label: 'Vegetatif', day: t.vegetative_day },
        ];
        if (t.flowering_day) stages.push({ key: 'FLOWERING', label: 'Berbunga', day: t.flowering_day });
        if (t.fruiting_day) stages.push({ key: 'FRUITING', label: 'Berbuah', day: t.fruiting_day });
        stages.push({ key: 'HARVEST', label: 'Panen', day: t.harvest_start_day });

        const currentStageIndex = stages.findIndex(s => s.key === plant.stage);
        const container = document.getElementById('pd-timeline');

        container.innerHTML = stages.map((s, i) => {
            const cfg = STAGE_CONFIG[s.key] || STAGE_CONFIG['SEED'];
            const isPast = i < currentStageIndex;
            const isCurrent = i === currentStageIndex;
            const isFuture = i > currentStageIndex;

            const lineColor = isPast ? cfg.color : '#e5e7eb';
            const dotBg = isCurrent ? cfg.color : (isPast ? cfg.color : '#d1d5db');
            const textClass = isCurrent ? 'font-black text-on-surface' : (isPast ? 'font-bold text-on-surface' : 'font-medium text-on-surface-variant');

            return `
                <div class="flex items-stretch gap-4">
                    <div class="flex flex-col items-center w-6 shrink-0">
                        <div class="w-4 h-4 rounded-full border-2 shrink-0 ${isCurrent ? 'ring-4 ring-opacity-20' : ''}" style="background: ${dotBg}; border-color: ${dotBg}; ${isCurrent ? `ring-color: ${dotBg};` : ''}"></div>
                        ${i < stages.length - 1 ? `<div class="w-0.5 flex-1 min-h-[24px]" style="background: ${lineColor};"></div>` : ''}
                    </div>
                    <div class="pb-4 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] ${textClass}">${s.label}</span>
                            <span class="text-[11px] text-on-surface-variant">HST ${s.day || 0}</span>
                        </div>
                        ${isCurrent ? `<span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full mt-1 inline-block" style="background: ${cfg.color}15; color: ${cfg.color};">Saat ini</span>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    function closePlantDetail() {
        document.getElementById('plant-detail-modal').classList.add('hidden');
        currentPlantDetail = null;
    }

    async function deleteCurrentPlant() {
        if (!currentPlantDetail) return;
        const result = await Alert.modal.confirm('Hapus Tanaman?', 'Hapus tanaman ini dari kebun Anda?', 'Ya, Hapus', true);
        if (!result.isConfirmed) return;

        try {
            await api(`/api/plants/${currentPlantDetail.id}`, { method: 'DELETE' });
            closePlantDetail();
            await loadPlants(selectedGardenId);
            if (window.AppState) window.AppState.usage.plants--;
            Alert.toast.success('Tanaman berhasil dihapus');
        } catch (e) {
            Alert.modal.error('Gagal menghapus tanaman', e.message);
        }
    }

    // ── Helpers ──
    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str || '';
        return d.innerHTML;
    }
    function escAttr(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    // ── Expose ──
    return {
        init,
        selectGarden,
        openAddGardenModal,
        closeAddGardenModal,
        submitAddGarden,
        deleteCurrentGarden,
        openAddPlantModal,
        closeAddPlantModal,
        selectTemplate,
        deselectTemplate,
        submitAddPlant,
        filterByCategory,
        filterTemplates,
        openPlantDetail,
        closePlantDetail,
        deleteCurrentPlant,
    };
})();

document.addEventListener('DOMContentLoaded', () => GardenApp.init());
</script>

<style>
    .active-tab {
        background: #006c49;
        color: white;
        border-color: #006c49;
    }
</style>
@endpush
