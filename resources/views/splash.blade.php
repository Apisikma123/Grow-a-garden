@extends('layouts.app')

@section('title', 'Grow a Garden — Smart Garden Manager')

@push('head')
<style>
    /* ── Splash-specific keyframes (scoped, not global) ── */
    @keyframes splashGearSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes splashTextPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    #splash-screen {
        background: linear-gradient(180deg, #03785A 0%, #026A4D 45%, #01543D 100%);
        box-shadow: inset 0 0 120px rgba(0,0,0,0.12); /* Vignette */
        will-change: opacity;
    }

    @keyframes splashFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .splash-decorative {
        position: absolute;
        border-radius: 50%;
        background: #5D8C46;
        filter: blur(60px);
        animation: splashFloat 15s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes dotBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-dot-1 { animation: dotBounce 1s infinite ease-in-out; }
    .animate-dot-2 { animation: dotBounce 1s infinite ease-in-out 200ms; }
    .animate-dot-3 { animation: dotBounce 1s infinite ease-in-out 400ms; }

    #splash-gear-wrapper {
        will-change: transform, opacity;
    }
    #splash-leaf-wrapper {
        will-change: transform, opacity;
    }

    .splash-gear-spinning {
        animation: splashGearSpin 1.2s linear infinite;
    }
    .splash-text-pulsing {
        animation: splashTextPulse 900ms ease-in-out infinite;
    }

    /* Reduced motion: disable rotation and scaling, use fade only */
    @media (prefers-reduced-motion: reduce) {
        .splash-gear-spinning {
            animation: none !important;
        }
        .splash-text-pulsing {
            animation: none !important;
        }
    }
</style>
@endpush

