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
    
    .input-premium {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0, 108, 73, 0.15);
        transition: all 0.3s ease;
    }
    .input-premium:focus {
        background: #ffffff;
        border-color: #006c49;
        box-shadow: 0 0 0 4px rgba(0, 108, 73, 0.1);
        outline: none;
    }
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Toggle Switch */
    .toggle-checkbox:checked { right: 0; border-color: #006c49; }
    .toggle-checkbox:checked + .toggle-label { background-color: #006c49; }
    .toggle-checkbox:checked + .toggle-label:after { transform: translateX(100%); }
</style>

<div class="min-h-screen flex items-center justify-center p-4 md:p-8 font-['Poppins']">
    
    <div class="w-full max-w-[1100px] flex flex-col md:flex-row rounded-[32px] overflow-hidden glass-card relative z-10 transition-colors duration-500" id="checkout-card">
        
        {{-- Left: Order Summary & Benefits --}}
        <div id="left-panel" class="w-full md:w-[45%] premium-gradient-subur p-8 md:p-12 text-white relative overflow-hidden flex flex-col transition-colors duration-500">
            <div class="absolute inset-0 bg-pattern"></div>
            <div id="glow-1" class="absolute -bottom-24 -left-24 w-64 h-64 bg-[#10b981] rounded-full mix-blend-multiply filter blur-3xl opacity-50 transition-colors duration-500"></div>
            <div id="glow-2" class="absolute -top-24 -right-24 w-64 h-64 bg-[#059669] rounded-full mix-blend-multiply filter blur-3xl opacity-50 transition-colors duration-500"></div>
            
            <div class="relative z-10">
                <a href="/#pricing" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition-colors mb-10 font-medium text-sm">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Pricing
                </a>
                
                <div id="plan-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-400/20 text-yellow-300 text-[11px] font-black uppercase tracking-widest mb-4 border border-yellow-400/30">
                    <span class="material-symbols-outlined text-[14px]">star</span>
                    Premium Plan
                </div>
                
                <h1 id="plan-name" class="text-[32px] md:text-[40px] font-black leading-tight mb-2">Paket Subur</h1>
                <p id="plan-desc" class="text-white/80 text-[15px] mb-8 leading-relaxed font-medium">Investasi terbaik untuk panen yang konsisten. Unlock semua fitur autopilot dan cuaca cerdas.</p>

                {{-- Billing Toggle --}}
                <div class="bg-black/20 backdrop-blur-md rounded-full p-1.5 mb-10 inline-grid grid-cols-2 relative border border-white/10 shadow-inner w-auto">
                    <button id="btn-monthly" class="relative z-10 py-3 px-8 text-[16px] font-bold rounded-full text-white/50 hover:text-white transition-colors duration-300 flex items-center justify-center">Monthly</button>
                    <button id="btn-yearly" class="relative z-10 py-3 px-8 text-[16px] font-bold rounded-full text-white transition-colors duration-300 flex items-center justify-center gap-2">
                        Yearly <span class="bg-[#ffb200] text-[#4d2a00] text-[10px] px-2.5 py-1 rounded-full font-black uppercase tracking-wider hidden md:flex items-center shadow-sm leading-none">SAVE 40%</span>
                    </button>
                    {{-- Active Pill --}}
                    <div id="toggle-pill" class="absolute top-1.5 bottom-1.5 left-1.5 bg-white/20 rounded-full transition-transform duration-300 ease-out shadow-sm border border-white/10" style="width: calc(50% - 6px); transform: translateX(100%);"></div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-white/80 font-medium">Subscription</span>
                        <span id="summary-cycle" class="font-bold">Annual (Save 40%)</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-white/80 font-medium">Billed Today</span>
                        <span id="summary-price" class="text-[24px] font-black text-yellow-400">Rp 199.000</span>
                    </div>
                    <div class="h-px bg-white/20 w-full my-4"></div>
                    <div class="flex items-center gap-2 text-[12px] text-white/70">
                        <span class="material-symbols-outlined text-[16px]">autorenew</span>
                        <span id="summary-renew-text">Auto-renews yearly. Cancel anytime.</span>
                    </div>
                </div>

                <div class="space-y-5" id="features-list">
                    <!-- Features injected via JS -->
                </div>
            </div>
        </div>

        {{-- Right: Payment Form --}}
        <div class="w-full md:w-[55%] bg-white/60 p-8 md:p-12 relative flex flex-col justify-center">
            
            <div class="mb-8">
                <h2 class="text-[24px] font-black text-slate-800 mb-2">Payment Details</h2>
                <p class="text-[14px] text-slate-500 font-medium">Complete your purchase safely and securely.</p>
            </div>

            {{-- Express Checkout --}}
            <div class="flex gap-4 mb-8">
                <button class="flex-1 bg-black text-white h-12 rounded-xl flex items-center justify-center gap-2 hover:bg-slate-800 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">apple</span> <span class="font-bold">Pay</span>
                </button>
                <button class="flex-1 bg-white border border-slate-200 text-slate-800 h-12 rounded-xl flex items-center justify-center gap-2 hover:bg-slate-50 transition-colors shadow-sm">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="w-5 h-5"> <span class="font-bold">Pay</span>
                </button>
            </div>

            <div class="flex items-center gap-4 mb-8">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">Or pay with card</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <form class="space-y-6">
                <div>
                    <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" class="w-full input-premium rounded-xl px-5 py-4 text-[15px] text-slate-800 font-medium" placeholder="you@example.com" value="greenthumb@example.com">
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Card Information</label>
                    <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm focus-within:border-[#006c49] focus-within:ring-1 focus-within:ring-[#006c49] transition-all bg-white">
                        <div class="border-b border-slate-200">
                            <input type="text" class="w-full px-5 py-4 text-[15px] text-slate-800 font-medium placeholder-slate-400 outline-none bg-transparent" placeholder="Card number">
                        </div>
                        <div class="flex">
                            <div class="w-1/2 border-r border-slate-200">
                                <input type="text" class="w-full px-5 py-4 text-[15px] text-slate-800 font-medium placeholder-slate-400 outline-none bg-transparent" placeholder="MM / YY">
                            </div>
                            <div class="w-1/2 relative">
                                <input type="text" class="w-full px-5 py-4 text-[15px] text-slate-800 font-medium placeholder-slate-400 outline-none bg-transparent" placeholder="CVC">
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-[20px]">credit_card</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-2">Cardholder Name</label>
                    <input type="text" class="w-full input-premium rounded-xl px-5 py-4 text-[15px] text-slate-800 font-medium" placeholder="Name on card">
                </div>

                <div class="pt-4">
                    <button type="button" id="btn-submit-pay" class="w-full bg-[#006c49] text-white font-bold py-4 rounded-xl hover:bg-[#005236] active:scale-[0.98] transition-all shadow-[0_8px_24px_rgba(0,108,73,0.25)] text-[16px] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">lock</span> <span id="pay-button-text">Pay Rp 199.000</span>
                    </button>
                    <p class="text-center text-[12px] text-slate-400 mt-4 font-medium flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">encrypted</span> Payments are securely encrypted.
                    </p>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedPlan = urlParams.get('plan') === 'pro' ? 'pro' : 'subur';
    let isYearly = true; // Default to yearly for savings

    const plans = {
        subur: {
            name: "Paket Subur",
            desc: "Investasi terbaik untuk panen yang konsisten. Unlock semua fitur autopilot dan cuaca cerdas.",
            monthlyPrice: "Rp 29.000",
            yearlyPrice: "Rp 199.000",
            badgeText: "Premium Plan",
            badgeIcon: "star",
            theme: "premium-gradient-subur",
            glow1: "#10b981",
            glow2: "#059669",
            features: [
                { icon: "smart_toy", title: "Asisten Autopilot", desc: "Otomatisasi jadwal berdasarkan AI." },
                { icon: "cloud_done", title: "Weather Adjustment", desc: "Anti gagal panen karena cuaca ekstrem." },
                { icon: "all_inclusive", title: "Kapasitas Ekstra", desc: "Hingga 50 plot dan 100 tanaman aktif." }
            ]
        },
        pro: {
            name: "Panen Raya (Pro)",
            desc: "Skalabilitas maksimal untuk power user. Ideal untuk urban farming dan komunitas besar.",
            monthlyPrice: "Rp 99.000",
            yearlyPrice: "Rp 799.000",
            badgeText: "Pro Plan",
            badgeIcon: "workspace_premium",
            theme: "premium-gradient-pro",
            glow1: "#3b82f6",
            glow2: "#8b5cf6",
            features: [
                { icon: "all_inclusive", title: "Unlimited Segala", desc: "Plot & tanaman tanpa batas." },
                { icon: "history", title: "Activity Log", desc: "Tracking historis tanpa batas sepanjang masa." },
                { icon: "groups", title: "Dukungan Komunitas", desc: "Akses prioritas CS dan fitur multi-user." }
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
    
    const btnMonthly = document.getElementById('btn-monthly');
    const btnYearly = document.getElementById('btn-yearly');
    const togglePill = document.getElementById('toggle-pill');

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

    function updatePricingUI() {
        if (isYearly) {
            summaryCycle.textContent = "Annual (Save 40%)";
            summaryPrice.textContent = currentPlanData.yearlyPrice;
            summaryRenewText.textContent = "Auto-renews yearly. Cancel anytime.";
            payButtonText.textContent = `Pay ${currentPlanData.yearlyPrice}`;
            
            togglePill.style.transform = "translateX(100%)";
            btnYearly.classList.add('text-white');
            btnYearly.classList.remove('text-white/50');
            btnMonthly.classList.remove('text-white');
            btnMonthly.classList.add('text-white/50');
        } else {
            summaryCycle.textContent = "Monthly";
            summaryPrice.textContent = currentPlanData.monthlyPrice;
            summaryRenewText.textContent = "Auto-renews monthly. Cancel anytime.";
            payButtonText.textContent = `Pay ${currentPlanData.monthlyPrice}`;
            
            togglePill.style.transform = "translateX(0)";
            btnMonthly.classList.add('text-white');
            btnMonthly.classList.remove('text-white/50');
            btnYearly.classList.remove('text-white');
            btnYearly.classList.add('text-white/50');
        }
    }

    function initPlan() {
        // Theme
        leftPanel.className = `w-full md:w-[45%] ${currentPlanData.theme} p-8 md:p-12 text-white relative overflow-hidden flex flex-col transition-colors duration-500`;
        glow1.style.backgroundColor = currentPlanData.glow1;
        glow2.style.backgroundColor = currentPlanData.glow2;

        // Content
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

    // Initialize
    initPlan();
});
</script>
@endsection
