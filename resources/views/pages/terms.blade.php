@extends('layouts.app')

@section('title', 'Syarat Layanan — Grow a Garden')

@section('content')
<div class="min-h-screen bg-surface px-5 py-12 lg:py-20">
    <div class="max-w-[800px] mx-auto bg-white rounded-[24px] p-8 md:p-12 ambient-shadow-lg border border-outline-variant/20">
        {{-- Back Button --}}
        <div class="mb-8 flex justify-start">
            <a href="/" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Beranda
            </a>
        </div>

        <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight mb-8">Syarat Layanan</h1>
        
        <div class="space-y-6 text-on-surface-variant leading-relaxed">
            <p>Dengan mendaftar dan menggunakan <strong>Grow a Garden</strong>, Anda menyetujui ketentuan layanan kami.</p>
            
            <h3 class="text-lg font-bold text-on-surface mt-8 mb-2">1. Ketentuan Paket Langganan</h3>
            <ul class="list-disc pl-5 space-y-2 mt-2">
                <li><strong>Paket Bibit (Gratis):</strong> Pengguna dapat memetakan maksimal 1 kebun dengan batasan 4 plot dan 10 tanaman aktif.</li>
                <li><strong>Paket Subur:</strong> Pengguna dapat menikmati fitur Asisten Autopilot, otomatisasi <em>Care Template</em>, serta penyesuaian cuaca. Cocok untuk pekebun hobi.</li>
                <li><strong>Paket Panen Raya (Pro):</strong> Memberikan skala tak terbatas untuk kebutuhan komunitas atau <em>urban farming</em> skala besar.</li>
            </ul>
            
            <h3 class="text-lg font-bold text-on-surface mt-8 mb-2">2. Penggunaan Edukasi & Data</h3>
            <p>Estimasi panen dan <em>Growth Calendar</em> kami dirancang menggunakan referensi agrikultur terpercaya. Namun, kondisi alam yang tidak dapat diprediksi berarti kami tidak dapat menjamin panen pasti 100%. Kami hadir sebagai asisten cerdas kebun Anda.</p>
        </div>
    </div>
</div>
@endsection
