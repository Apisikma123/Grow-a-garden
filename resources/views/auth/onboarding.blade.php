@extends('layouts.app')

@section('title', 'Setup Kebun & Profil — Grow a Garden')

@section('content')
<style>
    body { background-color: #f8faf9; }
    .glass-card {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(187, 202, 191, 0.4);
        box-shadow: 0 24px 48px -12px rgba(0, 108, 73, 0.1);
    }
    .option-card {
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    .option-card:hover {
        transform: translateY(-2px);
        border-color: #006c49;
        box-shadow: 0 10px 24px -4px rgba(0, 108, 73, 0.12);
    }
    .option-card:active {
        transform: scale(0.985);
    }
    .option-card.selected {
        border-color: #006c49 !important;
        background-color: rgba(0, 108, 73, 0.04) !important;
        box-shadow: 0 0 0 2px #006c49, 0 12px 28px -6px rgba(0, 108, 73, 0.14) !important;
    }
    .step-indicator-pill {
        transition: all 0.3s ease;
    }
</style>

<div class="min-h-screen flex items-center justify-center p-3.5 sm:p-6 md:p-10 relative overflow-hidden">
    {{-- Ambient Background Glows --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[5%] left-[-5%] w-[45%] aspect-square rounded-full bg-primary-fixed/[0.2] blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[40%] aspect-square rounded-full bg-secondary-fixed/[0.18] blur-3xl"></div>
    </div>

    {{-- Main Container Card --}}
    <div class="w-full max-w-[720px] glass-card rounded-[24px] sm:rounded-[32px] p-5 sm:p-8 md:p-10 relative z-10 my-2 sm:my-6 flex flex-col">
        
        {{-- Header: Logo & Step Badge --}}
        <div class="flex items-center justify-between gap-3 mb-5 border-b border-outline-variant/20 pb-4">
            <div class="flex items-center gap-2.5 min-w-0">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl object-contain shadow-xs shrink-0">
                <span class="text-sm sm:text-base font-bold text-on-surface tracking-tight truncate">Grow a Garden</span>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-primary bg-primary/10 px-3 py-1 rounded-full border border-primary/20 whitespace-nowrap" id="step-badge">
                    Langkah 1 dari 5
                </span>
            </div>
        </div>

        {{-- Interactive Stepper Indicator Pills --}}
        <div class="grid grid-cols-5 gap-1.5 sm:gap-2 mb-6 sm:mb-8 w-full" id="stepper-pills">
            <button type="button" onclick="goToStep(1)" class="step-indicator-pill flex flex-col items-center gap-1 group text-left cursor-pointer" id="pill-1">
                <div class="w-full h-2 rounded-full bg-primary transition-all duration-300 pill-bar"></div>
                <span class="text-[10px] sm:text-[11px] font-bold text-primary truncate hidden sm:inline">1. Identitas</span>
            </button>
            <button type="button" onclick="goToStep(2)" class="step-indicator-pill flex flex-col items-center gap-1 group text-left cursor-pointer" id="pill-2">
                <div class="w-full h-2 rounded-full bg-surface-container-high transition-all duration-300 pill-bar"></div>
                <span class="text-[10px] sm:text-[11px] font-semibold text-on-surface-variant truncate hidden sm:inline">2. Pengalaman</span>
            </button>
            <button type="button" onclick="goToStep(3)" class="step-indicator-pill flex flex-col items-center gap-1 group text-left cursor-pointer" id="pill-3">
                <div class="w-full h-2 rounded-full bg-surface-container-high transition-all duration-300 pill-bar"></div>
                <span class="text-[10px] sm:text-[11px] font-semibold text-on-surface-variant truncate hidden sm:inline">3. Target Skala</span>
            </button>
            <button type="button" onclick="goToStep(4)" class="step-indicator-pill flex flex-col items-center gap-1 group text-left cursor-pointer" id="pill-4">
                <div class="w-full h-2 rounded-full bg-surface-container-high transition-all duration-300 pill-bar"></div>
                <span class="text-[10px] sm:text-[11px] font-semibold text-on-surface-variant truncate hidden sm:inline">4. Kendala</span>
            </button>
            <button type="button" onclick="calculateAndShowRecommendation()" class="step-indicator-pill flex flex-col items-center gap-1 group text-left cursor-pointer" id="pill-5">
                <div class="w-full h-2 rounded-full bg-surface-container-high transition-all duration-300 pill-bar"></div>
                <span class="text-[10px] sm:text-[11px] font-semibold text-on-surface-variant truncate hidden sm:inline">5. Solusi</span>
            </button>
        </div>

        {{-- ONBOARDING FORM --}}
        <form id="onboarding-form" onsubmit="event.preventDefault();" class="flex flex-col gap-6 w-full">
            @csrf

            {{-- ══════════════════════════════════════════════════
                 STEP 1: NAMA USER, NAMA KEBUN & LOKASI
            ══════════════════════════════════════════════════ --}}
            <div id="step-1" class="step-pane flex flex-col gap-5 sm:gap-6 w-full">
                <div class="w-full">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary mb-1.5 sm:mb-2">
                        <span class="material-symbols-outlined text-[16px]">person</span> Identitas & Kebun
                    </span>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-1.5 leading-tight">
                        Profil Anda & Setup Kebun
                    </h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Lengkapi nama akun dan data kebun pertama Anda. Lokasi digunakan untuk memuat prakiraan cuaca setempat dan jadwal penyiraman.
                    </p>
                </div>

                {{-- User Full Name Input (Required) --}}
                <div class="flex flex-col gap-2 w-full">
                    <label for="user_name" class="text-xs sm:text-sm font-bold text-on-surface">
                        Nama Lengkap Anda <span class="text-error">*</span>
                    </label>
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">person</span>
                        <input 
                            type="text" 
                            id="user_name" 
                            name="user_name" 
                            value="{{ old('user_name', Auth::user()->name ?? '') }}"
                            placeholder="Masukkan nama lengkap atau panggilan Anda" 
                            class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl pl-11 pr-4 py-3 text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium"
                            required
                        />
                    </div>
                </div>

                {{-- Garden Name Input (Required) --}}
                <div class="flex flex-col gap-2 w-full">
                    <label for="garden_name" class="text-xs sm:text-sm font-bold text-on-surface">
                        Nama Kebun Pertama <span class="text-error">*</span>
                    </label>
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">potted_plant</span>
                        <input 
                            type="text" 
                            id="garden_name" 
                            name="garden_name" 
                            value="Kebun Rumah Saya"
                            placeholder="Contoh: Kebun Balkon, Kebun Belakang" 
                            class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl pl-11 pr-4 py-3 text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium"
                            required
                        />
                    </div>

                    {{-- Quick Suggestion Chips --}}
                    <div class="flex items-center gap-1.5 flex-wrap mt-1 w-full">
                        <span class="text-[11px] text-on-surface-variant font-medium mr-1 shrink-0">Pilihan nama:</span>
                        <button type="button" onclick="setGardenName('Kebun Balkon')" class="text-[11px] font-semibold bg-surface-container-high hover:bg-primary/10 hover:text-primary px-2.5 py-1 rounded-lg transition-colors cursor-pointer">
                            Kebun Balkon
                        </button>
                        <button type="button" onclick="setGardenName('Kebun Belakang Rumah')" class="text-[11px] font-semibold bg-surface-container-high hover:bg-primary/10 hover:text-primary px-2.5 py-1 rounded-lg transition-colors cursor-pointer">
                            Kebun Belakang Rumah
                        </button>
                        <button type="button" onclick="setGardenName('Kebun Hidroponik Rooftop')" class="text-[11px] font-semibold bg-surface-container-high hover:bg-primary/10 hover:text-primary px-2.5 py-1 rounded-lg transition-colors cursor-pointer">
                            Kebun Hidroponik Rooftop
                        </button>
                        <button type="button" onclick="setGardenName('Kebun Organik')" class="text-[11px] font-semibold bg-surface-container-high hover:bg-primary/10 hover:text-primary px-2.5 py-1 rounded-lg transition-colors cursor-pointer">
                            Kebun Organik
                        </button>
                    </div>
                </div>

                {{-- Garden Location Input (Required) --}}
                <div class="flex flex-col gap-2 w-full">
                    <label for="garden_location" class="text-xs sm:text-sm font-bold text-on-surface">
                        Lokasi / Kota Kebun <span class="text-error">*</span>
                    </label>
                    <div class="flex items-center gap-2 w-full">
                        <div class="relative flex-1 min-w-0">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">location_on</span>
                            <input 
                                type="text" 
                                id="garden_location" 
                                name="location" 
                                placeholder="Contoh: Jakarta Selatan, DKI Jakarta" 
                                class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl pl-11 pr-4 py-3 text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium truncate"
                                required
                            />
                        </div>
                        <button type="button" id="btn-detect-gps" onclick="detectGPSLocation()" class="h-[46px] px-3.5 sm:px-4 rounded-xl bg-primary/10 text-primary hover:bg-primary/20 active:scale-95 transition-all font-bold text-xs sm:text-sm flex items-center gap-1.5 shrink-0 cursor-pointer border border-primary/20">
                            <span class="material-symbols-outlined text-[18px]" id="gps-icon">my_location</span>
                            <span id="gps-btn-text">Deteksi GPS</span>
                        </button>
                    </div>
                    <input type="hidden" id="garden_latitude" name="latitude" value="" />
                    <input type="hidden" id="garden_longitude" name="longitude" value="" />
                    
                    <p class="text-[11.5px] sm:text-[12px] text-on-surface-variant flex items-center gap-1.5 mt-0.5" id="location-hint">
                        <span class="material-symbols-outlined text-[15px] text-primary shrink-0">info</span>
                        <span>Gunakan tombol Deteksi GPS untuk mengisi nama kota dan koordinat cuaca secara otomatis.</span>
                    </p>
                </div>

                {{-- Action Navigation --}}
                <div class="pt-3 sm:pt-4 flex justify-end w-full">
                    <button type="button" onclick="goToStep(2)" class="w-full sm:w-auto bg-primary text-on-primary font-bold text-sm px-8 py-3.5 rounded-full hover:bg-primary/90 active:scale-98 transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                        Lanjut ke Pengalaman
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════
                 STEP 2: PENGALAMAN BERKEBUN
            ══════════════════════════════════════════════════ --}}
            <div id="step-2" class="step-pane hidden flex-col gap-5 sm:gap-6 w-full">
                <div class="w-full">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary mb-1.5 sm:mb-2">
                        <span class="material-symbols-outlined text-[16px]">history_edu</span> Riwayat Pengalaman
                    </span>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-1.5 leading-tight">
                        Seberapa Lama Anda Sudah Berkebun?
                    </h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Tingkat pengalaman membantu sistem menyesuaikan petunjuk dan frekuensi panduan teknis perawatan.
                    </p>
                </div>

                {{-- 4 Experience Level Option Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3.5 w-full">
                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2 w-full" onclick="selectExperience('beginner', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">spa</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Pemula (Kurang dari 3 Bulan)</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Baru mulai menyemai benih dan membutuhkan panduan dasar penyiraman serta pencahayaan.
                        </p>
                    </div>

                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2 w-full" onclick="selectExperience('learning', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">psychology</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Menengah (3–12 Bulan)</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Sudah menanam beberapa jenis sayuran, namun masih membutuhkan pengingat jadwal dan penanganan hama.
                        </p>
                    </div>

                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2 w-full" onclick="selectExperience('intermediate', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">yard</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Berpengalaman (1–3 Tahun)</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Rutin merawat berbagai tanaman sayur dan buah, fokus pada efisiensi pemupukan dan waktu panen.
                        </p>
                    </div>

                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2 w-full" onclick="selectExperience('pro', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">agriculture</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Pengelola Kebun (Lebih dari 3 Tahun)</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Mengelola banyak bedengan atau instalasi hidroponik dengan kebutuhan otomasi dan integrasi cuaca.
                        </p>
                    </div>
                </div>

                <input type="hidden" id="gardening_experience" name="gardening_experience" value="beginner" />

                {{-- Action Navigation --}}
                <div class="pt-3 sm:pt-4 flex items-center justify-between gap-3 w-full">
                    <button type="button" onclick="goToStep(1)" class="text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface px-4 py-2.5 rounded-full transition-colors flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="goToStep(3)" class="text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface px-3 sm:px-4 py-2.5 rounded-full transition-colors cursor-pointer">
                            Lewati
                        </button>
                        <button type="button" onclick="goToStep(3)" class="bg-primary text-on-primary font-bold text-xs sm:text-sm px-6 sm:px-8 py-3.5 rounded-full hover:bg-primary/90 active:scale-98 transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                            Lanjut ke Target Skala
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════
                 STEP 3: SKALA & KAPASITAS KEBUN
            ══════════════════════════════════════════════════ --}}
            <div id="step-3" class="step-pane hidden flex-col gap-5 sm:gap-6 w-full">
                <div class="w-full">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary mb-1.5 sm:mb-2">
                        <span class="material-symbols-outlined text-[16px]">equalizer</span> Target Kapasitas
                    </span>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-1.5 leading-tight">
                        Jumlah Tanaman yang Dikelola
                    </h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Pilih rentang kapasitas tanaman untuk menentukan batas kuota dan struktur kebun Anda.
                    </p>
                </div>

                {{-- Scale Options --}}
                <div class="flex flex-col gap-3 sm:gap-3.5 w-full">
                    {{-- Option 1: 1 - 10 Plants --}}
                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 sm:p-5 bg-surface-container-lowest flex items-start justify-between gap-3.5 w-full" onclick="selectScale('1-10', this)">
                        <div class="flex items-start gap-3 sm:gap-3.5 min-w-0">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[22px] sm:text-[24px]">potted_plant</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">1 – 10 Tanaman Aktif</h4>
                                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant whitespace-nowrap">Paket Bibit (Gratis)</span>
                                </div>
                                <p class="text-xs text-on-surface-variant leading-relaxed">
                                    Cocok untuk kebun mini di teras atau balkon dengan kebutuhan pencatatan dasar.
                                </p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-outline-variant check-icon text-[22px] shrink-0 mt-1">radio_button_unchecked</span>
                    </div>

                    {{-- Option 2: 10 - 50 Plants --}}
                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 sm:p-5 bg-surface-container-lowest flex items-start justify-between gap-3.5 w-full" onclick="selectScale('10-50', this)">
                        <div class="flex items-start gap-3 sm:gap-3.5 min-w-0">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[22px] sm:text-[24px]">yard</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">10 – 50 Tanaman (Multikebun)</h4>
                                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20 whitespace-nowrap">Rekomendasi Paket Subur (Pro)</span>
                                </div>
                                <p class="text-xs text-on-surface-variant leading-relaxed">
                                    Kapasitas hingga 10 kebun dan 100 tanaman dengan penyesuaian cuaca otomatis.
                                </p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-outline-variant check-icon text-[22px] shrink-0 mt-1">radio_button_unchecked</span>
                    </div>

                    {{-- Option 3: > 50 Plants --}}
                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 sm:p-5 bg-surface-container-lowest flex items-start justify-between gap-3.5 w-full" onclick="selectScale('50+', this)">
                        <div class="flex items-start gap-3 sm:gap-3.5 min-w-0">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[22px] sm:text-[24px]">workspace_premium</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Lebih dari 50 Tanaman / Skala Komersial</h4>
                                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 whitespace-nowrap">Paket Panen Raya (Premium)</span>
                                </div>
                                <p class="text-xs text-on-surface-variant leading-relaxed">
                                    Kapasitas hingga 100 kebun dan tanaman tanpa batas untuk perkebunan produktif.
                                </p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-outline-variant check-icon text-[22px] shrink-0 mt-1">radio_button_unchecked</span>
                    </div>
                </div>

                <input type="hidden" id="gardening_scale" name="gardening_scale" value="10-50" />

                {{-- Action Navigation --}}
                <div class="pt-3 sm:pt-4 flex items-center justify-between gap-3 w-full">
                    <button type="button" onclick="goToStep(2)" class="text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface px-4 py-2.5 rounded-full transition-colors flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="goToStep(4)" class="text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface px-3 sm:px-4 py-2.5 rounded-full transition-colors cursor-pointer">
                            Lewati
                        </button>
                        <button type="button" onclick="goToStep(4)" class="bg-primary text-on-primary font-bold text-xs sm:text-sm px-6 sm:px-8 py-3.5 rounded-full hover:bg-primary/90 active:scale-98 transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                            Lanjut ke Kendala Utama
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════
                 STEP 4: KENDALA UTAMA & FITUR PERAWATAN
            ══════════════════════════════════════════════════ --}}
            <div id="step-4" class="step-pane hidden flex-col gap-5 sm:gap-6 w-full">
                <div class="w-full">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary mb-1.5 sm:mb-2">
                        <span class="material-symbols-outlined text-[16px]">troubleshoot</span> Kendala Perawatan
                    </span>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-1.5 leading-tight">
                        Kendala Utama dalam Perawatan Tanaman
                    </h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Pilih tantangan yang paling sering Anda alami agar sistem menyiapkan pengingat dan parameter yang relevan.
                    </p>
                </div>

                {{-- Pain Point Options --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3.5 w-full">
                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2.5 w-full" onclick="selectGoal('automation', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">alarm</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Lupa Jadwal Siram atau Pupuk</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Jadwal perawatan terlewat karena kesibukan harian sehingga tanaman layu atau kurang nutrisi.
                        </p>
                        <div class="mt-auto pt-2 border-t border-outline-variant/20 flex items-center gap-1 text-[11px] font-bold text-primary">
                            <span class="material-symbols-outlined text-[14px]">task_alt</span> Fitur: Jadwal Perawatan Otomatis
                        </div>
                    </div>

                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2.5 w-full" onclick="selectGoal('weather', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">cloud_sync</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Pengaruh Cuaca dan Hujan</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Kelebihan air saat musim hujan atau kekeringan saat kemarau panjang tanpa penyesuaian dosis air.
                        </p>
                        <div class="mt-auto pt-2 border-t border-outline-variant/20 flex items-center gap-1 text-[11px] font-bold text-primary">
                            <span class="material-symbols-outlined text-[14px]">thermostat</span> Fitur: Penyesuaian Cuaca Otomatis
                        </div>
                    </div>

                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2.5 w-full" onclick="selectGoal('pest', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">pest_control</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Penanganan Hama dan Penyakit</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Kesulitan mengidentifikasi gejala penyakit daun, kutu tanaman, atau dosis pestisida organik.
                        </p>
                        <div class="mt-auto pt-2 border-t border-outline-variant/20 flex items-center gap-1 text-[11px] font-bold text-primary">
                            <span class="material-symbols-outlined text-[14px]">menu_book</span> Fitur: Katalog Aturan dan Organisme
                        </div>
                    </div>

                    <div class="option-card border border-outline-variant/60 rounded-2xl p-4 bg-surface-container-lowest flex flex-col gap-2.5 w-full" onclick="selectGoal('tracking', this)">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold shrink-0">
                                <span class="material-symbols-outlined text-[22px]">calendar_month</span>
                            </div>
                            <span class="material-symbols-outlined text-outline-variant check-icon text-[20px]">radio_button_unchecked</span>
                        </div>
                        <h4 class="font-bold text-sm sm:text-base text-on-surface leading-tight">Pencatatan Riwayat dan Panen</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Tidak memiliki catatan umur tanaman (HST) dan perkiraan tanggal panen yang terstruktur.
                        </p>
                        <div class="mt-auto pt-2 border-t border-outline-variant/20 flex items-center gap-1 text-[11px] font-bold text-primary">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span> Fitur: Kalender Pertumbuhan
                        </div>
                    </div>
                </div>

                <input type="hidden" id="gardening_goal" name="gardening_goal" value="automation" />

                {{-- Action Navigation --}}
                <div class="pt-3 sm:pt-4 flex items-center justify-between gap-3 w-full">
                    <button type="button" onclick="goToStep(3)" class="text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface px-4 py-2.5 rounded-full transition-colors flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="calculateAndShowRecommendation()" class="text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface px-3 sm:px-4 py-2.5 rounded-full transition-colors cursor-pointer">
                            Lewati
                        </button>
                        <button type="button" onclick="calculateAndShowRecommendation()" class="bg-primary text-on-primary font-bold text-xs sm:text-sm px-6 sm:px-8 py-3.5 rounded-full hover:bg-primary/90 active:scale-98 transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                            Lihat Rekomendasi
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════
                 STEP 5: HASIL ANALISIS & REKOMENDASI PAKET
            ══════════════════════════════════════════════════ --}}
            <div id="step-5" class="step-pane hidden flex-col gap-5 sm:gap-6 w-full">
                
                {{-- Analyzing Loader State --}}
                <div id="rec-loading" class="flex flex-col items-center justify-center py-12 w-full">
                    <x-brand-loader size="md" text="Menyiapkan rekomendasi sistem kebun Anda..." />
                </div>

                {{-- Recommended Content State --}}
                <div id="rec-content" class="hidden flex-col gap-5 sm:gap-6 w-full">
                    <div class="w-full">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary mb-1.5 sm:mb-2">
                            <span class="material-symbols-outlined text-[16px]">verified</span> Rekomendasi Paket
                        </span>
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-1.5 leading-tight" id="rec-headline">
                            Paket Subur (Pro) Direkomendasikan
                        </h2>
                        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed" id="rec-subtext">
                            Berdasarkan skala kebun dan fokus perawatan Anda, fitur jadwal otomatis dan penyesuaian cuaca akan membantu menjaga konsistensi hasil kebun.
                        </p>
                    </div>

                    {{-- Dynamic Recommendation Card --}}
                    <div id="rec-gradient-box" class="rounded-2xl sm:rounded-3xl p-5 sm:p-7 bg-gradient-to-br from-[#004d34] to-[#006c49] text-white shadow-xl relative overflow-hidden flex flex-col gap-5 border border-white/20 w-full">
                        {{-- Background Pattern --}}
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl"></div>
                        <div class="absolute -left-10 -top-10 w-48 h-48 bg-emerald-300/15 rounded-full blur-2xl"></div>

                        <div class="relative z-10 flex flex-col gap-4 w-full">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span id="rec-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-400 text-yellow-950 font-black text-[11px] sm:text-xs uppercase tracking-wider shadow-xs whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[15px]">star</span>
                                    Rekomendasi Paket
                                </span>
                                <div class="text-right flex items-baseline gap-2">
                                    <span class="text-xs text-white/80 line-through" id="rec-old-price">Rp 29.000/bln</span>
                                    <span class="text-base sm:text-xl font-black text-yellow-300 whitespace-nowrap" id="rec-price">Rp 199.000 / thn</span>
                                </div>
                            </div>

                            <h3 class="text-lg sm:text-2xl font-black leading-tight" id="rec-plan-title">Paket Subur (Pro)</h3>

                            {{-- Benefits Matrix --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 pt-1 sm:pt-2 w-full">
                                <div class="flex items-start gap-2.5 bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15 min-w-0">
                                    <span class="material-symbols-outlined text-yellow-300 text-[20px] shrink-0 mt-0.5">checklist</span>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-white leading-tight">Jadwal Perawatan Otomatis</h5>
                                        <p class="text-[11px] text-white/80 leading-relaxed mt-0.5">Jadwal siram dan pemupukan dibuat teratur tanpa input manual.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-2.5 bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15 min-w-0">
                                    <span class="material-symbols-outlined text-yellow-300 text-[20px] shrink-0 mt-0.5">cloud_done</span>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-white leading-tight">Penyesuaian Cuaca Otomatis</h5>
                                        <p class="text-[11px] text-white/80 leading-relaxed mt-0.5">Frekuensi penyiraman otomatis disesuaikan saat curah hujan berubah.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-2.5 bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15 min-w-0">
                                    <span class="material-symbols-outlined text-yellow-300 text-[20px] shrink-0 mt-0.5">all_inclusive</span>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-white leading-tight" id="rec-cap-title">Kapasitas 10 Kebun & 100 Tanaman</h5>
                                        <p class="text-[11px] text-white/80 leading-relaxed mt-0.5">Kapasitas luas untuk mencatat tanaman aktif di berbagai lokasi.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-2.5 bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15 min-w-0">
                                    <span class="material-symbols-outlined text-yellow-300 text-[20px] shrink-0 mt-0.5">calendar_month</span>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-white leading-tight">Kalender Pertumbuhan Tanaman</h5>
                                        <p class="text-[11px] text-white/80 leading-relaxed mt-0.5">Memantau hari setelah tanam dan estimasi panen setiap varietas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Choice CTAs --}}
                    <div class="flex flex-col gap-2.5 sm:gap-3 pt-1 w-full">
                        {{-- Paid Upgrade Button --}}
                        <button type="button" id="btn-activate-pro" onclick="submitOnboarding(recommendedPlan, 'yearly')" class="w-full bg-[#006c49] text-white font-extrabold text-sm sm:text-base py-3.5 sm:py-4 rounded-2xl hover:bg-[#005236] active:scale-98 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.3)] flex items-center justify-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-[20px] sm:text-[22px]">rocket_launch</span>
                            <span id="btn-pro-text">Aktifkan Paket Subur (Pro) — Rp 199.000 / thn</span>
                        </button>

                        {{-- Alternative Free Button --}}
                        <button type="button" id="btn-activate-free" onclick="submitOnboarding('free', 'monthly')" class="w-full bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-bold text-xs sm:text-sm py-3 sm:py-3.5 rounded-2xl transition-colors flex items-center justify-center gap-2 cursor-pointer border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[18px] text-primary">eco</span>
                            <span>Lanjut dengan Paket Bibit (Gratis)</span>
                        </button>
                    </div>
                </div>

            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
    let currentStep = 1;
    let selectedExperience = 'beginner';
    let selectedScale = '10-50';
    let selectedGoal = 'automation';
    let recommendedPlan = 'subur';

    // ── Garden Name Suggester ──
    function setGardenName(name) {
        const input = document.getElementById('garden_name');
        if (input) {
            input.value = name;
            input.focus();
        }
    }

    // ── Fast Location Detector ──
    async function detectGPSLocation() {
        const btn = document.getElementById('btn-detect-gps');
        const btnText = document.getElementById('gps-btn-text');
        const icon = document.getElementById('gps-icon');
        const input = document.getElementById('garden_location');
        const latInput = document.getElementById('garden_latitude');
        const lngInput = document.getElementById('garden_longitude');
        const hint = document.getElementById('location-hint');

        btn.disabled = true;
        btnText.textContent = 'Mendeteksi...';
        icon.classList.add('animate-spin');
        hint.innerHTML = '<span class="material-symbols-outlined text-[15px] text-primary animate-spin">sync</span> Mencari koordinat GPS dan lokasi...';

        try {
            let coords = null;
            if (navigator.geolocation) {
                coords = await new Promise((resolve) => {
                    let done = false;
                    const timer = setTimeout(() => { if (!done) { done = true; resolve(null); } }, 3500);
                    navigator.geolocation.getCurrentPosition(
                        (pos) => { if (!done) { done = true; clearTimeout(timer); resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude }); } },
                        () => { if (!done) { done = true; clearTimeout(timer); resolve(null); } },
                        { enableHighAccuracy: false, timeout: 3000, maximumAge: 60000 }
                    );
                });
            }

            if (coords) {
                latInput.value = coords.lat;
                lngInput.value = coords.lon;
                
                try {
                    const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${coords.lat}&lon=${coords.lon}&zoom=10`, {
                        headers: { 'Accept-Language': 'id, en' }
                    });
                    if (resp.ok) {
                        const data = await resp.json();
                        const addr = data.address || {};
                        const city = addr.city || addr.town || addr.municipality || addr.city_district || addr.county || 'Kota Terdeteksi';
                        const state = addr.state || addr.region || city;
                        input.value = `${city}, ${state}`;
                        hint.innerHTML = `<span class="material-symbols-outlined text-[15px] text-primary">check_circle</span> Lokasi GPS terdeteksi: <strong>${input.value}</strong>`;
                        btnText.textContent = 'Terdeteksi';
                        icon.classList.remove('animate-spin');
                        btn.disabled = false;
                        return;
                    }
                } catch(e) {}
            }

            // Fallback IP
            const ipResp = await fetch('https://ipwho.is/');
            if (ipResp.ok) {
                const ipData = await ipResp.json();
                if (ipData.success) {
                    input.value = `${ipData.city || 'Kota'}, ${ipData.region || 'Indonesia'}`;
                    latInput.value = ipData.latitude || 0;
                    lngInput.value = ipData.longitude || 0;
                    hint.innerHTML = `<span class="material-symbols-outlined text-[15px] text-primary">check_circle</span> Lokasi terdeteksi: <strong>${input.value}</strong>`;
                    btnText.textContent = 'Terdeteksi';
                    icon.classList.remove('animate-spin');
                    btn.disabled = false;
                    return;
                }
            }

            // Fallback default
            input.value = 'Jakarta Selatan, DKI Jakarta';
            hint.innerHTML = `<span class="material-symbols-outlined text-[15px] text-primary">check_circle</span> Lokasi: <strong>${input.value}</strong>`;
            btnText.textContent = 'Deteksi GPS';
            icon.classList.remove('animate-spin');
            btn.disabled = false;

        } catch (err) {
            console.warn('GPS error:', err);
            input.value = 'Jakarta Selatan, DKI Jakarta';
            hint.textContent = 'Gagal mendeteksi otomatis. Anda dapat mengetik lokasi secara manual.';
            btnText.textContent = 'Deteksi GPS';
            icon.classList.remove('animate-spin');
            btn.disabled = false;
        }
    }

    // ── Option Selectors ──
    function selectExperience(val, elem) {
        selectedExperience = val;
        document.getElementById('gardening_experience').value = val;
        document.querySelectorAll('#step-2 .option-card').forEach(el => {
            el.classList.remove('selected');
            el.querySelector('.check-icon').textContent = 'radio_button_unchecked';
            el.querySelector('.check-icon').classList.remove('text-primary');
            el.querySelector('.check-icon').classList.add('text-outline-variant');
        });
        elem.classList.add('selected');
        elem.querySelector('.check-icon').textContent = 'check_circle';
        elem.querySelector('.check-icon').classList.remove('text-outline-variant');
        elem.querySelector('.check-icon').classList.add('text-primary');
    }

    function selectScale(val, elem) {
        selectedScale = val;
        document.getElementById('gardening_scale').value = val;
        document.querySelectorAll('#step-3 .option-card').forEach(el => {
            el.classList.remove('selected');
            el.querySelector('.check-icon').textContent = 'radio_button_unchecked';
            el.querySelector('.check-icon').classList.remove('text-primary');
            el.querySelector('.check-icon').classList.add('text-outline-variant');
        });
        elem.classList.add('selected');
        elem.querySelector('.check-icon').textContent = 'check_circle';
        elem.querySelector('.check-icon').classList.remove('text-outline-variant');
        elem.querySelector('.check-icon').classList.add('text-primary');
    }

    function selectGoal(val, elem) {
        selectedGoal = val;
        document.getElementById('gardening_goal').value = val;
        document.querySelectorAll('#step-4 .option-card').forEach(el => {
            el.classList.remove('selected');
            el.querySelector('.check-icon').textContent = 'radio_button_unchecked';
            el.querySelector('.check-icon').classList.remove('text-primary');
            el.querySelector('.check-icon').classList.add('text-outline-variant');
        });
        elem.classList.add('selected');
        elem.querySelector('.check-icon').textContent = 'check_circle';
        elem.querySelector('.check-icon').classList.remove('text-outline-variant');
        elem.querySelector('.check-icon').classList.add('text-primary');
    }

    // ── Navigation Flow ──
    function goToStep(step) {
        // Validate step 1 fields
        if (step > 1 && currentStep === 1) {
            const userName = document.getElementById('user_name').value.trim();
            const name = document.getElementById('garden_name').value.trim();
            const loc = document.getElementById('garden_location').value.trim();

            if (!userName) {
                if (window.Alert) Alert.warning('Nama Lengkap Diperlukan', 'Silakan isi nama lengkap atau panggilan Anda.');
                else alert('Silakan isi nama lengkap atau panggilan Anda.');
                document.getElementById('user_name').focus();
                return;
            }
            if (!name) {
                if (window.Alert) Alert.warning('Nama Kebun Diperlukan', 'Silakan isi nama kebun pertama Anda.');
                else alert('Silakan isi nama kebun pertama Anda.');
                document.getElementById('garden_name').focus();
                return;
            }
            if (!loc) {
                if (window.Alert) Alert.warning('Lokasi Diperlukan', 'Silakan isi lokasi atau gunakan tombol Deteksi GPS.');
                else alert('Silakan isi lokasi atau gunakan tombol Deteksi GPS.');
                document.getElementById('garden_location').focus();
                return;
            }
        }

        document.querySelectorAll('.step-pane').forEach(el => el.classList.add('hidden'));
        const activePane = document.getElementById(`step-${step}`);
        if (activePane) {
            activePane.classList.remove('hidden');
            activePane.classList.add('flex');
        }

        currentStep = step;
        document.getElementById('step-badge').textContent = `Langkah ${step} dari 5`;

        // Update Stepper Navigation Pills
        for (let i = 1; i <= 5; i++) {
            const pill = document.getElementById(`pill-${i}`);
            if (!pill) continue;
            const bar = pill.querySelector('.pill-bar');
            const label = pill.querySelector('span');

            if (i <= step) {
                bar.classList.add('bg-primary');
                bar.classList.remove('bg-surface-container-high');
                if (label) {
                    label.classList.add('text-primary', 'font-bold');
                    label.classList.remove('text-on-surface-variant', 'font-semibold');
                }
            } else {
                bar.classList.remove('bg-primary');
                bar.classList.add('bg-surface-container-high');
                if (label) {
                    label.classList.remove('text-primary', 'font-bold');
                    label.classList.add('text-on-surface-variant', 'font-semibold');
                }
            }
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── Dynamic Recommendation Pitch Calculator ──
    function calculateAndShowRecommendation() {
        goToStep(5);

        const loading = document.getElementById('rec-loading');
        const content = document.getElementById('rec-content');
        loading.classList.remove('hidden');
        loading.classList.add('flex');
        content.classList.add('hidden');
        content.classList.remove('flex');

        if (selectedScale === '50+') {
            recommendedPlan = 'pro'; // Panen raya premium
        } else {
            recommendedPlan = 'subur'; // Subur pro
        }

        setTimeout(() => {
            loading.classList.add('hidden');
            loading.classList.remove('flex');
            content.classList.remove('hidden');
            content.classList.add('flex');

            const headline = document.getElementById('rec-headline');
            const subtext = document.getElementById('rec-subtext');
            const planTitle = document.getElementById('rec-plan-title');
            const price = document.getElementById('rec-price');
            const btnProText = document.getElementById('btn-pro-text');
            const capTitle = document.getElementById('rec-cap-title');
            const gradBox = document.getElementById('rec-gradient-box');

            if (recommendedPlan === 'pro') {
                headline.textContent = 'Paket Panen Raya (Premium) Direkomendasikan';
                subtext.textContent = 'Untuk kebutuhan pengelolaan lebih dari 50 tanaman, Paket Panen Raya menyediakan kapasitas hingga 100 kebun dan tanaman tanpa batas.';
                planTitle.textContent = 'Paket Panen Raya (Premium)';
                price.textContent = 'Rp 799.000 / thn';
                btnProText.textContent = 'Aktifkan Paket Panen Raya — Rp 799.000 / thn';
                capTitle.textContent = 'Kapasitas 100 Kebun & Tanaman Tanpa Batas';
                if (gradBox) gradBox.className = 'rounded-2xl sm:rounded-3xl p-5 sm:p-7 bg-gradient-to-br from-[#0f172a] to-[#1e293b] text-white shadow-xl relative overflow-hidden flex flex-col gap-5 border border-white/20 w-full';
            } else {
                headline.textContent = 'Paket Subur (Pro) Direkomendasikan';
                subtext.textContent = 'Berdasarkan skala kebun dan fokus perawatan Anda, jadwal otomatis dan penyesuaian cuaca akan membantu menjaga konsistensi hasil kebun.';
                planTitle.textContent = 'Paket Subur (Pro)';
                price.textContent = 'Rp 199.000 / thn';
                btnProText.textContent = 'Aktifkan Paket Subur (Pro) — Rp 199.000 / thn';
                capTitle.textContent = 'Kapasitas 10 Kebun & 100 Tanaman Aktif';
                if (gradBox) gradBox.className = 'rounded-2xl sm:rounded-3xl p-5 sm:p-7 bg-gradient-to-br from-[#004d34] to-[#006c49] text-white shadow-xl relative overflow-hidden flex flex-col gap-5 border border-white/20 w-full';
            }
        }, 800);
    }

    // ── Submission Handler ──
    async function submitOnboarding(planChoice, billingCycle = 'yearly') {
        const userName = document.getElementById('user_name').value.trim();
        const gardenName = document.getElementById('garden_name').value.trim() || 'Kebun Saya';
        const location = document.getElementById('garden_location').value.trim() || 'Jakarta Selatan, DKI Jakarta';
        const latitude = document.getElementById('garden_latitude').value || null;
        const longitude = document.getElementById('garden_longitude').value || null;

        if (!userName) {
            goToStep(1);
            if (window.Alert) Alert.warning('Nama Lengkap Diperlukan', 'Silakan isi nama lengkap Anda.');
            document.getElementById('user_name').focus();
            return;
        }

        const payload = {
            user_name: userName,
            garden_name: gardenName,
            location: location,
            latitude: latitude,
            longitude: longitude,
            gardening_experience: selectedExperience,
            gardening_scale: selectedScale,
            gardening_goal: selectedGoal,
            selected_plan: planChoice,
            billing_cycle: billingCycle,
        };

        if (window.LoadingOverlay) LoadingOverlay.show('Menyimpan data kebun Anda...');

        try {
            const resp = await fetch('/onboarding', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await resp.json();

            if (!resp.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data.');
            }

            // Save location to local storage for instant live weather sync
            const syncLoc = {
                lat: latitude || 3.58,
                lon: longitude || 98.67,
                name: location,
                formatted: location
            };
            localStorage.setItem('garden_location', JSON.stringify(syncLoc));

            if (planChoice !== 'free' && window.Alert && Alert.premium) {
                const planName = planChoice === 'pro' ? 'Panen Raya (Premium)' : 'Subur (Pro)';
                Alert.premium.celebrate(planName, 'Paket langganan Anda telah aktif bersamaan dengan kebun pertama Anda!', data.redirect_url || '/dashboard');
            } else {
                window.location.href = data.redirect_url || '/dashboard';
            }

        } catch (err) {
            if (window.LoadingOverlay) LoadingOverlay.hide();
            if (window.Alert) {
                Alert.error('Gagal Menyimpan', err.message || 'Terjadi kesalahan jaringan.');
            } else {
                alert(err.message || 'Terjadi kesalahan jaringan.');
            }
        }
    }

    // ── Skip Onboarding Handler ──
    async function skipOnboarding() {
        const userNameInput = document.getElementById('user_name');
        const userName = userNameInput ? userNameInput.value.trim() : '';

        if (window.LoadingOverlay) LoadingOverlay.show('Menyiapkan dashboard Anda...');

        try {
            const resp = await fetch('/onboarding/skip', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ user_name: userName })
            });

            const data = await resp.json();
            window.location.href = data.redirect_url || '/dashboard';
        } catch (err) {
            window.location.href = '/dashboard';
        }
    }

    // Auto-select initial options
    document.addEventListener('DOMContentLoaded', () => {
        const expCards = document.querySelectorAll('#step-2 .option-card');
        if (expCards.length > 0) selectExperience('beginner', expCards[0]);

        const scaleCards = document.querySelectorAll('#step-3 .option-card');
        if (scaleCards.length > 1) selectScale('10-50', scaleCards[1]);

        const goalCards = document.querySelectorAll('#step-4 .option-card');
        if (goalCards.length > 0) selectGoal('automation', goalCards[0]);

        // Trigger auto-detect GPS gently in background
        detectGPSLocation();
    });
</script>
@endpush
@endsection
