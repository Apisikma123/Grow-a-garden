@extends('layouts.app')

@section('title', 'Grow a Garden — Kebun Digital di Genggaman Anda')
@section('description', 'Kelola kebun rumahan, urban farming, atau hidroponik dengan pemetaan cerdas dan kalender pertumbuhan otomatis.')

@push('head')
<style>
    /* Premium Scroll Animations */
    .scroll-trigger {
        opacity: 0;
        transition-property: opacity, transform;
        transition-duration: 0.8s;
        transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    
    .scroll-fade-up { transform: translateY(30px); }
    .scroll-fade-left { transform: translateX(30px); }
    .scroll-fade-right { transform: translateX(-30px); }
    .scroll-scale-in { transform: scale(0.95); }
    
    .scroll-trigger.scroll-in {
        opacity: 1;
        transform: translate(0) scale(1);
    }

    /* Stagger Delays */
    .delay-100 { transition-delay: 100ms; }
    .delay-200 { transition-delay: 200ms; }
    .delay-300 { transition-delay: 300ms; }
    .delay-400 { transition-delay: 400ms; }

    /* Premium Shadows & Tactile Feedback */
    .premium-shadow { box-shadow: 0 16px 40px rgba(0, 108, 73, 0.08); border-color: rgba(255,255,255,0.8); }
</style>
@endpush


@section('content')
{{-- ============================================
     NAVIGATION BAR
     ============================================ --}}
<header id="navbar" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-outline-variant/30">
    <div class="max-w-[1280px] mx-auto flex items-center justify-between px-5 lg:px-8 h-16">
        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2 group" id="nav-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Grow a Garden Logo" class="w-8 h-8 rounded-md transition-transform duration-200 group-hover:scale-110 object-contain">
            <span class="text-lg font-bold text-on-surface tracking-tight">Grow a Garden</span>
        </a>

        {{-- Desktop Nav Links --}}
        <nav class="hidden md:flex items-center gap-8" id="nav-links">
            <a href="#features" class="nav-link active text-sm font-semibold text-primary transition-colors duration-200">Fitur</a>
            <a href="#how-it-works" class="nav-link text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200">How It Works</a>
            <a href="#pricing" class="nav-link text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200">Harga</a>
        </nav>

        {{-- CTA Button --}}
        <a href="/login" class="hidden md:inline-flex items-center gap-2 bg-primary text-on-primary text-sm font-semibold px-6 py-2.5 rounded-full hover:bg-primary/90 active:scale-[0.97] transition-all duration-200 shadow-sm" id="nav-cta">
            Mulai Sekarang
        </a>

        {{-- Mobile Menu Toggle --}}
        <button class="md:hidden text-on-surface-variant p-2 rounded-lg hover:bg-surface-container-high transition-colors" id="mobile-menu-toggle" aria-label="Open navigation menu">
            <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-outline-variant/30 px-5 py-4 space-y-2">
        <a href="#features" class="block text-sm font-semibold text-primary py-2 px-4 rounded-lg bg-primary/5">Fitur</a>
        <a href="#how-it-works" class="block text-sm font-medium text-on-surface-variant py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors">How It Works</a>
        <a href="#pricing" class="block text-sm font-medium text-on-surface-variant py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors">Harga</a>
        <a href="/login" class="block text-center bg-primary text-on-primary text-sm font-semibold px-6 py-2.5 rounded-full mt-2">Mulai Sekarang</a>
    </div>
</header>

{{-- ============================================
     HERO SECTION
     ============================================ --}}
<section id="hero" class="relative overflow-hidden bg-surface">
    {{-- Subtle gradient background --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[60%] h-full bg-gradient-to-l from-primary/[0.04] to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-[40%] h-[60%] bg-gradient-to-tr from-primary/[0.03] to-transparent"></div>
    </div>

    <div class="relative max-w-[1280px] mx-auto px-5 lg:px-8 py-12 md:py-16 lg:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            {{-- Left: Text Content --}}
            <div class="order-2 lg:order-1">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-surface-container-low text-on-surface-variant text-xs font-medium px-4 py-2 rounded-full mb-6 border border-outline-variant/50 scroll-trigger scroll-fade-up">
                    <span class="material-symbols-outlined text-primary" style="font-size: 16px;">energy_savings_leaf</span>
                    Smart Gardening App
                </div>

                {{-- Headline --}}
                <h1 class="text-[40px] md:text-[48px] lg:text-[52px] font-bold text-on-surface leading-[1.1] tracking-tight mb-5 scroll-trigger scroll-fade-up delay-100">
                    Kebun Digital di<br>Genggaman Anda.
                </h1>

                {{-- Subtitle --}}
                <p class="text-base md:text-lg text-on-surface-variant leading-relaxed mb-8 max-w-[440px] scroll-trigger scroll-fade-up delay-200">
                    Kelola kebun rumahan, urban farming, atau hidroponik dengan pemetaan cerdas dan kalender pertumbuhan otomatis.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-4 scroll-trigger scroll-fade-up delay-300">
                    <a href="/login" class="group inline-flex items-center gap-2 bg-primary text-on-primary font-semibold px-7 py-3.5 rounded-full hover:bg-primary/90 hover:-translate-y-0.5 hover:shadow-lg active:scale-[0.98] transition-all duration-300 shadow-md text-sm" id="hero-cta-primary">
                        Mulai Berkebun Sekarang
                        <span class="material-symbols-outlined transition-transform duration-300 ease-out group-hover:translate-x-1" style="font-size: 20px;">arrow_forward</span>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center gap-2 border-2 border-outline-variant text-on-surface-variant font-semibold px-5 py-3 rounded-full hover:border-primary hover:text-primary hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-300 text-sm" id="hero-cta-secondary">
                        <span class="material-symbols-outlined text-primary" style="font-size: 20px;">play_circle</span>
                        Cara Kerja
                    </a>
                </div>
            </div>

            {{-- Right: Hero Image with Overlays --}}
            <div class="order-1 lg:order-2 relative scroll-trigger scroll-scale-in delay-200">
                <div class="relative rounded-2xl overflow-hidden ambient-shadow-lg group">
                    {{-- Garden Photo --}}
                    <img
                        src="/images/hero-garden.png"
                        alt="Kebun hijau dengan tanaman yang tumbuh subur dan teratur"
                        class="w-full h-auto object-cover aspect-[4/3] transition-transform duration-[1.5s] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.03]"
                        loading="eager"
                    />

                    {{-- Grid Overlay Effect --}}
                    <div class="absolute inset-0 opacity-20 pointer-events-none"
                        style="background-image: linear-gradient(rgba(16,185,129,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,0.3) 1px, transparent 1px); background-size: 60px 60px;">
                    </div>

                    {{-- Growth Phase Widget (Top Right) --}}
                    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm rounded-xl p-3 ambient-shadow min-w-[140px]">
                        <p class="text-xs font-medium text-on-surface-variant mb-1.5">Fase Pertumbuhan</p>
                        <div class="w-full bg-surface-container-high rounded-full h-2 mb-1">
                            <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: 65%;"></div>
                        </div>
                        <p class="text-right text-xs font-bold text-primary">65%</p>
                    </div>

                    {{-- Water Status Widget (Bottom Left) --}}
                    <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm rounded-xl px-4 py-2.5 ambient-shadow flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary" style="font-size: 22px;">water_drop</span>
                        <div>
                            <p class="text-xs text-on-surface-variant leading-none mb-0.5">Tomat Cherry (A1)</p>
                            <p class="text-sm font-semibold text-on-surface">Air Cukup</p>
                        </div>
                    </div>

                    {{-- Map Dots --}}
                    <div class="absolute top-1/3 left-1/3 w-3 h-3 bg-primary-container rounded-full ring-2 ring-white/60 animate-pulse"></div>
                    <div class="absolute top-2/3 right-1/3 w-3 h-3 bg-primary-container rounded-full ring-2 ring-white/60 animate-pulse" style="animation-delay: 0.5s;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
     FEATURES SECTION
     ============================================ --}}
<section id="features" class="bg-surface-container-low py-16 md:py-20 lg:py-24">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-12 md:mb-16 max-w-[672px] mx-auto scroll-trigger scroll-fade-up">
            <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-4">
                Berkebun Lebih Mudah & Terukur
            </h2>
            <p class="text-base text-on-surface-variant leading-relaxed">
                Pengalaman mengelola kebun layaknya bermain game farming dengan fitur otomatisasi yang cerdas.
            </p>
        </div>

        {{-- Feature Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Feature 1: Garden Canvas --}}
            <div class="bg-white rounded-3xl p-8 premium-shadow tactile-card group scroll-trigger scroll-fade-up delay-100 border border-white/60" id="feature-garden-canvas">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-1 group-hover:scale-105 group-hover:bg-primary/20">
                    <span class="material-symbols-outlined text-primary text-2xl">draw</span>
                </div>

                <h3 class="text-lg font-bold text-on-surface mb-2">Garden Canvas</h3>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-5">
                    Drag and create based on your real garden's layout. Visual grid system interaktif layaknya bermain game.
                </p>

                {{-- Visual Preview: Mini Grid --}}
                <div class="mt-4 rounded-xl overflow-hidden border border-outline-variant/30 shadow-sm">
                    <img src="{{ asset('images/garden-canvas-preview.png') }}" alt="Garden Canvas Preview" class="w-full h-auto object-cover">
                </div>
            </div>

            {{-- Feature 2: Growth Calendar --}}
            <div class="bg-white rounded-3xl p-8 premium-shadow tactile-card group scroll-trigger scroll-fade-up delay-200 border border-white/60" id="feature-growth-calendar">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-1 group-hover:scale-105 group-hover:bg-primary/20">
                    <span class="material-symbols-outlined text-primary text-2xl">calendar_month</span>
                </div>

                <h3 class="text-lg font-bold text-on-surface mb-2">Growth Calendar</h3>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-5">
                    Timeline otomatis yang memantau fase perkembangan tanaman dari penyemaian benih hingga waktu panen tiba.
                </p>

                {{-- Visual Preview: Timeline --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-primary-container flex-shrink-0"></div>
                        <div class="flex-1 bg-surface-container-high rounded-lg py-2 px-3">
                            <span class="text-xs font-medium text-on-surface-variant">Penyemaian (Hari 1)</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-tertiary-container flex-shrink-0"></div>
                        <div class="flex-1 bg-surface-container-high rounded-lg py-2 px-3">
                            <span class="text-xs font-medium text-on-surface-variant">Tunas (Hari 7)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Feature 3: Care Reminder --}}
            <div class="bg-white rounded-3xl p-8 premium-shadow tactile-card group scroll-trigger scroll-fade-up delay-300 border border-white/60" id="feature-care-reminder">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center mb-4 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-1 group-hover:scale-105 group-hover:bg-secondary/20">
                    <span class="material-symbols-outlined text-secondary text-2xl">notifications_active</span>
                </div>

                <h3 class="text-lg font-bold text-on-surface mb-2">Care Reminder</h3>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-5">
                    Notifikasi pintar untuk jadwal penyiraman dan pemupukan berdasarkan template jenis tanaman Anda.
                </p>

                {{-- Visual Preview: Status Chips --}}
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 bg-error-container text-on-error-container text-xs font-semibold px-3 py-1.5 rounded-full">
                        <span class="material-symbols-outlined" style="font-size: 14px;">warning</span>
                        Butuh Air
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-primary-fixed text-on-primary-fixed text-xs font-semibold px-3 py-1.5 rounded-full">
                        <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                        Sehat
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================
     SMART ADAPTATION SECTION
     ============================================ --}}
