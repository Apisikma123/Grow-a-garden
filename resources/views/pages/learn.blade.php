@extends('layouts.app')

@section('title', 'Pelajari Sistem Cerdas — Grow a Garden')
@section('description', 'Jelajahi bagaimana Grow a Garden menggunakan rule engine cerdas, template pertumbuhan otomatis, dan adaptasi cuaca untuk merawat kebun Anda secara autopilot.')

@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<style>
/* ============================================
   LEARN PAGE — 3D ELITE (VERDANT GROWTH)
   ============================================ */
*{box-sizing:border-box}

.sticky-bg {
    position: sticky;
    top: 0; 
    width: 100%; 
    height: 100vh;
    z-index: 0;
    background-color: #f8f9fa;
    overflow: hidden;
}
#hero-sequence-bg, #hero-sequence {
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    opacity: 1;
    transition: opacity 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    will-change: opacity, transform;
    transform: translateZ(0);
}
#hero-sequence-bg {
    object-fit: cover;
    object-position: center center;
    filter: blur(40px) brightness(1.1);
    transform: scale(1.1) translateZ(0); /* Prevents blurred edges from bleeding inward */
    z-index: 0;
    will-change: transform;
}
#hero-sequence {
    object-fit: contain;
    object-position: center center;
    z-index: 1;
}
#hero-sequence.ready, #hero-sequence-bg.ready { opacity: 1; }

.content-wrap {
    margin-top: -100vh;
    position: relative;
    z-index: 1;
}

#scroll-wrap { position: relative; z-index: 1; }

.s-block {
    position: relative;
    display: flex;
    align-items: flex-start;
    padding: 0 5%;
    overflow: visible;
}

/* Bright Glassmorphism Text Panel */
.s-panel {
    position: sticky;
    top: 18vh;
    max-width: 440px;
    padding: 36px 32px;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 24px;
    color: #111827; 
    pointer-events: auto;
    z-index: 2;
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.9s cubic-bezier(.16,1,.3,1), transform 0.9s cubic-bezier(.16,1,.3,1);
    box-shadow: 0 24px 48px rgba(0,108,73,0.05), 0 0 0 1px rgba(0,0,0,0.02) inset;
}
.s-panel.visible {
    opacity: 1;
    transform: translateY(0);
}
.s-panel--right { margin-left: auto; }
.s-panel--center { margin: 0 auto; text-align: center; }

