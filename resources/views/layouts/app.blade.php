<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Grow a Garden — Smart Garden Manager')</title>
    <meta name="description" content="@yield('description', 'Kelola kebun rumahan, urban farming, atau hidroponik dengan pemetaan cerdas dan kalender pertumbuhan otomatis.')" />
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}" />

    {{-- Google Fonts: Be Vietnam Pro + Material Symbols --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-background text-on-background font-sans min-h-screen antialiased">
    @yield('content')

    {{-- Global Loading Overlay (available on all pages via GardenLoader.show()/hide()) --}}
    @include('components.loading-overlay')

    @stack('scripts')
</body>
</html>
