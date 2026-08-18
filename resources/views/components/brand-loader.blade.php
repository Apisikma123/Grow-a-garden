@props([
    'size' => 'md',
    'text' => null,
])

@php
    $sizeClasses = [
        'sm' => [
            'gear' => 'w-[36px] h-[36px]',
            'leaf' => 'w-[44px] h-[44px] -mt-5',
            'text' => 'text-[12px]',
        ],
        'md' => [
            'gear' => 'w-[56px] h-[56px]',
            'leaf' => 'w-[70px] h-[70px] -mt-8',
            'text' => 'text-[13px]',
        ],
        'lg' => [
            'gear' => 'w-[80px] h-[80px]',
            'leaf' => 'w-[100px] h-[100px] -mt-11',
            'text' => 'text-[14px]',
        ],
    ];
    $s = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="flex flex-col items-center justify-center py-4 select-none">
    {{-- Gear (rotating) --}}
    <div class="flex items-center justify-center relative z-10">
        <img
            src="{{ asset('images/Gear.png') }}"
            alt="Loading..."
            class="{{ $s['gear'] }} brand-loader-gear-spin brand-filter-primary"
            draggable="false"
        >
    </div>

    {{-- Leaf (breathing under gear) --}}
    <div class="flex items-center justify-center relative z-0">
        <img
            src="{{ asset('images/Leaf.png') }}"
            alt=""
            class="{{ $s['leaf'] }} brand-loader-leaf-breathe brand-filter-primary"
            draggable="false"
        >
    </div>

    @if($text)
        <p class="{{ $s['text'] }} font-semibold text-[#006c49] mt-3 text-center tracking-wide">
            {{ $text }}
        </p>
    @endif
</div>

<style>
    @keyframes brandLoaderGearSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes brandLoaderLeafSway {
        0%, 100% { transform: rotate(-4deg) scale(1); }
        50% { transform: rotate(4deg) scale(1.02); }
    }
    .brand-loader-gear-spin {
        animation: brandLoaderGearSpin 1.2s linear infinite;
        will-change: transform;
    }
    .brand-loader-leaf-breathe {
        animation: brandLoaderLeafSway 2.5s ease-in-out infinite;
        transform-origin: center center;
        will-change: transform;
    }
    .brand-filter-primary {
        filter: brightness(0) saturate(100%) invert(26%) sepia(90%) saturate(1637%) hue-rotate(138deg) brightness(96%) contrast(101%);
    }
</style>