/* Panel Typography */
.s-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: rgba(0, 108, 73, 0.08); /* Primary Green */
    color: #006c49;
    border: 1px solid rgba(0, 108, 73, 0.15);
    margin-bottom: 16px;
}
.s-panel h1 {
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 800;
    line-height: 1.08;
    letter-spacing: -0.03em;
    margin: 0 0 16px;
    color: #111827;
}
.s-panel h2 {
    font-size: clamp(26px, 3.5vw, 38px);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin: 0 0 12px;
    color: #111827;
}
.s-panel p {
    font-size: 16px;
    line-height: 1.6;
    color: #4b5563; 
    margin: 0 0 24px;
    white-space: normal;
    word-break: normal;
    font-weight: 400;
}
.s-panel .s-metric {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 12px;
    background: rgba(0, 108, 73, 0.05);
    border: 1px solid rgba(0, 108, 73, 0.1);
    font-size: 13px;
    color: #374151;
    margin-bottom: 6px;
    font-weight: 500;
}
.s-metric b { color: #006c49; font-size: 18px; font-weight: 800; }
.s-metric.drought b { color: #d97706; } 
.s-metric.drought { background: rgba(217, 119, 6, 0.05); border-color: rgba(217, 119, 6, 0.1); }

/* Holographic Text */
.holo {
    background: linear-gradient(135deg, #006c49 0%, #10b981 40%, #059669 70%, #006c49 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% auto;
    animation: holoShift 5s ease-in-out infinite;
}
@keyframes holoShift {
    0%,100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Scroll Cue */
.scroll-cue {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    margin-top: 24px;
}
.scroll-cue-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #006c49;
    animation: scrollPulse 2s ease-in-out infinite;
}
@keyframes scrollPulse {
    0%,100%{ transform: translateY(0); opacity: .4; }
    50%{ transform: translateY(10px); opacity: 1; }
}

/* CTA Button */
.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #006c49;
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    padding: 16px 36px;
    border-radius: 999px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(.16,1,.3,1);
    box-shadow: 0 10px 24px rgba(0,108,73,0.3);
    pointer-events: auto;
}
.cta-btn:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(0,108,73,0.4);
}

@media (max-width: 768px) {
    .s-panel { max-width: 100%; padding: 28px 24px; top: auto; bottom: 8vh; position: sticky; border-radius: 20px; }
    .s-panel--right { margin-left: 0; }
}

@media (prefers-reduced-motion: reduce) {
    .holo { animation: none !important; }
    .scroll-cue-dot { animation: none !important; }
    #hero-sequence { transition: none !important; opacity: 1 !important; }
    .s-panel { transition-duration: 0.01ms !important; opacity: 1 !important; transform: none !important; }
}
</style>
@endpush

@section('content')
{{-- ============================================
     WELCOME.BLADE.PHP NAVBAR LAYOUT
     ============================================ --}}
<header id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/30 backdrop-blur-xl border-b border-white/20 transition-all duration-300">
    <div class="max-w-[1280px] mx-auto flex items-center justify-between px-5 lg:px-8 h-16">
        <a href="/" class="flex items-center gap-2 group" id="nav-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Grow a Garden Logo" class="w-8 h-8 rounded-md transition-transform duration-200 group-hover:scale-110 object-contain">
            <span class="text-lg font-bold text-on-surface tracking-tight">Grow a Garden</span>
        </a>

        <nav class="hidden md:flex items-center gap-8" id="nav-links">
            <a href="/#features" class="nav-link text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200">Fitur</a>
            <a href="/learn" class="nav-link active text-sm font-semibold text-primary transition-colors duration-200">How It Works</a>
            <a href="/#pricing" class="nav-link text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200">Harga</a>
        </nav>

        <a href="/login" class="hidden md:inline-flex items-center gap-2 bg-primary text-on-primary text-sm font-semibold px-6 py-2.5 rounded-full hover:bg-primary/90 active:scale-[0.97] transition-all duration-200 shadow-sm" id="nav-cta">
            Mulai Sekarang
        </a>

        <button class="md:hidden text-on-surface-variant p-2 rounded-lg hover:bg-surface-container-high transition-colors" id="mobile-menu-toggle" aria-label="Open navigation menu">
            <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
    </div>

    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-outline-variant/30 px-5 py-4 space-y-2">
        <a href="/#features" class="block text-sm font-medium text-on-surface-variant py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors">Fitur</a>
        <a href="/learn" class="block text-sm font-semibold text-primary bg-primary/10 py-2 px-4 rounded-lg transition-colors">How It Works</a>
        <a href="/#pricing" class="block text-sm font-medium text-on-surface-variant py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors">Harga</a>
        <a href="/login" class="block text-center bg-primary text-on-primary text-sm font-semibold px-6 py-2.5 rounded-full mt-2">Mulai Sekarang</a>
    </div>
</header>

<div id="scroll-wrap" style="margin-top: 0;">
    <div class="sticky-bg relative">
        <canvas id="hero-sequence-bg"></canvas>
        <canvas id="hero-sequence"></canvas>
    </div>
    
    <div class="content-wrap">
        <section class="s-block" id="s-intro" style="min-height: 140vh; padding-top: 20vh;">
        <div class="s-panel" data-panel="intro">
            <div class="s-chip"><span class="material-symbols-outlined" style="font-size:16px;">auto_awesome</span> Sistem Cerdas</div>
            <h1>Berkebun di era digital.<br>Lebih cerdas, <span class="holo">lebih presisi.</span></h1>
            <p>Grow a Garden adalah asisten kebun pintar Anda — tanpa sensor IoT. Dirancang dengan keanggunan modern untuk pekebun rumahan, urban farming, hidroponik, hingga sekolah.</p>
            <div class="scroll-cue"><span>Scroll untuk menjelajahi</span><div class="scroll-cue-dot"></div></div>
        </div>
    </section>

    <section class="s-block" id="s-plant" style="min-height: 140vh;">
        <div class="s-panel s-panel--right" data-panel="plant">
            <div class="s-chip"><span class="material-symbols-outlined" style="font-size:16px;">eco</span> Langkah 1 — Tanam</div>
            <h2>Pilih Bibit, Tanam ke Kebun Digital Anda</h2>
            <p>Pilih dari koleksi komoditas Indonesia — Cabai, Tomat, Selada, dan lainnya. Sistem langsung mengenali jenis tanaman dan menyiapkan jadwal perawatan otomatis secara instan.</p>
        </div>
    </section>

    <section class="s-block" id="s-calendar" style="min-height: 130vh;">
        <div class="s-panel" data-panel="calendar">
            <div class="s-chip"><span class="material-symbols-outlined" style="font-size:16px;">calendar_month</span> Langkah 2 — Auto-Calendar</div>
            <h2>Kalender Pertumbuhan Terbentuk Instan</h2>
            <p>Growth & Care Template dari database langsung memetakan fase hidup tanaman Anda — dari Germinasi (Hari 0) hingga estimasi Panen secara otomatis.</p>
        </div>
    </section>

    <section class="s-block" id="s-water" style="min-height: 160vh;">
        <div class="s-panel s-panel--right" data-panel="water">
            <div class="s-chip"><span class="material-symbols-outlined" style="font-size:16px;">water_drop</span> Langkah 3 — Perawatan Presisi</div>
            <h2>Penyiraman yang Tepat Waktu & Tepat Takaran</h2>
            <p>Jadwal perawatan harian dihasilkan otomatis dari template. Setiap tugas — penyiraman, pemupukan, pemangkasan — tercatat rapi di Activity Log Anda.</p>
        </div>
    </section>

    <section class="s-block" id="s-weather" style="min-height: 150vh;">
        <div class="s-panel" data-panel="weather">
            <div class="s-chip"><span class="material-symbols-outlined" style="font-size:16px;">thunderstorm</span> Adaptasi Cuaca</div>
            <h2>Sistem yang Membaca Cuaca untuk Anda</h2>
            <p>Terhubung data cuaca lokal. Sistem otomatis menyesuaikan jadwal perawatan berdasarkan kondisi lingkungan kebun Anda setiap harinya.</p>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                <div class="s-metric"><span class="material-symbols-outlined" style="font-size:18px;color:#0ea5e9;">water_drop</span> Hujan → Siram <b>-30%</b></div>
                <div class="s-metric drought"><span class="material-symbols-outlined" style="font-size:18px;color:#d97706;">wb_sunny</span> Kemarau → Siram <b>+50%</b></div>
            </div>
        </div>
    </section>

    <section class="s-block" id="s-harvest" style="min-height: 120vh; padding-bottom: 120px;">
        <div class="s-panel s-panel--center" data-panel="harvest">
            <div class="s-chip" style="margin:0 auto 20px;"><span class="material-symbols-outlined" style="font-size:16px;">emoji_events</span> Hasil Akhir</div>
            <h2>Berhenti menebak,<br><span class="holo">mulailah memanen.</span></h2>
            <p>Jadwal otomatis berdasarkan komoditas pertanian Indonesia. "Resep bertani" dirumuskan oleh Admin melalui Plant Master — Anda tinggal menikmati hasilnya. Berkebun semudah bermain game!</p>
            <a href="/register" class="cta-btn mt-4">Mulai Berkebun Gratis <span class="material-symbols-outlined" style="font-size:22px;">arrow_forward</span></a>
        </div>
    </section>
    </div> <!-- Close content-wrap -->
</div> <!-- Close scroll-wrap -->

<footer class="bg-surface border-t border-outline-variant/30 py-6 relative z-10">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm font-bold text-on-surface italic">Grow a Garden</div>

        <nav class="flex items-center gap-6 text-xs font-medium text-on-surface-variant">
            <a href="/sitemap" class="hover:text-primary transition-colors">Sitemap</a>
            <a href="/privacy-policy" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
            <a href="/terms" class="hover:text-primary transition-colors">Syarat Layanan</a>
        </nav>

        <p class="text-xs text-on-surface-variant">&copy; {{ date('Y') }} Grow a Garden. All rights reserved.</p>
    </div>
</footer>
@endsection

@push('scripts')
{{-- Navbar Mobile Script --}}
<script>
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            const icon = toggle.querySelector('.material-symbols-outlined');
            icon.textContent = menu.classList.contains('hidden') ? 'menu' : 'close';
        });
    }