@section('content')
<div id="splash-screen" class="fixed inset-0 z-[100] flex items-center justify-center overflow-hidden" style="opacity: 0;">

    {{-- Decorative Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="splash-decorative" style="width: 400px; height: 400px; top: -100px; left: -100px; animation-duration: 16s; opacity: 0.05;"></div>
        <div class="splash-decorative" style="width: 600px; height: 600px; bottom: -200px; right: -150px; animation-duration: 14s; opacity: 0.07; background: #A8D08D;"></div>
        <div class="splash-decorative" style="width: 350px; height: 350px; top: 30%; left: 70%; animation-duration: 18s; opacity: 0.06;"></div>
    </div>

    {{-- Center Glow --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div style="width: 350px; height: 350px; background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.05) 35%, transparent 70%);"></div>
    </div>

    {{-- Centered content — symmetrical like the logo: Gear on top, Leaf below --}}
    <div class="relative z-10 flex flex-col items-center">

        {{-- Gear (rotating indicator, layered above leaf) --}}
        <div id="splash-gear-wrapper" class="flex items-center justify-center relative z-10" style="opacity: 0; transform: scale(0.8);">
            <img
                id="splash-gear"
                src="{{ asset('images/Gear.png') }}"
                alt=""
                aria-hidden="true"
                class="w-[120px] h-[120px] md:w-[142px] md:h-[142px]"
                draggable="false"
            >
        </div>

        {{-- Spacing: Gear → Leaf = 0px (unifying as one logo) --}}
        <div style="height: 0px;"></div>

        {{-- Leaf (brand identity, layered under gear) --}}
        <div id="splash-leaf-wrapper" class="flex items-center justify-center relative z-0" style="opacity: 0; transform: scale(0);">
            <img
                id="splash-leaf"
                src="{{ asset('images/Leaf.png') }}"
                alt="Grow a Garden"
                class="w-[160px] h-[160px] md:w-[196px] md:h-[196px] -mt-20 md:-mt-24"
                draggable="false"
            >
        </div>

        {{-- Spacing: Leaf → Text = 24px --}}
        <div style="height: 24px;"></div>

        {{-- Slogan --}}
        <p id="splash-slogan" class="text-[13px] md:text-[14px] font-normal text-white/60 text-center select-none tracking-wide" style="opacity: 0;">
            Manage Your Garden
        </p>

        {{-- Bouncing Dots --}}
        <div id="splash-text" class="flex justify-center items-center space-x-[4px] mt-3 h-6" style="opacity: 0;">
            <div class="w-1.5 h-1.5 bg-white/90 rounded-full animate-dot-1"></div>
            <div class="w-1.5 h-1.5 bg-white/90 rounded-full animate-dot-2"></div>
            <div class="w-1.5 h-1.5 bg-white/90 rounded-full animate-dot-3"></div>
        </div>

    </div>
</div>

@push('scripts')
<script>
(function() {
    'use strict';

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ─── Play-once guard ───
    if (sessionStorage.getItem('splash_played')) {
        window.location.replace('/login');
        return;
    }
    sessionStorage.setItem('splash_played', '1');

    // ─── DOM refs ───
    const screen     = document.getElementById('splash-screen');
    const gearWrap   = document.getElementById('splash-gear-wrapper');
    const gear       = document.getElementById('splash-gear');
    const leafWrap   = document.getElementById('splash-leaf-wrapper');
    const text       = document.getElementById('splash-text');
    const slogan     = document.getElementById('splash-slogan');

    // ─── Transition helper (GPU-friendly: transform + opacity only) ───
    function anim(el, props, duration, easing) {
        return new Promise(resolve => {
            const propNames = Object.keys(props);
            el.style.transition = propNames.map(p => `${p} ${duration}ms ${easing || 'ease-in-out'}`).join(', ');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    propNames.forEach(p => el.style[p] = props[p]);
                    setTimeout(resolve, duration);
                });
            });
        });
    }

    function wait(ms) {
        return new Promise(r => setTimeout(r, ms));
    }

    // ─── Reduced motion path ───
    if (prefersReducedMotion) {
        screen.style.opacity = '1';
        leafWrap.style.opacity = '1';
        leafWrap.style.transform = 'scale(1)';
        gearWrap.style.opacity = '1';
        gearWrap.style.transform = 'scale(1)';
        text.style.opacity = '1';
        slogan.style.opacity = '1';
        setTimeout(() => window.location.replace('/login'), 1200);
        return;
    }

    // ─── Full animation sequence ───
    async function run() {
        // Phase 1: Background fades in (300ms)
        await anim(screen, { opacity: '1' }, 300, 'ease-in-out');

        // Phase 2: Leaf grows (700ms, easeOutBack: scale 0 → 1.1 → 1.0)
        // Using two-step approach for the overshoot
        await anim(leafWrap, {
            opacity: '1',
            transform: 'scale(1.1)'
        }, 500, 'cubic-bezier(0.34, 1.56, 0.64, 1)');

        await anim(leafWrap, {
            transform: 'scale(1)'
        }, 200, 'ease-out');

        // Phase 3: Pause (500ms)
        await wait(500);

        // Phase 4: Gear fades in above leaf (300ms, scale 0.8 → 1.0)
        await anim(gearWrap, {
            opacity: '1',
            transform: 'scale(1)'
        }, 300, 'ease-out');

        // Phase 5: Gear begins rotating (1.2s, linear, infinite)
        gear.classList.add('splash-gear-spinning');

        // Phase 6: Dots and slogan appear
        await Promise.all([
            anim(text, { opacity: '1' }, 300, 'ease-in-out'),
            anim(slogan, { opacity: '1' }, 300, 'ease-in-out')
        ]);

        // Hold for ~1.5 seconds (loading simulation)
        await wait(1500);

        // Phase 7: Exit sequence
        // Stop gear rotation smoothly
        gear.classList.remove('splash-gear-spinning');
        gear.style.transform = getComputedStyle(gear).transform; // freeze rotation

        // Fade out gear (300ms)
        await anim(gearWrap, { opacity: '0' }, 300, 'ease-in-out');

        // Fade out leaf + text + slogan (300ms)
        await Promise.all([
            anim(leafWrap, { opacity: '0' }, 300, 'ease-in-out'),
            anim(text, { opacity: '0' }, 300, 'ease-in-out'),
            anim(slogan, { opacity: '0' }, 300, 'ease-in-out'),
        ]);

        // Fade entire screen (300ms)
        await anim(screen, { opacity: '0' }, 300, 'ease-in-out');

        // Navigate to login
        window.location.replace('/login');
    }

    // Start after all images loaded
    if (document.readyState === 'complete') {
        run();
    } else {
        window.addEventListener('load', run);
    }
})();
</script>
@endpush
@endsection
