{{-- ═══════════════════════════════════════════════════════
     GLOBAL LOADING OVERLAY — Grow a Garden
     ═══════════════════════════════════════════════════════
     Usage in JS:
       GardenLoader.show('Saving journal...')
       GardenLoader.hide()
       GardenLoader.show()  // defaults to "Loading..."
     ═══════════════════════════════════════════════════════ --}}

<div id="global-loading-overlay"
     class="fixed inset-0 z-[999999] flex items-center justify-center pointer-events-none"
     style="opacity: 0; visibility: hidden;"
     aria-live="polite"
     role="status">

    {{-- Solid background matching DESIGN.md surface color --}}
    <div class="absolute inset-0" style="background-color: #f8f9fa;"></div>

    {{-- Decorative Background removed for performance optimization (was causing lag on mobile) --}}

    {{-- Center Glow --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div style="width: 350px; height: 350px; background: radial-gradient(circle, rgba(0, 108, 73, 0.08) 0%, rgba(0, 108, 73, 0.02) 40%, transparent 70%);"></div>
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
                class="w-[98px] h-[98px] md:w-[120px] md:h-[120px] loader-gear-spin filter-primary"
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
                class="w-[120px] h-[120px] md:w-[140px] md:h-[140px] -mt-14 md:-mt-16 loader-leaf-breathe filter-primary"
                draggable="false"
            >
        </div>

        {{-- Spacing: Leaf → Text = 24px --}}
        <div style="height: 24px;"></div>

        {{-- Slogan --}}
        <p class="text-[12px] md:text-[13px] font-medium text-[#3c4a42] text-center select-none tracking-wide">
            Manage Your Garden
        </p>

        {{-- Bouncing Dots --}}
        <div class="flex justify-center items-center space-x-[4px] mt-3 h-6">
            <div class="w-1.5 h-1.5 bg-[#006c49] rounded-full animate-dot-1"></div>
            <div class="w-1.5 h-1.5 bg-[#006c49] rounded-full animate-dot-2"></div>
            <div class="w-1.5 h-1.5 bg-[#006c49] rounded-full animate-dot-3"></div>
        </div>

    </div>
</div>

<style>
    /* Turn white images into #006c49 */
    .filter-primary {
        filter: brightness(0) saturate(100%) invert(26%) sepia(90%) saturate(1637%) hue-rotate(138deg) brightness(96%) contrast(101%);
    }

    /* ── Global loader keyframes ── */

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
    @keyframes loaderLeafSway {
        0%, 100% { transform: rotate(-4deg) scale(1); }
        50% { transform: rotate(4deg) scale(1.02); }
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
        animation: loaderLeafSway 2.5s ease-in-out infinite;
        transform-origin: center center;
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

        // Make visible instantly to prevent missing frames during navigation
        overlay.style.transition = 'none';
        overlay.style.visibility = 'visible';
        overlay.style.opacity = '1';
        overlay.classList.add('is-visible');
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

// Fix BFCache issue (loader stays visible when hitting browser back button)
window.addEventListener('pageshow', function (e) {
    if (e.persisted && window.GardenLoader) {
        window.GardenLoader.hide();
    }
});

// Intercept link clicks to show loader BEFORE the browser freezes the page for navigation
document.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (link && link.href && !link.href.startsWith('javascript:') && !link.getAttribute('href').startsWith('#') && link.target !== '_blank') {
        // Only trigger for internal links
        if (link.hostname === window.location.hostname) {
            if (window.GardenLoader) window.GardenLoader.show();
        }
    }
});

document.addEventListener('submit', function (e) {
    if (!e.defaultPrevented && (!e.target.target || e.target.target !== '_blank')) {
        if (window.GardenLoader) window.GardenLoader.show();
    }
});
</script>
