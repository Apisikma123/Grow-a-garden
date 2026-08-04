@props([
    'padding' => 'md',
    'shadow' => 'default',
    'interactive' => false,
])

@php
    $baseClasses = 'bg-surface rounded-2xl border border-outline-variant overflow-hidden';
    
    $paddings = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];

    $shadows = [
        'none' => '',
        'default' => 'ambient-shadow',
        'lg' => 'ambient-shadow-lg',
    ];

    $interactiveClasses = $interactive ? 'transition-transform hover:-translate-y-1 hover:ambient-shadow-lg cursor-pointer' : '';

    $classes = $baseClasses . ' ' . ($paddings[$padding] ?? $paddings['md']) . ' ' . ($shadows[$shadow] ?? $shadows['default']) . ' ' . $interactiveClasses;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
