{{-- Cropper Modal Component for Profile Avatar (1:1 Ratio) --}}
<div id="profile-cropper-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 transition-all duration-300">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/70 backdrop-blur-xs -z-10" id="cropper-modal-backdrop"></div>

    {{-- Modal Card --}}
    <div class="bg-surface w-full max-w-3xl rounded-[28px] ambient-shadow-lg border border-outline-variant/30 overflow-hidden flex flex-col max-h-[92vh] animate-in fade-in zoom-in-95 duration-200">
        
        {{-- Modal Header --}}
        <div class="px-6 py-5 border-b border-outline-variant/20 flex items-center justify-between bg-surface-container-lowest">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">crop</span>
                </div>
                <div>
                    <h3 class="text-[18px] font-bold text-on-surface leading-tight">Sesuaikan Foto Profil</h3>
                    <p class="text-[12px] text-on-surface-variant">Atur posisi dan potongan foto profil Anda</p>
                </div>
            </div>

            <button type="button" id="cropper-btn-close" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest flex items-center justify-center transition-colors cursor-pointer" aria-label="Tutup">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 overflow-y-auto flex-1 flex flex-col gap-6">
            
            <div class="flex flex-col lg:flex-row gap-6 items-center lg:items-start">
                
                {{-- Cropper Workspace --}}
                <div class="w-full flex-1 flex flex-col items-center">
                    <div class="w-full h-72 sm:h-84 md:h-96 bg-[#111815] rounded-2xl overflow-hidden relative flex items-center justify-center border border-outline-variant/30 shadow-inner">
                        <div class="w-full h-full flex items-center justify-center p-2">
                            <img id="cropper-image" src="" alt="Potong Gambar" class="max-w-full max-h-full block">
                        </div>
                    </div>

                    {{-- Toolbars & Controls --}}
                    <div class="mt-4 w-full flex flex-wrap items-center justify-center sm:justify-between gap-2 p-2 bg-surface-container-low rounded-2xl border border-outline-variant/20">
                        <div class="flex items-center gap-1">
                            <button type="button" id="crop-zoom-in" title="Perbesar (Zoom In)" class="w-9 h-9 rounded-xl bg-surface hover:bg-primary/10 hover:text-primary text-on-surface-variant flex items-center justify-center transition-all active:scale-95 shadow-2xs cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">zoom_in</span>
                            </button>
                            <button type="button" id="crop-zoom-out" title="Perkecil (Zoom Out)" class="w-9 h-9 rounded-xl bg-surface hover:bg-primary/10 hover:text-primary text-on-surface-variant flex items-center justify-center transition-all active:scale-95 shadow-2xs cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">zoom_out</span>
                            </button>
                            <div class="h-5 w-px bg-outline-variant/40 mx-1"></div>
                            <button type="button" id="crop-rotate-left" title="Putar Kiri (-90°)" class="w-9 h-9 rounded-xl bg-surface hover:bg-primary/10 hover:text-primary text-on-surface-variant flex items-center justify-center transition-all active:scale-95 shadow-2xs cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">rotate_left</span>
                            </button>
                            <button type="button" id="crop-rotate-right" title="Putar Kanan (+90°)" class="w-9 h-9 rounded-xl bg-surface hover:bg-primary/10 hover:text-primary text-on-surface-variant flex items-center justify-center transition-all active:scale-95 shadow-2xs cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">rotate_right</span>
                            </button>
                            <div class="h-5 w-px bg-outline-variant/40 mx-1"></div>
                            <button type="button" id="crop-flip-x" title="Balik Horizontal" class="w-9 h-9 rounded-xl bg-surface hover:bg-primary/10 hover:text-primary text-on-surface-variant flex items-center justify-center transition-all active:scale-95 shadow-2xs cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                            </button>
                            <button type="button" id="crop-reset" title="Reset Posisi" class="w-9 h-9 rounded-xl bg-surface hover:bg-primary/10 hover:text-primary text-on-surface-variant flex items-center justify-center transition-all active:scale-95 shadow-2xs cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-1.5 px-3 py-1 bg-surface text-on-surface-variant rounded-xl text-[12px] font-medium border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[15px] text-primary">crop</span>
                            <span>Potong Foto</span>
                        </div>
                    </div>
                </div>

                {{-- Live Preview Section --}}
                <div class="w-full lg:w-56 flex flex-col items-center lg:items-start gap-4 p-4 bg-surface-container-low rounded-2xl border border-outline-variant/20 shrink-0">
                    <span class="text-[13px] font-bold text-on-surface flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-primary">visibility</span>
                        Pratinjau Hasil
                    </span>

                    <div class="flex flex-row lg:flex-col items-center justify-center gap-4 w-full">
                        {{-- Circular Preview (Avatar format) --}}
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-3 border-primary shadow-sm bg-surface-container-highest flex items-center justify-center">
                                <div class="cropper-preview w-full h-full overflow-hidden"></div>
                            </div>
                            <span class="text-[11px] font-medium text-on-surface-variant">Tampilan Bulat</span>
                        </div>

                        {{-- Square Preview --}}
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="w-16 h-16 rounded-xl overflow-hidden border border-outline-variant shadow-2xs bg-surface-container-highest flex items-center justify-center">
                                <div class="cropper-preview w-full h-full overflow-hidden"></div>
                            </div>
                            <span class="text-[11px] font-medium text-on-surface-variant">Tampilan Kotak</span>
                        </div>
                    </div>

                    <div class="text-[11px] text-on-surface-variant bg-surface-container-lowest p-2.5 rounded-xl border border-outline-variant/30 w-full leading-relaxed">
                        <span class="font-bold text-primary">Tips:</span> Geser kotak pemotong atau gunakan tombol kontrol untuk mengatur fokus foto.
                    </div>
                </div>

            </div>

        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface-container-lowest flex items-center justify-end gap-3">
            <button type="button" id="cropper-btn-cancel" class="px-5 py-2.5 rounded-full text-[14px] font-bold text-on-surface bg-surface-container-high hover:bg-surface-container-highest active:scale-95 transition-all cursor-pointer">
                Batal
            </button>
            <button type="button" id="cropper-btn-apply" class="px-6 py-2.5 rounded-full text-[14px] font-bold text-on-primary bg-primary hover:bg-primary/90 active:scale-95 transition-all flex items-center gap-2 shadow-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-[18px]" id="cropper-apply-icon">check</span>
                <span id="cropper-apply-text">Terapkan Foto</span>
            </button>
        </div>

    </div>
</div>
