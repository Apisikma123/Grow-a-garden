@extends('layouts.app')

@section('title', 'Admin Console — Grow a Garden')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-surface">

    {{-- ============================================
         MOBILE TOP APP BAR
         ============================================ --}}
    <header class="md:hidden w-full sticky top-0 bg-surface/95 backdrop-blur-md z-40 shadow-sm flex justify-between items-center px-5 py-3" id="mobile-header">
        <a href="/admin/dashboard" class="text-[20px] font-bold text-primary flex items-center gap-2">
            <img src="/logo.png" alt="Logo" class="w-8 h-8 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
            <div class="flex flex-col">
                <span class="leading-none">Grow a Garden</span>
                <span class="text-[10px] text-on-surface-variant font-medium mt-0.5">Admin Console</span>
            </div>
        </a>
        <button class="text-on-surface-variant active:opacity-80 transition-opacity p-1" aria-label="Menu">
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>
    </header>

    {{-- ============================================
         DESKTOP SIDEBAR NAVIGATION
         ============================================ --}}
    <nav class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 py-6 border-r border-outline-variant/50 bg-surface-container-lowest z-40" id="sidebar-nav">
        {{-- Logo --}}
        <div class="px-6 mb-8">
            <a href="/admin/dashboard" class="flex items-center gap-3">
                <img src="/logo.png" alt="Logo" class="w-10 h-10 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[40px] text-primary\'>local_florist</span>'">
                <div class="flex flex-col">
                    <span class="text-[18px] font-bold text-primary leading-tight">Grow a<br>Garden</span>
                    <span class="text-[11px] text-on-surface-variant font-semibold mt-0.5">Admin Console</span>
                </div>
            </a>
        </div>

        {{-- Nav Items --}}
        <div class="flex-1 flex flex-col gap-1 overflow-y-auto no-scrollbar px-4">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard', 'url' => '/admin/dashboard'],
                    ['route' => 'admin.users', 'label' => 'User Management', 'icon' => 'group', 'url' => '#'],
                    ['route' => 'admin.plants', 'label' => 'Plant Database', 'icon' => 'local_florist', 'url' => '#'],
                    ['route' => 'admin.reports', 'label' => 'Reports', 'icon' => 'bar_chart', 'url' => '#'],
                    ['route' => 'admin.settings', 'label' => 'Settings', 'icon' => 'settings', 'url' => '#'],
                ];
                $currentRoute = request()->path();
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = ltrim($item['url'], '/') === $currentRoute || ($item['label'] === 'Dashboard' && str_contains($currentRoute, 'admin/dashboard'));
                @endphp
                <a href="{{ $item['url'] }}" class="{{ $isActive ? 'bg-primary text-on-primary font-bold shadow-sm' : 'text-on-surface-variant font-medium hover:bg-surface-container-high' }} rounded-[12px] px-4 py-3 flex items-center gap-3 transition-all duration-200">
                    <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                    <span class="text-[14px]">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Bottom: Admin Profile --}}
        <div class="px-6 mt-auto">
            <div class="mt-4 flex items-center gap-3 pt-4 border-t border-outline-variant/50 cursor-pointer group">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&q=80" alt="Admin avatar" class="w-10 h-10 rounded-full object-cover shadow-sm group-hover:scale-105 transition-transform" />
                <div class="min-w-0 flex-1">
                    <div class="text-[14px] font-bold text-on-surface truncate">Admin User</div>
                    <div class="text-[11px] font-medium text-on-surface-variant truncate">admin@growagarden.com</div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================
         MAIN CONTENT CANVAS
         ============================================ --}}
    <main class="flex-1 md:ml-64 p-5 md:p-8 overflow-y-auto no-scrollbar w-full min-h-screen flex flex-col">
        {{-- Top Header Bar --}}
        <header class="hidden md:flex justify-between items-center mb-8 gap-6">
            {{-- Search Bar --}}
            <div class="relative w-full max-w-[400px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="text" placeholder="Search users, plants, or activity..." class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-full pl-12 pr-4 py-2.5 text-[14px] focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all ambient-shadow text-on-surface placeholder:text-on-surface-variant/60" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                <button class="relative text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full ring-2 ring-surface"></span>
                </button>
                <button class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">help</span>
                </button>
            </div>
        </header>

        @yield('admin-content')
    </main>

</div>
@endsection
