@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- ============================================
         MOBILE TOP APP BAR
         ============================================ --}}
    <header class="md:hidden w-full sticky top-0 bg-surface/95 backdrop-blur-md z-40 shadow-sm flex justify-between items-center px-5 py-3" id="mobile-header">
        <a href="/" class="text-[20px] font-bold text-primary flex items-center gap-2">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
            Grow a Garden
        </a>
        <div class="flex items-center gap-3">
            {{-- Mobile Upgrade Button --}}
            <button onclick="document.getElementById('pricing-modal').classList.remove('hidden')" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900 text-[11px] font-black tracking-wide px-3 py-1.5 rounded-full flex items-center gap-1 shadow-sm hover:scale-105 active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[14px]">star</span> PRO
            </button>

            <button class="text-on-surface-variant active:opacity-80 transition-opacity p-1 flex items-center justify-center" aria-label="Notifications">
                <span class="material-symbols-outlined text-[24px]">notifications</span>
            </button>
            <a href="/settings" class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-sm shadow-sm active:scale-95 transition-transform" aria-label="Profile and Settings">
                {{ strtoupper(substr(Auth::user()->name ?? 'GT', 0, 2)) }}
            </a>
        </div>
    </header>

    {{-- ============================================
         DESKTOP SIDEBAR NAVIGATION
         ============================================ --}}
    <nav class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 py-6 border-r border-outline-variant/50 bg-surface-container-low z-40" id="sidebar-nav">
        {{-- Logo --}}
        <div class="px-6 mb-8">
            <a href="/" class="text-xl font-bold text-primary flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
                Grow a Garden
            </a>
        </div>

        {{-- Nav Items --}}
        <div class="flex-1 flex flex-col gap-1 overflow-y-auto no-scrollbar px-2">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'dashboard', 'url' => '/dashboard'],
                    ['route' => 'garden-plots', 'label' => 'Plot Kebun', 'icon' => 'potted_plant', 'url' => '/garden-plots'],
                    ['route' => 'growth-calendar', 'label' => 'Kalender Tanam', 'icon' => 'calendar_month', 'url' => '/growth-calendar'],
                    ['route' => 'care-tasks', 'label' => 'Tugas Perawatan', 'icon' => 'water_drop', 'url' => '/care-tasks'],
                ];
                $currentRoute = request()->path();
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = ltrim($item['url'], '/') === $currentRoute;
                @endphp
                <a href="{{ $item['url'] }}" class="{{ $isActive ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-full px-4 py-3 flex items-center gap-3 transition-all duration-200 active:scale-95">
                    <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                    <span class="text-sm font-semibold">{{ $item['label'] }}</span>
                </a>
            @endforeach

            {{-- Logout Button --}}
            <div class="mt-4 pt-4 border-t border-outline-variant/50 px-2">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-error rounded-full px-4 py-3 flex items-center gap-3 hover:bg-error-container/50 transition-colors duration-200">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm font-semibold">Keluar</span>
                </a>
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        {{-- Bottom: Profile --}}
        <div class="px-6 mt-auto">

            {{-- Upgrade Ad Box (Kapitalis Style) --}}
            <div class="bg-gradient-to-br from-[#0f172a] to-[#1e293b] rounded-2xl p-4 flex flex-col relative overflow-hidden group shadow-lg mb-2">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-[#006c49] rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="absolute -left-4 -bottom-4 w-12 h-12 bg-yellow-400 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="flex items-center gap-2 mb-1 z-10">
                    <span class="material-symbols-outlined text-yellow-400 text-[18px]">verified</span>
                    <span class="text-[12px] font-black text-white tracking-widest uppercase">Go Premium</span>
                </div>
                <p class="text-[11px] text-slate-300 font-medium mb-3 leading-snug z-10">Unlock unlimited plots, plants, and smart weather adjustments.</p>
                <button type="button" onclick="document.getElementById('pricing-modal').classList.remove('hidden')" class="w-full text-center bg-yellow-400 text-yellow-900 font-bold text-[13px] py-2 rounded-xl hover:bg-yellow-300 transition-colors z-10 shadow-sm cursor-pointer">
                    Upgrade Now
                </button>
            </div>

            <div class="bg-surface rounded-[20px] p-2 flex items-center justify-between border border-outline-variant/30 ambient-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-outline-variant/20 flex items-center justify-center text-[#006c49] font-black text-[14px]">
                        {{ strtoupper(substr(Auth::user()->name ?? 'GT', 0, 2)) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[14px] font-bold text-on-surface leading-tight truncate max-w-[120px]">{{ Auth::user()->name ?? 'Green Thumb' }}</span>
                        <span class="text-[11px] text-on-surface-variant font-medium">Profil & Pengaturan</span>
                    </div>
                </div>
                <a href="/settings" class="p-2 text-on-surface-variant hover:text-[#006c49] transition-colors flex items-center justify-center rounded-full hover:bg-black/5">
                    <span class="material-symbols-outlined text-[22px] font-bold">settings</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- ============================================
         MAIN CONTENT CANVAS
         ============================================ --}}
    <main class="flex-1 md:ml-64 p-5 md:p-8 pb-32 md:pb-8 overflow-y-auto no-scrollbar max-w-[1280px] mx-auto w-full">
        @yield('dashboard-content')
    </main>

</div>

{{-- ============================================
     PRICING MODAL
     ============================================ --}}
<div id="pricing-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    {{-- Backdrop (Optimized for performance: removed backdrop-blur, used fixed bg) --}}
    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="document.getElementById('pricing-modal').classList.add('hidden')"></div>
    
    {{-- Modal Content --}}
    <div class="min-h-screen px-4 py-8 flex items-center justify-center pointer-events-none">
        <div class="w-full max-w-[1200px] bg-surface-container-lowest rounded-3xl p-6 md:p-10 ambient-shadow-lg border border-outline-variant/30 pointer-events-auto relative">
            
            {{-- Close Button --}}
            <button onclick="document.getElementById('pricing-modal').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-surface-container-high rounded-full flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>

            {{-- Modal Header --}}
            <div class="text-center mb-10 max-w-[672px] mx-auto">
                <h2 class="text-[28px] md:text-[36px] font-bold text-on-surface tracking-tight mb-4">
                    Pilih Paket Sesuai Kebutuhan Kebun Anda
                </h2>
                <p class="text-base text-on-surface-variant leading-relaxed">
                    Mulai dari hobi kecil hingga komunitas hidroponik besar.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Paket 1: Bibit --}}
                <div class="bg-white rounded-3xl p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:shadow-xl transition-all duration-300">
                    <h3 class="text-xl font-bold text-on-surface mb-2">Bibit <span class="text-xs font-semibold bg-surface-container-high px-2 py-1 rounded-full text-on-surface-variant ml-2">Gratis</span></h3>
                    <div class="flex items-baseline gap-1 mb-4">
                        <span class="text-[32px] font-black text-on-surface">Rp 0</span>
                    </div>
                    <p class="text-sm text-on-surface-variant mb-6 min-h-[40px]">Tetap dipertahankan sebagai umpan (Lead Magnet).</p>
                    
                    <div class="space-y-4 flex-1 mb-6">
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

                    <button class="w-full text-center border-2 border-outline-variant text-on-surface font-bold py-3 rounded-xl hover:bg-surface-container-high transition-colors" disabled>Current Plan</button>
                </div>

                {{-- Paket 2: Subur --}}
                <div class="bg-gradient-to-b from-[#006c49] to-[#005236] rounded-3xl p-6 shadow-xl flex flex-col relative transform lg:-translate-y-4 border border-[#008c5f]">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-[11px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md whitespace-nowrap flex items-center gap-1.5">
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

                    <div class="space-y-4 flex-1 mb-6 mt-2">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">check_circle</span>
                            <span class="text-sm text-white font-medium">Maks. 10 Garden, 50 Plot & 100 Tanaman Aktif</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">smart_toy</span>
                            <div class="flex flex-col">
                                <span class="text-sm text-white font-bold">Asisten Autopilot</span>
                                <span class="text-[11px] text-white/80 mt-0.5">Rule Engine menghasilkan task perawatan otomatis berbasis Growth & Care Template.</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">cloud_done</span>
                            <div class="flex flex-col">
                                <span class="text-sm text-white font-bold">Anti-Gagal Panen</span>
                                <span class="text-[11px] text-white/80 mt-0.5">Weather Adjustment (-30% penyiraman hujan, +50% kemarau).</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-yellow-400 text-[20px] mt-0.5">emoji_events</span>
                            <span class="text-sm text-white font-medium">Notifikasi Upcoming Harvest di Dashboard</span>
                        </div>
                    </div>

                    <a href="/checkout?plan=subur&from={{ urlencode(request()->path()) }}" class="w-full text-center bg-yellow-400 text-yellow-900 font-bold py-3 rounded-xl hover:bg-yellow-300 transition-colors shadow-lg mb-2 text-[15px] block">Mulai 7-Day Free Trial</a>
                    <p class="text-center text-[11px] text-white/70">Cancel anytime. Bebas risiko.</p>
                </div>

                {{-- Paket 3: Panen Raya --}}
                <div class="bg-white rounded-3xl p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 bg-primary/10 w-24 h-24 rounded-full"></div>
                    <h3 class="text-xl font-bold text-on-surface mb-2 relative z-10">Panen Raya <span class="text-xs font-semibold bg-primary-container text-on-primary-container px-2 py-1 rounded-full ml-2">Pro</span></h3>
                    
                    <div class="bg-surface-container-low rounded-xl p-3 mb-4 mt-2">
                        <div class="mb-2">
                            <p class="text-[11px] text-on-surface-variant font-medium uppercase tracking-wider mb-0.5">Paket Tahunan</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-[28px] font-black text-on-surface">Rp 799.000</span>
                            </div>
                            <p class="text-[11px] font-bold text-[#006c49] mt-1 bg-primary/10 inline-block px-2 py-0.5 rounded">Hemat hampir 1 Juta! (vs Rp 1.788k)</p>
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
                    
                    <div class="space-y-4 flex-1 mb-6 relative z-10">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">all_inclusive</span>
                            <span class="text-sm text-on-surface font-bold">Maks. 100 Garden, Unlimited Plot & Tanaman</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">check_circle</span>
                            <span class="text-sm text-on-surface">Seluruh fitur otomatis Paket Subur</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">history</span>
                            <div class="flex flex-col">
                                <span class="text-sm text-on-surface font-bold">Activity Log Tanpa Batas</span>
                                <span class="text-[11px] text-on-surface-variant mt-0.5">Tracking tak terbatas untuk menyiram, memupuk, dll.</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[#006c49] text-[20px] mt-0.5">groups</span>
                            <span class="text-sm text-on-surface font-medium">Ideal untuk Urban Farming & Komunitas</span>
                        </div>
                    </div>

                    <a href="/checkout?plan=pro&from={{ urlencode(request()->path()) }}" class="w-full text-center bg-[#006c49] text-white font-bold py-3 rounded-xl hover:bg-[#005236] transition-colors shadow-sm relative z-10 block">Upgrade ke Pro</a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ============================================
     MOBILE BOTTOM NAVIGATION
     ============================================ --}}
