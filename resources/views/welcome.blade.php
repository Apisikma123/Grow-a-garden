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
<header id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/30 backdrop-blur-xl border-b border-white/20 transition-all duration-300">
    <div class="max-w-[1280px] mx-auto flex items-center justify-between px-5 lg:px-8 h-16">
        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2 group" id="nav-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Grow a Garden Logo" class="w-8 h-8 rounded-md transition-transform duration-200 group-hover:scale-110 object-contain">
            <span class="text-lg font-bold text-on-surface tracking-tight">Grow a Garden</span>
        </a>

        {{-- Desktop Nav Links --}}
        <nav class="hidden md:flex items-center gap-8" id="nav-links">
            <a href="#features" class="nav-link active text-sm font-semibold text-primary transition-colors duration-200">Fitur</a>
            <a href="/learn" class="nav-link text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200">How It Works</a>

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
        <a href="/learn" class="block text-sm font-medium text-on-surface-variant py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors">How It Works</a>

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

    <div class="relative max-w-[1280px] mx-auto px-5 lg:px-8 pt-24 pb-12 md:pt-28 md:pb-16 lg:pt-32 lg:pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            {{-- Left: Text Content --}}
            <div class="order-2 lg:order-1 pt-6">

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
                    <a href="/learn" class="inline-flex items-center gap-2 border-2 border-outline-variant text-on-surface-variant font-semibold px-5 py-3 rounded-full hover:border-primary hover:text-primary hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-300 text-sm" id="hero-cta-secondary">
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

        {{-- Feature Cards (3 Compact, High-Fidelity App Preview Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

            {{-- Feature 1: Tugas Perawatan --}}
            <div class="bg-white rounded-[24px] p-6 premium-shadow border border-white/60 flex flex-col justify-between gap-5 hover:-translate-y-1 transition-all duration-300 group" id="feature-tugas-perawatan">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#006c49]/10 to-[#10b981]/20 border border-[#006c49]/20 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[#006c49] text-xl font-bold">water_drop</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-on-surface leading-tight">Tugas Perawatan</h3>
                            <p class="text-[12px] text-on-surface-variant leading-tight">Rekomendasi harian berbasis cuaca.</p>
                        </div>
                    </div>

                    {{-- Compact Task Items List --}}
                    <div class="space-y-2.5">
                        {{-- Task 1 --}}
                        <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/30 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-[#e6f4ea] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[#006c49] text-[15px]">water_drop</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[12px] font-bold text-on-surface truncate">Pengingat Penyiraman</div>
                                    <span class="bg-[#ffdad6] text-[#ba1a1a] text-[9px] font-bold px-1.5 py-0.2 rounded-full inline-block">Dilewati (Hujan)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <span class="w-6 h-6 rounded-full bg-[#e6f4ea] text-[#006c49] flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[13px] font-bold">check</span></span>
                                <span class="w-6 h-6 rounded-full bg-[#ffdad6] text-[#ba1a1a] flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[13px] font-bold">close</span></span>
                            </div>
                        </div>

                        {{-- Task 2 --}}
                        <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/30 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-[#e6f4ea] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[#006c49] text-[15px]">eco</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[12px] font-bold text-on-surface truncate">Pengingat Pemupukan</div>
                                    <span class="bg-[#fff8d6] text-[#7c6300] text-[9px] font-bold px-1.5 py-0.2 rounded-full inline-block">Tunda Pupuk Cair</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <span class="w-6 h-6 rounded-full bg-[#e6f4ea] text-[#006c49] flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[13px] font-bold">check</span></span>
                                <span class="w-6 h-6 rounded-full bg-[#ffdad6] text-[#ba1a1a] flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[13px] font-bold">close</span></span>
                            </div>
                        </div>

                        {{-- Task 3 --}}
                        <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/30 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-[#fbebe4] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[#944a23] text-[15px]">pest_control</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[12px] font-bold text-on-surface truncate">Inspeksi Hama</div>
                                    <span class="bg-[#f3e8ff] text-[#6b21a8] text-[9px] font-bold px-1.5 py-0.2 rounded-full inline-block">Cek Jamur Daun</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <span class="w-6 h-6 rounded-full bg-[#e6f4ea] text-[#006c49] flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[13px] font-bold">check</span></span>
                                <span class="w-6 h-6 rounded-full bg-[#ffdad6] text-[#ba1a1a] flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[13px] font-bold">close</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Feature 2: Growth Calendar --}}
            <div class="bg-white rounded-[24px] p-6 premium-shadow border border-white/60 flex flex-col justify-between gap-5 hover:-translate-y-1 transition-all duration-300 group" id="feature-growth-calendar">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#006c49]/10 to-[#10b981]/20 border border-[#006c49]/20 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[#006c49] text-xl font-bold">calendar_month</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-on-surface leading-tight">Growth Calendar</h3>
                            <p class="text-[12px] text-on-surface-variant leading-tight">Kalender bulanan interaktif & kelola jadwal.</p>
                        </div>
                    </div>

                    {{-- Mini Monthly Calendar Grid Mockup --}}
                    <div class="bg-surface rounded-2xl p-3.5 border border-outline-variant/30 space-y-2.5">
                        {{-- Month Header --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-black text-on-surface">September 2026</span>
                                <span class="bg-primary/10 text-primary text-[9px] font-black px-1.5 py-0.2 rounded-full uppercase">Bayam</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-on-surface-variant font-bold">
                                <span class="w-4 h-4 rounded bg-white border border-outline-variant/40 flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-[12px]">chevron_left</span></span>
                                <span class="w-4 h-4 rounded bg-white border border-outline-variant/40 flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-[12px]">chevron_right</span></span>
                            </div>
                        </div>

                        {{-- Weekdays mini --}}
                        <div class="grid grid-cols-7 gap-1 text-center text-[9px] font-extrabold text-on-surface-variant uppercase">
                            <div>Sn</div><div>Sl</div><div>Rb</div><div>Km</div><div>Jm</div><div class="text-[#006c49]">Sb</div><div class="text-[#ba1a1a]">Mg</div>
                        </div>

                        {{-- Mini Days Grid --}}
                        <div class="grid grid-cols-7 gap-1 text-[10px]">
                            <div class="p-1 rounded-lg border border-outline-variant/20 bg-white/40 text-center opacity-40">13</div>
                            {{-- Day 14 (Hari Ini) --}}
                            <div class="p-1 rounded-lg border border-primary bg-primary/10 text-center font-black text-primary ring-1 ring-primary/40 relative shadow-2xs">
                                <span>14</span>
                                <span class="block w-1.5 h-1.5 rounded-full bg-primary mx-auto mt-0.5"></span>
                            </div>
                            {{-- Day 15 --}}
                            <div class="p-1 rounded-lg border border-outline-variant/30 bg-white text-center font-bold text-slate-700">15</div>
                            {{-- Day 16 (Event Pupuk) --}}
                            <div class="p-1 rounded-lg border border-primary/40 bg-primary/5 text-center font-bold text-primary relative">
                                <span>16</span>
                                <span class="block w-1.5 h-1.5 rounded-full bg-emerald-500 mx-auto mt-0.5"></span>
                            </div>
                            {{-- Day 17 --}}
                            <div class="p-1 rounded-lg border border-outline-variant/30 bg-white text-center font-bold text-slate-700">17</div>
                            {{-- Day 18 (Event Hama) --}}
                            <div class="p-1 rounded-lg border border-[#944a23]/30 bg-[#944a23]/5 text-center font-bold text-[#944a23] relative">
                                <span>18</span>
                                <span class="block w-1.5 h-1.5 rounded-full bg-[#944a23] mx-auto mt-0.5"></span>
                            </div>
                            <div class="p-1 rounded-lg border border-outline-variant/30 bg-white text-center font-bold text-slate-700">19</div>
                        </div>

                        {{-- Active Task Info Box --}}
                        <div class="bg-white rounded-xl p-2.5 border border-primary/25 flex items-center justify-between gap-2 shadow-2xs">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[14px]">water_drop</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-black text-on-surface truncate leading-tight">Penyiraman & Perawatan</p>
                                    <p class="text-[9px] text-on-surface-variant font-medium truncate">Klik tanggal • Tambah & Kelola</p>
                                </div>
                            </div>
                            <span class="text-[8px] font-black uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 px-1.5 py-0.5 rounded-full shrink-0">Hari Ini</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Feature 3: Care Reminder & Weather Adjust --}}
            <div class="bg-white rounded-[24px] p-6 premium-shadow border border-white/60 flex flex-col justify-between gap-5 hover:-translate-y-1 transition-all duration-300 group" id="feature-care-reminder">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#944a23]/10 to-[#944a23]/20 border border-[#944a23]/20 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[#944a23] text-xl font-bold">notifications_active</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-on-surface leading-tight">Care Reminder</h3>
                            <p class="text-[12px] text-on-surface-variant leading-tight">Notifikasi pintar & cuaca otomatis.</p>
                        </div>
                    </div>

                    {{-- Compact Weather Status & Reminder Chips --}}
                    <div class="space-y-3">
                        <div class="bg-gradient-to-r from-[#f4fbf6] to-[#e6f4ea]/60 rounded-xl p-3 border border-[#006c49]/20 flex items-center justify-between gap-2 shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#006c49]/10 flex items-center justify-center shrink-0 border border-[#006c49]/20">
                                    <span class="material-symbols-outlined text-[#006c49] text-[18px]">partly_cloudy_day</span>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-[#006c49]">Hujan Ringan (24°C)</div>
                                    <div class="text-[10px] text-on-surface-variant font-medium">Penyesuaian Jadwal Otomatis</div>
                                </div>
                            </div>
                            <span class="bg-[#006c49] text-white text-[9px] font-black px-2.5 py-0.5 rounded-full shadow-xs tracking-wider flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#6ffbbe] animate-pulse"></span> LIVE
                            </span>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between bg-[#ffdad6]/80 text-[#410002] text-[11px] font-bold px-3 py-2 rounded-xl border border-[#ffdad6] shadow-xs">
                                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[#ba1a1a] text-[15px]">warning</span> Penyiraman Ditunda</span>
                                <span class="text-[9px] bg-white/60 px-2 py-0.5 rounded-md font-semibold text-[#ba1a1a]">Karena Hujan</span>
                            </div>
                            <div class="flex items-center justify-between bg-[#fff8d6] text-[#7c6300] text-[11px] font-bold px-3 py-2 rounded-xl border border-[#fff8d6] shadow-xs">
                                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[#7c6300] text-[15px]">science</span> Tunda Pemupukan</span>
                                <span class="text-[9px] bg-white/60 px-2 py-0.5 rounded-md font-semibold text-[#7c6300]">Bilas Nutrisi</span>
                            </div>
                            <div class="flex items-center justify-between bg-[#e6f4ea] text-[#006c49] text-[11px] font-bold px-3 py-2 rounded-xl border border-[#006c49]/20 shadow-xs">
                                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[#006c49] text-[15px]">check_circle</span> Kondisi Kebun</span>
                                <span class="text-[9px] bg-[#006c49] text-white px-2 py-0.5 rounded-md font-bold">Optimal</span>
                            </div>
                        </div>
                    </div>
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
                <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-5 leading-tight scroll-trigger scroll-fade-up delay-100">
                    Beradaptasi dengan<br>Cuaca Sekitar
                </h2>

                <p class="text-base text-on-surface-variant leading-relaxed mb-6 max-w-[512px] scroll-trigger scroll-fade-up delay-200">
                    Sistem kami terhubung dengan data cuaca lokal. Jika terdeteksi musim hujan, jadwal penyiraman otomatis dikurangi. Saat kemarau, pengingat penyiraman akan lebih sering muncul untuk menjaga kelembaban tanah.
                </p>

                <a href="/learn" class="inline-flex items-center gap-2 text-primary font-semibold text-sm hover:gap-3 transition-all duration-200 scroll-trigger scroll-fade-up delay-300">
                    Pelajari Sistem Cerdas Kami
                    <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                </a>
            </div>

            {{-- Right: Weather Card --}}
            <div class="flex justify-center lg:justify-end scroll-trigger scroll-fade-left delay-200">
                <div class="bg-white rounded-3xl p-8 premium-shadow max-w-[448px] w-full tactile-card">
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 22px;">rainy</span>
                            <span class="text-sm font-bold text-on-surface">Prediksi Cuaca: Hujan</span>
                        </div>
                        <span class="text-xs font-semibold text-secondary bg-secondary-fixed px-3 py-1 rounded-full whitespace-nowrap">Hujan Ringan (24°C)</span>
                    </div>

                    {{-- Card Body --}}
                    <div class="bg-surface-container-low rounded-xl p-4">
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
            <a href="/sitemap" class="hover:text-primary transition-colors">Sitemap</a>
            <a href="/privacy-policy" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
            <a href="/terms" class="hover:text-primary transition-colors">Syarat Layanan</a>
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

    // ScrollSpy for dynamic navigation highlighting
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('#nav-links a, #mobile-menu a');
    
    window.addEventListener('scroll', () => {
        let current = '';
        const scrollY = window.pageYOffset;
        
        sections.forEach(section => {
            const sectionHeight = section.offsetHeight;
            const sectionTop = section.offsetTop - 100; // offset for fixed header
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (!href.startsWith('#')) return; // ignore /learn
            
            if (href === '#' + current) {
                // Active state
                link.classList.remove('font-medium', 'text-on-surface-variant');
                link.classList.add('active', 'font-semibold', 'text-primary');
                if (link.parentElement.id === 'mobile-menu') {
                    link.classList.add('bg-primary/10');
                }
            } else {
                // Inactive state
                link.classList.remove('active', 'font-semibold', 'text-primary', 'bg-primary/10');
                link.classList.add('font-medium', 'text-on-surface-variant');
            }
        });
    }, { passive: true });

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
