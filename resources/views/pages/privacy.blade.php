@extends('layouts.app')

@section('title', 'Kebijakan Privasi — Grow a Garden')

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

        <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight mb-8">Kebijakan Privasi</h1>
        
        <div class="space-y-6 text-on-surface-variant leading-relaxed">
            <p>Di <strong>Grow a Garden</strong>, privasi dan data kebun Anda sangat kami hargai. Kami mendesain aplikasi ini untuk menghubungkan Anda dengan lingkungan secara natural tanpa mengeksploitasi data Anda.</p>
            
            <h3 class="text-lg font-bold text-on-surface mt-8 mb-2">1. Data Lokasi dan Cuaca</h3>
            <p>Fitur <em>Weather Adjustment</em> memerlukan akses lokasi sekadar untuk memberikan peringatan penyesuaian curah hujan. Koordinat ini tidak kami bagikan ke pihak ketiga dan hanya digunakan untuk mengoptimalkan jadwal penyiraman kebun Anda.</p>
            
            <h3 class="text-lg font-bold text-on-surface mt-8 mb-2">2. Data Pertumbuhan Tanaman</h3>
            <p>Data seperti <em>Growth Calendar</em> dan plot kebun disimpan dengan aman. Kami dapat menggunakan data agregat secara anonim (seperti komoditas populer atau rata-rata masa panen) untuk meningkatkan kualitas fitur aplikasi bagi seluruh komunitas.</p>

            <h3 class="text-lg font-bold text-on-surface mt-8 mb-2">3. Komunikasi</h3>
            <p>Kami akan mengirimkan notifikasi <em>Care Reminder</em> melalui email atau notifikasi in-app. Anda dapat mengatur preferensi ini kapan saja di menu Pengaturan.</p>
        </div>
    </div>
</div>
@endsection
