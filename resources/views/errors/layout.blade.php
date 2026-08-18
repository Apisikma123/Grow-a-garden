<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Grow a Garden</title>
    
    {{-- Google Fonts matching DESIGN.md & app.blade.php --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&family=Be+Vietnam+Pro:wght@600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-sans antialiased text-[#191c1d]">
    <div class="min-h-screen w-full grid place-items-center p-4 sm:p-6 relative overflow-hidden">
        
        {{-- Organic Background Accents --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#10b981] rounded-full mix-blend-multiply filter blur-[100px] opacity-[0.07]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-[#006c49] rounded-full mix-blend-multiply filter blur-[120px] opacity-[0.06]"></div>
        </div>

        {{-- Card Container --}}
        <div class="relative z-10 bg-white/95 backdrop-blur-md p-6 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl text-center" style="width: 100%; max-width: 480px; box-sizing: border-box;">
            
            {{-- Icon Container --}}
            <div class="mb-4 inline-flex items-center justify-center text-[#006c49] p-3.5 bg-[#006c49]/10 rounded-2xl">
                @yield('icon')
            </div>

            {{-- Clean Error Code --}}
            <div class="text-4xl sm:text-5xl font-extrabold text-[#006c49] tracking-tight mb-2 font-['Be_Vietnam_Pro']" style="width: 100%; display: block;">
                @yield('code')
            </div>

            {{-- Headline --}}
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3 font-['Be_Vietnam_Pro'] tracking-tight leading-snug" style="width: 100%; display: block; white-space: normal; word-break: normal;">
                @yield('headline')
            </h1>

            {{-- Message --}}
            <p class="text-sm sm:text-base text-slate-600 mb-8 leading-relaxed font-['Poppins']" style="width: 100%; display: block; white-space: normal; word-break: normal;">
                @yield('message')
            </p>

            {{-- Actions --}}
            <div style="width: 100%; display: flex; justify-content: center; align-items: center;">
                @yield('actions')
            </div>

        </div>
    </div>
</body>
</html>