<section id="how-it-works" class="bg-surface py-16 md:py-20 lg:py-24">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Left: Text --}}
            <div>
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-4 py-2 rounded-full mb-6 scroll-trigger scroll-fade-up">
                    <span class="material-symbols-outlined" style="font-size: 16px;">auto_awesome</span>
                    Smart Adaptation
                </div>

                <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-5 leading-tight scroll-trigger scroll-fade-up delay-100">
                    Beradaptasi dengan<br>Cuaca Sekitar
                </h2>

                <p class="text-base text-on-surface-variant leading-relaxed mb-6 max-w-[512px] scroll-trigger scroll-fade-up delay-200">
                    Sistem kami terhubung dengan data cuaca lokal. Jika terdeteksi musim hujan, jadwal penyiraman otomatis dikurangi. Saat kemarau, pengingat penyiraman akan lebih sering muncul untuk menjaga kelembaban tanah.
                </p>

                <a href="#" class="inline-flex items-center gap-2 text-primary font-semibold text-sm hover:gap-3 transition-all duration-200 scroll-trigger scroll-fade-up delay-300">
                    Pelajari Sistem Cerdas Kami
                    <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                </a>
            </div>

            {{-- Right: Weather Card --}}
            <div class="flex justify-center lg:justify-end scroll-trigger scroll-fade-left delay-200">
                <div class="bg-white rounded-3xl p-8 premium-shadow border border-white/60 max-w-[448px] w-full tactile-card">
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 22px;">cloud</span>
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 22px;">water_drop</span>
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 22px;">thermostat</span>
                            <span class="text-sm font-bold text-on-surface">Prediksi Cuaca: Hujan</span>
                        </div>
                        <span class="text-xs font-semibold text-secondary bg-secondary-fixed px-3 py-1 rounded-full whitespace-nowrap">Hujan Ringan</span>
                    </div>

                    {{-- Card Body --}}
                    <div class="bg-surface-container-low rounded-xl p-4 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary flex-shrink-0 mt-0.5" style="font-size: 20px;">info</span>
                        <p class="text-sm text-on-surface-variant leading-relaxed">
                            Jadwal penyiraman otomatis ditunda hari ini karena curah hujan yang cukup.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
     PRICING SECTION
     ============================================ --}}
