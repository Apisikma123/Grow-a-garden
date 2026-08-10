@extends('layouts.dashboard')

@section('title', 'Semua Lencana — Grow a Garden')
@section('description', 'Koleksi seluruh lencana yang tersedia untuk Anda dapatkan.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div>
                <div class="inline-flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[16px] text-amber-600">workspace_premium</span>
                    </div>
                    <span class="text-[13px] font-black uppercase tracking-wider text-amber-600">Koleksi Lengkap</span>
                </div>
                <h1 class="text-[32px] sm:text-[40px] font-black text-on-surface leading-tight tracking-tight">Semua Lencana</h1>
                <p class="text-[15px] sm:text-[16px] text-on-surface-variant font-medium mt-2 max-w-2xl">
                    Jelajahi seluruh lencana eksklusif yang bisa Anda dapatkan dengan merawat kebun Anda. Kumpulkan semuanya!
                </p>
            </div>
            
            <a href="{{ route('settings') }}" class="inline-flex items-center gap-2 text-[14px] font-bold text-primary hover:bg-primary/10 px-4 py-2 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Profil
            </a>
        </div>

        {{-- Progress Bar --}}
        @php
            $progressPct = $totalBadgeCount > 0 ? round(($unlockedCount / $totalBadgeCount) * 100) : 0;
        @endphp
        <div class="bg-surface rounded-[24px] p-6 ambient-shadow-lg border border-outline-variant/20">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <h3 class="text-[18px] font-bold text-on-surface">Progres Koleksi Anda</h3>
                    <p class="text-[13px] text-on-surface-variant">Terus lengkapi koleksi Anda.</p>
                </div>
                <div class="text-right">
                    <span class="text-[24px] font-black text-amber-600">{{ $unlockedCount }} / {{ $totalBadgeCount }}</span>
                    <span class="text-[13px] font-bold text-amber-600/70 ml-1">({{ $progressPct }}%)</span>
                </div>
            </div>
            <div class="w-full bg-surface-container-high h-3 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-amber-400 to-amber-600 h-full rounded-full transition-all duration-500" style="width: {{ $progressPct }}%;"></div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="flex items-center gap-3 mt-4">
            <span class="text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Urutkan:</span>
            
            <form id="sortForm" action="{{ route('badges') }}" method="GET" class="inline-block">
                <select name="sort" onchange="document.getElementById('sortForm').submit()" class="bg-surface border border-outline-variant/30 text-on-surface text-[14px] font-medium rounded-xl focus:ring-primary focus:border-primary block w-full p-2.5 outline-none cursor-pointer">
                    <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>Standar</option>
                    <option value="rarest" {{ $sort === 'rarest' ? 'selected' : '' }}>Paling Langka</option>
                    <option value="most_owned" {{ $sort === 'most_owned' ? 'selected' : '' }}>Pemain Terbanyak</option>
                    <option value="unlocked" {{ $sort === 'unlocked' ? 'selected' : '' }}>Telah Terbuka</option>
                    <option value="locked" {{ $sort === 'locked' ? 'selected' : '' }}>Terkunci</option>
                </select>
            </form>
        </div>

        {{-- Badges Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($badges as $badge)
                @php
                    $isEarned = in_array($badge->id, $userBadgeIds ?? []);
                @endphp
                <div class="rounded-2xl p-4 border transition-all relative overflow-hidden flex flex-col justify-between {{ $isEarned ? 'bg-gradient-to-br from-amber-500/10 to-yellow-500/5 border-amber-400/50 shadow-sm' : 'bg-surface-container-low border-outline-variant/30 opacity-60 grayscale hover:opacity-100 hover:grayscale-0' }}">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm shrink-0 {{ $isEarned ? 'bg-amber-500 text-white shadow-amber-500/30' : 'bg-surface-container-high text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-[26px]">{{ $badge->icon_url ?? 'military_tech' }}</span>
                        </div>
                        <div>
                            <h4 class="text-[14px] font-bold text-on-surface leading-tight">{{ $badge->name }}</h4>
                            @if($isEarned)
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md mt-1">
                                    <span class="material-symbols-outlined text-[12px]">check_circle</span> Terbuka
                                </span>
                            @else
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-on-surface-variant bg-surface-container-highest px-2 py-0.5 rounded-md mt-1">
                                    <span class="material-symbols-outlined text-[12px]">lock</span> Terkunci
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-[12px] text-on-surface-variant font-medium leading-relaxed flex-grow">{{ $badge->description }}</p>
                    
                    <div class="mt-3 pt-3 border-t border-outline-variant/20 flex items-center justify-between">
                        @php
                            $globalPct = number_format(($badge->users_count / $totalUsers) * 100, 1);
                            // Rarity color logic
                            $rarityColor = $globalPct < 10 ? 'text-primary font-black' : ($globalPct < 50 ? 'text-amber-600 font-bold' : 'text-on-surface-variant');
                        @endphp
                        <span class="text-[10px] font-bold uppercase tracking-wider {{ $rarityColor }}">
                            Dimiliki {{ $globalPct }}% pemain
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>
@endsection
