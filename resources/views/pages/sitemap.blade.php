@extends('layouts.app')

@section('title', 'Sitemap — Grow a Garden')

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

        <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight mb-8">Sitemap</h1>
        
        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-bold text-primary mb-4 border-b border-outline-variant/30 pb-2">Halaman Utama</h2>
                <ul class="list-disc pl-5 space-y-2 text-on-surface-variant">
                    <li><a href="/" class="hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="/login" class="hover:text-primary transition-colors">Masuk</a></li>
                    <li><a href="/register" class="hover:text-primary transition-colors">Daftar</a></li>
                </ul>
            </div>
            
            <div>
                <h2 class="text-xl font-bold text-primary mb-4 border-b border-outline-variant/30 pb-2">Aplikasi Pengguna</h2>
                <ul class="list-disc pl-5 space-y-2 text-on-surface-variant">
                    <li><a href="/dashboard" class="hover:text-primary transition-colors">Dashboard (Ringkasan Kebun & Jadwal)</a></li>
                    <li><a href="/garden-plots" class="hover:text-primary transition-colors">Garden Map & Plot Management</a></li>
                    <li><a href="/growth-calendar" class="hover:text-primary transition-colors">Growth Calendar</a></li>
                    <li><a href="/care-tasks" class="hover:text-primary transition-colors">Care Reminder</a></li>
                    <li><a href="/settings" class="hover:text-primary transition-colors">Pengaturan Profil</a></li>
                </ul>
            </div>
            
            <div>
                <h2 class="text-xl font-bold text-primary mb-4 border-b border-outline-variant/30 pb-2">Informasi</h2>
                <ul class="list-disc pl-5 space-y-2 text-on-surface-variant">
                    <li><a href="/privacy-policy" class="hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="/terms" class="hover:text-primary transition-colors">Syarat Layanan</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
