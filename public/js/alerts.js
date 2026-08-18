/**
 * Grow a Garden — Global Alert & Celebration System (SweetAlert2)
 * Based on DESIGN.md (Verdant Growth Theme)
 * Primary: #006c49 | Secondary: #944a23 | Tertiary: #1b6b51 | Error: #ba1a1a | Surface: #ffffff
 */

// ── Global SweetAlert2 Base Configuration ─────────────────────
const baseCustomClass = {
    popup: 'swal2-verdant-popup',
    title: 'swal2-verdant-title',
    htmlContainer: 'swal2-verdant-html',
    confirmButton: 'swal2-verdant-btn-primary',
    cancelButton: 'swal2-verdant-btn-cancel',
    denyButton: 'swal2-verdant-btn-deny',
    actions: 'swal2-verdant-actions',
    icon: 'swal2-verdant-icon',
    closeButton: 'swal2-verdant-close-btn'
};

const modernSwal = Swal.mixin({
    customClass: baseCustomClass,
    buttonsStyling: false,
    showCloseButton: true,
    backdrop: 'rgba(25, 28, 29, 0.55)',
});

const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: false,
    customClass: {
        popup: 'swal2-verdant-toast',
        title: 'swal2-verdant-toast-title',
        htmlContainer: 'swal2-verdant-toast-html',
        icon: 'swal2-verdant-toast-icon',
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// ── Confetti Engine (Pure Canvas Animation) ───────────────────
window.ConfettiEngine = (function () {
    let canvas, ctx, particles = [], animFrame, running = false;

    const COLORS_BADGE = ['#006c49', '#10b981', '#944a23', '#fd9e70', '#ffffff'];
    const COLORS_QUEST = ['#006c49', '#10b981', '#67b193', '#ffffff'];
    const COLORS_PREMIUM = ['#006c49', '#10b981', '#944a23', '#fd9e70', '#ffffff'];

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
        canvas.style.display = 'block';
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

// ── Global Alert API ──────────────────────────────────────────
window.Alert = {

    // 1. Direct Modal Shorthands
    success: (title, text) => {
        return modernSwal.fire({
            icon: 'success',
            iconHtml: '<span class="material-symbols-outlined swal-icon-symbol text-[#006c49]">check_circle</span>',
            title: title || 'Berhasil!',
            text: text || '',
            confirmButtonText: 'Lanjutkan'
        });
    },

    error: (title, text) => {
        return modernSwal.fire({
            icon: 'error',
            iconHtml: '<span class="material-symbols-outlined swal-icon-symbol text-[#ba1a1a]">error</span>',
            title: title || 'Terjadi Kesalahan!',
            text: text || '',
            confirmButtonText: 'Tutup'
        });
    },

    warning: (title, text) => {
        return modernSwal.fire({
            icon: 'warning',
            iconHtml: '<span class="material-symbols-outlined swal-icon-symbol text-[#944a23]">warning</span>',
            title: title || 'Peringatan',
            text: text || '',
            confirmButtonText: 'Mengerti'
        });
    },

    info: (title, text) => {
        return modernSwal.fire({
            icon: 'info',
            iconHtml: '<span class="material-symbols-outlined swal-icon-symbol text-[#1b6b51]">info</span>',
            title: title || 'Informasi',
            text: text || '',
            confirmButtonText: 'Tutup'
        });
    },

    confirm: (title, text, confirmText = 'Ya, Lanjutkan', isDanger = false) => {
        const customClass = {
            ...baseCustomClass,
            confirmButton: isDanger ? 'swal2-verdant-btn-danger' : 'swal2-verdant-btn-primary'
        };

        return modernSwal.fire({
            icon: isDanger ? 'error' : 'warning',
            iconHtml: `<span class="material-symbols-outlined swal-icon-symbol ${isDanger ? 'text-[#ba1a1a]' : 'text-[#944a23]'}">${isDanger ? 'warning' : 'help'}</span>`,
            title: title || 'Konfirmasi',
            text: text || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal',
            customClass: customClass,
            reverseButtons: true
        });
    },

    // 2. Toast Notifications
    toast: {
        success: (message) => {
            return Toast.fire({
                icon: 'success',
                iconHtml: '<span class="material-symbols-outlined text-[#006c49] text-[22px]">check_circle</span>',
                title: message,
            });
        },
        error: (message) => {
            return Toast.fire({
                icon: 'error',
                iconHtml: '<span class="material-symbols-outlined text-[#ba1a1a] text-[22px]">error</span>',
                title: message,
            });
        },
        warning: (message) => {
            return Toast.fire({
                icon: 'warning',
                iconHtml: '<span class="material-symbols-outlined text-[#944a23] text-[22px]">warning</span>',
                title: message,
            });
        },
        info: (message) => {
            return Toast.fire({
                icon: 'info',
                iconHtml: '<span class="material-symbols-outlined text-[#1b6b51] text-[22px]">info</span>',
                title: message,
            });
        }
    },

    // 3. Modal Namespace (Aliases for consistency)
    modal: {
        success: (title, text) => window.Alert.success(title, text),
        error: (title, text) => window.Alert.error(title, text),
        warning: (title, text) => window.Alert.warning(title, text),
        info: (title, text) => window.Alert.info(title, text),
        confirm: (title, text, confirmText, isDanger) => window.Alert.confirm(title, text, confirmText, isDanger),

        // Badge Unlock Celebration Modal
        badge: (badgeData) => {
            setTimeout(() => ConfettiEngine.badge(), 200);

            return Swal.fire({
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Klaim Pencapaian',
                buttonsStyling: false,
                backdrop: 'rgba(25, 28, 29, 0.65)',
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal2-verdant-popup swal2-verdant-badge-popup',
                    confirmButton: 'swal2-verdant-btn-primary w-full text-center block',
                    actions: 'w-full mt-6 m-0 p-0',
                    closeButton: 'swal2-verdant-close-btn',
                    htmlContainer: 'm-0 p-0'
                },
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding-top:12px;">
                        <div style="
                            width: 76px; height: 76px;
                            background: #006c49;
                            border-radius: 22px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 12px 28px -6px rgba(0,108,73,0.45);
                            margin-bottom: 20px;
                        ">
                            <span class="material-symbols-outlined" style="font-size:42px;color:white">${badgeData.icon_url || 'military_tech'}</span>
                        </div>

                        <span style="
                            display:inline-flex;align-items:center;gap:6px;
                            padding:4px 14px;border-radius:999px;
                            background:rgba(148,74,35,0.12);color:#944a23;
                            font-size:11px;font-weight:800;letter-spacing:0.05em;text-transform:uppercase;
                            margin-bottom:12px;border:1px solid rgba(148,74,35,0.25);
                        ">
                            <span class="material-symbols-outlined" style="font-size:14px">military_tech</span>
                            Pencapaian Baru Terbuka
                        </span>

                        <h3 style="font-size:22px;font-weight:800;color:#191c1d;margin-bottom:8px;line-height:1.3">${badgeData.name}</h3>
                        <p style="font-size:14px;color:#3c4a42;line-height:1.5;max-width:320px;margin:0 auto">${badgeData.description}</p>
                    </div>
                `
            });
        },
    },

    // 4. Quest Celebration Modal
    quest: {
        complete: (completedCount = 0) => {
            ConfettiEngine.quest();

            return Swal.fire({
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Lanjutkan',
                buttonsStyling: false,
                backdrop: 'rgba(25, 28, 29, 0.65)',
                allowOutsideClick: true,
                customClass: {
                    popup: 'swal2-verdant-popup swal2-verdant-badge-popup',
                    confirmButton: 'swal2-verdant-btn-primary w-full text-center block',
                    actions: 'w-full mt-6 m-0 p-0',
                    closeButton: 'swal2-verdant-close-btn',
                    htmlContainer: 'm-0 p-0'
                },
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding-top:12px;">
                        <div style="
                            width: 76px; height: 76px;
                            background: #006c49;
                            border-radius: 22px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 12px 28px -6px rgba(0,108,73,0.45);
                            margin-bottom: 20px;
                        ">
                            <span class="material-symbols-outlined" style="font-size:42px;color:white">task_alt</span>
                        </div>

                        <span style="
                            display:inline-flex;align-items:center;gap:6px;
                            padding:4px 14px;border-radius:999px;
                            background:rgba(0,108,73,0.12);color:#006c49;
                            font-size:11px;font-weight:800;letter-spacing:0.05em;text-transform:uppercase;
                            margin-bottom:12px;border:1px solid rgba(0,108,73,0.25);
                        ">
                            <span class="material-symbols-outlined" style="font-size:14px">check_circle</span>
                            Tugas Perawatan Selesai
                        </span>

                        <h3 style="font-size:22px;font-weight:800;color:#191c1d;margin-bottom:8px;line-height:1.3">Semua Tugas Hari Ini Selesai</h3>
                        <p style="font-size:14px;color:#3c4a42;line-height:1.5;max-width:320px;margin:0 auto">
                            Anda telah menyelesaikan <strong style="color:#006c49">${completedCount} tugas</strong> perawatan tanaman hari ini.
                        </p>
                    </div>
                `
            });
        }
    },

    // 5. Premium Upgrade Celebration Modal
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
                backdrop: 'rgba(25, 28, 29, 0.65)',
                allowOutsideClick: true,
                customClass: {
                    popup: 'swal2-verdant-popup swal2-verdant-premium-popup',
                    confirmButton: 'swal2-verdant-btn-primary w-full text-center block',
                    actions: 'w-full mt-6 m-0 p-0',
                    closeButton: 'swal2-verdant-close-btn',
                    htmlContainer: 'm-0 p-0'
                },
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding-top:4px;">
                        <!-- Icon Circle -->
                        <div style="
                            width: 76px; height: 76px;
                            background: #006c49;
                            border-radius: 22px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 12px 28px -6px rgba(0,108,73,0.45);
                            margin-bottom: 16px;
                        ">
                            <span class="material-symbols-outlined" style="font-size:42px;color:white">${iconName}</span>
                        </div>

                        <!-- Badge Tag -->
                        <span style="
                            display:inline-flex;align-items:center;gap:6px;
                            padding:4px 14px;border-radius:999px;
                            background:rgba(0,108,73,0.12);color:#006c49;
                            font-size:11px;font-weight:800;letter-spacing:0.05em;text-transform:uppercase;
                            margin-bottom:10px;border:1px solid rgba(0,108,73,0.25);
                        ">
                            <span class="material-symbols-outlined" style="font-size:14px">verified</span>
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
                            border-radius:18px;
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
