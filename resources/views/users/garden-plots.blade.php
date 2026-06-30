@extends('layouts.dashboard')

@section('title', 'Garden Map — Grow a Garden')

@section('dashboard-content')
<style>
    /* Full bleed overrides for dashboard layout */
    main { padding: 0 !important; max-width: none !important; height: 100vh; overflow: hidden !important; }
    
    /* Premium Modern Organic Theme */
    .premium-shadow { box-shadow: 0 16px 40px rgba(0, 108, 73, 0.08); }
    .premium-shadow-hover { box-shadow: 0 24px 48px rgba(0, 108, 73, 0.12); }
    
    .bg-grid {
        background-color: #f2f6f4; /* Slightly cooler, premium green-gray tint */
        background-image: 
            linear-gradient(rgba(0, 108, 73, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 108, 73, 0.05) 1px, transparent 1px);
        background-size: 24px 24px;
        background-position: center center;
    }
    
    .zone-box {
        position: absolute;
        border: 2px dashed;
        border-radius: 20px;
        background-color: rgba(255,255,255,0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        user-select: none;
        touch-action: none;
        box-shadow: inset 0 0 0 2px rgba(255,255,255,0.6), 0 8px 24px rgba(0,0,0,0.04);
        transition: box-shadow 0.3s ease, border-style 0.3s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform-origin: center center;
    }
    .zone-box.active-drag { 
        cursor: grabbing !important; 
        transform: scale(0.95);
        z-index: 50 !important;
        box-shadow: inset 0 0 0 2px rgba(255,255,255,0.8), 0 12px 32px rgba(0,0,0,0.08);
    }
    .zone-box.selected {
        border-style: solid;
        border-width: 3px;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.8), 0 16px 48px rgba(0, 108, 73, 0.2);
        z-index: 10;
        background-color: rgba(255,255,255,0.98);
        transform: scale(1.02);
    }
    .zone-box.selected.active-drag {
        transform: scale(0.98);
    }
    .zone-box.collision {
        border-color: #ef4444 !important;
        background-color: rgba(254,226,226, 0.9) !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.3) !important;
        animation: premiumShake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }
    @keyframes premiumShake {
        10%, 90% { transform: scale(0.95) translateX(-2px); }
        20%, 80% { transform: scale(0.95) translateX(4px); }
        30%, 50%, 70% { transform: scale(0.95) translateX(-8px); }
        40%, 60% { transform: scale(0.95) translateX(8px); }
    }

    /* Shape Modifiers */
    .zone-box.shape-circle {
        border-radius: 50% !important;
    }
    .zone-box.shape-hexagon {
        border: none !important;
        border-radius: 0 !important;
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    }

    .resize-handle {
        position: absolute;
        bottom: -10px;
        right: -10px;
        width: 26px;
        height: 26px;
        background-color: #ffffff;
        border: 3px solid;
        border-radius: 50%;
        cursor: nwse-resize;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        touch-action: none;
    }
    .resize-handle:hover {
        transform: scale(1.3);
    }
    .zone-label {
        background-color: rgba(255,255,255,0.95);
        padding: 8px 16px;
        border-radius: 30px;
        box-shadow: 0 8px 24px rgba(0, 108, 73, 0.08), inset 0 0 0 1px rgba(255,255,255,1);
        display: flex;
        align-items: center;
        gap: 8px;
        pointer-events: none;
    }

    /* Mode classes */
    #canvas-container.mode-select .zone-box { cursor: grab; }
    #canvas-container.mode-pan { cursor: grab; touch-action: none; }
    #canvas-container.mode-pan:active { cursor: grabbing; }
    #canvas-container.mode-pan .zone-box { cursor: grab; pointer-events: none; } 
    #canvas-container.mode-draw { cursor: crosshair; touch-action: none; }
    #canvas-container.mode-draw .zone-box { pointer-events: none; }
    #canvas-container.mode-draw-poly { cursor: crosshair; touch-action: none; }
    #canvas-container.mode-draw-poly .zone-box { pointer-events: none; }

    .toolbar-btn.active {
        background-color: var(--color-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 108, 73, 0.3);
    }

    /* Drawing Preview Box */
    #draw-preview {
        position: absolute;
        border: 2px dashed #006c49;
        background-color: rgba(111, 251, 190, 0.2);
        border-radius: 20px;
        pointer-events: none;
        z-index: 100;
        display: none;
        backdrop-filter: blur(4px);
    }

    /* Fullscreen Mode Classes */
    #app-container.is-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        margin: 0;
        padding: 0;
    }

    #zones-layer-wrapper {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        transform-origin: 0 0;
        will-change: transform;
    }

    /* Premium Scrollbar */
    .premium-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .premium-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .premium-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(0, 108, 73, 0.1);
        border-radius: 10px;
    }
    .premium-scroll:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 108, 73, 0.2);
    }
</style>

<div id="dashboard-view" class="w-full min-h-screen bg-[#f8f9fa] font-sans overflow-y-auto relative z-10">
    <!-- Ambient Organic Background Glow -->
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-status-healthy/5 rounded-full blur-[140px]"></div>
    </div>
    
    <div class="max-w-[1280px] mx-auto px-6 py-12 md:py-20 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-16 gap-6">
            <div>
                <h1 class="text-[40px] md:text-[56px] font-black text-on-surface tracking-tight leading-none mb-3">Kebunku</h1>
                <p class="text-[18px] text-[#6c7a71] font-medium">Pilih zona tanam untuk mengelola plot dan tugas</p>
            </div>
            <button id="btn-create-garden" class="bg-primary text-white px-8 py-4 rounded-full font-bold text-[16px] hover:bg-primary-container transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] hover:shadow-[0_12px_32px_rgba(0,108,73,0.35)] flex items-center gap-3 transform hover:-translate-y-1">
                <span class="material-symbols-outlined text-[24px]">add</span>
                Buat Kebun Baru
            </button>
        </div>

        <div id="gardens-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Garden cards injected by JS -->
        </div>
    </div>

    <!-- Modal for New Garden -->
    <div id="new-garden-modal" class="fixed inset-0 z-[99999] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" id="new-garden-backdrop"></div>
        
        <!-- Modal Content Centered -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-[420px] p-4">
            <div class="bg-white/95 backdrop-blur-xl rounded-[32px] p-8 premium-shadow-hover flex flex-col">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#006c49]/20 to-[#10b981]/20 text-primary flex items-center justify-center shrink-0 border border-primary/10">
                        <span class="material-symbols-outlined text-[28px]">park</span>
                    </div>
                    <h2 class="text-2xl font-black text-on-surface leading-tight">Buat<br><span class="text-primary">New Garden</span></h2>
                </div>
                
                <div class="space-y-4 max-h-[60vh] overflow-y-auto premium-scroll pr-2">
                    <div>
                        <label class="block text-[13px] font-bold text-[#6c7a71] uppercase tracking-wider mb-2">Nama Kebun</label>
                        <input type="text" id="input-garden-name" class="w-full bg-[#f8f9fa] border border-[#e1e3e4] rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-on-surface font-medium transition-all" placeholder="e.g. Backyard Oasis">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#6c7a71] uppercase tracking-wider mb-2">Lokasi / Zona</label>
                        <input type="text" id="input-garden-location" class="w-full bg-[#f8f9fa] border border-[#e1e3e4] rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-on-surface font-medium transition-all" placeholder="e.g. Zone 4b">
                    </div>
                </div>
                <div class="flex gap-4 mt-8">
                    <button id="btn-cancel-garden-modal" class="flex-1 py-4 text-[#6c7a71] font-bold rounded-2xl hover:bg-[#f3f4f5] transition-colors text-[16px]">Batal</button>
                    <button id="btn-confirm-garden-modal" class="flex-[1.5] py-4 bg-primary text-white font-bold rounded-2xl hover:bg-primary-container active:scale-95 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] text-[16px] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">done</span> Buat Kebun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit Garden -->
