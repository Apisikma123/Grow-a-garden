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

    .toolbar-btn.active {
        background-color: #006c49;
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

<div id="app-container" class="flex w-full h-[100vh] font-['Poppins'] relative bg-[#f2f6f4] transition-all duration-300">
    
    {{-- Mobile Sidebar Backdrop --}}
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[55] hidden md:hidden transition-opacity"></div>

    {{-- Main Sidebar (Moved to Right) --}}
    <div id="left-sidebar" class="order-last fixed md:relative right-0 top-0 bottom-0 w-[85%] md:w-[360px] bg-white/85 backdrop-blur-2xl border-l border-white/60 flex flex-col h-full flex-shrink-0 z-[60] md:z-20 premium-shadow transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] translate-x-full md:translate-x-0">
        
        <div class="p-8 pb-4 flex justify-between items-center relative z-10">
            <div>
                <h1 class="text-[32px] md:text-[36px] font-black text-slate-800 leading-tight tracking-tight mb-1">Green Valley</h1>
                <div class="flex items-center gap-2 text-slate-500 text-[14px] font-semibold bg-slate-100/50 inline-flex px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-[18px] text-[#006c49]">location_on</span>
                    <span>Zone 4b • <span id="total-area-display" class="text-slate-700 font-bold">0</span> m²</span>
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
            <button id="btn-sidebar-add" class="w-full bg-[#006c49] text-white rounded-2xl py-4 font-bold text-[16px] hover:bg-[#005236] active:scale-95 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] flex items-center justify-center gap-2 group">
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
            <button id="btn-mobile-menu" class="md:hidden w-11 h-11 rounded-full text-[#006c49] bg-white shadow-sm flex items-center justify-center transition-transform active:scale-90 font-bold mr-1 shrink-0">
                <span class="material-symbols-outlined text-[22px]">menu_open</span>
            </button>
            <div class="md:hidden w-px h-8 bg-slate-200 mx-1 shrink-0"></div>

            <button class="toolbar-btn active w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="select" title="Select"><span class="material-symbols-outlined text-[22px]">ads_click</span></button>
            <button class="toolbar-btn w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="draw" title="Draw Zone"><span class="material-symbols-outlined text-[22px]">draw</span></button>
            <button class="toolbar-btn w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white/50 flex items-center justify-center transition-all shrink-0" data-mode="pan" title="Pan Map"><span class="material-symbols-outlined text-[22px]">pan_tool</span></button>
            
            <div class="w-px h-8 bg-slate-200 mx-1 md:mx-2 shrink-0"></div>
            
            <button id="btn-zoom-in" class="w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white flex items-center justify-center transition-all shrink-0 shadow-sm" title="Zoom In"><span class="material-symbols-outlined text-[22px]">zoom_in</span></button>
            <button id="btn-zoom-out" class="w-11 h-11 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white flex items-center justify-center transition-all shrink-0 shadow-sm" title="Zoom Out"><span class="material-symbols-outlined text-[22px]">zoom_out</span></button>
            
            <div class="hidden md:block w-px h-8 bg-slate-200 mx-2 shrink-0"></div>
            
            <button id="btn-fullscreen" class="hidden md:flex w-11 h-11 rounded-full text-[#006c49] hover:bg-[#006c49]/10 items-center justify-center transition-all font-bold shrink-0" title="Toggle Fullscreen">
                <span class="material-symbols-outlined text-[22px]" id="icon-fullscreen">fullscreen</span>
            </button>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-opacity duration-500 z-0 px-4">
            <div class="w-24 h-24 md:w-28 md:h-28 bg-white rounded-full flex items-center justify-center mb-8 premium-shadow border border-white/60">
                <span class="material-symbols-outlined text-[48px] md:text-[56px] text-[#006c49]">yard</span>
            </div>
            <p class="text-[18px] md:text-[22px] text-slate-700 font-semibold text-center bg-white/70 backdrop-blur-xl px-8 py-5 rounded-3xl premium-shadow border border-white/60">Canvas is empty.<br><span class="text-slate-500 text-[15px] font-medium">Add your first planting zone to begin.</span></p>
        </div>
        
        <!-- Interactive Zones Container -->
        <div id="zones-layer-wrapper" class="z-20">
            <div id="draw-preview"></div>
            <div id="zones-layer" class="absolute inset-0 w-full h-full"></div>
        </div>
    </div>

    {{-- Right Sidebar (Premium Overlay) --}}
    <div id="right-sidebar" class="absolute right-0 top-0 bottom-0 w-[100%] sm:w-[420px] bg-white/90 backdrop-blur-3xl border-l border-white/60 flex flex-col flex-shrink-0 z-[70] shadow-[-20px_0_40px_rgba(0,0,0,0.04)] transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] translate-x-full">
        <div class="p-8 pb-6 flex justify-between items-center relative z-10">
            <h2 class="text-[14px] font-black text-slate-400 uppercase tracking-[0.2em]">Plot Details</h2>
            <button id="btn-close-details" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 shadow-sm border border-slate-100 transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>

        <div class="p-8 pt-0 flex-1 overflow-y-auto premium-scroll relative z-10">
            <!-- Header -->
            <div class="flex items-center gap-5 mb-10">
                <div id="detail-icon-bg" class="w-20 h-20 rounded-[24px] flex items-center justify-center border-2 flex-shrink-0 bg-white shadow-sm transition-colors duration-300">
                    <span class="material-symbols-outlined text-[36px]" id="detail-icon" style="font-variation-settings: 'wght' 600;">eco</span>
                </div>
                <div>
                    <h3 class="text-[28px] font-black text-slate-800 leading-tight mb-2 tracking-tight" id="detail-name">Zone Name</h3>
                    <span id="detail-status" class="text-[12px] font-bold px-3 py-1.5 rounded-full inline-flex items-center shadow-sm">Status</span>
                </div>
            </div>

            <!-- Specs -->
            <div class="bg-white/60 backdrop-blur-md rounded-[24px] p-6 border border-white mb-8 shadow-[0_4px_24px_rgba(0,0,0,0.02)] space-y-5">
                <div class="flex justify-between items-center">
                    <span class="text-[15px] text-slate-500 font-semibold">Cultivar</span>
                    <span class="text-[16px] font-bold text-slate-800" id="detail-cultivar">Unknown</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[15px] text-slate-500 font-semibold">Dimension</span>
                    <span class="text-[15px] font-bold text-[#006c49] bg-[#006c49]/10 px-3 py-1 rounded-full" id="detail-area">0 m²</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[15px] text-slate-500 font-semibold">Planted Date</span>
                    <span class="text-[16px] font-bold text-slate-800">May 15, 2024</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[15px] text-slate-500 font-semibold">Est. Harvest</span>
                    <span class="text-[16px] font-bold text-slate-800">Aug 10 - Aug 25</span>
                </div>
            </div>

            <!-- Progress -->
            <div class="mb-10 px-2 bg-white/60 p-6 rounded-[24px] border border-white shadow-[0_4px_24px_rgba(0,0,0,0.02)]">
                <div class="flex justify-between text-[14px] font-bold mb-4">
                    <span class="text-slate-500">Growth Progress</span>
                    <span class="text-[#006c49] text-[18px] font-black" id="detail-progress-text">45%</span>
                </div>
                <div class="h-4 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-gradient-to-r from-[#006c49] to-[#10b981] rounded-full transition-all duration-1000 ease-out relative" style="width: 45%" id="detail-progress-bar">
                        <div class="absolute inset-0 bg-white/20 w-full h-full" style="background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent); background-size: 1rem 1rem;"></div>
                    </div>
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

    <!-- Modal for New Plot (Absolute Centered & Refined) -->
    <div id="new-plot-modal" class="fixed inset-0 z-[9999] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity"></div>
        
        <!-- Modal Content Centered -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-[420px] p-4">
            <div class="bg-white/95 backdrop-blur-xl rounded-[32px] p-8 premium-shadow-hover border border-white flex flex-col">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#006c49]/20 to-[#10b981]/20 text-[#006c49] flex items-center justify-center shrink-0 border border-[#006c49]/10">
                        <span class="material-symbols-outlined text-[28px]">add_location_alt</span>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 leading-tight">Create<br><span class="text-[#006c49]">New Plot</span></h2>
                </div>
                
                <div class="space-y-4 max-h-[60vh] overflow-y-auto premium-scroll pr-2">
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Plot Name</label>
                        <input type="text" id="input-plot-name" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-[#006c49] focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all" placeholder="e.g. Tomato Plot A1">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Plant Type</label>
                        <input type="text" id="input-plot-plant" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-[#006c49] focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all" placeholder="e.g. Tomato">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Cultivar (Optional)</label>
                        <input type="text" id="input-plot-cultivar" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-[#006c49] focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all" placeholder="e.g. Roma">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Shape</label>
                        <select id="input-plot-shape" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-[#006c49] focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all appearance-none cursor-pointer">
                            <option value="rectangle">Rectangle (Bedengan)</option>
                            <option value="circle">Circle (Pot/Drum)</option>
                            <option value="hexagon">Hexagon (Raised Bed)</option>
                        </select>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Width (cm)</label>
                            <input type="number" id="input-plot-width" value="240" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-[#006c49] focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Length (cm)</label>
                            <input type="number" id="input-plot-height" value="144" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-[#006c49] focus:ring-2 focus:ring-[#006c49]/20 focus:bg-white focus:outline-none text-[16px] text-slate-800 font-medium transition-all">
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mt-8">
                    <button id="btn-cancel-modal" class="flex-1 py-4 text-slate-500 font-bold rounded-2xl hover:bg-slate-100 transition-colors text-[16px]">Cancel</button>
                    <button id="btn-confirm-modal" class="flex-[1.5] py-4 bg-[#006c49] text-white font-bold rounded-2xl hover:bg-[#005236] active:scale-95 transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] text-[16px] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">done</span> Create Plot
                    </button>
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
        zones: [
            { id: 1, name: 'Tomato Plot A1', plant: 'Tomato', status: 'healthy', x: 240, y: 144, w: 264, h: 168, cultivar: 'Roma', progress: 45, shape: 'rectangle' },
            { id: 2, name: 'Chili Field B2', plant: 'Chili', status: 'attention', x: 552, y: 192, w: 216, h: 120, cultivar: 'Rawit', progress: 10, shape: 'rectangle' },
            { id: 3, name: 'Carrot Patch C1', plant: 'Carrot', status: 'late', x: 240, y: 360, w: 384, h: 168, cultivar: 'Orange', progress: 80, shape: 'rectangle' },
            { id: 4, name: 'Lettuce Bed D4', plant: 'Lettuce', status: 'new', x: 672, y: 360, w: 240, h: 168, cultivar: 'Iceberg', progress: 0, shape: 'rectangle' },
        ]
    };

    const snap = (val) => Math.round(val / GRID_SIZE) * GRID_SIZE;
    const getArea = (w, h) => Math.round((w * h) / 100);

    const getStatusColors = (status) => {
        switch(status) {
            case 'healthy': return { color: '#006c49', bg: '#6ffbbe', text: 'Healthy', border: '#006c49' }; 
            case 'attention': return { color: '#b45309', bg: '#fef3c7', text: 'Needs Attention', border: '#f59e0b' }; 
            case 'late': return { color: '#b91c1c', bg: '#fee2e2', text: 'Late Care', border: '#ef4444' }; 
            case 'new': return { color: '#0369a1', bg: '#e0f2fe', text: 'Newly Planted', border: '#0ea5e9' }; 
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
                btn.classList.add('active', 'bg-[#006c49]', 'text-white', 'shadow-[0_4px_12px_rgba(0,108,73,0.3)]');
                btn.classList.remove('text-slate-500', 'hover:bg-white/50');
            } else {
                btn.classList.remove('active', 'bg-[#006c49]', 'text-white', 'shadow-[0_4px_12px_rgba(0,108,73,0.3)]');
                btn.classList.add('text-slate-500', 'hover:bg-white/50');
            }
        });

        canvasContainer.className = `flex-1 relative bg-grid overflow-hidden mode-${newMode}`;
        if(newMode !== 'select') selectZone(null);
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

    function render() {
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
            if (zone.shape !== 'hexagon') {
                box.style.borderColor = palette.border;
            }
            box.style.backgroundColor = state.selectedZoneId === zone.id ? 'rgba(255,255,255,0.98)' : 'rgba(255,255,255,0.85)';
            
            let svgBorder = '';
            if (zone.shape === 'hexagon') {
                svgBorder = `
                <svg width="100%" height="100%" style="position: absolute; inset: 0; pointer-events: none; z-index: 0;">
                    <polygon points="${zone.w/2},2 ${zone.w-2},${zone.h*0.25} ${zone.w-2},${zone.h*0.75} ${zone.w/2},${zone.h-2} 2,${zone.h*0.75} 2,${zone.h*0.25}" 
                             stroke="${palette.border}" stroke-width="3" stroke-dasharray="8,8" fill="transparent" stroke-linejoin="round"/>
                </svg>`;
            }

            box.innerHTML = `
                ${svgBorder}
                <div class="zone-label" style="color: ${palette.color}; z-index: 1;">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 600;">eco</span>
                    <span class="text-[14px] md:text-[15px] font-bold truncate max-w-[100px] md:max-w-[140px] tracking-tight">${zone.name}</span>
                </div>
                ${state.selectedZoneId === zone.id ? `<div class="resize-handle" style="border-color: ${palette.border}; z-index: 2;"></div>` : ''}
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
            const item = document.createElement('div');
            item.id = `sidebar-card-${zone.id}`;
            item.className = `bg-white rounded-[24px] p-5 border-2 transition-all cursor-pointer hover:-translate-y-1 duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.03)] ${state.selectedZoneId === zone.id ? 'border-[#006c49] shadow-[0_8px_32px_rgba(0,108,73,0.12)]' : 'border-transparent hover:border-slate-200 hover:shadow-[0_8px_32px_rgba(0,0,0,0.06)]'}`;
            item.innerHTML = `
                <h3 class="font-bold text-slate-800 mb-2 text-[16px]">${zone.name}</h3>
                <div class="flex items-center gap-4 text-[13px] text-slate-500 font-semibold">
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full shadow-inner border border-black/5" style="background-color: ${palette.border}"></div> ${getArea(zone.w, zone.h)} m²</div>
                    <div class="flex items-center gap-1 text-[#006c49] bg-[#006c49]/5 px-2 py-0.5 rounded-md"><span class="material-symbols-outlined text-[16px]">eco</span> Planted</div>
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
                document.getElementById('detail-cultivar').textContent = zone.cultivar || zone.plant;
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
        }
    }

    canvasContainer.addEventListener('mousedown', (e) => handleCanvasDown(e, getClientPos(e)));
    canvasContainer.addEventListener('touchstart', (e) => handleCanvasDown(e, getClientPos(e)), {passive: false});

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
            proposedZone.x = Math.max(0, snap(initialX + dx));
            proposedZone.y = Math.max(0, snap(initialY + dy));
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
    document.addEventListener('touchmove', (e) => handleMove(e, getClientPos(e)), {passive: false});

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
                const newId = Date.now();
                state.zones.push({
                    id: newId,
                    name: 'Drawn Plot ' + (state.zones.length + 1),
                    plant: 'Unknown',
                    cultivar: 'None',
                    status: 'new',
                    x: x, y: y, w: w, h: h,
                    progress: 0
                });
                setMode('select');
                selectZone(newId);
                setTimeout(() => {
                    const card = document.getElementById(`sidebar-card-${newId}`);
                    if(card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 50);
            }
        }

        if(isDragging || isResizing) {
            isDragging = false;
            isResizing = false;
            if(draggedZoneId) {
                const box = document.getElementById(`zone-box-${draggedZoneId}`);
                if(box) box.classList.remove('active-drag');
            }
            render(); 
        }
        draggedZoneId = null;
    }

    document.addEventListener('mouseup', handleUp);
    document.addEventListener('touchend', handleUp);

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
        return { x: startX > 0 ? startX : 0, y: startY > 0 ? startY : 0 };
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
        
        const newId = Date.now();
        state.zones.push({
            id: newId,
            name: name,
            plant: plant,
            cultivar: cultivar,
            shape: shape,
            status: 'new',
            x: safePos.x, y: safePos.y, w: w, h: h,
            progress: 0
        });
        
        setMode('select');
        selectZone(newId);
        panToZone(state.zones[state.zones.length - 1]);
        
        setTimeout(() => {
            const card = document.getElementById(`sidebar-card-${newId}`);
            if(card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    });

    document.getElementById('btn-delete-zone').addEventListener('click', () => {
        if (state.selectedZoneId) {
            state.zones = state.zones.filter(z => z.id !== state.selectedZoneId);
            selectZone(null);
        }
    });

    document.getElementById('btn-close-details').addEventListener('click', () => {
        selectZone(null);
    });

    document.addEventListener('dragstart', (e) => e.preventDefault());

    // Center Map initially
    updateTransform();
    render();
});
</script>
@endsection
