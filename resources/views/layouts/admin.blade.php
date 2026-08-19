@extends('layouts.app')

@section('title', 'Admin Console — Grow a Garden')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-surface">

    {{-- ============================================
         MOBILE TOP APP BAR
         ============================================ --}}
    <header class="md:hidden w-full sticky top-0 bg-surface/95 backdrop-blur-md z-40 shadow-sm flex justify-between items-center px-5 py-3" id="mobile-header">
        <a href="/admin/dashboard" class="text-[20px] font-bold text-primary flex items-center gap-2">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
            <div class="flex flex-col">
                <span class="leading-none">Grow a Garden</span>
                <span class="text-[10px] text-on-surface-variant font-medium mt-0.5">Admin Console</span>
            </div>
        </a>
        <div class="flex items-center gap-3">
            <a href="/admin/settings" class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-sm shadow-sm active:scale-95 transition-transform overflow-hidden" aria-label="Profile and Settings">
                @if(Auth::user()->avatar)
                    <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Profile">
                @else
                    {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                @endif
            </a>
            <button id="mobile-menu-btn" class="text-on-surface-variant active:opacity-80 transition-opacity p-1" aria-label="Menu">
                <span class="material-symbols-outlined text-[24px]">menu</span>
            </button>
        </div>
    </header>

    {{-- ============================================
         DESKTOP SIDEBAR NAVIGATION
         ============================================ --}}
    <nav class="hidden md:flex flex-col h-screen w-[260px] fixed left-0 top-0 py-6 border-r border-outline-variant/30 bg-[#f8f9fa] z-40" id="sidebar-nav">
        {{-- Logo --}}
        <div class="px-8 mb-8 flex justify-between items-start">
            <a href="/admin/dashboard" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain shrink-0" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px] text-[#006c49]\'>local_florist</span>'">
                <div class="flex flex-col">
                    <span class="text-[20px] font-bold text-[#006c49] leading-tight tracking-tight">Grow a Garden</span>
                    <span class="text-[12px] font-bold text-[#006c49]/80 mt-0.5">Admin</span>
                </div>
            </a>
            <button id="close-menu-btn" class="md:hidden text-on-surface-variant p-1">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Nav Items --}}
        <div class="flex-1 flex flex-col gap-2 overflow-y-auto no-scrollbar px-5">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard', 'url' => '/admin/dashboard'],
                    ['route' => 'admin.users', 'label' => 'Users', 'icon' => 'group', 'url' => '/admin/users'],
                    ['route' => 'admin.plants', 'label' => 'Plants', 'icon' => 'local_florist', 'url' => '/admin/plants'],
                    ['route' => 'admin.care-templates', 'label' => 'Care Template', 'icon' => 'assignment', 'url' => '/admin/care-templates'],
                    ['route' => 'admin.badges', 'label' => 'Badge', 'icon' => 'workspace_premium', 'url' => '/admin/badges'],
                    ['route' => 'admin.weather', 'label' => 'Weather Rules', 'icon' => 'partly_cloudy_day', 'url' => '/admin/weather'],
                    ['route' => 'admin.settings', 'label' => 'Settings', 'icon' => 'settings', 'url' => '/admin/settings'],
                ];
                $currentRoute = request()->path();
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = ltrim($item['url'], '/') === $currentRoute || ($item['label'] === 'Dashboard' && str_contains($currentRoute, 'admin/dashboard'));
                @endphp
                <a href="{{ $item['url'] }}" class="{{ $isActive ? 'text-[#006c49] font-bold' : 'text-[#334155] font-semibold hover:bg-black/5' }} rounded-xl px-4 py-3 flex items-center gap-4 transition-all duration-200">
                    <span class="material-symbols-outlined text-[24px] {{ $isActive ? 'text-[#006c49]' : 'text-[#475569]' }}">{{ $item['icon'] }}</span>
                    <span class="text-[15px]">{{ $item['label'] }}</span>
                </a>
            @endforeach

            <div class="h-px w-full bg-outline-variant/40 my-2"></div>
            
            <form method="POST" action="/logout" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-[#b91c1c] font-bold flex items-center gap-4 hover:bg-error/5 rounded-xl transition-all duration-200">
                    <span class="material-symbols-outlined text-[24px]">logout</span>
                    <span class="text-[15px]">Keluar</span>
                </button>
            </form>
        </div>

        {{-- Bottom Area --}}
        <div class="px-5 mt-auto flex flex-col gap-4">

            {{-- Profile & Pengaturan Box --}}
            <div class="bg-surface-container-low border border-outline-variant/30 rounded-[24px] p-3 flex items-center justify-between shadow-sm">
                <a href="/admin/settings" class="flex items-center gap-3 min-w-0 group flex-1">
                    <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden border border-outline-variant/40 shrink-0 group-hover:border-primary transition-colors">
                        @if(Auth::user()->avatar)
                            <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Profile">
                        @else
                            <span class="text-[#006c49] font-black text-[14px] uppercase">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[14px] font-bold text-on-surface leading-tight truncate group-hover:text-primary transition-colors">{{ Auth::user()->name }}</span>
                        <span class="text-[11px] text-on-surface-variant font-medium">Profile & Pengaturan</span>
                    </div>
                </a>
                <a href="/admin/settings" class="p-2 text-on-surface-variant hover:text-[#006c49] transition-colors flex items-center justify-center rounded-full hover:bg-black/5 shrink-0" title="Pengaturan">
                    <span class="material-symbols-outlined text-[22px] font-bold">settings</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- ============================================
         MAIN CONTENT CANVAS
         ============================================ --}}
    <main class="flex-1 md:ml-64 p-5 md:p-8 overflow-y-auto no-scrollbar w-full min-h-screen flex flex-col">
        {{-- Top Header Bar --}}
        @if(!request()->is('admin/settings*') && !request()->is('admin/dashboard*') && !request()->is('admin/weather*') && !request()->is('admin/plants*'))
        <header class="hidden md:flex justify-between items-center mb-8 gap-6">
            {{-- Search Bar --}}
            <div class="relative w-full max-w-[400px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="text" id="admin-global-search" placeholder="Search users, templates, or badges..." class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-full pl-12 pr-4 py-2.5 text-[14px] focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all ambient-shadow text-on-surface placeholder:text-on-surface-variant/60" />
            </div>

            {{-- Admin Profile Chip / Avatar in Top Bar --}}
            <a href="/admin/settings" class="flex items-center gap-3 bg-surface-container-lowest hover:bg-surface-container-low border border-outline-variant/30 rounded-full py-1.5 pl-2 pr-4 transition-all duration-200 shadow-2xs hover:shadow-xs group">
                <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden border border-outline-variant/40 shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Profile">
                    @else
                        <span class="text-[#006c49] font-black text-[12px] uppercase">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</span>
                    @endif
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-[13px] font-bold text-on-surface group-hover:text-primary transition-colors leading-tight">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-on-surface-variant font-medium uppercase tracking-wider">Admin</span>
                </div>
            </a>
        </header>
        @else
        <div class="mb-8 hidden md:block"></div>
        @endif

        @yield('admin-content')
    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const sidebar = document.getElementById('sidebar-nav');
        const menuBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('close-menu-btn');

        if (menuBtn && sidebar) {
            menuBtn.addEventListener('click', () => {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
            });
        }
        
        if (closeBtn && sidebar) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
            });
        }

        // Global Search Feature
        const searchInput = document.getElementById('admin-global-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                
                // Find all searchable items (table rows or explicitly marked items)
                const searchableElements = document.querySelectorAll('main tbody tr, main .searchable-item');
                
                searchableElements.forEach(el => {
                    const searchTarget = el.hasAttribute('data-search') 
                        ? el.getAttribute('data-search') 
                        : el.textContent;
                    const text = searchTarget.toLowerCase();
                    el.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
