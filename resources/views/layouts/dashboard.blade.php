@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- ============================================
         MOBILE TOP APP BAR
         ============================================ --}}
    <header class="md:hidden w-full sticky top-0 bg-surface/95 backdrop-blur-md z-40 shadow-sm flex justify-between items-center px-5 py-3" id="mobile-header">
        <a href="/" class="text-[20px] font-bold text-primary flex items-center gap-2">
            <img src="/logo.png" alt="Logo" class="w-8 h-8 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
            Grow a Garden
        </a>
        <div class="flex items-center gap-4">
            <button class="text-on-surface-variant active:opacity-80 transition-opacity p-1 flex items-center justify-center" aria-label="Notifications">
                <span class="material-symbols-outlined text-[24px]">notifications</span>
            </button>
            <a href="/settings" class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-sm shadow-sm active:scale-95 transition-transform" aria-label="Profile and Settings">
                GT
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
                <img src="/logo.png" alt="Logo" class="w-8 h-8 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
                Grow a Garden
            </a>
        </div>

        {{-- Nav Items --}}
        <div class="flex-1 flex flex-col gap-1 overflow-y-auto no-scrollbar px-2">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard', 'url' => '/dashboard'],
                    ['route' => 'garden-plots', 'label' => 'Garden Plots', 'icon' => 'potted_plant', 'url' => '/garden-plots'],
                    ['route' => 'growth-calendar', 'label' => 'Growth Calendar', 'icon' => 'calendar_month', 'url' => '/growth-calendar'],
                    ['route' => 'care-tasks', 'label' => 'Care Tasks', 'icon' => 'water_drop', 'url' => '/care-tasks'],
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
                    <span class="text-sm font-semibold">Log Out</span>
                </a>
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        {{-- Bottom: Add Plant + Profile --}}
        <div class="px-6 mt-auto">
            <button class="w-full bg-primary text-on-primary rounded-full py-3 text-sm font-semibold hover:bg-primary/90 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 shadow-sm" id="btn-add-plant">
                <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                Add New Plant
            </button>

            <a href="/settings" class="mt-4 flex items-center gap-3 pt-4 border-t border-outline-variant/50 hover:bg-surface-container-high transition-colors p-2 -mx-2 rounded-xl group cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-sm flex-shrink-0 group-hover:bg-primary-container group-hover:text-on-primary-container transition-colors">GT</div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-semibold text-on-surface truncate">Green Thumb</div>
                    <div class="text-xs font-medium text-on-surface-variant">Profile & Settings</div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-[20px] group-hover:text-on-surface transition-colors">settings</span>
            </a>
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
     MOBILE BOTTOM NAVIGATION
     ============================================ --}}
<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 py-2 bg-surface/95 backdrop-blur-md z-50 rounded-t-xl shadow-[0_-4px_6px_-1px_rgba(6,95,70,0.08)]" id="mobile-bottom-nav" style="padding-bottom: max(0.5rem, env(safe-area-inset-bottom));">
    @php
        $bnavItems = [
            ['route' => 'dashboard', 'label' => 'Home', 'icon' => 'home', 'url' => '/dashboard'],
            ['route' => 'garden-plots', 'label' => 'Plots', 'icon' => 'potted_plant', 'url' => '/garden-plots'],
            ['route' => 'growth-calendar', 'label' => 'Calendar', 'icon' => 'event_note', 'url' => '/growth-calendar'],
            ['route' => 'care-tasks', 'label' => 'Tasks', 'icon' => 'checklist', 'url' => '/care-tasks'],
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
@endsection