<section id="pricing" class="bg-surface-container-lowest py-16 md:py-20 lg:py-24 border-t border-outline-variant/20">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-12 md:mb-16 max-w-[672px] mx-auto scroll-trigger scroll-fade-up">
            <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-4">
                Pilih Paket Sesuai Kebutuhan Kebun Anda
            </h2>
            <p class="text-base text-on-surface-variant leading-relaxed">
                Mulai dari hobi kecil hingga komunitas hidroponik besar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Paket 1: Bibit --}}
            <div class="bg-white rounded-3xl p-8 premium-shadow border border-white/60 flex flex-col tactile-card scroll-trigger scroll-fade-up delay-100">
                <h3 class="text-xl font-bold text-on-surface mb-2">Bibit <span class="text-xs font-semibold bg-surface-container-high px-2 py-1 rounded-full text-on-surface-variant ml-2">Gratis</span></h3>
                <div class="flex items-baseline gap-1 mb-4">
                    <span class="text-[32px] font-black text-on-surface">Rp 0</span>
                </div>
                <p class="text-sm text-on-surface-variant mb-8 min-h-[40px]">Tetap dipertahankan sebagai umpan (Lead Magnet).</p>
                
                <div class="space-y-4 flex-1 mb-8">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">check_circle</span>
                        <span class="text-sm text-on-surface">Maks. 1 Garden, 4 Plot & 10 Tanaman Aktif</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">check_circle</span>
                        <span class="text-sm text-on-surface">Akses ke Growth Calendar</span>
                    </div>
                    
                    {{-- Kekurangan Sengaja --}}
                    <div class="mt-4 pt-4 border-t border-outline-variant/20 space-y-4 opacity-75">
                        <div class="flex items-start gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px] mt-0.5">cancel</span>
                            <span class="text-sm line-through">Otomatisasi Care Template</span>
                        </div>
                        <div class="flex items-start gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px] mt-0.5">warning</span>
                            <span class="text-sm">Care reminder isi manual</span>
                        </div>
                        <div class="flex items-start gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px] mt-0.5">cancel</span>
                            <span class="text-sm line-through">Penyesuaian Cuaca Lokal</span>
                        </div>
                    </div>
                </div>

                <a href="/login" class="w-full block text-center border-2 border-outline-variant text-on-surface font-bold py-3 rounded-xl hover:border-[#006c49] hover:text-[#006c49] hover:shadow-sm active:scale-[0.98] transition-all duration-300">Daftar Gratis</a>
            </div>

            {{-- Paket 2: Subur --}}
            <div class="bg-gradient-to-b from-[#006c49] to-[#005236] rounded-3xl p-8 shadow-[0_24px_48px_rgba(0,108,73,0.3)] flex flex-col relative border border-[#008c5f] scroll-trigger scroll-fade-up delay-200 tactile-card-featured">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md whitespace-nowrap flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px]">star</span>
                    Paling Populer - Best Value
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2">Subur</h3>
                
                <div class="bg-white/10 rounded-xl p-3 mb-4 backdrop-blur-sm">
                    <div class="mb-2">
                        <p class="text-[11px] text-white/80 font-medium uppercase tracking-wider mb-0.5">Paket Tahunan (Super Hemat)</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[28px] font-black text-yellow-400">Rp 199.000</span>
                            <span class="text-sm text-white/70 line-through">Rp 588.000</span>
                        </div>
                        <p class="text-xs text-white/90 font-medium bg-yellow-400/20 inline-block px-2 py-0.5 rounded text-yellow-300 mt-1">Setara Rp 16.500 / bln!</p>
                    </div>
                    <div class="h-px bg-white/20 w-full my-2"></div>
                    <div>
                        <p class="text-[10px] text-white/70 font-medium uppercase tracking-wider mb-0.5">Paket Bulanan</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[16px] font-bold text-white">Rp 29.000</span>
                            <span class="text-[11px] text-white/50 line-through">Rp 49k</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 flex-1 mb-8 mt-2">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">check_circle</span>
                        <span class="text-sm text-white font-medium">Maks. 10 Garden, 50 Plot & 100 Tanaman Aktif</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">smart_toy</span>
                        <div class="flex flex-col">
                            <span class="text-sm text-white font-bold">Asisten Autopilot</span>
                            <span class="text-xs text-white/80 mt-0.5">Rule Engine menghasilkan task perawatan otomatis berbasis Growth & Care Template.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">cloud_done</span>
                        <div class="flex flex-col">
                            <span class="text-sm text-white font-bold">Anti-Gagal Panen</span>
                            <span class="text-xs text-white/80 mt-0.5">Weather Adjustment (-30% penyiraman hujan, +50% kemarau).</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">emoji_events</span>
                        <span class="text-sm text-white font-medium">Notifikasi Upcoming Harvest di Dashboard</span>
                    </div>
                </div>

                <a href="/checkout?plan=subur" class="w-full block text-center bg-yellow-400 text-yellow-900 font-bold py-3 rounded-xl hover:bg-yellow-300 active:scale-[0.98] hover:shadow-lg transition-all duration-300 shadow-md mb-2 text-[15px]">Mulai 7-Day Free Trial</a>
                <p class="text-center text-[11px] text-white/70">Cancel anytime. Bebas risiko.</p>
            </div>

            {{-- Paket 3: Panen Raya --}}
            <div class="bg-white rounded-3xl p-8 premium-shadow border border-white/60 flex flex-col tactile-card relative overflow-hidden scroll-trigger scroll-fade-up delay-300">
                <div class="absolute -right-6 -top-6 bg-primary/10 w-24 h-24 rounded-full"></div>
                <h3 class="text-xl font-bold text-on-surface mb-2 relative z-10">Panen Raya <span class="text-xs font-semibold bg-primary-container text-on-primary-container px-2 py-1 rounded-full ml-2">Pro</span></h3>
                
                <div class="bg-surface-container-low rounded-xl p-3 mb-4 mt-2">
                    <div class="mb-2">
                        <p class="text-[11px] text-on-surface-variant font-medium uppercase tracking-wider mb-0.5">Paket Tahunan</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[28px] font-black text-on-surface">Rp 799.000</span>
                        </div>
                        <p class="text-xs font-bold text-[#006c49] mt-1 bg-primary/10 inline-block px-2 py-0.5 rounded">Hemat hampir 1 Juta Rupiah! (vs Rp 1.788k)</p>
                    </div>
                    <div class="h-px bg-outline-variant/30 w-full my-2"></div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-medium uppercase tracking-wider mb-0.5">Paket Bulanan</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[16px] font-bold text-on-surface">Rp 99.000</span>
                            <span class="text-[11px] text-on-surface-variant/60 line-through">Rp 149.000</span>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-on-surface-variant mb-6 relative z-10 font-medium">Skalabilitas maksimal untuk power user.</p>
                
                <div class="space-y-4 flex-1 mb-8 relative z-10">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">all_inclusive</span>
                        <span class="text-sm text-on-surface font-bold">Maks. 100 Garden, Unlimited Plot & Tanaman Aktif</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">check_circle</span>
                        <span class="text-sm text-on-surface">Seluruh fitur otomasi Paket Subur</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">history</span>
                        <div class="flex flex-col">
                            <span class="text-sm text-on-surface font-bold">Activity Log Tanpa Batas</span>
                            <span class="text-xs text-on-surface-variant mt-0.5">Tracking tak terbatas untuk menyiram, memupuk, memangkas, dll.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">groups</span>
                        <span class="text-sm text-on-surface font-medium">Ideal untuk Sekolah, Urban Farming besar & Komunitas</span>
                    </div>
                </div>

                <a href="/checkout?plan=pro" class="w-full block text-center bg-[#006c49] text-white font-bold py-3 rounded-xl hover:bg-[#005236] active:scale-[0.98] hover:shadow-lg transition-all duration-300 shadow-md relative z-10">Upgrade ke Pro</a>
            </div>

        </div>
    </div>
</section>

{{-- ============================================
     FOOTER
     ============================================ --}}
<footer class="bg-surface border-t border-outline-variant/30 py-6">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm font-bold text-on-surface italic">Grow a Garden</div>

        <nav class="flex items-center gap-6 text-xs font-medium text-on-surface-variant">
            <a href="#" class="hover:text-primary transition-colors">Sitemap</a>
            <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
            <a href="#" class="hover:text-primary transition-colors">Syarat Layanan</a>
            <a href="#" class="hover:text-primary transition-colors">Komunitas</a>
        </nav>

        <p class="text-xs text-on-surface-variant">&copy; {{ date('Y') }} Grow a Garden. All rights reserved.</p>
    </div>
</footer>
@endsection

@push('scripts')
<script>
    // Mobile menu toggle
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            const icon = toggle.querySelector('.material-symbols-outlined');
            icon.textContent = menu.classList.contains('hidden') ? 'menu' : 'close';
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Premium Scroll Animations
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
    };

    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('scroll-in');
                scrollObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-trigger').forEach(el => {
        scrollObserver.observe(el);
    });
</script>
@endpush