<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 py-2 bg-surface/95 backdrop-blur-md z-50 rounded-t-xl shadow-[0_-4px_6px_-1px_rgba(6,95,70,0.08)]" id="mobile-bottom-nav" style="padding-bottom: max(0.5rem, env(safe-area-inset-bottom));">
    @php
        $bnavItems = [
            ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home', 'url' => '/dashboard'],
            ['route' => 'garden-plots', 'label' => 'Plot', 'icon' => 'potted_plant', 'url' => '/garden-plots'],
            ['route' => 'growth-calendar', 'label' => 'Kalender', 'icon' => 'event_note', 'url' => '/growth-calendar'],
            ['route' => 'care-tasks', 'label' => 'Tugas', 'icon' => 'checklist', 'url' => '/care-tasks'],
        ];
    @endphp

    @foreach($bnavItems as $item)
        @php
            $isActive = ltrim($item['url'], '/') === $currentRoute;
        @endphp
        <a href="{{ $item['url'] }}" class="flex flex-col items-center justify-center {{ $isActive ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-xl px-4 py-1.5 active:scale-95 transition-transform duration-200">
            <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
            <span class="text-[10px] font-semibold mt-0.5">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
<script>
    window.AppState = {
        plan: '{{ Auth::check() ? Auth::user()->role : "free" }}',
        usage: { 
            gardens: {{ Auth::check() ? \App\Models\Garden::where('user_id', Auth::id())->count() : 0 }}, 
            plots: {{ Auth::check() ? \App\Models\GardenPlot::whereIn('garden_id', \App\Models\Garden::where('user_id', Auth::id())->pluck('id'))->count() : 0 }}, 
            plants: {{ Auth::check() ? \App\Models\GardenPlot::whereIn('garden_id', \App\Models\Garden::where('user_id', Auth::id())->pluck('id'))->whereNotNull('plant_id')->count() : 0 }} 
        }
    };

    const PLAN_LIMITS = {
        free: { gardens: 1, plots: 4, plants: 10 },
        pro: { gardens: 10, plots: 50, plants: 100 },
        premium: { gardens: Infinity, plots: Infinity, plants: Infinity }
    };

    window.checkLimit = function(resourceType) {
        if (window.AppState.plan === 'premium' || window.AppState.plan === 'admin') return true;
        
        let limit = PLAN_LIMITS[window.AppState.plan] ? PLAN_LIMITS[window.AppState.plan][resourceType] : PLAN_LIMITS['free'][resourceType];
        
        if (window.AppState.usage[resourceType] >= limit) {
            document.getElementById('pricing-modal').classList.remove('hidden');
            return false;
        }
        return true;
    };
</script>
@endsection
