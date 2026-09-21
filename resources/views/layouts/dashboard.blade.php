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


            <div class="relative" id="mobile-notif-wrapper">
                <button onclick="toggleNotifications()" class="text-on-surface-variant active:opacity-80 transition-opacity p-1 flex items-center justify-center relative" aria-label="Notifications">
                    <span class="material-symbols-outlined text-[24px]">notifications</span>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span id="mobile-notif-badge" class="absolute top-0 right-0 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
                    @endif
                </button>
            </div>
            <a href="/settings" class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-sm shadow-sm active:scale-95 transition-transform overflow-hidden" aria-label="Profile and Settings">
                @if(Auth::user()->avatar)
                    <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Profile">
                @else
                    {{ strtoupper(substr(Auth::user()->name ?? 'GT', 0, 2)) }}
                @endif
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
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain shrink-0" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[32px]\'>local_florist</span>'">
                <span>Grow a Garden</span>
            </a>
        </div>

        {{-- Nav Items --}}
        <div class="flex-1 flex flex-col gap-1 overflow-y-auto no-scrollbar px-2">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'dashboard', 'url' => '/dashboard'],
                    ['route' => 'gardens', 'label' => 'Kebun Saya', 'icon' => 'yard', 'url' => '/gardens'],
                    ['route' => 'growth-calendar', 'label' => 'Kalender Tanam', 'icon' => 'calendar_month', 'url' => '/growth-calendar'],
                    ['route' => 'care-tasks', 'label' => 'Tugas Perawatan', 'icon' => 'water_drop', 'url' => '/care-tasks'],
                    ['route' => 'activity-log', 'label' => 'Activity Log', 'icon' => 'history', 'url' => '/activity-log'],
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



            <div class="bg-surface rounded-[20px] p-2 flex items-center justify-between border border-outline-variant/30 ambient-shadow">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-outline-variant/20 flex items-center justify-center text-[#006c49] font-black text-[13px] shrink-0">
                        @if(Auth::user()->avatar)
                            <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover" alt="Profile">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'GT', 0, 2)) }}
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[13px] font-bold text-on-surface leading-tight truncate max-w-[95px]">{{ Auth::user()->name ?? 'Green Thumb' }}</span>
                        <span class="text-[10px] text-on-surface-variant font-medium">Profil</span>
                    </div>
                </div>
                <div class="relative group shrink-0">
                    <button type="button" class="p-1.5 text-on-surface-variant hover:text-[#006c49] transition-colors flex items-center justify-center rounded-full hover:bg-black/5 relative" title="Menu" aria-label="Menu">
                        <span class="material-symbols-outlined text-[18px] font-bold">more_vert</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span id="profile-menu-notif-badge" class="absolute top-1 right-1 w-1.5 h-1.5 bg-error rounded-full ring-2 ring-surface"></span>
                        @endif
                    </button>
                    
                    <!-- Dropdown -->
                    <div class="absolute right-0 bottom-full mb-2 w-48 bg-surface border border-outline-variant/30 rounded-[16px] shadow-lg ring-1 ring-black ring-opacity-5 invisible opacity-0 scale-95 group-hover:visible group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 transform origin-bottom-right z-50 overflow-hidden ambient-shadow">
                        <div class="py-1">
                            <a href="javascript:void(0)" onclick="toggleNotifications()" class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface hover:bg-surface-container-highest hover:text-[#006c49] transition-colors group/item">
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover/item:text-[#006c49] transition-colors">notifications</span>
                                <span class="font-medium">Notifikasi</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span id="desktop-notif-badge" class="ml-auto w-5 h-5 bg-error text-white text-[10px] flex items-center justify-center rounded-full shadow-sm">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </a>
                            <a href="/settings" class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface hover:bg-surface-container-highest hover:text-[#006c49] transition-colors group/item">
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover/item:text-[#006c49] transition-colors">settings</span>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================
         MAIN CONTENT CANVAS
         ============================================ --}}
    <main class="flex-1 md:ml-64 p-5 md:p-8 pb-32 md:pb-8 overflow-y-auto no-scrollbar max-w-[1280px] mx-auto w-full min-w-0" style="width: 100%;">
        @yield('dashboard-content')
    </main>

</div>


{{-- (Pricing modal removed — all features are now accessible to all users) --}}


{{-- ============================================
     NOTIFICATIONS DROPDOWN/MODAL
     ============================================ --}}
<div id="notifications-modal" class="fixed inset-y-0 right-0 w-full md:w-96 bg-surface z-[110] shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col border-l border-outline-variant/30">
    <div class="px-5 py-4 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
        <h3 class="font-bold text-lg text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[#006c49]">notifications</span> Notifikasi
        </h3>
        <div class="flex items-center gap-3">
            <button onclick="markAllAsRead()" class="text-xs font-semibold text-[#006c49] hover:underline">Tandai Semua Dibaca</button>
            <button onclick="toggleNotifications()" class="text-on-surface-variant hover:text-error bg-surface-container p-1 rounded-full"><span class="material-symbols-outlined text-[20px]">close</span></button>
        </div>
    </div>
    <div class="flex-1 overflow-y-auto" id="notifications-list">
        @forelse(Auth::user()->notifications as $notification)
            <a href="{{ $notification->data['action_url'] ?? '#' }}" class="block px-5 py-4 border-b border-outline-variant/30 hover:bg-surface-container-lowest transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-[#006c49]/5' }}" onclick="markAsRead('{{ $notification->id }}')">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">eco</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-on-surface mb-0.5">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</p>
                        <p class="text-xs text-on-surface-variant line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                        <p class="text-[10px] text-on-surface-variant mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)
                        <div class="w-2 h-2 bg-error rounded-full shrink-0 mt-1.5"></div>
                    @endif
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-on-surface-variant p-6 text-center">
                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">notifications_paused</span>
                <p class="text-sm">Tidak ada notifikasi saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- ============================================
     MOBILE BOTTOM NAVIGATION
     ============================================ --}}
