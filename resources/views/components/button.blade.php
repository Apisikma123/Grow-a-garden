@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none active:scale-95';

    $variants = [
        'primary' => 'bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container',
        'secondary' => 'border-2 border-secondary text-secondary hover:bg-secondary hover:text-on-secondary',
        'ghost' => 'text-primary hover:bg-surface-variant hover:text-on-surface',
        'outline' => 'border border-outline text-on-surface hover:bg-surface-variant',
    ];

    $sizes = [
        'sm' => 'h-10 px-4 text-sm rounded-full',
        'md' => 'h-12 px-6 text-base rounded-full',
        'lg' => 'h-14 px-8 text-lg rounded-full',
        'icon' => 'h-12 w-12 rounded-full',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
