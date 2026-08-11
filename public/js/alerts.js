// ============================================================
// Grow a Garden — Alert & Celebration System
// Design System: Verdant Growth (DESIGN.md)
// Primary: #006c49 | Secondary: #944a23 | Surface: #ffffff
// ============================================================

// ── SweetAlert2 Base Config ──────────────────────────────────
const defaultCustomClass = {
    popup: 'bg-white border border-outline-variant/30 rounded-[28px] p-6 shadow-[0_12px_40px_-12px_rgba(0,0,0,0.12)] backdrop-blur-sm',
    title: 'text-[22px] font-bold text-[#191c1d] font-sans tracking-tight mb-2',
    htmlContainer: 'text-[15px] text-[#3c4a42] font-sans leading-relaxed',
    confirmButton: 'bg-[#006c49] text-white px-6 py-3 rounded-full text-[14px] font-bold hover:bg-[#005236] active:scale-95 transition-all shadow-sm cursor-pointer',
    cancelButton: 'bg-surface-container-high text-[#191c1d] px-6 py-3 rounded-full text-[14px] font-bold hover:bg-surface-container-highest active:scale-95 transition-all cursor-pointer',
    actions: 'mt-6 gap-3',
    icon: 'border-0',
    closeButton: 'text-slate-400 hover:text-slate-700 transition-colors focus:outline-none'
};

const modernSwal = Swal.mixin({
    customClass: defaultCustomClass,
    buttonsStyling: false,
    showCloseButton: true
});