<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 py-2 bg-surface/95 backdrop-blur-md z-50 rounded-t-xl shadow-[0_-4px_6px_-1px_rgba(6,95,70,0.08)]" id="mobile-bottom-nav" style="padding-bottom: max(0.5rem, env(safe-area-inset-bottom));">
    @php
        $bnavItems = [
            ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home', 'url' => '/dashboard'],
            ['route' => 'gardens', 'label' => 'Kebun', 'icon' => 'yard', 'url' => '/gardens'],
            ['route' => 'growth-calendar', 'label' => 'Kalender', 'icon' => 'event_note', 'url' => '/growth-calendar'],
            ['route' => 'care-tasks', 'label' => 'Tugas', 'icon' => 'checklist', 'url' => '/care-tasks'],
            ['route' => 'activity-log', 'label' => 'Riwayat', 'icon' => 'history', 'url' => '/activity-log'],
        ];
    @endphp

    @foreach($bnavItems as $item)
        @php
            $isActive = ltrim($item['url'], '/') === $currentRoute;
        @endphp
        <a href="{{ $item['url'] }}" class="flex flex-col items-center justify-center {{ $isActive ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-xl px-2.5 py-1.5 active:scale-95 transition-transform duration-200">
            <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
            <span class="text-[10px] font-semibold mt-0.5">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
<script>
    window.AppState = {
        role: '{{ Auth::check() ? Auth::user()->role : "user" }}',
        usage: { 
            gardens: {{ Auth::check() ? \App\Models\Garden::where('user_id', Auth::id())->count() : 0 }}, 
            plants: {{ Auth::check() ? \App\Models\Plant::whereIn('garden_id', \App\Models\Garden::where('user_id', Auth::id())->pluck('id'))->count() : 0 }} 
        }
    };

    window.checkLimit = function(resourceType) {
        // Semua fitur gratis dan tanpa batasan kuota
        return true;
    };


    // Badge unlock from session (for redirect-back flows)
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('new_badge'))
            setTimeout(() => {
                if (window.Alert && Alert.modal && Alert.modal.badge) {
                    Alert.modal.badge({!! json_encode(session('new_badge')) !!});
                }
            }, 600);
        @endif
    });

    // Notification Logic
    function toggleNotifications() {
        const modal = document.getElementById('notifications-modal');
        if (modal.classList.contains('translate-x-full')) {
            modal.classList.remove('translate-x-full');
            modal.classList.add('translate-x-0');
        } else {
            modal.classList.remove('translate-x-0');
            modal.classList.add('translate-x-full');
        }
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.reload();
        });
    }

    function markAsRead(id) {
        fetch('/notifications/' + id + '/mark-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    }

    @if(Auth::check())
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.private('App.Models.User.{{ Auth::id() }}')
                .notification((notification) => {
                    // Update badges
                    const mBadge = document.getElementById('mobile-notif-badge');
                    if (mBadge) mBadge.style.display = 'block';
                    else {
                        const mBtn = document.querySelector('#mobile-notif-wrapper button');
                        if (mBtn) mBtn.innerHTML += '<span id="mobile-notif-badge" class="absolute top-0 right-0 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>';
                    }

                    const dBadge = document.getElementById('desktop-notif-badge');
                    if (dBadge) {
                        dBadge.innerText = parseInt(dBadge.innerText) + 1;
                        dBadge.style.display = 'flex';
                    }

                    const pBadge = document.getElementById('profile-menu-notif-badge');
                    if (pBadge) pBadge.style.display = 'block';
                    else {
                        const pBtn = document.querySelector('.relative.group.shrink-0 button');
                        if (pBtn) pBtn.innerHTML += '<span id="profile-menu-notif-badge" class="absolute top-1 right-1 w-1.5 h-1.5 bg-error rounded-full ring-2 ring-surface"></span>';
                    }

                    // Prepend to list
                    const list = document.getElementById('notifications-list');
                    const emptyState = list.querySelector('.flex-col');
                    if (emptyState) emptyState.remove();

                    const html = `
                        <a href="${notification.action_url || '#'}" class="block px-5 py-4 border-b border-outline-variant/30 hover:bg-surface-container-lowest transition-colors bg-[#006c49]/5" onclick="markAsRead('${notification.id}')">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined">eco</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-on-surface mb-0.5">${notification.title || 'Notifikasi Baru'}</p>
                                    <p class="text-xs text-on-surface-variant line-clamp-2">${notification.message || ''}</p>
                                    <p class="text-[10px] text-on-surface-variant mt-1">Baru saja</p>
                                </div>
                                <div class="w-2 h-2 bg-error rounded-full shrink-0 mt-1.5"></div>
                            </div>
                        </a>
                    `;
                    list.insertAdjacentHTML('afterbegin', html);

                    // Show toast
                    if (window.Alert && Alert.toast) {
                        Alert.toast(notification.title || 'Notifikasi Baru', 'success');
                    }
                });
        }
    });
    @endif
</script>

@stack('scripts')
@endsection