</script>

{{-- Premium GSAP Image Sequence Engine --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const canvas = document.getElementById("hero-sequence");
        const canvasBg = document.getElementById("hero-sequence-bg");
        if (!canvas || !canvasBg) return;
        
        const ctx = canvas.getContext("2d", { alpha: false });
        const ctxBg = canvasBg.getContext("2d", { alpha: false });
        
        const frameCount = 480;
        const images = [];
        let loadedCount = 0;
        
        let currentFrame = 0;
        let targetFrame = 0;
        const easeFactor = 0.1; // Smooth Lerp factor
        let lastDrawnFrame = -1;
        let canvasSetupDone = false;
        
        // 1. Canvas DPR & Resizing Logic (Mimic Object-Fit Cover in JS)
        function resizeCanvases() {
            const dpr = Math.min(window.devicePixelRatio || 1, 2); // Cap at 2 to prevent 4K GPU lag
            const container = canvas.parentElement;
            if (!container) return;

            const rect = container.getBoundingClientRect();
            canvas.width = Math.floor(rect.width * dpr);
            canvas.height = Math.floor(rect.height * dpr);
            
            canvasBg.width = Math.max(1, Math.floor(rect.width * 0.1 * dpr));
            canvasBg.height = Math.max(1, Math.floor(rect.height * 0.1 * dpr));
            
            // Force redraw on resize
            lastDrawnFrame = -1;
        }

        // 5. Sizing Logic: JS Math to mimic object-fit: contain/cover with a Zoom-Out scale factor (0.85)
        function drawCover(context, img, targetW, targetH) {
            if (!img || !img.complete) return;
            const imgRatio = img.naturalWidth / img.naturalHeight;
            const targetRatio = targetW / targetH;
            let renderW, renderH, offsetX, offsetY;

            // Zoom-out scale factor so the 3D model isn't cropped or overly large
            const scaleFactor = 0.85;

            if (targetRatio > imgRatio) {
                renderH = targetH * scaleFactor;
                renderW = renderH * imgRatio;
            } else {
                renderW = targetW * scaleFactor;
                renderH = renderW / imgRatio;
            }

            offsetX = (targetW - renderW) / 2;
            offsetY = (targetH - renderH) / 2;

            context.drawImage(img, offsetX, offsetY, renderW, renderH);
        }

        // 2. Full Preloading & Caching
        const assetBaseUrl = "{{ asset('images/3d') }}";
        
        const loadingStatus = document.createElement('div');
        loadingStatus.style.cssText = "position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#006c49; font-weight:bold; font-size:1.2rem; z-index:50;";
        canvas.parentElement.appendChild(loadingStatus);
        
        function preloadAllImages() {
            for (let i = 0; i < frameCount; i++) {
                const img = new Image();
                img.onload = () => {
                    loadedCount++;
                    loadingStatus.innerText = `Loading 3D Experience... ${Math.round((loadedCount / frameCount) * 100)}%`;
                    
                    if (loadedCount === frameCount) {
                        loadingStatus.remove();
                        initEngine();
                    }
                };
                img.src = `${assetBaseUrl}/lv_0_20260811155500_${(i + 1).toString().padStart(6, '0')}.jpg`;
                images.push(img);
            }
        }
        
        function initEngine() {
            resizeCanvases();
            
            canvas.classList.add("ready");
            canvasBg.classList.add("ready");
            canvasSetupDone = true;
            
            // Start render loop
            requestAnimationFrame(renderLoop);
            
            // Event listeners
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', () => {
                resizeCanvases();
                onScroll();
            }, { passive: true });
            
            onScroll(); // Initialize first position
            
            // GSAP Panel animations
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                const panels = document.querySelectorAll('.s-panel');
                panels.forEach((panel) => {
                    ScrollTrigger.create({
                        trigger: panel.parentElement,
                        start: "top 60%",
                        end: "bottom 40%",
                        toggleClass: {targets: panel, className: "visible"}
                    });
                });
            }
        }
        
        function onScroll() {
            const wrap = document.getElementById('scroll-wrap');
            if (!wrap) return;
            
            const rect = wrap.getBoundingClientRect();
            const scrollDistance = rect.height - window.innerHeight;
            let progress = -rect.top / scrollDistance;
            
            progress = Math.max(0, Math.min(1, progress));
            targetFrame = progress * (frameCount - 1);
        }
        
        // 3. Decoupled Scroll & LERP & 4. Render Optimization
        function renderLoop() {
            requestAnimationFrame(renderLoop);
            if (!canvasSetupDone) return;
            
            // Decoupled LERP Smoothing
            currentFrame += (targetFrame - currentFrame) * easeFactor;
            
            // 4. Render Optimization: Only draw when calculated frame index actually changes!
            const frameIndex = Math.round(currentFrame);
            if (frameIndex === lastDrawnFrame) return;
            
            const img = images[frameIndex];
            if (!img || !img.complete) return;
            
            // Layer 0: Background Blur (Fast Low-res Fill)
            ctxBg.drawImage(img, 0, 0, canvasBg.width, canvasBg.height);
            
            // Layer 1: Main High-Res Canvas (Sharp HD Cover Fit)
            drawCover(ctx, img, canvas.width, canvas.height);
            
            lastDrawnFrame = frameIndex;
        }
        
        preloadAllImages();
    });
</script>
@endpush