const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: false,
    customClass: {
        popup: 'bg-white border border-outline-variant/20 rounded-[16px] shadow-[0_8px_30px_-8px_rgba(0,0,0,0.12)] flex items-center mb-4 mr-4 p-3',
        title: 'text-[14px] font-medium text-[#191c1d] ml-2 font-sans',
        icon: 'border-0 scale-75 m-0',
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// ── Confetti Engine (Pure Canvas) ─────────────────────────────
window.ConfettiEngine = (function () {
    let canvas, ctx, particles = [], animFrame, running = false;

    const COLORS_BADGE = ['#006c49', '#10b981', '#944a23', '#fd9e70', '#ffffff'];
    const COLORS_QUEST = ['#006c49', '#10b981', '#67b193', '#ffffff'];
    const COLORS_PREMIUM = ['#006c49', '#10b981', '#944a23', '#f59e0b', '#ffffff'];

    function makeParticle(colors, origin) {
        const angle = Math.random() * Math.PI * 2;
        const speed = Math.random() * 8 + 3;
        return {
            x: origin.x,
            y: origin.y,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed - (Math.random() * 5 + 3),
            w: Math.random() * 10 + 4,
            h: Math.random() * 6 + 3,
            color: colors[Math.floor(Math.random() * colors.length)],
            rotation: Math.random() * 360,
            rotSpeed: (Math.random() - 0.5) * 8,
            alpha: 1,
            gravity: 0.25 + Math.random() * 0.15,
            shape: Math.random() > 0.5 ? 'rect' : 'circle',
        };
    }

    function setup() {
        if (!canvas) {
            canvas = document.createElement('canvas');
            canvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:99999;';
            canvas.id = 'confetti-canvas';
            document.body.appendChild(canvas);
        }
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        ctx = canvas.getContext('2d');
    }

    function burst(colors, origin, count = 120) {
        setup();
        for (let i = 0; i < count; i++) {
            particles.push(makeParticle(colors, origin));
        }
        if (!running) animate();
    }

    function animate() {
        running = true;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles = particles.filter(p => p.alpha > 0.05);

        for (const p of particles) {
            p.x += p.vx;
            p.vy += p.gravity;
            p.y += p.vy;
            p.rotation += p.rotSpeed;
            p.alpha -= 0.013;
            p.vx *= 0.99;

            ctx.save();
            ctx.globalAlpha = Math.max(0, p.alpha);
            ctx.translate(p.x, p.y);
            ctx.rotate((p.rotation * Math.PI) / 180);
            ctx.fillStyle = p.color;

            if (p.shape === 'circle') {
                ctx.beginPath();
                ctx.arc(0, 0, p.w / 2, 0, Math.PI * 2);
                ctx.fill();
            } else {
                ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
            }
            ctx.restore();
        }

        if (particles.length > 0) {
            animFrame = requestAnimationFrame(animate);
        } else {
            running = false;
            if (canvas) canvas.style.display = 'none';
        }
    }

    function stop() {
        cancelAnimationFrame(animFrame);
        particles = [];
        running = false;
        if (canvas) canvas.style.display = 'none';
    }

    return {
        badge: (origin) => burst(COLORS_BADGE, origin || { x: window.innerWidth / 2, y: window.innerHeight / 3 }, 130),
        quest: (origin) => burst(COLORS_QUEST, origin || { x: window.innerWidth / 2, y: window.innerHeight * 0.4 }, 150),
        premium: (origin) => burst(COLORS_PREMIUM, origin || { x: window.innerWidth / 2, y: window.innerHeight * 0.35 }, 180),
        stop,
    };
})();

// ── Main Alert API ───────────────────────────────────────────
window.Alert = {

    // 1. Toast Alerts
    toast: {
        success: (message) => {
            Toast.fire({
                icon: 'success',
                iconHtml: '<span class="material-symbols-outlined text-[#006c49] text-[24px]">check_circle</span>',
                title: message,
            });
        },
        error: (message) => {
            Toast.fire({
                icon: 'error',
                iconHtml: '<span class="material-symbols-outlined text-[#ba1a1a] text-[24px]">error</span>',
                title: message,
            });
        },
        info: (message) => {
            Toast.fire({
                icon: 'info',
                iconHtml: '<span class="material-symbols-outlined text-[#006c49] text-[24px]">info</span>',
                title: message,
            });
        }
    },

    // 2. Standard Modals
    modal: {
        success: (title, text) => {
            modernSwal.fire({ icon: 'success', title, text, confirmButtonText: 'Lanjutkan' });
        },
        error: (title, text) => {
            modernSwal.fire({ icon: 'error', title, text, confirmButtonText: 'Tutup' });
        },
        confirm: (title, text, confirmText = 'Ya, Lanjutkan', isDanger = false) => {
            const options = {
                icon: 'warning',
                title,
                text,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            };
            if (isDanger) {
                options.customClass = {
                    ...defaultCustomClass,
                    confirmButton: 'bg-[#ba1a1a] text-white px-6 py-3 rounded-full text-[14px] font-bold hover:bg-[#93000a] active:scale-95 transition-all shadow-sm cursor-pointer'
                };
            }
            return modernSwal.fire(options);
        },

        // 3. Badge Unlock Modal
        badge: (badgeData) => {
            setTimeout(() => ConfettiEngine.badge(), 200);

            return Swal.fire({
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Klaim Pencapaian',
                buttonsStyling: false,
                backdrop: `rgba(15, 23, 42, 0.6)`,
                allowOutsideClick: false,
                customClass: {
                    popup: 'bg-white border border-outline-variant/30 rounded-[28px] p-6 shadow-2xl overflow-hidden w-full max-w-[380px] relative',
                    confirmButton: 'w-full bg-[#006c49] text-white font-bold text-[15px] py-3.5 px-6 rounded-full hover:bg-[#005236] active:scale-95 transition-all shadow-md cursor-pointer block text-center',
                    actions: 'w-full mt-6 m-0 p-0',
                    closeButton: 'text-slate-400 hover:text-slate-700 transition-colors focus:outline-none absolute top-4 right-4 z-30',
                    htmlContainer: 'm-0 p-0'
                },
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding-top:12px;">
                        <div style="
                            width: 72px; height: 72px;
                            background: #006c49;
                            border-radius: 20px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 10px 25px -5px rgba(0,108,73,0.4);
                            margin-bottom: 20px;
                        ">
                            <span class="material-symbols-outlined" style="font-size:38px;color:white">${badgeData.icon_url || 'military_tech'}</span>
                        </div>

                        <span style="
                            display:inline-flex;align-items:center;gap:6px;
                            padding:4px 12px;border-radius:999px;
                            background:rgba(148,74,35,0.1);color:#944a23;
                            font-size:11px;font-weight:800;letter-spacing:0.05em;text-transform:uppercase;
                            margin-bottom:10px;border:1px solid rgba(148,74,35,0.2);
                        ">
                            <span class="material-symbols-outlined" style="font-size:13px">military_tech</span>
                            Pencapaian Baru Terbuka
                        </span>

                        <h3 style="font-size:22px;font-weight:800;color:#191c1d;margin-bottom:8px;line-height:1.3">${badgeData.name}</h3>
                        <p style="font-size:14px;color:#3c4a42;line-height:1.5;max-width:300px;margin:0 auto">${badgeData.description}</p>
                    </div>
                `
            });
        },
    },

    // 4. Quest Celebration — daily quest complete
    quest: {
        complete: (completedCount = 0) => {
            ConfettiEngine.quest();

            return Swal.fire({
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Lanjutkan',
                buttonsStyling: false,
                backdrop: `rgba(15, 23, 42, 0.6)`,
                allowOutsideClick: true,
                customClass: {
                    popup: 'bg-white border border-outline-variant/30 rounded-[28px] p-6 shadow-2xl overflow-hidden w-full max-w-[380px] relative',
                    confirmButton: 'w-full bg-[#006c49] text-white font-bold text-[15px] py-3.5 px-6 rounded-full hover:bg-[#005236] active:scale-95 transition-all shadow-md cursor-pointer block text-center',
                    actions: 'w-full mt-6 m-0 p-0',
                    closeButton: 'text-slate-400 hover:text-slate-700 transition-colors focus:outline-none absolute top-4 right-4 z-30',
                    htmlContainer: 'm-0 p-0'
                },
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding-top:12px;">
                        <div style="
                            width: 72px; height: 72px;
                            background: #006c49;
                            border-radius: 20px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 10px 25px -5px rgba(0,108,73,0.4);
                            margin-bottom: 20px;
                        ">
                            <span class="material-symbols-outlined" style="font-size:38px;color:white">task_alt</span>
                        </div>

                        <span style="
                            display:inline-flex;align-items:center;gap:6px;
                            padding:4px 12px;border-radius:999px;
                            background:rgba(0,108,73,0.1);color:#006c49;
                            font-size:11px;font-weight:800;letter-spacing:0.05em;text-transform:uppercase;
                            margin-bottom:10px;border:1px solid rgba(0,108,73,0.2);
                        ">
                            <span class="material-symbols-outlined" style="font-size:13px">check_circle</span>
                            Tugas Perawatan Selesai
                        </span>

                        <h3 style="font-size:22px;font-weight:800;color:#191c1d;margin-bottom:8px;line-height:1.3">Semua Tugas Hari Ini Selesai</h3>
                        <p style="font-size:14px;color:#3c4a42;line-height:1.5;max-width:300px;margin:0 auto">
                            Anda telah menyelesaikan <strong style="color:#006c49">${completedCount} tugas</strong> perawatan hari ini.
                        </p>
                    </div>
                `
            });
        }
    },

    // 5. Premium Upgrade Celebration — Clean DESIGN.md Layout
    premium: {
        celebrate: (planName = 'Subur (Pro)', planLabel = '', redirectUrl = '/gardens') => {
            ConfettiEngine.premium();
            setTimeout(() => ConfettiEngine.premium({ x: window.innerWidth * 0.2, y: window.innerHeight * 0.4 }), 800);
            setTimeout(() => ConfettiEngine.premium({ x: window.innerWidth * 0.8, y: window.innerHeight * 0.4 }), 1200);

            const isPremium = planName.toLowerCase().includes('panen') || planName.toLowerCase().includes('premium');
            const iconName = isPremium ? 'workspace_premium' : 'star';

            const features = isPremium ? [
                { icon: 'all_inclusive', title: 'Kapasitas Maksimal', desc: 'Maksimal 100 Kebun & Tanaman Tanpa Batas.' },
                { icon: 'calendar_month', title: 'Growth Calendar', desc: 'Jadwal estimasi tanam hingga panen interaktif.' },
                { icon: 'cloud_done', title: 'Weather Adjustment', desc: 'Anti gagal panen karena cuaca ekstrem.' },
                { icon: 'emoji_events', title: 'Harvest Notification', desc: 'Notifikasi panen di dashboard.' },
                { icon: 'history', title: 'Activity Log Tanpa Batas', desc: 'Tracking historis tanpa batas sepanjang masa.' }
            ] : [
                { icon: 'yard', title: 'Kapasitas Ekstra', desc: 'Maksimal 10 Kebun & 100 Tanaman Aktif.' },
                { icon: 'calendar_month', title: 'Growth Calendar', desc: 'Jadwal estimasi tanam hingga panen interaktif.' },
                { icon: 'cloud_done', title: 'Weather Adjustment', desc: 'Anti gagal panen karena cuaca ekstrem.' },
                { icon: 'emoji_events', title: 'Harvest Notification', desc: 'Notifikasi panen di dashboard.' }
            ];

            return Swal.fire({
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Lanjutkan ke Kebun Saya',
                buttonsStyling: false,
                backdrop: `rgba(15, 23, 42, 0.65)`,
                allowOutsideClick: true,
                customClass: {
                    popup: 'bg-white border border-outline-variant/30 rounded-[28px] p-6 md:p-8 shadow-2xl overflow-hidden w-full max-w-[440px] relative',
                    confirmButton: 'w-full bg-[#006c49] text-white font-bold text-[15px] py-3.5 px-6 rounded-full hover:bg-[#005236] active:scale-95 transition-all shadow-md cursor-pointer block text-center',
                    actions: 'w-full mt-6 m-0 p-0',
                    closeButton: 'text-slate-400 hover:text-slate-700 transition-colors focus:outline-none absolute top-4 right-4 z-30',
                    htmlContainer: 'm-0 p-0'
                },
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding-top:4px;">
                        <!-- Icon Circle -->
                        <div style="
                            width: 72px; height: 72px;
                            background: #006c49;
                            border-radius: 20px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 10px 24px -5px rgba(0,108,73,0.4);
                            margin-bottom: 16px;
                        ">
                            <span class="material-symbols-outlined" style="font-size:38px;color:white">${iconName}</span>
                        </div>

                        <!-- Badge Tag -->
                        <span style="
                            display:inline-flex;align-items:center;gap:6px;
                            padding:4px 14px;border-radius:999px;
                            background:rgba(0,108,73,0.1);color:#006c49;
                            font-size:11px;font-weight:800;letter-spacing:0.05em;text-transform:uppercase;
                            margin-bottom:10px;border:1px solid rgba(0,108,73,0.2);
                        ">
                            <span class="material-symbols-outlined" style="font-size:13px">verified</span>
                            Paket Berhasil Diaktifkan
                        </span>

                        <!-- Plan Title -->
                        <h3 style="font-size:22px;font-weight:800;color:#191c1d;margin-bottom:6px;line-height:1.2;letter-spacing:-0.01em">
                            ${planName}
                        </h3>
                        <p style="font-size:13px;color:#3c4a42;line-height:1.4;max-width:340px;margin:0 auto 16px">
                            ${planLabel || 'Fitur langganan Anda telah aktif secara penuh.'}
                        </p>

                        <!-- Feature List Items -->
                        <div style="
                            width:100%;
                            background:#f8f9fa;
                            border:1px solid #e1e3e4;
                            border-radius:16px;
                            padding:14px;
                            display:flex;
                            flex-direction:column;
                            gap:10px;
                            text-align:left;
                        ">
                            ${features.map(f => `
                                <div style="display:flex;align-items:flex-start;gap:12px">
                                    <div style="
                                        width:32px;height:32px;
                                        border-radius:10px;
                                        background:#ecfdf5;
                                        color:#006c49;
                                        display:flex;align-items:center;justify-content:center;
                                        flex-shrink:0;
                                        margin-top:1px;
                                    ">
                                        <span class="material-symbols-outlined" style="font-size:18px">${f.icon}</span>
                                    </div>
                                    <div style="flex:1;min-width:0">
                                        <div style="font-size:13px;font-weight:700;color:#191c1d;line-height:1.2">${f.title}</div>
                                        <div style="font-size:11px;color:#3c4a42;margin-top:2px;line-height:1.3">${f.desc}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `
            }).then((result) => {
                if (result.isConfirmed && redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
        }
    }
};
