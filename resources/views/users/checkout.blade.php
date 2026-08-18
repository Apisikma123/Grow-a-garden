@extends('layouts.app')

@section('title', 'Secure Checkout — Grow a Garden')

@section('content')
<style>
    body { background-color: #f8faf9; }
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 24px 48px rgba(0, 108, 73, 0.08);
    }
    .premium-gradient-subur { background: linear-gradient(135deg, #004d34 0%, #006c49 100%); }
    .premium-gradient-pro { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    @keyframes checkmark-draw {
        0% { stroke-dashoffset: 100; }
        100% { stroke-dashoffset: 0; }
    }
    @keyframes success-scale {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }
    .success-circle { animation: success-scale 0.5s ease-out forwards; }
    .success-check { stroke-dasharray: 100; stroke-dashoffset: 100; animation: checkmark-draw 0.6s ease-out 0.4s forwards; }

    @keyframes pulse-ring {
        0% { transform: scale(0.9); opacity: 0.5; }
        100% { transform: scale(1.3); opacity: 0; }
    }
    .dev-badge-pulse { animation: pulse-ring 2s ease-out infinite; }
</style>

<div class="min-h-screen flex items-center justify-center p-4 md:p-8">
    
    {{-- Main Checkout Card --}}
    <div id="checkout-card" class="w-full max-w-[1200px] flex flex-col md:flex-row rounded-[32px] overflow-hidden glass-card relative z-10 transition-colors duration-500">
        
        {{-- Left: Order Summary & Benefits --}}
        <div id="left-panel" class="w-full md:w-[40%] shrink-0 min-w-0 premium-gradient-subur p-8 md:p-12 text-white relative overflow-hidden flex flex-col transition-colors duration-500">
            <div class="absolute inset-0 bg-pattern"></div>
            <div id="glow-1" class="absolute -bottom-24 -left-24 w-64 h-64 bg-[#10b981] rounded-full mix-blend-multiply filter blur-3xl opacity-50 transition-colors duration-500"></div>
            <div id="glow-2" class="absolute -top-24 -right-24 w-64 h-64 bg-[#059669] rounded-full mix-blend-multiply filter blur-3xl opacity-50 transition-colors duration-500"></div>
            
            <div class="relative z-10">
                @php
                    $backUrl = '/settings#subscription';
                    $backText = 'Kembali ke Pengaturan';
                    if (request()->query('from')) {
                        $fromPath = request()->query('from');
                        if ($fromPath === 'settings') {
                            $backUrl = '/settings#subscription';
                            $backText = 'Kembali ke Pengaturan';
                        } elseif ($fromPath === '/' || $fromPath === '') {
                            $backUrl = '/';
                            $backText = 'Kembali ke Landing Page';
                        } else {
                            $backUrl = '/' . ltrim($fromPath, '/');
                            if (str_contains($fromPath, 'growth-calendar')) {
                                $backText = 'Kembali ke Kalender';
                            } elseif (str_contains($fromPath, 'care-tasks')) {
                                $backText = 'Kembali ke Perawatan';
                            } elseif (str_contains($fromPath, 'gardens')) {
                                $backText = 'Kembali ke Kebun Saya';
                            } elseif (str_contains($fromPath, 'dashboard')) {
                                $backText = 'Kembali ke Dashboard';
                            } else {
                                $backText = 'Kembali';
                            }
                        }
                    }
                @endphp
                <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition-colors mb-10 font-medium text-sm">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    {{ $backText }}
                </a>
                
                <div id="plan-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-400/20 text-yellow-300 text-[11px] font-black uppercase tracking-widest mb-4 border border-yellow-400/30">
                    <span class="material-symbols-outlined text-[14px]">star</span>
                    Premium Plan
                </div>
                
                <h1 id="plan-name" class="text-[32px] md:text-[40px] font-black leading-tight mb-2">Paket Subur</h1>
                <p id="plan-desc" class="text-white/80 text-[15px] mb-8 leading-relaxed font-medium">Investasi terbaik untuk panen yang konsisten.</p>

                {{-- Billing Toggle --}}
                <div class="bg-black/20 backdrop-blur-md rounded-full p-1.5 mb-10 inline-grid grid-cols-2 relative border border-white/10 shadow-inner w-full max-w-[400px]">
                    <button id="btn-monthly" class="relative z-10 py-3 px-4 text-[16px] font-bold rounded-full text-white/50 hover:text-white transition-colors duration-300 flex items-center justify-center">Monthly</button>
                    <button id="btn-yearly" class="relative z-10 py-3 px-4 text-[16px] font-bold rounded-full text-white transition-colors duration-300 flex items-center justify-center gap-2">
                        Yearly <span class="bg-[#ffb200] text-[#4d2a00] text-[10px] px-2.5 py-1 rounded-full font-black uppercase tracking-wider hidden md:flex items-center shadow-sm leading-none shrink-0">SAVE 40%</span>
                    </button>
                    <div id="toggle-pill" class="absolute top-1.5 bottom-1.5 left-1.5 bg-white/20 rounded-full transition-transform duration-300 ease-out shadow-sm border border-white/10" style="width: calc(50% - 6px); transform: translateX(100%);"></div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-white/80 font-medium">Langganan</span>
                        <span id="summary-cycle" class="font-bold text-right ml-4">Annual (Hemat 40%)</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-white/80 font-medium">Total Pembayaran</span>
                        <span id="summary-price" class="text-[24px] font-black text-yellow-400">Rp 199.000</span>
                    </div>
                    <div class="h-px bg-white/20 w-full my-4"></div>
                    <div class="flex items-center gap-2 text-[12px] text-white/70">
                        <span class="material-symbols-outlined text-[16px] shrink-0">autorenew</span>
                        <span id="summary-renew-text">Perpanjangan otomatis tahunan. Batalkan kapan saja.</span>
                    </div>
                </div>

                <div class="space-y-5" id="features-list">
                    <!-- Features injected via JS -->
                </div>
            </div>
        </div>

        {{-- Right: Payment Form --}}
        <div class="w-full md:w-[60%] shrink-0 min-w-0 bg-white/60 p-8 md:p-12 relative flex flex-col justify-center">
            
            {{-- Normal State: Payment Button --}}
            <div id="payment-form">
                <div class="mb-6">
                    <h2 class="text-[24px] font-black text-slate-800 mb-2">Konfirmasi Pembayaran</h2>
                    <p class="text-[14px] text-slate-500 font-medium">Anda masuk sebagai <strong>{{ Auth::user()->email }}</strong></p>
                </div>

                {{-- Dev Mode Banner --}}
                <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-5 mb-8 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-200 rounded-full opacity-40 dev-badge-pulse"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="w-10 h-10 bg-amber-400 rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px] text-amber-900">code</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-amber-900 text-[14px] mb-1">Development Mode</h3>
                            <p class="text-[13px] text-amber-700 leading-relaxed">Payment gateway belum aktif. Klik tombol di bawah untuk langsung mengaktifkan paket tanpa pembayaran nyata.</p>
                        </div>
                    </div>
                </div>

                {{-- Current Plan Info --}}
                @if(Auth::user()->role !== 'free')
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 mb-8">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[24px] text-emerald-600">verified</span>
                        <div>
                            <p class="text-[14px] font-bold text-emerald-800">Anda sudah berlangganan: {{ Auth::user()->planName() }}</p>
                            <p class="text-[12px] text-emerald-600">Upgrade ini akan menggantikan paket Anda saat ini.</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Payment Summary --}}
                <div class="bg-slate-50 rounded-2xl p-5 mb-8 border border-slate-200">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[13px] text-slate-500 font-medium">Paket</span>
                        <span id="confirm-plan-name" class="text-[14px] font-bold text-slate-800">Paket Subur</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[13px] text-slate-500 font-medium">Siklus</span>
                        <span id="confirm-cycle" class="text-[14px] font-bold text-slate-800">Tahunan</span>
                    </div>
                    <div class="h-px bg-slate-200 my-3"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-[14px] text-slate-800 font-bold">Total</span>
                        <span id="confirm-price" class="text-[20px] font-black text-[#006c49]">Rp 199.000</span>
                    </div>
                </div>

                <button type="button" id="btn-submit-pay" class="w-full bg-[#006c49] text-white font-bold py-4 rounded-xl hover:bg-[#005236] active:scale-[0.98] transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] text-[16px] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
                    <span id="pay-button-text">Aktifkan Paket — Rp 199.000</span>
                </button>
                <p class="text-center text-[12px] text-slate-400 mt-4 font-medium flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">info</span> Mode Development — tidak ada biaya nyata.
                </p>
            </div>

            {{-- Loading State --}}
            <div id="payment-loading" class="hidden flex-col items-center justify-center py-12 w-full">
                <x-brand-loader size="lg" text="Memproses transaksi & aktivasi paket..." />
            </div>

            {{-- Success State --}}
            <div id="payment-success" class="hidden flex-col w-full max-w-lg mx-auto py-4">
                <div class="w-full bg-white rounded-3xl p-8 shadow-2xl border border-emerald-100 text-center relative overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                    
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-[40px] text-emerald-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        </div>
                        <h3 class="text-[24px] font-black text-slate-800 mb-2">Pembayaran Berhasil!</h3>
                        <p id="success-message" class="text-[14px] text-slate-500 mb-6">Paket telah berhasil diaktifkan.</p>
                        
                        <div class="bg-emerald-50/50 rounded-2xl p-5 text-left space-y-4 mb-8">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-emerald-600 mt-0.5 text-[20px]">task_alt</span>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-700">Role Akun Diperbarui</p>
                                    <p class="text-[12px] text-slate-500">Akses fitur premium telah dibuka</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-emerald-600 mt-0.5 text-[20px]">event_available</span>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-700">Masa Aktif Paket</p>
                                    <p class="text-[12px] text-slate-500" id="success-valid-until-date">—</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3" id="success-autopilot-container" style="display: none;">
                                <span class="material-symbols-outlined text-emerald-600 mt-0.5 text-[20px]">checklist</span>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-700">Jadwal Perawatan Aktif</p>
                                    <p class="text-[12px] text-slate-500" id="success-autopilot-text"></p>
                                </div>
                            </div>
                        </div>

                        <a href="/gardens" class="w-full bg-[#006c49] text-white font-bold py-3.5 rounded-xl hover:bg-[#005236] transition-all shadow-md text-[15px] flex items-center justify-center gap-2">
                            Lanjutkan ke Kebun Saya
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedPlan = urlParams.get('plan') === 'pro' ? 'pro' : 'subur';
    let isYearly = true;

    const plans = {
        subur: {
            name: "Paket Subur",
            desc: "Investasi terbaik untuk panen yang konsisten. Unlock fitur perawatan otomatis dan cuaca cerdas.",
            monthlyPrice: 29000,
            yearlyPrice: 199000,
            monthlyPriceLabel: "Rp 29.000",
            yearlyPriceLabel: "Rp 199.000",
            badgeText: "Premium Plan",
            badgeIcon: "star",
            theme: "premium-gradient-subur",
            glow1: "#10b981",
            glow2: "#059669",
            features: [
                { icon: "calendar_month", title: "Growth Calendar", desc: "Jadwal estimasi tanam hingga panen interaktif." },
                { icon: "cloud_done", title: "Weather Adjustment", desc: "Anti gagal panen karena cuaca ekstrem." },
                { icon: "all_inclusive", title: "Kapasitas Ekstra", desc: "Hingga 10 kebun dan 100 tanaman aktif." }
            ]
        },
        pro: {
            name: "Panen Raya (Premium)",
            desc: "Skalabilitas maksimal untuk power user. Ideal untuk urban farming dan komunitas besar.",
            monthlyPrice: 99000,
            yearlyPrice: 799000,
            monthlyPriceLabel: "Rp 99.000",
            yearlyPriceLabel: "Rp 799.000",
            badgeText: "Premium Plan",
            badgeIcon: "workspace_premium",
            theme: "premium-gradient-pro",
            glow1: "#3b82f6",
            glow2: "#8b5cf6",
            features: [
                { icon: "all_inclusive", title: "Kapasitas Maksimal", desc: "Maksimal 100 Kebun & Tanaman Tanpa Batas." },
                { icon: "calendar_month", title: "Growth Calendar", desc: "Jadwal estimasi tanam hingga panen interaktif." },
                { icon: "cloud_done", title: "Weather Adjustment", desc: "Anti gagal panen karena cuaca ekstrem." },
                { icon: "emoji_events", title: "Harvest Notification", desc: "Notifikasi panen di dashboard." },
                { icon: "history", title: "Activity Log Tanpa Batas", desc: "Tracking historis tanpa batas sepanjang masa." }
            ]
        }
    };

    const currentPlanData = plans[selectedPlan];

    // DOM Elements
    const leftPanel = document.getElementById('left-panel');
    const glow1 = document.getElementById('glow-1');
    const glow2 = document.getElementById('glow-2');
    const planBadge = document.getElementById('plan-badge');
    const planName = document.getElementById('plan-name');
    const planDesc = document.getElementById('plan-desc');
    const summaryCycle = document.getElementById('summary-cycle');
    const summaryPrice = document.getElementById('summary-price');
    const summaryRenewText = document.getElementById('summary-renew-text');
    const payButtonText = document.getElementById('pay-button-text');
    const featuresList = document.getElementById('features-list');
    const confirmPlanName = document.getElementById('confirm-plan-name');
    const confirmCycle = document.getElementById('confirm-cycle');
    const confirmPrice = document.getElementById('confirm-price');
    
    const btnMonthly = document.getElementById('btn-monthly');
    const btnYearly = document.getElementById('btn-yearly');
    const togglePill = document.getElementById('toggle-pill');

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function renderFeatures() {
        featuresList.innerHTML = currentPlanData.features.map(f => `
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0 border border-white/20">
                    <span class="material-symbols-outlined text-[18px] text-yellow-400">${f.icon}</span>
                </div>
                <div>
                    <h4 class="font-bold text-[15px] mb-0.5">${f.title}</h4>
                    <p class="text-[13px] text-white/70">${f.desc}</p>
                </div>
            </div>
        `).join('');
    }

    function getPrice() {
        return isYearly ? currentPlanData.yearlyPrice : currentPlanData.monthlyPrice;
    }

    function getPriceLabel() {
        return isYearly ? currentPlanData.yearlyPriceLabel : currentPlanData.monthlyPriceLabel;
    }

    const userRole = '{{ Auth::check() ? Auth::user()->role : "free" }}';
    const isUpgrade = (userRole === 'pro' && selectedPlan === 'pro');

    function updatePricingUI() {
        let price = isYearly ? currentPlanData.yearlyPrice : currentPlanData.monthlyPrice;
        let discount = 0;

        if (isUpgrade) {
            discount = isYearly ? plans.subur.yearlyPrice : plans.subur.monthlyPrice;
        }

        const finalPrice = Math.max(0, price - discount);
        const finalPriceLabel = formatRupiah(finalPrice);

        if (isYearly) {
            summaryCycle.textContent = isUpgrade ? "Tahunan (Potongan Upgrade Subur)" : "Tahunan (Hemat 40%)";
            summaryRenewText.textContent = "Perpanjangan otomatis tahunan. Batalkan kapan saja.";
            confirmCycle.textContent = "Tahunan";
            
            togglePill.style.transform = "translateX(100%)";
            btnYearly.classList.add('text-white');
            btnYearly.classList.remove('text-white/50');
            btnMonthly.classList.remove('text-white');
            btnMonthly.classList.add('text-white/50');
        } else {
            summaryCycle.textContent = isUpgrade ? "Bulanan (Potongan Upgrade Subur)" : "Bulanan";
            summaryRenewText.textContent = "Perpanjangan otomatis bulanan. Batalkan kapan saja.";
            confirmCycle.textContent = "Bulanan";
            
            togglePill.style.transform = "translateX(0)";
            btnMonthly.classList.add('text-white');
            btnMonthly.classList.remove('text-white/50');
            btnYearly.classList.remove('text-white');
            btnYearly.classList.add('text-white/50');
        }

        if (isUpgrade) {
            summaryPrice.innerHTML = `${finalPriceLabel} <span class="text-[12px] font-medium text-yellow-200 block">Dipotong Paket Subur: -${formatRupiah(discount)}</span>`;
        } else {
            summaryPrice.textContent = finalPriceLabel;
        }

        payButtonText.textContent = isUpgrade ? `Upgrade Paket — ${finalPriceLabel}` : `Aktifkan Paket — ${finalPriceLabel}`;
        confirmPlanName.textContent = isUpgrade ? `${currentPlanData.name} (Upgrade)` : currentPlanData.name;
        confirmPrice.textContent = finalPriceLabel;
    }

    function initPlan() {
        leftPanel.className = `w-full md:w-[40%] shrink-0 min-w-0 ${currentPlanData.theme} p-8 md:p-12 text-white relative overflow-hidden flex flex-col transition-colors duration-500`;
        glow1.style.backgroundColor = currentPlanData.glow1;
        glow2.style.backgroundColor = currentPlanData.glow2;

        planBadge.innerHTML = `<span class="material-symbols-outlined text-[14px]">${currentPlanData.badgeIcon}</span> ${currentPlanData.badgeText}`;
        planName.textContent = currentPlanData.name;
        planDesc.textContent = currentPlanData.desc;
        
        renderFeatures();
        updatePricingUI();
    }

    // Toggle Listeners
    btnMonthly.addEventListener('click', () => {
        isYearly = false;
        updatePricingUI();
    });

    btnYearly.addEventListener('click', () => {
        isYearly = true;
        updatePricingUI();
    });

    // === PAYMENT SUBMISSION ===
    document.getElementById('btn-submit-pay').addEventListener('click', async () => {
        const paymentForm = document.getElementById('payment-form');
        const paymentLoading = document.getElementById('payment-loading');
        const paymentSuccess = document.getElementById('payment-success');

        // Show loading
        paymentForm.classList.add('hidden');
        paymentLoading.classList.remove('hidden');
        paymentLoading.classList.add('flex');

        try {
            const response = await fetch('/api/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    plan: selectedPlan,
                    billing_cycle: isYearly ? 'yearly' : 'monthly',
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }

            // Simulate brief processing delay for UX
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Show success
            paymentLoading.classList.add('hidden');
            paymentLoading.classList.remove('flex');
            paymentSuccess.classList.remove('hidden');
            paymentSuccess.classList.add('flex');

            document.getElementById('success-message').textContent = data.message;
            
            const validUntilEl = document.getElementById('success-valid-until-date');
            if (validUntilEl) {
                validUntilEl.textContent = data.subscription.valid_until;
            }
            
            if (data.autopilot_tasks_generated > 0) {
                const autopilotContainer = document.getElementById('success-autopilot-container');
                const autopilotText = document.getElementById('success-autopilot-text');
                if (autopilotContainer && autopilotText) {
                    autopilotContainer.style.display = 'flex';
                    autopilotText.textContent = `${data.autopilot_tasks_generated} tugas perawatan otomatis telah dijadwalkan!`;
                }
            }

            // Premium Upgrade Celebration
            if (window.Alert && Alert.premium) {
                const planKey = selectedPlan === 'pro' ? 'Panen Raya (Premium)' : 'Subur (Pro)';
                const planDesc = selectedPlan === 'pro'
                    ? 'Maksimal 100 kebun dan tanaman tanpa batas.'
                    : 'Kalender Tanam, Weather Adjustment, dan Jadwal Perawatan Otomatis sudah aktif.';
                setTimeout(() => {
                    Alert.premium.celebrate(planKey, planDesc);
                }, 500);
            }

        } catch (error) {
            // Show error and restore form
            paymentLoading.classList.add('hidden');
            paymentLoading.classList.remove('flex');
            paymentForm.classList.remove('hidden');

            alert('Error: ' + error.message);
        }
    });

    // Initialize
    initPlan();
});
</script>
@endsection
