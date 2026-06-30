<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Grow a Garden</title>
    
    {{-- Google Fonts matching DESIGN.md --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-sans antialiased text-[#191c1d]">
    <div class="min-h-screen w-full flex flex-col items-center justify-center p-6 relative overflow-hidden">
        
        {{-- Ambient shadows / organic gradients --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#10b981] rounded-full mix-blend-multiply filter blur-[100px] opacity-[0.06]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-[#006c49] rounded-full mix-blend-multiply filter blur-[120px] opacity-[0.05]"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center text-center max-w-lg w-full">
            
            {{-- Icon Container --}}
            <div class="mb-6 text-[#006c49]">
                @yield('icon')
            </div>
            
            {{-- Faded Background Code --}}
            <div class="relative w-full flex justify-center items-center mb-2">
                <h1 class="text-[120px] md:text-[160px] leading-none font-black text-[#10b981] opacity-10 select-none tracking-tighter absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 font-['Be_Vietnam_Pro']">
                    @yield('code')
                </h1>
                <h2 class="text-3xl md:text-4xl font-bold text-[#191c1d] relative z-10 mt-16 font-['Be_Vietnam_Pro'] tracking-tight">
                    @yield('headline')
                </h2>
            </div>
            
            <p class="text-[#3c4a42] mb-10 max-w-md mx-auto text-[16px] md:text-[18px] leading-relaxed font-['Poppins']">
                @yield('message')
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full sm:w-auto">
                @yield('actions')
            </div>
        </div>
    </div>
</body>
</html>
