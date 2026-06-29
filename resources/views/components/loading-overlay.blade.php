{{-- ═══════════════════════════════════════════════════════
     GLOBAL LOADING OVERLAY — Grow a Garden
     ═══════════════════════════════════════════════════════
     Usage in JS:
       GardenLoader.show('Saving journal...')
       GardenLoader.hide()
       GardenLoader.show()  // defaults to "Loading..."
     ═══════════════════════════════════════════════════════ --}}

<div id="global-loading-overlay"
     class="fixed inset-0 z-[999] flex items-center justify-center pointer-events-none"
     style="opacity: 0; visibility: hidden;"
     aria-live="polite"
     role="status">

    {{-- Solid gradient backdrop with vignette --}}
    <div class="absolute inset-0" style="background: linear-gradient(180deg, #03785A 0%, #026A4D 45%, #01543D 100%); box-shadow: inset 0 0 120px rgba(0,0,0,0.12);"></div>

    {{-- Decorative Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="overlay-decorative" style="width: 400px; height: 400px; top: -100px; left: -100px; animation-duration: 16s; opacity: 0.05;"></div>
        <div class="overlay-decorative" style="width: 600px; height: 600px; bottom: -200px; right: -150px; animation-duration: 14s; opacity: 0.07; background: #A8D08D;"></div>
        <div class="overlay-decorative" style="width: 350px; height: 350px; top: 30%; left: 70%; animation-duration: 18s; opacity: 0.06;"></div>
    </div>

    {{-- Center Glow --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div style="width: 350px; height: 350px; background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.05) 35%, transparent 70%);"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 flex flex-col items-center">

        {{-- Gear (rotating, layered above leaf) --}}
        <div class="flex items-center justify-center relative z-10">
            <img
                id="overlay-gear"
                src="{{ asset('images/Gear.png') }}"
                alt=""
                aria-hidden="true"
                class="w-[98px] h-[98px] md:w-[120px] md:h-[120px] loader-gear-spin"
                draggable="false"
            >
        </div>

        {{-- Spacing: Gear → Leaf = 0px (unifying as one logo) --}}
        <div style="height: 0px;"></div>

        {{-- Leaf (brand, subtle breathing, layered under gear) --}}
        <div class="flex items-center justify-center relative z-0">
            <img
                id="overlay-leaf"
                src="{{ asset('images/Leaf.png') }}"
                alt=""
                aria-hidden="true"
                class="w-[120px] h-[120px] md:w-[140px] md:h-[140px] -mt-14 md:-mt-16 loader-leaf-breathe"
                draggable="false"
            >
        </div>

        {{-- Spacing: Leaf → Text = 24px --}}
        <div style="height: 24px;"></div>

        {{-- Slogan --}}
        <p class="text-[12px] md:text-[13px] font-normal text-white/60 text-center select-none tracking-wide">
            Manage Your Garden
        </p>

        {{-- Bouncing Dots --}}
        <div class="flex justify-center items-center space-x-[4px] mt-3 h-6">
            <div class="w-1.5 h-1.5 bg-white/90 rounded-full animate-dot-1"></div>
            <div class="w-1.5 h-1.5 bg-white/90 rounded-full animate-dot-2"></div>
            <div class="w-1.5 h-1.5 bg-white/90 rounded-full animate-dot-3"></div>
        </div>

    </div>
</div>

<style>
    /* ── Global loader keyframes ── */
    @keyframes overlayFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .overlay-decorative {
        position: absolute;
        border-radius: 50%;
        background: #5D8C46;
        filter: blur(60px);
        animation: overlayFloat 15s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes dotBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-dot-1 { animation: dotBounce 1s infinite ease-in-out; }
    .animate-dot-2 { animation: dotBounce 1s infinite ease-in-out 200ms; }
    .animate-dot-3 { animation: dotBounce 1s infinite ease-in-out 400ms; }

    @keyframes loaderGearSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes loaderLeafBreathe {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.03); }
    }
    @keyframes loaderTextPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    .loader-gear-spin {
        animation: loaderGearSpin 1.2s linear infinite;
        will-change: transform;
    }
    .loader-leaf-breathe {
        animation: loaderLeafBreathe 2.5s ease-in-out infinite;
        will-change: transform;
    }
    .loader-text-pulse {
        animation: loaderTextPulse 900ms ease-in-out infinite;
        will-change: opacity;
    }

    /* When overlay is shown, block interactions */
    #global-loading-overlay.is-visible {
        pointer-events: auto;
    }

    /* Reduced motion: disable all motion, keep static */
    @media (prefers-reduced-motion: reduce) {
        .loader-gear-spin {
            animation: none !important;
        }
        .loader-leaf-breathe {
            animation: none !important;
        }
        .loader-text-pulse,
        .animate-dot-1,
        .animate-dot-2,
        .animate-dot-3 {
            animation: none !important;
        }
    }
</style>

<script>
/**
 * GardenLoader — Global loading overlay API
 *
 * GardenLoader.show('Saving journal...')
 * GardenLoader.show()                    // "Loading..."
 * GardenLoader.hide()
 */
window.GardenLoader = (function() {
    'use strict';

    let overlay;

    function init() {
        overlay = document.getElementById('global-loading-overlay');
    }

    function show(message) { // message is ignored, using bouncing dots instead
        if (!overlay) init();

        // Make visible
        overlay.style.visibility = 'visible';
        overlay.classList.add('is-visible');

        // Animate in
        requestAnimationFrame(() => {
            overlay.style.transition = 'opacity 200ms ease-in-out';
            requestAnimationFrame(() => {
                overlay.style.opacity = '1';
            });
        });
    }

    function hide() {
        if (!overlay) init();

        overlay.style.transition = 'opacity 300ms ease-in-out';
        overlay.style.opacity = '0';

        setTimeout(() => {
            overlay.style.visibility = 'hidden';
            overlay.classList.remove('is-visible');
        }, 300);
    }

    return { show, hide };
})();
</script>
