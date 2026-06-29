@props([
    'status' => 'healthy', // healthy, attention, late, new
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider';

    $statuses = [
        'healthy' => 'bg-[var(--color-status-healthy-bg)] text-[var(--color-status-healthy-text)]',
        'attention' => 'bg-[var(--color-status-attention-bg)] text-[var(--color-status-attention-text)]',
        'late' => 'bg-[var(--color-status-late-bg)] text-[var(--color-status-late-text)]',
        'new' => 'bg-[var(--color-status-new-bg)] text-[var(--color-status-new-text)]',
    ];

    $classes = $baseClasses . ' ' . ($statuses[$status] ?? $statuses['healthy']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="material-symbols-outlined text-[16px]">{{ $icon }}</span>
    @endif
    {{ $slot }}
</span>