<div id="edit-garden-modal" class="fixed inset-0 z-[99999] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" id="edit-garden-backdrop"></div>
    
    <!-- Modal Content Centered -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-[420px] p-4">
        <div class="bg-white/95 backdrop-blur-xl rounded-[32px] p-8 premium-shadow-hover flex flex-col">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#006c49]/20 to-[#10b981]/20 text-primary flex items-center justify-center shrink-0 border border-primary/10">
                    <span class="material-symbols-outlined text-[28px]">edit</span>
                </div>
                <h2 class="text-2xl font-black text-on-surface leading-tight">Edit<br><span class="text-primary">Kebun</span></h2>
            </div>
            
            <div class="space-y-4 max-h-[60vh] overflow-y-auto premium-scroll pr-2">
                <div>
                    <label class="block text-[13px] font-bold text-[#6c7a71] uppercase tracking-wider mb-2">Nama Kebun</label>
                    <input type="text" id="edit-garden-name" class="w-full bg-[#f8f9fa] border border-[#e1e3e4] rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-on-surface font-medium transition-all" placeholder="e.g. Backyard Oasis">
                </div>
                <div>
                    <label class="block text-[13px] font-bold text-[#6c7a71] uppercase tracking-wider mb-2">Lokasi / Zona</label>
                    <input type="text" id="edit-garden-location" class="w-full bg-[#f8f9fa] border border-[#e1e3e4] rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-on-surface font-medium transition-all" placeholder="e.g. Zone 4b">
                </div>
            </div>
            <div class="flex gap-4 mt-8">
                <button id="btn-cancel-edit-garden" class="flex-1 py-4 text-[#6c7a71] font-bold rounded-2xl hover:bg-[#f3f4f5] transition-colors text-[16px]">Batal</button>
                <button id="btn-confirm-edit-garden" class="flex-[1.5] py-4 bg-primary text-white font-bold rounded-2xl hover:bg-primary-container active:scale-95 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] text-[16px] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<div id="app-container" class="hidden flex w-full h-[100vh] font-sans relative bg-[#f2f6f4] transition-all duration-300">
    
    {{-- Mobile Sidebar Backdrop --}}
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[55] hidden md:hidden transition-opacity"></div>

    {{-- Main Sidebar (Moved to Right) --}}
    <div id="left-sidebar" class="order-last fixed md:relative right-0 top-0 bottom-0 w-[85%] md:w-[360px] bg-white/85 backdrop-blur-2xl border-l border-white/60 flex flex-col h-full flex-shrink-0 z-[60] md:z-20 premium-shadow transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] translate-x-full md:translate-x-0">
        
        <div class="p-8 pb-4 flex justify-between items-start relative z-10">
            <div>
                <button id="btn-back-to-dashboard" class="flex items-center gap-2 text-[#6c7a71] hover:text-on-surface font-bold text-[14px] mb-4 transition-colors group">
                    <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span> Kembali ke Kebun
                </button>
                <h1 id="canvas-garden-name" class="text-[32px] md:text-[36px] font-black text-on-surface leading-tight tracking-tight mb-1">Green Valley</h1>
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex items-center gap-2 text-[#3c4a42] text-[14px] font-semibold bg-[#F1F5F2] inline-flex px-3 py-1.5 rounded-full border border-[#e1e3e4]">
                        <span class="material-symbols-outlined text-[18px] text-primary">location_on</span>
                        <span><span id="canvas-garden-location">Zone 4b</span> • <span id="total-area-display" class="text-on-surface font-bold">0</span> m²</span>
                    </div>
                    <button id="btn-edit-garden" class="w-8 h-8 rounded-full bg-white border border-[#e1e3e4] flex items-center justify-center text-[#6c7a71] hover:text-primary hover:border-primary transition-colors shadow-sm" title="Edit Kebun">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                    </button>
                    <button id="btn-delete-garden" class="w-8 h-8 rounded-full bg-white border border-[#e1e3e4] flex items-center justify-center text-[#6c7a71] hover:text-red-500 hover:border-red-500 hover:bg-red-50 transition-colors shadow-sm" title="Hapus Kebun">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>
            </div>
            <button id="btn-close-sidebar" class="md:hidden w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 shadow-sm border border-slate-100">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="px-8 py-2 flex-1 flex flex-col min-h-0 relative z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Garden Zones</h2>
                <span class="w-6 h-px bg-slate-200"></span>
            </div>
            <div id="zone-list" class="flex flex-col gap-4 overflow-y-auto premium-scroll pb-6 flex-1 pr-2 -mr-2">
                <!-- Zones will be injected here -->
            </div>
        </div>

        <div class="p-8 relative z-10 bg-gradient-to-t from-white/90 to-white/0 pt-12">
            <button id="btn-sidebar-add" class="w-full bg-primary text-white rounded-2xl py-4 font-bold text-[16px] hover:bg-primary-container active:scale-95 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] flex items-center justify-center gap-2 group">
                <span class="material-symbols-outlined text-[22px] group-hover:rotate-90 transition-transform duration-300">add</span>
                New Plot
            </button>
        </div>
    </div>

    {{-- Center Canvas --}}
    <div id="canvas-container" class="flex-1 relative bg-grid overflow-hidden mode-select">
        
        <!-- Premium Vignette Overlay -->
        <div class="absolute inset-0 pointer-events-none z-10" style="background: radial-gradient(circle at center, transparent 40%, rgba(242, 246, 244, 0.8) 120%);"></div>

        <!-- Premium Glass Toolbar -->
        <div class="absolute top-6 md:top-8 left-1/2 -translate-x-1/2 bg-white/70 backdrop-blur-2xl rounded-full premium-shadow border border-white/80 p-2 flex items-center gap-1 md:gap-2 z-30 overflow-x-auto max-w-[90vw] no-scrollbar">
            <!-- Mobile Menu Toggle -->
            <button id="btn-mobile-menu" class="md:hidden w-11 h-11 rounded-full text-primary bg-white shadow-sm flex items-center justify-center transition-transform active:scale-90 font-bold mr-1 shrink-0">
                <span class="material-symbols-outlined text-[22px]">menu_open</span>
            </button>
            <div class="md:hidden w-px h-8 bg-slate-200 mx-1 shrink-0"></div>

            <button class="toolbar-btn active w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="select" title="Select"><span class="material-symbols-outlined text-[22px]">ads_click</span></button>
            <button class="toolbar-btn w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="draw" title="Draw Rect"><span class="material-symbols-outlined text-[22px]">draw</span></button>
            <button class="toolbar-btn w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="draw-poly" title="Draw Polygon"><span class="material-symbols-outlined text-[22px]">polyline</span></button>
            <button class="toolbar-btn w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="pan" title="Pan Map"><span class="material-symbols-outlined text-[22px]">pan_tool</span></button>
            
            <div class="w-px h-8 bg-slate-200 mx-1 md:mx-2 shrink-0"></div>
            
            <button id="btn-zoom-in" class="w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white flex items-center justify-center transition-all shrink-0 shadow-sm" title="Zoom In"><span class="material-symbols-outlined text-[22px]">zoom_in</span></button>
            <button id="btn-zoom-out" class="w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white flex items-center justify-center transition-all shrink-0 shadow-sm" title="Zoom Out"><span class="material-symbols-outlined text-[22px]">zoom_out</span></button>
            
            <div class="hidden md:block w-px h-8 bg-slate-200 mx-2 shrink-0"></div>
            
            <button id="btn-fullscreen" class="hidden md:flex w-11 h-11 rounded-full text-primary hover:bg-primary/10 items-center justify-center transition-all font-bold shrink-0" title="Toggle Fullscreen">
                <span class="material-symbols-outlined text-[22px]" id="icon-fullscreen">fullscreen</span>
            </button>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-opacity duration-500 z-0 px-4">
            <div class="w-24 h-24 md:w-28 md:h-28 bg-white rounded-full flex items-center justify-center mb-8 premium-shadow border border-white/60">
                <span class="material-symbols-outlined text-[48px] md:text-[56px] text-primary">yard</span>
            </div>
            <p class="text-[18px] md:text-[22px] text-slate-700 font-semibold text-center bg-white/70 backdrop-blur-xl px-8 py-5 rounded-3xl premium-shadow border border-white/60">Canvas is empty.<br><span class="text-slate-500 text-[15px] font-medium">Add your first planting zone to begin.</span></p>
        </div>
        
        <!-- Interactive Zones Container -->
        <div id="zones-layer-wrapper" class="z-20">
            <div id="draw-preview"></div>
            <svg id="draw-poly-preview" style="position: absolute; left: 0; top: 0; width: 1px; height: 1px; overflow: visible; display: none; z-index: 100;">
                <polygon id="draw-poly-lines" points="" fill="rgba(111, 251, 190, 0.3)" stroke="#006c49" stroke-width="2" stroke-dasharray="4" />
                <line id="draw-poly-cursor-line" x1="0" y1="0" x2="0" y2="0" stroke="#006c49" stroke-width="2" stroke-dasharray="4" />
                <circle id="draw-poly-start-node" cx="0" cy="0" r="6" fill="white" stroke="#006c49" stroke-width="2" />
            </svg>
            <div id="zones-layer" class="absolute inset-0 w-full h-full"></div>
        </div>
    </div>

    {{-- Right Sidebar (Premium Overlay) --}}
    <div id="right-sidebar" class="absolute right-0 top-0 bottom-0 w-[100%] sm:w-[420px] bg-white/90 backdrop-blur-3xl border-l border-white/60 flex flex-col flex-shrink-0 z-[70] shadow-[-20px_0_40px_rgba(0,0,0,0.04)] transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] translate-x-full">
        <div class="p-8 pb-6 flex justify-between items-center relative z-10">
            <h2 class="text-[14px] font-black text-slate-400 uppercase tracking-[0.2em]">Detail Plot</h2>
            <button id="btn-close-details" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 shadow-sm border border-slate-100 transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>

        <div class="p-8 pt-0 flex-1 overflow-y-auto premium-scroll relative z-10">
            <!-- Header -->
            <div class="flex items-center gap-5 mb-8">
                <div id="detail-icon-bg" class="w-20 h-20 rounded-[24px] flex items-center justify-center border-2 flex-shrink-0 bg-white shadow-sm transition-colors duration-300">
                    <span class="material-symbols-outlined text-[36px]" id="detail-icon" style="font-variation-settings: 'wght' 600;">eco</span>
                </div>
                <div>
                    <h3 class="text-[28px] font-black text-on-surface leading-tight mb-2 tracking-tight" id="detail-name">Zone Name</h3>
                    <span id="detail-status" class="text-[12px] font-bold px-3 py-1.5 rounded-full inline-flex items-center shadow-sm">Status</span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-3 mb-6" id="detail-quick-stats">
                <!-- injected by JS -->
            </div>

            <!-- Specs -->
            <div class="bg-white/60 backdrop-blur-md rounded-[24px] p-6 border border-white mb-6 shadow-[0_4px_24px_rgba(0,0,0,0.02)] space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-[14px] font-semibold text-[#6c7a71]">Plant</span>
                    <span class="text-[15px] font-bold text-on-surface flex items-center gap-2">
                        <span id="detail-plant">Unknown</span>
                        <button id="btn-assign-plant" class="w-6 h-6 rounded-full bg-[#f1f5f2] border border-[#e1e3e4] flex items-center justify-center text-[#3c4a42] hover:bg-[#e1e3e4] hover:text-on-surface transition-colors" title="Change Plant"><span class="material-symbols-outlined text-[14px]">edit</span></button>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[14px] font-semibold text-[#6c7a71]">Quantity</span>
                    <span class="text-[15px] font-bold text-on-surface" id="detail-qty">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[14px] font-semibold text-[#6c7a71]">Cultivar</span>
                    <span class="text-[15px] font-bold text-on-surface" id="detail-cultivar">Unknown</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[14px] font-semibold text-[#6c7a71]">Dimension</span>
                    <span class="text-[14px] font-bold text-primary bg-primary/10 px-3 py-1 rounded-full" id="detail-area">0 m²</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[14px] font-semibold text-[#6c7a71]">Tanggal Tanam</span>
                    <span class="text-[15px] font-bold text-on-surface">May 15, 2024</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[14px] font-semibold text-[#6c7a71]">Perkiraan Panen</span>
                    <span class="text-[15px] font-bold text-on-surface">Aug 10 - Aug 25</span>
                </div>
            </div>

            <!-- Progress -->
            <div class="mb-6 px-2 bg-white/60 p-6 rounded-[24px] border border-white shadow-[0_4px_24px_rgba(0,0,0,0.02)]">
                <div class="flex justify-between text-[14px] font-bold mb-4">
                    <span class="text-slate-500">Progres Tumbuh</span>
                    <span class="text-primary text-[18px] font-black" id="detail-progress-text">45%</span>
                </div>
                <div class="h-4 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-gradient-to-r from-[#006c49] to-[#10b981] rounded-full transition-all duration-1000 ease-out relative" style="width: 45%" id="detail-progress-bar">
                        <div class="absolute inset-0 bg-white/20 w-full h-full" style="background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent); background-size: 1rem 1rem;"></div>
                    </div>
                </div>
            </div>
            
            <!-- To Do List -->
            <div class="mb-8" id="detail-todos">
                <!-- injected by JS -->
            </div>

            <!-- Log Aktivitas -->
            <div class="mb-8">
                <h4 class="text-[14px] font-bold text-on-surface mb-4">Log Aktivitas</h4>
                <div class="relative pl-4 border-l-2 border-slate-200 space-y-5" id="detail-activity-log">
                    <!-- injected by JS -->
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 mt-auto">
                <button class="flex-1 bg-white border border-slate-200 text-slate-700 rounded-2xl py-4 font-bold text-[16px] hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all flex justify-center items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">edit</span> Edit
                </button>
                <button id="btn-delete-zone" class="flex-1 bg-red-50 border border-red-100 text-red-600 rounded-2xl py-4 font-bold text-[16px] hover:bg-red-100 active:scale-95 transition-all flex justify-center items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">delete</span> Delete
                </button>
            </div>
        </div>
    </div>
    
    <!-- Confirm Delete Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 z-[9999] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" id="delete-confirm-backdrop"></div>
        
        <!-- Modal Content Centered -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-[400px] p-4 z-10" id="delete-confirm-wrapper">
            <div class="bg-white/95 backdrop-blur-xl rounded-[32px] p-8 premium-shadow-hover flex flex-col transform transition-transform scale-95 duration-200" id="delete-confirm-content">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-[#ffdad6]/50 text-[#ba1a1a] flex items-center justify-center shrink-0 border border-[#ffdad6]">
                        <span class="material-symbols-outlined text-[28px]">delete</span>
                    </div>
                    <h3 class="text-[24px] font-black text-on-surface leading-tight">Delete Plot?</h3>
                </div>
                
                <p class="text-[15px] font-medium text-[#6c7a71] mb-8 leading-relaxed">Are you sure you want to delete this plot? This action cannot be undone and you will lose all planted data.</p>
                
                <div class="flex gap-4">
                    <button id="btn-cancel-delete" class="flex-1 py-4 px-4 rounded-2xl font-bold text-[#3c4a42] bg-[#f3f4f5] hover:bg-[#e7e8e9] transition-colors">Batal</button>
                    <button id="btn-confirm-delete" class="flex-1 py-4 px-4 rounded-2xl font-bold text-white bg-[#ba1a1a] hover:bg-[#93000a] transition-colors shadow-sm">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for New Plot (Absolute Centered & Refined) -->
    <div id="new-plot-modal" class="fixed inset-0 z-[9999] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity"></div>
        
        <!-- Modal Content Centered -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-[420px] p-4">
            <div class="bg-white/95 backdrop-blur-xl rounded-[32px] p-8 premium-shadow-hover flex flex-col">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#006c49]/20 to-[#10b981]/20 text-primary flex items-center justify-center shrink-0 border border-primary/10">
                        <span class="material-symbols-outlined text-[28px]">add_location_alt</span>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 leading-tight">Buat<br><span class="text-primary">New Plot</span></h2>
                </div>
                
                <div class="space-y-4 max-h-[60vh] overflow-y-auto premium-scroll pr-2">
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Plot Name</label>
                        <input type="text" id="input-plot-name" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all" placeholder="e.g. Tomato Plot A1">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Plant Type</label>
                        <input type="text" id="input-plot-plant" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all" placeholder="e.g. Tomato">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Cultivar (Optional)</label>
                        <input type="text" id="input-plot-cultivar" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all" placeholder="e.g. Roma">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Shape</label>
                        <select id="input-plot-shape" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all appearance-none cursor-pointer">
                            <option value="rectangle">Rectangle (Bedengan)</option>
                            <option value="circle">Circle (Pot/Drum)</option>
                            <option value="hexagon">Hexagon (Raised Bed)</option>
                        </select>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Width (cm)</label>
                            <input type="number" id="input-plot-width" value="240" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Length (cm)</label>
                            <input type="number" id="input-plot-height" value="144" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all">
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mt-8">
                    <button id="btn-cancel-modal" class="flex-1 py-4 text-slate-500 font-bold rounded-2xl hover:bg-slate-100 transition-colors text-[16px]">Batal</button>
                    <button id="btn-confirm-modal" class="flex-[1.5] py-4 bg-primary text-white font-bold rounded-2xl hover:bg-primary-container active:scale-95 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] text-[16px] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">done</span> Buat Plot
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pilih Tanaman Modal -->
    <div id="assign-plant-modal" class="fixed inset-0 z-[99999] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" id="assign-plant-backdrop"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-[420px] p-4 z-10">
            <div class="bg-white/95 backdrop-blur-xl rounded-[32px] p-8 premium-shadow-hover flex flex-col">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#6ffbbe]/20 text-primary flex items-center justify-center shrink-0 border border-primary/10">
                        <span class="material-symbols-outlined text-[24px]">local_florist</span>
                    </div>
                    <h3 class="text-[20px] font-black text-on-surface leading-tight">Pilih Tanaman</h3>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-[13px] font-bold text-[#6c7a71] uppercase tracking-wider mb-2">Plant Type</label>
                        <select id="input-assign-plant" class="w-full bg-[#f8f9fa] border border-[#e1e3e4] rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-on-surface font-medium transition-all appearance-none cursor-pointer">
                            <option value="Tomato">Tomato</option>
                            <option value="Chili">Chili</option>
                            <option value="Carrot">Carrot</option>
                            <option value="Lettuce">Lettuce</option>
                            <option value="Cabbage">Cabbage</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#6c7a71] uppercase tracking-wider mb-2">Quantity (estimated)</label>
                        <input type="number" id="input-assign-qty" class="w-full bg-[#f8f9fa] border border-[#e1e3e4] rounded-2xl px-5 py-4 focus:border-primary focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-on-surface font-medium transition-all" value="12">
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button id="btn-cancel-assign" class="flex-1 py-3.5 px-4 rounded-xl font-bold text-[#3c4a42] bg-[#f3f4f5] hover:bg-[#e7e8e9] transition-colors">Batal</button>
                    <button id="btn-confirm-assign" class="flex-[1.5] py-3.5 px-4 rounded-xl font-bold text-white bg-primary hover:bg-primary-container transition-colors shadow-sm">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const appContainer = document.getElementById('app-container');
    const canvasContainer = document.getElementById('canvas-container');
    const zonesLayerWrapper = document.getElementById('zones-layer-wrapper');
    const zonesLayer = document.getElementById('zones-layer');
    const emptyState = document.getElementById('empty-state');
    const zoneList = document.getElementById('zone-list');
    const rightSidebar = document.getElementById('right-sidebar');
    const leftSidebar = document.getElementById('left-sidebar');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');
    const btnFullscreen = document.getElementById('btn-fullscreen');
    const iconFullscreen = document.getElementById('icon-fullscreen');
    const totalAreaDisplay = document.getElementById('total-area-display');
    const toolbarBtns = document.querySelectorAll('.toolbar-btn');
    const drawPreview = document.getElementById('draw-preview');

    // Central State
    const GRID_SIZE = 24;
    let state = {
        mode: 'select', // 'select', 'draw', 'pan'
        zoom: 1.0,
        pan: { x: 0, y: 0 },
        selectedZoneId: null,
        currentGardenId: null,
        gardens: [],
        zones: []
    };

    const snap = (val) => Math.round(val / GRID_SIZE) * GRID_SIZE;
    const getArea = (w, h) => Math.round((w * h) / 100);

    const getStatusColors = (status) => {
        switch(status) {
            case 'healthy': return { color: 'var(--color-status-healthy-text)', bg: 'var(--color-status-healthy-bg)', text: 'Healthy', border: 'var(--color-status-healthy)' }; 
            case 'attention': return { color: 'var(--color-status-attention-text)', bg: 'var(--color-status-attention-bg)', text: 'Needs Attention', border: 'var(--color-status-attention)' }; 
            case 'late': return { color: 'var(--color-status-late-text)', bg: 'var(--color-status-late-bg)', text: 'Late Care', border: 'var(--color-status-late)' }; 
            case 'new': return { color: 'var(--color-status-new-text)', bg: 'var(--color-status-new-bg)', text: 'Baru Ditanam', border: 'var(--color-status-new)' }; 
            default: return { color: '#475569', bg: '#f1f5f9', text: 'Unknown', border: '#94a3b8' };
        }
    };

    function updateTransform() {
        zonesLayerWrapper.style.transform = `translate(${state.pan.x}px, ${state.pan.y}px) scale(${state.zoom})`;
        canvasContainer.style.backgroundPosition = `${state.pan.x}px ${state.pan.y}px`;
        canvasContainer.style.backgroundSize = `${GRID_SIZE * state.zoom}px ${GRID_SIZE * state.zoom}px`;
    }

    function setMode(newMode) {
        state.mode = newMode;
        toolbarBtns.forEach(btn => {
            if(btn.dataset.mode === newMode) {
                btn.classList.add('active', 'bg-primary', 'text-white', 'shadow-[0_4px_12px_rgba(0,108,73,0.3)]');
                btn.classList.remove('text-slate-500', 'hover:bg-white/50');
            } else {
                btn.classList.remove('active', 'bg-primary', 'text-white', 'shadow-[0_4px_12px_rgba(0,108,73,0.3)]');
                btn.classList.add('text-slate-500', 'hover:bg-white/50');
            }
        });

        canvasContainer.className = `flex-1 relative bg-grid overflow-hidden mode-${newMode}`;
        if(newMode !== 'select') selectZone(null);
        
        if (newMode !== 'draw-poly') {
            polyPoints = [];
            if(drawPolyPreview) drawPolyPreview.style.display = 'none';
        }
    }

    toolbarBtns.forEach(btn => btn.addEventListener('click', () => setMode(btn.dataset.mode)));

    document.getElementById('btn-zoom-in').addEventListener('click', () => { state.zoom = Math.min(2.5, state.zoom + 0.1); updateTransform(); });
    document.getElementById('btn-zoom-out').addEventListener('click', () => { state.zoom = Math.max(0.4, state.zoom - 0.1); updateTransform(); });

    // Mobile Sidebar Toggle
    const toggleSidebar = (show) => {
        if(show) {
            leftSidebar.classList.remove('translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
        } else {
            leftSidebar.classList.add('translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        }
    };
    document.getElementById('btn-mobile-menu').addEventListener('click', () => toggleSidebar(true));
    document.getElementById('btn-close-sidebar').addEventListener('click', () => toggleSidebar(false));
    sidebarBackdrop.addEventListener('click', () => toggleSidebar(false));

    // Fullscreen Toggle
    let isFullscreen = false;
    if(btnFullscreen) {
        btnFullscreen.addEventListener('click', () => {
            isFullscreen = !isFullscreen;
            if(isFullscreen) {
                appContainer.classList.add('is-fullscreen');
                iconFullscreen.textContent = 'fullscreen_exit';
            } else {
                appContainer.classList.remove('is-fullscreen');
                iconFullscreen.textContent = 'fullscreen';
            }
        });
    }

    // Collision Detection AABB
    function isColliding(rect1, rect2) {
        return !(
            (rect1.y + rect1.h) <= rect2.y ||
            rect1.y >= (rect2.y + rect2.h) ||
            (rect1.x + rect1.w) <= rect2.x ||
            rect1.x >= (rect2.x + rect2.w)
        );
    }

    function checkCollisionForZone(testZone) {
        for(let i=0; i<state.zones.length; i++) {
            if(state.zones[i].id !== testZone.id) {
                if(isColliding(testZone, state.zones[i])) return true;
            }
        }
        return false;
    }

    function panToZone(zone) {
        const canvasRect = canvasContainer.getBoundingClientRect();
        const centerX = canvasRect.width / 2;
        const centerY = canvasRect.height / 2;

        const zoneCenterX = zone.x * state.zoom + (zone.w * state.zoom) / 2;
        const zoneCenterY = zone.y * state.zoom + (zone.h * state.zoom) / 2;

        state.pan.x = centerX - zoneCenterX;
        state.pan.y = centerY - zoneCenterY;
        updateTransform();
    }

    // DOM Updates
    function render() {
        if (!state.currentGardenId) return; // Don't render canvas if no garden selected

        zonesLayer.innerHTML = '';
        emptyState.style.opacity = state.zones.length === 0 ? '1' : '0';

        let totalArea = 0;
        const sortedZones = [...state.zones].sort((a, b) => (a.id === state.selectedZoneId ? 1 : b.id === state.selectedZoneId ? -1 : 0));

        sortedZones.forEach(zone => {
            const area = getArea(zone.w, zone.h);
            totalArea += area;
            const palette = getStatusColors(zone.status);

            const box = document.createElement('div');
            box.className = `zone-box shape-${zone.shape || 'rectangle'} ${state.selectedZoneId === zone.id ? 'selected' : ''}`;
            box.id = `zone-box-${zone.id}`;
            box.style.left = zone.x + 'px';
            box.style.top = zone.y + 'px';
            box.style.width = zone.w + 'px';
            box.style.height = zone.h + 'px';
            
            let svgBorder = '';
            let bgElement = '';
            
            if (zone.shape === 'hexagon') {
                box.style.border = 'none';
                box.style.borderRadius = '0';
                box.style.backgroundColor = 'transparent';
                box.style.boxShadow = 'none';
                
                bgElement = `<div style="position: absolute; inset: 0; background-color: ${state.selectedZoneId === zone.id ? 'rgba(255,255,255,0.98)' : 'rgba(255,255,255,0.85)'}; backdrop-filter: blur(12px); clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); z-index: -1;"></div>`;
                
                svgBorder = `
                <svg width="100%" height="100%" style="position: absolute; inset: 0; pointer-events: none; z-index: 0; overflow: visible;">
                    <polygon points="${zone.w/2},2 ${zone.w-2},${zone.h*0.25} ${zone.w-2},${zone.h*0.75} ${zone.w/2},${zone.h-2} 2,${zone.h*0.75} 2,${zone.h*0.25}" 
                             stroke="${palette.border}" stroke-width="3" stroke-dasharray="8,8" fill="transparent" stroke-linejoin="round"/>
                </svg>`;
            } else if (zone.shape === 'custom' && zone.points) {
                box.style.border = 'none';
                box.style.borderRadius = '0';
                box.style.backgroundColor = 'transparent';
                box.style.boxShadow = 'none';
                
                let clipPointsStr = zone.points.map(p => `${(p.px * 100).toFixed(2)}% ${(p.py * 100).toFixed(2)}%`).join(', ');
                bgElement = `<div style="position: absolute; inset: 0; background-color: ${state.selectedZoneId === zone.id ? 'rgba(255,255,255,0.98)' : 'rgba(255,255,255,0.85)'}; backdrop-filter: blur(12px); clip-path: polygon(${clipPointsStr}); z-index: -1;"></div>`;
                
                let svgPointsStr = zone.points.map(p => `${p.px * zone.w},${p.py * zone.h}`).join(' ');
                svgBorder = `
                <svg width="100%" height="100%" style="position: absolute; inset: 0; pointer-events: none; z-index: 0; overflow: visible;">
                    <polygon points="${svgPointsStr}" 
                             stroke="${palette.border}" stroke-width="3" stroke-dasharray="8,8" fill="transparent" stroke-linejoin="round"/>
                </svg>`;
            } else {
                box.style.borderColor = palette.border;
                box.style.backgroundColor = state.selectedZoneId === zone.id ? 'rgba(255,255,255,0.98)' : 'rgba(255,255,255,0.85)';
            }

            box.innerHTML = `
                ${bgElement}
                ${svgBorder}
                <div class="zone-label" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: ${palette.color}; z-index: 10; white-space: nowrap; box-shadow: 0 8px 24px rgba(6,95,70,0.12), inset 0 0 0 1px rgba(255,255,255,1);">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                    <span class="text-[15px] font-bold tracking-tight">${zone.name}</span>
                </div>
                ${state.selectedZoneId === zone.id ? `<div class="resize-handle" style="border-color: ${palette.border}; z-index: 20;"></div>` : ''}
            `;
            
            // Mouse Events
            box.addEventListener('mousedown', (e) => handleBoxDown(e, zone.id, getClientPos(e)));
            // Touch Events
            box.addEventListener('touchstart', (e) => handleBoxDown(e, zone.id, getClientPos(e)), {passive: false});
            
            zonesLayer.appendChild(box);
        });

        totalAreaDisplay.textContent = totalArea;

        // Render Sidebar List
        zoneList.innerHTML = '';
        state.zones.forEach(zone => {
            const palette = getStatusColors(zone.status);
            
            // Mock data for premium sidebar design
            const moisture = zone.moisture || Math.floor(Math.random() * 40 + 40);
            const sun = zone.sun || ['Full Sun', 'Part Shade', 'Full Shade'][Math.floor(Math.random() * 3)];
            const todos = zone.todos || ['Menyiram', 'Memupuk', 'Memangkas', 'Panen'].sort(() => 0.5 - Math.random()).slice(0, 2);
            
            const shadowPremium = `box-shadow: 0 4px 20px rgba(6, 95, 70, 0.05)`;
            const shadowHover = `box-shadow: 0 12px 32px rgba(6, 95, 70, 0.12)`;
            
            const item = document.createElement('div');
            item.id = `sidebar-card-${zone.id}`;
            item.className = `bg-white rounded-[24px] p-5 border-2 transition-all cursor-pointer hover:-translate-y-1 duration-300 relative overflow-hidden flex-shrink-0`;
            item.style = state.selectedZoneId === zone.id 
                ? `border-color: var(--color-primary); ${shadowHover}` 
                : `border-color: transparent; ${shadowPremium}`;
            
            const statBg = 'bg-[#F1F5F2]';

            item.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-on-surface text-[18px] leading-tight tracking-tight">${zone.name}</h3>
                    <div class="w-4 h-4 rounded-full shadow-sm flex-shrink-0 mt-0.5 border-2 border-white" style="background-color: ${palette.border}"></div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="${statBg} rounded-[16px] p-3 flex flex-col justify-center items-start transition-colors group">
                        <span class="text-[11px] font-semibold text-[#6c7a71] uppercase tracking-wider mb-1">Moisture</span>
                        <div class="flex items-center gap-2 text-[14px] font-bold text-primary whitespace-nowrap w-full">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm text-primary group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-outlined text-[14px]">water_drop</span>
                            </div>
                            <span class="truncate">${moisture}%</span>
                        </div>
                    </div>
                    <div class="${statBg} rounded-[16px] p-3 flex flex-col justify-center items-start transition-colors group overflow-hidden">
                        <span class="text-[11px] font-semibold text-[#6c7a71] uppercase tracking-wider mb-1">Sunlight</span>
                        <div class="flex items-center gap-2 text-[14px] font-bold text-[#944a23] whitespace-nowrap w-full">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm text-[#944a23] group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-outlined text-[14px]">light_mode</span>
                            </div>
                            <span class="truncate">${sun}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-[13px] font-semibold mb-5">
                    <div class="flex items-center gap-1.5 text-[#3c4a42] bg-white px-3 py-1.5 rounded-full border border-[#e1e3e4] shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">square_foot</span> ${getArea(zone.w, zone.h)} m²
                    </div>
                    <div class="flex items-center gap-1.5 bg-primary text-white px-3 py-1.5 rounded-full shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">eco</span>
                        <span>Ditanam</span>
                    </div>
                </div>


            `;
            item.addEventListener('click', () => {
                if(window.innerWidth < 768) toggleSidebar(false); // Close sidebar on mobile after select
                setMode('select');
                selectZone(zone.id);
                panToZone(zone);
            });
            zoneList.appendChild(item);
        });

        // Render Right Sidebar
        if (state.selectedZoneId) {
            const zone = state.zones.find(z => z.id === state.selectedZoneId);
            if(zone) {
                const palette = getStatusColors(zone.status);
                document.getElementById('detail-name').textContent = zone.name;
                document.getElementById('detail-plant').textContent = zone.plant || 'Unknown';
                document.getElementById('detail-qty').textContent = zone.qty ? `${zone.qty} plants` : '12 plants';
                document.getElementById('detail-cultivar').textContent = zone.cultivar || zone.plant || 'None';
                document.getElementById('detail-area').textContent = getArea(zone.w, zone.h) + ' m²';
                
                const iconBg = document.getElementById('detail-icon-bg');
                iconBg.style.color = palette.color;
                iconBg.style.backgroundColor = palette.bg;
                iconBg.style.borderColor = palette.border;

                const statusEl = document.getElementById('detail-status');
                statusEl.textContent = palette.text;
                statusEl.style.color = palette.color;
                statusEl.style.backgroundColor = palette.bg;

                document.getElementById('detail-progress-text').textContent = (zone.progress || 0) + '%';
                document.getElementById('detail-progress-bar').style.width = (zone.progress || 0) + '%';
                
                // Mock data for premium sidebar design
                const moisture = zone.moisture || Math.floor(Math.random() * 40 + 40);
                const sun = zone.sun || ['Full Sun', 'Part Shade', 'Full Shade'][Math.floor(Math.random() * 3)];
                const todos = zone.todos || ['Menyiram', 'Memupuk', 'Memangkas', 'Panen'].sort(() => 0.5 - Math.random()).slice(0, 3);
                
                document.getElementById('detail-quick-stats').innerHTML = `
                    <div class="bg-[#F1F5F2] rounded-[16px] p-3 flex flex-col justify-center items-start transition-colors group">
                        <span class="text-[11px] font-semibold text-[#6c7a71] uppercase tracking-wider mb-1">Moisture</span>
                        <div class="flex items-center gap-2 text-[14px] font-bold text-primary">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm text-primary group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[14px]">water_drop</span>
                            </div>
                            ${moisture}%
                        </div>
                    </div>
                    <div class="bg-[#F1F5F2] rounded-[16px] p-3 flex flex-col justify-center items-start transition-colors group">
                        <span class="text-[11px] font-semibold text-[#6c7a71] uppercase tracking-wider mb-1">Sunlight</span>
                        <div class="flex items-center gap-2 text-[14px] font-bold text-[#944a23]">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm text-[#944a23] group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[14px]">light_mode</span>
                            </div>
                            ${sun}
                        </div>
                    </div>
                `;

                document.getElementById('detail-todos').innerHTML = `
                    <h4 class="text-[14px] font-bold text-on-surface mb-3">Tugas hari ini</h4>
                    <div class="flex flex-col gap-2.5">
                        ${todos.map(todo => `
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-[16px] bg-white border border-[#e1e3e4] hover:border-[#bbcabf] transition-colors shadow-sm group">
                                <div class="relative flex items-center justify-center w-5 h-5">
                                    <input type="checkbox" class="peer appearance-none w-5 h-5 border-2 border-[#bbcabf] rounded-[6px] checked:bg-primary checked:border-primary transition-all cursor-pointer" onclick="event.stopPropagation()">
                                    <span class="material-symbols-outlined text-white text-[14px] absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                </div>
                                <span class="text-[14px] font-medium text-[#3c4a42] group-hover:text-on-surface transition-colors">${todo}</span>
                            </label>
                        `).join('')}
                    </div>
                `;

                // Mock activity log
                const activities = zone.activities || [
                    { action: 'Disiram', time: '2 jam lalu', icon: 'water_drop', color: 'text-blue-500', bg: 'bg-blue-50' },
                    { action: 'Diberi Pupuk', time: 'Kemarin', icon: 'science', color: 'text-purple-500', bg: 'bg-purple-50' },
                    { action: 'Ditanam', time: '15 Mei 2024', icon: 'eco', color: 'text-primary', bg: 'bg-[#6ffbbe]/30' }
                ];
                
                document.getElementById('detail-activity-log').innerHTML = activities.map(act => `
                    <div class="relative">
                        <div class="absolute -left-[27px] top-0 w-7 h-7 rounded-full ${act.bg} ${act.color} flex items-center justify-center border-2 border-white shadow-sm z-10">
                            <span class="material-symbols-outlined text-[14px]">${act.icon}</span>
                        </div>
                        <div class="ml-2 bg-white rounded-xl p-3 border border-[#e1e3e4] shadow-sm relative top-[-4px]">
                            <p class="text-[13px] font-bold text-on-surface leading-tight">${act.action}</p>
                            <span class="text-[11px] font-semibold text-[#6c7a71] block mt-1">${act.time}</span>
                        </div>
                    </div>
                `).join('');

                rightSidebar.classList.remove('translate-x-full');
            }
        } else {
            rightSidebar.classList.add('translate-x-full');
        }
    }

    // Interaction State
    let isDragging = false;
    let isResizing = false;
    let isPanning = false;
    let isDrawing = false;
    let polyPoints = [];
    
    const drawPolyPreview = document.getElementById('draw-poly-preview');
    const drawPolyLines = document.getElementById('draw-poly-lines');
    const drawPolyCursorLine = document.getElementById('draw-poly-cursor-line');
    const drawPolyStartNode = document.getElementById('draw-poly-start-node');

    function updatePolyPreview(cursorX, cursorY) {
        if (polyPoints.length === 0) {
            drawPolyPreview.style.display = 'none';
            return;
        }
        drawPolyPreview.style.display = 'block';
        
        let pointsStr = polyPoints.map(p => `${p.x},${p.y}`).join(' ');
        drawPolyLines.setAttribute('points', pointsStr);
        
        const last = polyPoints[polyPoints.length - 1];
        drawPolyCursorLine.setAttribute('x1', last.x);
        drawPolyCursorLine.setAttribute('y1', last.y);
        drawPolyCursorLine.setAttribute('x2', cursorX);
        drawPolyCursorLine.setAttribute('y2', cursorY);
        
        drawPolyStartNode.setAttribute('cx', polyPoints[0].x);
        drawPolyStartNode.setAttribute('cy', polyPoints[0].y);
    }

    function finishPolygon() {
        if (polyPoints.length < 3) {
            polyPoints = [];
            drawPolyPreview.style.display = 'none';
            return;
        }
        
        let minX = Math.min(...polyPoints.map(p => p.x));
        let maxX = Math.max(...polyPoints.map(p => p.x));
        let minY = Math.min(...polyPoints.map(p => p.y));
        let maxY = Math.max(...polyPoints.map(p => p.y));
        let w = maxX - minX;
        let h = maxY - minY;
        
        if (w < GRID_SIZE) w = GRID_SIZE;
        if (h < GRID_SIZE) h = GRID_SIZE;

        let relativePoints = polyPoints.map(p => ({ 
            px: (p.x - minX) / w, 
            py: (p.y - minY) / h 
        }));
        
        const testZone = { id: 'temp', x: minX, y: minY, w: w, h: h };
        if(!checkCollisionForZone(testZone)) {
            const newZoneData = {
                id: Date.now(),
                name: 'Custom Plot ' + (state.zones.length + 1),
                plant: 'Unknown',
                cultivar: 'None',
                status: 'new',
                shape: 'custom',
                points: relativePoints,
                x: minX, y: minY, w: w, h: h,
                progress: 0
            };
            
            savePlotToDB(newZoneData).then(dbData => {
                if (dbData) {
                    newZoneData.id = dbData.id;
                    state.zones.push(newZoneData);
                    
                    // Update dashboard count
                    const g = state.gardens.find(g => g.id === state.currentGardenId);
                    if (g) g.plots = state.zones.length;
                    
                    setMode('select');
                    selectZone(dbData.id);
                    setTimeout(() => {
                        const card = document.getElementById(`sidebar-card-${dbData.id}`);
                        if(card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 50);
                    render();
                }
            });
        } else {
            drawPolyLines.setAttribute('stroke', '#ef4444');
            drawPolyLines.setAttribute('fill', 'rgba(254,226,226, 0.5)');
            setTimeout(() => {
                drawPolyLines.setAttribute('stroke', '#006c49');
                drawPolyLines.setAttribute('fill', 'rgba(111, 251, 190, 0.3)');
            }, 400);
            return; 
        }
        
        polyPoints = [];
        drawPolyPreview.style.display = 'none';
    }
    
    let draggedZoneId = null;
    let startX, startY;
    let initialX, initialY, initialW, initialH;
    let panStartX, panStartY, initialPanX, initialPanY;

    function getClientPos(e) {
        if (e.touches && e.touches.length > 0) {
            return { x: e.touches[0].clientX, y: e.touches[0].clientY };
        }
        return { x: e.clientX, y: e.clientY };
    }

    function handleBoxDown(e, id, pos) {
        if (state.mode !== 'select') return;

        if (e.target.classList.contains('resize-handle')) {
            isResizing = true;
        } else {
            isDragging = true;
        }
        
        draggedZoneId = id;
        if(state.selectedZoneId !== id) {
            selectZone(id);
            const card = document.getElementById(`sidebar-card-${id}`);
            if(card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        const zone = state.zones.find(z => z.id === id);
        const box = document.getElementById(`zone-box-${id}`);
        if(box) box.classList.add('active-drag');

        startX = pos.x;
        startY = pos.y;
        initialX = zone.x;
        initialY = zone.y;
        initialW = zone.w;
        initialH = zone.h;
        
        e.stopPropagation();
        // Don't prevent default on touchstart here so it registers properly, but we prevent on move
    }

    function handleCanvasDown(e, pos) {
        if(e.target.closest('.toolbar-btn') || e.target.closest('#btn-fullscreen') || e.target.closest('#btn-mobile-menu')) return;

        if(state.mode === 'select') {
            if(e.target === canvasContainer || e.target === zonesLayerWrapper || e.target === zonesLayer) {
                selectZone(null);
            }
        } else if (state.mode === 'pan') {
            isPanning = true;
            panStartX = pos.x;
            panStartY = pos.y;
            initialPanX = state.pan.x;
            initialPanY = state.pan.y;
        } else if (state.mode === 'draw') {
            isDrawing = true;
            const rect = canvasContainer.getBoundingClientRect();
            startX = (pos.x - rect.left - state.pan.x) / state.zoom;
            startY = (pos.y - rect.top - state.pan.y) / state.zoom;
            
            startX = snap(startX);
            startY = snap(startY);

            drawPreview.style.display = 'block';
            drawPreview.style.left = startX + 'px';
            drawPreview.style.top = startY + 'px';
            drawPreview.style.width = '0px';
            drawPreview.style.height = '0px';
        } else if (state.mode === 'draw-poly') {
            const rect = canvasContainer.getBoundingClientRect();
            let currentX = (pos.x - rect.left - state.pan.x) / state.zoom;
            let currentY = (pos.y - rect.top - state.pan.y) / state.zoom;
            
            currentX = snap(currentX);
            currentY = snap(currentY);

            if (polyPoints.length >= 3) {
                const dx = currentX - polyPoints[0].x;
                const dy = currentY - polyPoints[0].y;
                if (Math.hypot(dx, dy) <= GRID_SIZE) {
                    finishPolygon();
                    return;
                }
            }
            
            polyPoints.push({x: currentX, y: currentY});
            updatePolyPreview(currentX, currentY);
        }
    }

    canvasContainer.addEventListener('mousedown', (e) => handleCanvasDown(e, getClientPos(e)));

    // Touch events for pinch-to-zoom and pan
    let initialPinchDistance = null;
    let initialPinchZoom = null;
    let pinchCenter = null;

    canvasContainer.addEventListener('touchstart', (e) => {
        if (e.touches.length === 2) {
            e.preventDefault();
            const dx = e.touches[0].clientX - e.touches[1].clientX;
            const dy = e.touches[0].clientY - e.touches[1].clientY;
            initialPinchDistance = Math.hypot(dx, dy);
            initialPinchZoom = state.zoom;
            
            const rect = canvasContainer.getBoundingClientRect();
            pinchCenter = {
                x: (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left,
                y: (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top
            };
        } else {
            handleCanvasDown(e, getClientPos(e));
        }
    }, {passive: false});

    function handleMove(e, pos) {
        if (isPanning || isDrawing || isDragging || isResizing) {
            if(e.cancelable) e.preventDefault(); // Prevent scrolling on mobile
        }

        if (isPanning) {
            state.pan.x = initialPanX + (pos.x - panStartX);
            state.pan.y = initialPanY + (pos.y - panStartY);
            updateTransform();
            return;
        }

        if (state.mode === 'draw-poly' && polyPoints.length > 0) {
            const rect = canvasContainer.getBoundingClientRect();
            let currentX = (pos.x - rect.left - state.pan.x) / state.zoom;
            let currentY = (pos.y - rect.top - state.pan.y) / state.zoom;
            
            currentX = snap(currentX);
            currentY = snap(currentY);
            
            if (polyPoints.length >= 3) {
                const dx = currentX - polyPoints[0].x;
                const dy = currentY - polyPoints[0].y;
                if (Math.hypot(dx, dy) <= GRID_SIZE) {
                    currentX = polyPoints[0].x;
                    currentY = polyPoints[0].y;
                }
            }

            updatePolyPreview(currentX, currentY);
            return;
        }

        if (isDrawing) {
            const rect = canvasContainer.getBoundingClientRect();
            let currentX = (pos.x - rect.left - state.pan.x) / state.zoom;
            let currentY = (pos.y - rect.top - state.pan.y) / state.zoom;
            
            currentX = snap(currentX);
            currentY = snap(currentY);

            const w = Math.max(GRID_SIZE, Math.abs(currentX - startX));
            const h = Math.max(GRID_SIZE, Math.abs(currentY - startY));
            const x = Math.min(startX, currentX);
            const y = Math.min(startY, currentY);

            drawPreview.style.left = x + 'px';
            drawPreview.style.top = y + 'px';
            drawPreview.style.width = w + 'px';
            drawPreview.style.height = h + 'px';

            const testZone = { id: 'preview', x, y, w, h };
            if(checkCollisionForZone(testZone)) {
                drawPreview.style.borderColor = '#ef4444';
                drawPreview.style.backgroundColor = 'rgba(254,226,226, 0.5)';
            } else {
                drawPreview.style.borderColor = '#006c49';
                drawPreview.style.backgroundColor = 'rgba(111, 251, 190, 0.3)';
            }
            return;
        }

        if (!draggedZoneId) return;
        
        const zone = state.zones.find(z => z.id === draggedZoneId);
        if (!zone) return;

        const dx = (pos.x - startX) / state.zoom;
        const dy = (pos.y - startY) / state.zoom;

        let proposedZone = { ...zone };

        if (isDragging) {
            proposedZone.x = snap(initialX + dx);
            proposedZone.y = snap(initialY + dy);
        } else if (isResizing) {
            proposedZone.w = Math.max(GRID_SIZE * 3, snap(initialW + dx));
            proposedZone.h = Math.max(GRID_SIZE * 2, snap(initialH + dy));
        }

        const box = document.getElementById(`zone-box-${zone.id}`);

        if (checkCollisionForZone(proposedZone)) {
            if(box) {
                box.classList.add('collision');
                setTimeout(() => box.classList.remove('collision'), 400);
            }
            return; 
        }

        zone.x = proposedZone.x;
        zone.y = proposedZone.y;
        zone.w = proposedZone.w;
        zone.h = proposedZone.h;
        
        if(box) {
            box.style.left = zone.x + 'px';
            box.style.top = zone.y + 'px';
            box.style.width = zone.w + 'px';
            box.style.height = zone.h + 'px';
        }
        
        if(isResizing) {
            document.getElementById('detail-area').textContent = getArea(zone.w, zone.h) + ' m²';
        }
    }

    document.addEventListener('mousemove', (e) => handleMove(e, getClientPos(e)));
    
    document.addEventListener('touchmove', (e) => {
        if (e.touches.length === 2) {
            e.preventDefault();
            if (initialPinchDistance) {
                const dx = e.touches[0].clientX - e.touches[1].clientX;
                const dy = e.touches[0].clientY - e.touches[1].clientY;
                const currentDistance = Math.hypot(dx, dy);
                
                const zoomFactor = currentDistance / initialPinchDistance;
                const newZoom = Math.min(Math.max(0.1, initialPinchZoom * zoomFactor), 5.0);
                
                state.pan.x = pinchCenter.x - (pinchCenter.x - state.pan.x) * (newZoom / state.zoom);
                state.pan.y = pinchCenter.y - (pinchCenter.y - state.pan.y) * (newZoom / state.zoom);
                
                state.zoom = newZoom;
                updateTransform();
            }
        } else {
            handleMove(e, getClientPos(e));
        }
    }, {passive: false});

    function handleUp() {
        if (isPanning) {
            isPanning = false;
        }

        if (isDrawing) {
            isDrawing = false;
            drawPreview.style.display = 'none';
            
            const w = parseInt(drawPreview.style.width);
            const h = parseInt(drawPreview.style.height);
            const x = parseInt(drawPreview.style.left);
            const y = parseInt(drawPreview.style.top);

            const testZone = { id: 'temp', x, y, w, h };
            if(w >= GRID_SIZE && h >= GRID_SIZE && !checkCollisionForZone(testZone)) {
                const newZoneData = {
                    id: Date.now(),
                    name: 'Drawn Plot ' + (state.zones.length + 1),
                    plant: 'Unknown',
                    cultivar: 'None',
                    status: 'new',
                    x: x, y: y, w: w, h: h,
                    progress: 0
                };
                
                savePlotToDB(newZoneData).then(dbData => {
                    if (dbData) {
                        newZoneData.id = dbData.id;
                        state.zones.push(newZoneData);
                        
                        // Update dashboard count
                        const g = state.gardens.find(g => g.id === state.currentGardenId);
                        if (g) g.plots = state.zones.length;
                        
                        setMode('select');
                        selectZone(dbData.id);
                        setTimeout(() => {
                            const card = document.getElementById(`sidebar-card-${dbData.id}`);
                            if(card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 50);
                        render();
                    }
                });
            }
        }

        if(isDragging || isResizing) {
            isDragging = false;
            isResizing = false;
            if(draggedZoneId) {
                const box = document.getElementById(`zone-box-${draggedZoneId}`);
                if(box) box.classList.remove('active-drag');
                
                const updatedZone = state.zones.find(z => z.id === draggedZoneId);
                if (updatedZone) {
                    updatePlotInDB(updatedZone);
                }
            }
            render(); 
        }
        draggedZoneId = null;
    }

    document.addEventListener('mouseup', handleUp);
    document.addEventListener('touchend', (e) => {
        if (e.touches.length < 2) {
            initialPinchDistance = null;
        }
        handleUp();
    });

    // Wheel event for Mouse Zoom / Trackpad Pan & Zoom
    canvasContainer.addEventListener('wheel', (e) => {
        e.preventDefault();
        
        if (e.ctrlKey) {
            // Pinch-to-zoom (trackpad) or Ctrl+MouseWheel
            const zoomFactor = Math.exp(-e.deltaY * 0.01);
            const newZoom = Math.min(Math.max(0.1, state.zoom * zoomFactor), 5.0);
            
            const rect = canvasContainer.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;
            
            state.pan.x = mouseX - (mouseX - state.pan.x) * (newZoom / state.zoom);
            state.pan.y = mouseY - (mouseY - state.pan.y) * (newZoom / state.zoom);
            
            state.zoom = newZoom;
        } else {
            // Mouse wheel or trackpad pan
            const isTouchpadPan = Math.abs(e.deltaX) > 0 || Math.abs(e.deltaY) < 50; 
            if (isTouchpadPan && !e.ctrlKey) {
                // Trackpad Pan
                state.pan.x -= e.deltaX * 1.5;
                state.pan.y -= e.deltaY * 1.5;
            } else {
                // Mouse Wheel Zoom
                const zoomFactor = Math.exp(-e.deltaY * 0.002);
                const newZoom = Math.min(Math.max(0.1, state.zoom * zoomFactor), 5.0);
                
                const rect = canvasContainer.getBoundingClientRect();
                const mouseX = e.clientX - rect.left;
                const mouseY = e.clientY - rect.top;
                
                state.pan.x = mouseX - (mouseX - state.pan.x) * (newZoom / state.zoom);
                state.pan.y = mouseY - (mouseY - state.pan.y) * (newZoom / state.zoom);
                
                state.zoom = newZoom;
            }
        }
        updateTransform();
    }, { passive: false });

    function selectZone(id) {
        state.selectedZoneId = id;
        render();
    }

    // Smart Auto Placement
    function findSafeSpot(width, height) {
        // center of current view
        const canvasRect = canvasContainer.getBoundingClientRect();
        const centerX = (canvasRect.width / 2 - state.pan.x) / state.zoom;
        const centerY = (canvasRect.height / 2 - state.pan.y) / state.zoom;
        
        let startX = snap(centerX - width/2);
        let startY = snap(centerY - height/2);

        // Search outward
        for(let offset = 0; offset < 1000; offset += GRID_SIZE) {
            let testPositions = [
                {x: startX, y: startY + offset},
                {x: startX, y: startY - offset},
                {x: startX + offset, y: startY},
                {x: startX - offset, y: startY},
                {x: startX + offset, y: startY + offset},
                {x: startX - offset, y: startY - offset}
            ];
            
            for(let pos of testPositions) {
                if(pos.x >= 0 && pos.y >= 0 && !checkCollisionForZone({id: 'temp', x: pos.x, y: pos.y, w: width, h: height})) {
                    return pos;
                }
            }
        }
        return { x: startX, y: startY };
    }

    // Modal Logic
    const modal = document.getElementById('new-plot-modal');
    const btnSidebarAdd = document.getElementById('btn-sidebar-add');
    const btnCancelModal = document.getElementById('btn-cancel-modal');
    const btnConfirmModal = document.getElementById('btn-confirm-modal');
    const inputName = document.getElementById('input-plot-name');
    const inputPlant = document.getElementById('input-plot-plant');
    const inputCultivar = document.getElementById('input-plot-cultivar');
    const inputShape = document.getElementById('input-plot-shape');
    const inputWidth = document.getElementById('input-plot-width');
    const inputHeight = document.getElementById('input-plot-height');

    btnSidebarAdd.addEventListener('click', () => {
        if(window.checkLimit && !window.checkLimit('plots')) return;
        if(window.innerWidth < 768) toggleSidebar(false); // hide sidebar on mobile to show modal clearly
        modal.classList.remove('hidden');
        inputName.value = 'Plot ' + (state.zones.length + 1);
        inputPlant.value = '';
        inputCultivar.value = '';
        inputShape.value = 'rectangle';
        inputWidth.value = '240';
        inputHeight.value = '144';
        setTimeout(() => inputName.focus(), 100);
    });

    btnCancelModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    btnConfirmModal.addEventListener('click', () => {
        const name = inputName.value.trim() || 'New Plot';
        const plant = inputPlant.value.trim() || 'Unknown';
        const cultivar = inputCultivar.value.trim() || 'None';
        const shape = inputShape.value;
        const w = parseInt(inputWidth.value) || 240;
        const h = parseInt(inputHeight.value) || 144;
        
        modal.classList.add('hidden');
        const safePos = findSafeSpot(w, h);
        
        const newZoneData = {
            id: Date.now(),
            name: name,
            plant: plant,
            cultivar: cultivar,
            shape: shape,
            status: 'new',
            x: safePos.x, y: safePos.y, w: w, h: h,
            progress: 0
        };
        
        savePlotToDB(newZoneData).then(dbData => {
            if (dbData) {
                newZoneData.id = dbData.id;
                state.zones.push(newZoneData);
                
                // Update dashboard count
                const g = state.gardens.find(g => g.id === state.currentGardenId);
                if (g) g.plots = state.zones.length;
                
                setMode('select');
                selectZone(dbData.id);
                render();
                panToZone(newZoneData);
                
                setTimeout(() => {
                    const card = document.getElementById(`sidebar-card-${dbData.id}`);
                    if(card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        });
    });

    document.getElementById('btn-close-details').addEventListener('click', () => {
        selectZone(null);
    });

    // Delete Confirmation Logic
    const deleteModal = document.getElementById('delete-confirm-modal');
    const deleteBackdrop = document.getElementById('delete-confirm-backdrop');
    const deleteContent = document.getElementById('delete-confirm-content');
    
    function closeDeleteModal() {
        deleteContent.classList.remove('scale-100');
        deleteContent.classList.add('scale-95');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
        }, 150);
    }

    document.getElementById('btn-delete-zone').addEventListener('click', () => {
        deleteModal.classList.remove('hidden');
        // Trigger reflow for animation
        void deleteContent.offsetWidth;
        deleteContent.classList.remove('scale-95');
        deleteContent.classList.add('scale-100');
    });

    document.getElementById('btn-cancel-delete').addEventListener('click', closeDeleteModal);
    
    deleteBackdrop.addEventListener('click', closeDeleteModal);

    document.getElementById('btn-confirm-delete').addEventListener('click', () => {
        if (state.selectedZoneId) {
            deletePlotFromDB(state.selectedZoneId);
            state.zones = state.zones.filter(z => z.id !== state.selectedZoneId);
            
            // Update dashboard count
            const g = state.gardens.find(g => g.id === state.currentGardenId);
            if (g) g.plots = state.zones.length;
            
            selectZone(null);
            closeDeleteModal();
            render();
        }
    });

    // Pilih Tanaman Logic
    const assignModal = document.getElementById('assign-plant-modal');
    document.getElementById('btn-assign-plant').addEventListener('click', () => {
        assignModal.classList.remove('hidden');
        if (state.selectedZoneId) {
            const z = state.zones.find(z => z.id === state.selectedZoneId);
            document.getElementById('input-assign-plant').value = z.plant || 'Tomato';
            document.getElementById('input-assign-qty').value = z.qty || 12;
        }
    });

    document.getElementById('btn-cancel-assign').addEventListener('click', () => assignModal.classList.add('hidden'));
    document.getElementById('assign-plant-backdrop').addEventListener('click', () => assignModal.classList.add('hidden'));

    document.getElementById('btn-confirm-assign').addEventListener('click', () => {
        if (state.selectedZoneId) {
            const z = state.zones.find(z => z.id === state.selectedZoneId);
            z.plant = document.getElementById('input-assign-plant').value;
            z.qty = parseInt(document.getElementById('input-assign-qty').value) || 0;
            
            // Add to activity log
            if (!z.activities) z.activities = [
                { action: 'Disiram', time: '2 jam lalu', icon: 'water_drop', color: 'text-blue-500', bg: 'bg-blue-50' },
                { action: 'Diberi Pupuk', time: 'Kemarin', icon: 'science', color: 'text-purple-500', bg: 'bg-purple-50' },
                { action: 'Ditanam', time: '15 Mei 2024', icon: 'eco', color: 'text-primary', bg: 'bg-[#6ffbbe]/30' }
            ];
            z.activities.unshift({ action: `Changed plant to ${z.plant}`, time: 'Just now', icon: 'local_florist', color: 'text-primary', bg: 'bg-[#6ffbbe]/30' });
            
            render();
            // Re-trigger selectZone to update sidebar details immediately
            const currentSelected = state.selectedZoneId;
            selectZone(null);
            selectZone(currentSelected);
        }
        assignModal.classList.add('hidden');
    });

    document.addEventListener('dragstart', (e) => e.preventDefault());

    // Dashboard & Garden Navigation Logic
    const dashboardView = document.getElementById('dashboard-view');
    const gardensGrid = document.getElementById('gardens-grid');
    const canvasGardenName = document.getElementById('canvas-garden-name');
    const canvasGardenLocation = document.getElementById('canvas-garden-location');
    const newGardenModal = document.getElementById('new-garden-modal');

    function renderDashboard() {
        gardensGrid.innerHTML = '';
        state.gardens.forEach(garden => {
            const card = document.createElement('div');
            card.className = "bg-white rounded-[32px] p-4 border border-white premium-shadow hover:-translate-y-2 hover:shadow-[0_24px_48px_rgba(0,108,73,0.12)] transition-all duration-500 cursor-pointer group flex flex-col h-full relative z-10";
            card.innerHTML = `
                <div class="w-full h-48 rounded-[24px] bg-[#f1f5f2] mb-6 overflow-hidden relative">
                    <!-- Lush placeholder background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-[#006c49]/10 to-[#10b981]/5 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-700">
                        <span class="material-symbols-outlined text-[64px] text-primary/20 group-hover:rotate-6 transition-transform duration-500">nature</span>
                    </div>
                    <!-- Soft inner shadow to mimic depth -->
                    <div class="absolute inset-0 shadow-[inset_0_4px_24px_rgba(0,0,0,0.04)] pointer-events-none"></div>
                </div>
                <div class="flex-1 px-4">
                    <h3 class="text-[26px] font-black text-on-surface mb-2 group-hover:text-primary transition-colors leading-tight">${garden.name}</h3>
                    <div class="flex items-center gap-2 text-[#6c7a71] text-[15px] font-medium mb-6">
                        <span class="material-symbols-outlined text-[18px] text-primary/70">location_on</span> ${garden.location}
                    </div>
                </div>
                <div class="pt-5 pb-3 px-4 border-t border-[#f1f5f2] flex justify-between items-center text-[14px] font-bold text-[#3c4a42]">
                    <div class="flex items-center gap-2 bg-[#f8f9fa] px-4 py-2 rounded-full shadow-sm">
                        <span class="material-symbols-outlined text-[18px] text-primary">grid_view</span> ${garden.plots} Plots
                    </div>
                    <div class="flex items-center gap-2 bg-[#f8f9fa] px-4 py-2 rounded-full text-[#6c7a71] shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">straighten</span> ${garden.area} m²
                    </div>
                </div>
            `;
            card.onclick = () => openGarden(garden.id);
            gardensGrid.appendChild(card);
        });
    }

    window.openGarden = async function(id) {
        state.currentGardenId = id;
        const garden = state.gardens.find(g => g.id === id);
        if (garden) {
            canvasGardenName.textContent = garden.name;
            canvasGardenLocation.textContent = garden.location;
        }
        
        dashboardView.classList.add('hidden');
        appContainer.classList.remove('hidden');
        
        // Reset canvas state
        state.selectedZoneId = null;
        state.zoom = 1.0;
        state.pan = { x: 0, y: 0 };
        updateTransform();
        
        // Fetch Plots
        try {
            const response = await fetch(`/api/gardens/${id}/plots`);
            const data = await response.json();
            state.zones = data.map(p => ({
                id: p.id,
                name: p.name,
                plant: p.plant ? p.plant.name : 'Empty Plot',
                status: 'new', // placeholder
                x: p.pos_x || 100,
                y: p.pos_y || 100,
                w: p.width || 120,
                h: p.length || 120,
                cultivar: 'Unknown', // placeholder
                progress: 0,
                shape: p.shape
            }));
            render();
        } catch (error) {
            console.error("Failed to fetch plots", error);
        }
    }

    function showDashboard() {
        appContainer.classList.add('hidden');
        dashboardView.classList.remove('hidden');
        renderDashboard();
    }

    document.getElementById('btn-back-to-dashboard').addEventListener('click', showDashboard);

    // Buat Kebun Baru Logic
    document.getElementById('btn-create-garden').addEventListener('click', () => {
        if(window.checkLimit && !window.checkLimit('gardens')) return;
        newGardenModal.classList.remove('hidden');
        document.getElementById('input-garden-name').value = '';
        document.getElementById('input-garden-location').value = '';
        document.getElementById('input-garden-name').focus();
    });
    
    document.getElementById('btn-cancel-garden-modal').addEventListener('click', () => {
        newGardenModal.classList.add('hidden');
    });
    document.getElementById('new-garden-backdrop').addEventListener('click', () => {
        newGardenModal.classList.add('hidden');
    });

    document.getElementById('btn-confirm-garden-modal').addEventListener('click', async () => {
        const name = document.getElementById('input-garden-name').value || 'New Garden';
        const location = document.getElementById('input-garden-location').value || 'Unknown Zone';
        
        try {
            const response = await fetch('/api/gardens', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: name, location: location })
            });
            const data = await response.json();
            
            if (!response.ok) {
                alert(data.error || 'Failed to create garden');
                return;
            }
            
            state.gardens.push({
                id: data.id,
                name: data.name,
                location: data.location_name || 'Unknown Zone',
                area: data.area_size_m2 || 0,
                plots: 0
            });
            
            if (window.AppState) window.AppState.usage.gardens++;
            
            newGardenModal.classList.add('hidden');
            renderDashboard();
        } catch (error) {
            console.error("Error creating garden:", error);
        }
    });

    // Edit Garden Logic
    const editGardenModal = document.getElementById('edit-garden-modal');
    document.getElementById('btn-edit-garden').addEventListener('click', () => {
        const garden = state.gardens.find(g => g.id === state.currentGardenId);
        if (!garden) return;
        document.getElementById('edit-garden-name').value = garden.name;
        document.getElementById('edit-garden-location').value = garden.location;
        editGardenModal.classList.remove('hidden');
    });

    document.getElementById('btn-cancel-edit-garden').addEventListener('click', () => editGardenModal.classList.add('hidden'));
    document.getElementById('edit-garden-backdrop').addEventListener('click', () => editGardenModal.classList.add('hidden'));

    document.getElementById('btn-confirm-edit-garden').addEventListener('click', async () => {
        const name = document.getElementById('edit-garden-name').value;
        const location = document.getElementById('edit-garden-location').value;
        
        try {
            const response = await fetch(`/api/gardens/${state.currentGardenId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name, location })
            });
            const data = await response.json();
            if (!response.ok) {
                alert(data.error || 'Gagal menyimpan perubahan');
                return;
            }
            
            // Update local state
            const gardenIndex = state.gardens.findIndex(g => g.id === state.currentGardenId);
            if (gardenIndex !== -1) {
                state.gardens[gardenIndex].name = data.name;
                state.gardens[gardenIndex].location = data.location_name || 'Unknown Zone';
            }
            
            // Update UI
            canvasGardenName.textContent = data.name;
            canvasGardenLocation.textContent = data.location_name || 'Unknown Zone';
            
            editGardenModal.classList.add('hidden');
        } catch (error) {
            console.error("Error updating garden:", error);
        }
    });

    // Delete Garden Logic
    document.getElementById('btn-delete-garden').addEventListener('click', async () => {
        if (!confirm('Apakah Anda yakin ingin menghapus kebun ini? Semua plot dan tanaman di dalamnya akan ikut terhapus secara permanen.')) return;
        
        try {
            const response = await fetch(`/api/gardens/${state.currentGardenId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                const data = await response.json();
                alert(data.error || 'Gagal menghapus kebun');
                return;
            }
            
            // Decrement usage based on what's deleted
            if (window.AppState) {
                window.AppState.usage.gardens--;
                const garden = state.gardens.find(g => g.id === state.currentGardenId);
                if (garden) {
                    window.AppState.usage.plots -= garden.plots;
                }
            }
            
            // Remove from local state
            state.gardens = state.gardens.filter(g => g.id !== state.currentGardenId);
            
            // Back to dashboard
            showDashboard();
        } catch (error) {
            console.error("Error deleting garden:", error);
        }
    });

    // Initialize Dashboard First
    async function initDashboard() {
        try {
            const response = await fetch('/api/gardens');
            const data = await response.json();
            state.gardens = data.map(g => ({
                id: g.id,
                name: g.name,
                location: g.location_name || 'Unknown Zone',
                area: g.area_size_m2 || 0,
                plots: g.plots_count || 0
            }));
            renderDashboard();
        } catch (error) {
            console.error("Failed to fetch gardens", error);
        }
    }

    initDashboard();

    async function savePlotToDB(zoneData) {
        try {
            const response = await fetch('/api/plots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    garden_id: state.currentGardenId,
                    name: zoneData.name,
                    shape: zoneData.shape || 'rectangle',
                    width: Math.round(zoneData.w),
                    length: Math.round(zoneData.h),
                    pos_x: Math.round(zoneData.x),
                    pos_y: Math.round(zoneData.y)
                })
            });
            const data = await response.json();
            if (!response.ok) {
                alert(data.error || 'Failed to save plot');
                return null;
            }
            if (window.AppState) window.AppState.usage.plots++;
            return data;
        } catch(e) { console.error(e); return null; }
    }

    async function updatePlotInDB(zoneData) {
        try {
            await fetch(`/api/plots/${zoneData.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: zoneData.name,
                    width: Math.round(zoneData.w),
                    length: Math.round(zoneData.h),
                    pos_x: Math.round(zoneData.x),
                    pos_y: Math.round(zoneData.y)
                })
            });
        } catch(e) { console.error(e); }
    }

    async function deletePlotFromDB(plotId) {
        try {
            await fetch(`/api/plots/${plotId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            if (window.AppState) window.AppState.usage.plots--;
        } catch(e) { console.error(e); }
    }

});
</script>
@endsection
