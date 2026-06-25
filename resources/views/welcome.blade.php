@extends('layouts.app')

@section('title', 'Grow a Garden — Kebun Digital di Genggaman Anda')
@section('description', 'Kelola kebun rumahan, urban farming, atau hidroponik dengan pemetaan cerdas dan kalender pertumbuhan otomatis.')

@section('content')
{{-- ============================================
     NAVIGATION BAR
     ============================================ --}}
<header id="navbar" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-outline-variant/30">
    <div class="max-w-[1280px] mx-auto flex items-center justify-between px-5 lg:px-8 h-16">
        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2 group" id="nav-logo">
            <span class="material-symbols-outlined text-primary text-[28px] transition-transform duration-200 group-hover:scale-110">yard</span>
            <span class="text-lg font-bold text-on-surface tracking-tight">Grow a Garden</span>
        </a>

        {{-- Desktop Nav Links --}}
        <nav class="hidden md:flex items-center gap-8" id="nav-links">
            <a href="#features" class="nav-link active text-sm font-semibold text-primary transition-colors duration-200">Features</a>
            <a href="#how-it-works" class="nav-link text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200">How It Works</a>
        </nav>

        {{-- CTA Button --}}
        <a href="/login" class="hidden md:inline-flex items-center gap-2 bg-primary text-on-primary text-sm font-semibold px-6 py-2.5 rounded-full hover:bg-primary/90 active:scale-[0.97] transition-all duration-200 shadow-sm" id="nav-cta">
            Get Started
        </a>

        {{-- Mobile Menu Toggle --}}
        <button class="md:hidden text-on-surface-variant p-2 rounded-lg hover:bg-surface-container-high transition-colors" id="mobile-menu-toggle" aria-label="Open navigation menu">
            <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-outline-variant/30 px-5 py-4 space-y-2">
        <a href="#features" class="block text-sm font-semibold text-primary py-2 px-4 rounded-lg bg-primary/5">Features</a>
        <a href="#how-it-works" class="block text-sm font-medium text-on-surface-variant py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors">How It Works</a>
        <a href="/login" class="block text-center bg-primary text-on-primary text-sm font-semibold px-6 py-2.5 rounded-full mt-2">Get Started</a>
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
                <div class="inline-flex items-center gap-2 bg-surface-container-low text-on-surface-variant text-xs font-medium px-4 py-2 rounded-full mb-6 border border-outline-variant/50">
                    <span class="material-symbols-outlined text-primary" style="font-size: 16px;">energy_savings_leaf</span>
                    Smart Gardening App
                </div>

                {{-- Headline --}}
                <h1 class="text-[40px] md:text-[48px] lg:text-[52px] font-bold text-on-surface leading-[1.1] tracking-tight mb-5">
                    Kebun Digital di<br>Genggaman Anda.
                </h1>

                {{-- Subtitle --}}
                <p class="text-base md:text-lg text-on-surface-variant leading-relaxed mb-8 max-w-[440px]">
                    Kelola kebun rumahan, urban farming, atau hidroponik dengan pemetaan cerdas dan kalender pertumbuhan otomatis.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-4">
                    <a href="/login" class="inline-flex items-center gap-2 bg-primary text-on-primary font-semibold px-7 py-3.5 rounded-full hover:bg-primary/90 active:scale-[0.97] transition-all duration-200 shadow-md text-sm" id="hero-cta-primary">
                        Mulai Berkebun Sekarang
                        <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center gap-2 border-2 border-outline-variant text-on-surface-variant font-semibold px-5 py-3 rounded-full hover:border-primary hover:text-primary transition-all duration-200 text-sm" id="hero-cta-secondary">
                        <span class="material-symbols-outlined text-primary" style="font-size: 20px;">play_circle</span>
                        Cara Kerja
                    </a>
                </div>
            </div>

            {{-- Right: Hero Image with Overlays --}}
            <div class="order-1 lg:order-2 relative">
                <div class="relative rounded-2xl overflow-hidden ambient-shadow-lg">
                    {{-- Garden Photo --}}
                    <img
                        src="/images/hero-garden.png"
                        alt="Kebun hijau dengan tanaman yang tumbuh subur dan teratur"
                        class="w-full h-auto object-cover aspect-[4/3]"
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
        <div class="text-center mb-12 md:mb-16 max-w-[672px] mx-auto">
            <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-4">
                Berkebun Lebih Mudah & Terukur
            </h2>
            <p class="text-base text-on-surface-variant leading-relaxed">
                Pengalaman mengelola kebun layaknya bermain game farming dengan fitur otomatisasi yang cerdas.
            </p>
        </div>

        {{-- Feature Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Feature 1: Garden Map --}}
            <div class="bg-white rounded-2xl p-6 ambient-shadow border border-outline-variant/20 hover:ambient-shadow-lg hover:-translate-y-1 transition-all duration-300 group" id="feature-garden-map">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/15 transition-colors">
                    <span class="material-symbols-outlined text-primary text-2xl">grid_view</span>
                </div>

                <h3 class="text-lg font-bold text-on-surface mb-2">Garden Map</h3>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-5">
                    Visual grid system (A1, B1, dll) untuk memetakan tanaman Anda layaknya game. Kelola tata letak dengan presisi.
                </p>

                {{-- Visual Preview: Mini Grid --}}
                <div class="flex gap-2">
                    <div class="w-16 h-16 rounded-lg bg-primary-container"></div>
                    <div class="w-16 h-16 rounded-lg bg-secondary-container"></div>
                    <div class="w-16 h-16 rounded-lg bg-surface-container-high border-2 border-dashed border-outline-variant"></div>
                </div>
            </div>

            {{-- Feature 2: Growth Calendar --}}
            <div class="bg-white rounded-2xl p-6 ambient-shadow border border-outline-variant/20 hover:ambient-shadow-lg hover:-translate-y-1 transition-all duration-300 group" id="feature-growth-calendar">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/15 transition-colors">
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
            <div class="bg-white rounded-2xl p-6 ambient-shadow border border-outline-variant/20 hover:ambient-shadow-lg hover:-translate-y-1 transition-all duration-300 group" id="feature-care-reminder">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center mb-4 group-hover:bg-secondary/15 transition-colors">
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
                <div class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <span class="material-symbols-outlined" style="font-size: 16px;">auto_awesome</span>
                    Smart Adaptation
                </div>

                <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-5 leading-tight">
                    Beradaptasi dengan<br>Cuaca Sekitar
                </h2>

                <p class="text-base text-on-surface-variant leading-relaxed mb-6 max-w-[512px]">
                    Sistem kami terhubung dengan data cuaca lokal. Jika terdeteksi musim hujan, jadwal penyiraman otomatis dikurangi. Saat kemarau, pengingat penyiraman akan lebih sering muncul untuk menjaga kelembaban tanah.
                </p>

                <a href="#" class="inline-flex items-center gap-2 text-primary font-semibold text-sm hover:gap-3 transition-all duration-200">
                    Pelajari Sistem Cerdas Kami
                    <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                </a>
            </div>

            {{-- Right: Weather Card --}}
            <div class="flex justify-center lg:justify-end">
                <div class="bg-white rounded-2xl p-6 ambient-shadow-lg border border-outline-variant/20 max-w-[448px] w-full">
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
     FOOTER
     ============================================ --}}
<footer class="bg-surface border-t border-outline-variant/30 py-6">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm font-bold text-on-surface italic">Grow a Garden</div>

        <nav class="flex items-center gap-6 text-xs font-medium text-on-surface-variant">
            <a href="#" class="hover:text-primary transition-colors">Sitemap</a>
            <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-primary transition-colors">Community</a>
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

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('[id^="feature-"]').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });

    // Add animate-in styles
    const style = document.createElement('style');
    style.textContent = `.animate-in { opacity: 1 !important; transform: translateY(0) !important; }`;
    document.head.appendChild(style);
</script>
@endpush
