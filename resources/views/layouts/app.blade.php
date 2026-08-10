<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Grow a Garden — Smart Garden Manager')</title>
    <meta name="description" content="@yield('description', 'Kelola kebun rumahan, urban farming, atau hidroponik dengan pemetaan cerdas dan kalender pertumbuhan otomatis.')" />
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}" />

    {{-- Google Fonts: Poppins + Material Symbols --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-background text-on-background font-sans min-h-screen antialiased">
    @yield('content')

    @include('components.loading-overlay')

    {{-- SweetAlert2 for Alerts & Toasts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/alerts.js') }}"></script>

    {{-- Generate All Notifications from Session --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                Alert.success('Berhasil!', {!! json_encode(session('success')) !!});
            @endif
            
            @if(session('error'))
                Alert.error('Oops!', {!! json_encode(session('error')) !!});
            @endif
            
            @if(session('warning'))
                Alert.warning('Peringatan', {!! json_encode(session('warning')) !!});
            @endif
            
            @if(session('info'))
                Alert.info('Informasi', {!! json_encode(session('info')) !!});
            @endif

            @if(session('new_badge'))
                @php $b = session('new_badge'); @endphp
                Swal.fire({
                    title: '⭐ BADGE BARU TERBUKA!',
                    html: `
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 10px 0;">
                            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #fbbf24, #d97706); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.4);">
                                <span class="material-symbols-outlined" style="font-size: 36px; color: #ffffff;">{{ $b['icon_url'] ?? 'workspace_premium' }}</span>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">{{ $b['name'] }}</h3>
                            <p style="font-size: 14px; font-weight: 500; color: #64748b; margin: 0; line-height: 1.5;">{{ $b['description'] }}</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Klaim & Lanjutkan',
                    confirmButtonColor: '#006c49',
                    customClass: {
                        popup: 'rounded-3xl p-6 border-2 border-amber-400/60 shadow-2xl',
                        confirmButton: 'rounded-full px-6 py-3 font-bold text-sm'
                    }
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
