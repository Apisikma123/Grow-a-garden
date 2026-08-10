@extends('layouts.dashboard')

@section('title', 'Pengaturan — Grow a Garden')
@section('description', 'Kelola akun, profil, dan pengaturan langganan Anda.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        <div>
            <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight mb-2">Pengaturan</h1>
            <p class="text-[16px] text-on-surface-variant leading-[24px]">Kelola preferensi akun dan paket langganan Anda.</p>
        </div>

        <div class="max-w-[800px] w-full mx-auto">
            
            @if(session('success'))
                <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-xl text-sm font-bold border border-[#bbf7d0] mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#fee2e2] text-[#991b1b] px-4 py-3 rounded-xl text-sm font-bold border border-[#fecaca] mb-4">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Main Settings Content --}}
            <div class="space-y-[24px]">
                
                {{-- Profile Settings Box --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-[24px] font-bold text-on-surface mb-6">Pengaturan Profil</h2>
                    
                    <form action="{{ route('settings.profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-[32px]">
                            <div class="flex flex-col items-center gap-4">
                                <div class="relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                                    <div class="w-24 h-24 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden border-4 border-surface shadow-sm group-hover:border-primary-container transition-colors duration-300">
                                        @if(Auth::user()->avatar)
                                            <img id="avatar-preview" src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            <img id="avatar-preview" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                                            <span id="avatar-icon" class="material-symbols-outlined text-[40px] text-on-surface-variant group-hover:scale-110 transition-transform duration-300">person</span>
                                        @endif
                                    </div>
                                    <div class="absolute inset-0 bg-on-surface/20 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                                        <span class="material-symbols-outlined text-surface">photo_camera</span>
                                    </div>
                                </div>
                                <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/jpeg,image/png,image/webp">
                                <button type="button" onclick="document.getElementById('avatar-input').click()" class="text-primary text-[14px] font-bold hover:opacity-80 transition-opacity">Ganti Foto</button>
                            </div>
                            
                            <div class="flex-1 space-y-[16px]">
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Alamat Email</label>
                                    <input type="email" value="{{ Auth::user()->email }}" class="w-full surface-recessed border border-outline-variant/50 bg-surface-container-lowest rounded-[12px] px-4 py-3 text-[16px] text-on-surface-variant focus:outline-none transition-all cursor-not-allowed" readonly>
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Nomor Telepon</label>
                                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="08xxxxxxxxxx" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Lokasi Kebun (Weather Adjustment)</label>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">location_on</span>
                                            <input type="text" id="garden-location" placeholder="Pilih atau deteksi lokasi..." value="{{ Auth::user()->province ? Auth::user()->province . ', Indonesia' : '' }}" class="w-full surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" readonly>
                                            <input type="hidden" name="province" id="hidden-province" value="{{ Auth::user()->province }}">
                                        </div>
                                        <button type="button" id="btn-detect-location" class="bg-surface-container-high text-primary hover:bg-primary/10 border border-outline-variant rounded-[12px] px-4 flex items-center justify-center gap-1.5 transition-all duration-300 font-bold text-[14px] whitespace-nowrap active:scale-95">
                                            <span class="material-symbols-outlined text-[20px]" id="detect-icon">my_location</span>
                                            Deteksi
                                        </button>
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Pilih Provinsi Manual (Alternatif)</label>
                                    <select id="manual-province" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @php
                                            $provinces = ['Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi', 'Sumatera Selatan', 'Bangka Belitung', 'Bengkulu', 'Lampung', 'DKI Jakarta', 'Jawa Barat', 'Banten', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara', 'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Maluku', 'Maluku Utara', 'Papua Barat', 'Papua'];
                                        @endphp
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov }}" {{ Auth::user()->province == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Bahasa / Language</label>
                                    <select name="language" id="app-language" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                        <option value="id" {{ Auth::user()->language == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                        <option value="en" {{ Auth::user()->language == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2">Role Akun</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="bg-primary-container text-on-primary-container px-3 py-1.5 rounded-full text-[13px] font-bold tracking-wide">
                                            {{ ucfirst(Auth::user()->role ?? 'Free User') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Password</label>
                                    <div class="flex items-center justify-between surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 transition-all">
                                        <span class="text-[16px] text-on-surface-variant tracking-[0.2em] font-medium mt-1">••••••••</span>
                                        <a href="{{ route('settings.password') }}" class="text-primary text-[14px] font-bold hover:underline active:scale-95 transition-all">
                                            Ganti Password
                                        </a>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="bg-primary text-on-primary px-6 py-3 rounded-full text-[14px] font-bold hover:-translate-y-0.5 hover:shadow-lg active:scale-95 transition-all duration-300">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Prestasi & Badge Kebun (Gamification Showcase) --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-[24px] font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[28px]">workspace_premium</span>
                                Prestasi & Badge Kebun
                            </h2>
                            <p class="text-[14px] text-on-surface-variant">Selesaikan tugas perawatan untuk membuka badge langka!</p>
                        </div>

                        @php
                            $unlockedCount = count($userBadgeIds ?? []);
                            $progressPct = $totalBadgeCount > 0 ? round(($unlockedCount / $totalBadgeCount) * 100) : 0;
                        @endphp
                        <div class="bg-primary/10 text-primary px-4 py-2 rounded-2xl border border-primary/20 flex flex-col sm:items-end">
                            <span class="text-[12px] font-bold uppercase tracking-wider">Koleksi Terbuka</span>
                            <span class="text-[18px] font-black text-primary">{{ $unlockedCount }} / {{ $totalBadgeCount }} Badge ({{ $progressPct }}%)</span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-surface-container-high h-2.5 rounded-full overflow-hidden mb-6">
                        <div class="bg-gradient-to-r from-primary to-tertiary h-full rounded-full transition-all duration-500" style="width: {{ $progressPct }}%;"></div>
                    </div>

                    {{-- Badges Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($displayBadges as $badge)
                            @php
                                $isEarned = in_array($badge->id, $userBadgeIds ?? []);
                            @endphp
                            <div class="rounded-2xl p-4 border transition-all relative overflow-hidden flex flex-col justify-between {{ $isEarned ? 'bg-gradient-to-br from-primary/10 to-tertiary/10 border-primary/40 shadow-sm' : 'bg-surface-container-low border-outline-variant/30 opacity-60 grayscale' }}">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm shrink-0 {{ $isEarned ? 'bg-primary text-on-primary shadow-primary/20' : 'bg-surface-container-high text-on-surface-variant' }}">
                                        <span class="material-symbols-outlined text-[26px]">{{ $badge->icon_url ?? 'military_tech' }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-[15px] font-bold text-on-surface leading-tight">{{ $badge->name }}</h4>
                                        @if($isEarned)
                                            <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md mt-1">
                                                <span class="material-symbols-outlined text-[12px]">check_circle</span> Terbuka
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-on-surface-variant bg-surface-container-highest px-2 py-0.5 rounded-md mt-1">
                                                <span class="material-symbols-outlined text-[12px]">lock</span> Terkunci
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-[12px] text-on-surface-variant font-medium leading-relaxed flex-grow">{{ $badge->description }}</p>
                                
                                <div class="mt-3 pt-3 border-t border-outline-variant/20 flex items-center justify-between">
                                    @php
                                        $globalPct = number_format(($badge->users_count / $totalUsers) * 100, 1);
                                        $rarityColor = $globalPct < 10 ? 'text-primary font-black' : ($globalPct < 50 ? 'text-secondary font-bold' : 'text-on-surface-variant');
                                    @endphp
                                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $rarityColor }}">
                                        Dimiliki {{ $globalPct }}% pemain
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('badges') }}" class="inline-flex items-center gap-2 bg-surface-container-low hover:bg-surface-container-high text-on-surface border border-outline-variant/50 px-6 py-3 rounded-full font-bold text-[14px] transition-all">
                            Lihat Semua Lencana <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                {{-- Subscription / Langganan Box --}}
                <div id="subscription" class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[24px] font-bold text-on-surface">Paket Langganan</h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider
                            {{ Auth::user()->role === 'premium' ? 'bg-[#3b82f6]/10 text-[#3b82f6] border border-[#3b82f6]/20' : 
                               (Auth::user()->role === 'pro' ? 'bg-primary/10 text-primary border border-primary/20' : 
                               'bg-surface-container-high text-on-surface-variant border border-outline-variant/30') }}">
                            <span class="material-symbols-outlined text-[14px]">
                                {{ Auth::user()->role === 'premium' ? 'workspace_premium' : (Auth::user()->role === 'pro' ? 'star' : 'eco') }}
                            </span>
                            {{ Auth::user()->planName() }}
                        </span>
                    </div>

                    {{-- Current Plan Card --}}
                    <div class="rounded-2xl p-5 mb-5 border
                        {{ Auth::user()->role === 'free' ? 'bg-surface-container-low border-outline-variant/20' : 
                           (Auth::user()->role === 'premium' ? 'bg-gradient-to-r from-[#0f172a] to-[#1e293b] border-[#3b82f6]/30 text-white' : 
                           'bg-gradient-to-r from-[#004d34] to-[#006c49] border-primary/30 text-white') }}">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-symbols-outlined text-[24px] {{ Auth::user()->role === 'free' ? 'text-on-surface-variant' : 'text-yellow-400' }}">
                                        {{ Auth::user()->role === 'premium' ? 'workspace_premium' : (Auth::user()->role === 'pro' ? 'star' : 'eco') }}
                                    </span>
                                    <h3 class="text-[18px] font-black {{ Auth::user()->role === 'free' ? 'text-on-surface' : '' }}">
                                        {{ Auth::user()->planName() }}
                                    </h3>
                                </div>
                                <p class="text-[13px] {{ Auth::user()->role === 'free' ? 'text-on-surface-variant' : 'text-white/70' }}">
                                    @if(Auth::user()->role === 'free')
                                        Paket gratis dengan fitur dasar.
                                    @elseif(Auth::user()->role === 'pro')
                                        Kalender Tanam aktif · Weather Adjustment aktif
                                    @else
                                        Unlimited · Semua fitur premium
                                    @endif
                                </p>
                            </div>

                            @if(Auth::user()->role === 'free')
                                <span class="text-[20px] font-black text-on-surface">Gratis</span>
                            @endif
                        </div>

                        {{-- Plan Limits --}}
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="rounded-xl p-3 text-center {{ Auth::user()->role === 'free' ? 'bg-surface-container-high' : 'bg-white/10' }}">
                                <div class="text-[18px] font-black {{ Auth::user()->role === 'free' ? 'text-on-surface' : '' }}">{{ Auth::user()->maxGardens() }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider {{ Auth::user()->role === 'free' ? 'text-on-surface-variant' : 'text-white/60' }}">Maks Kebun</div>
                            </div>
                            <div class="rounded-xl p-3 text-center {{ Auth::user()->role === 'free' ? 'bg-surface-container-high' : 'bg-white/10' }}">
                                <div class="text-[18px] font-black {{ Auth::user()->role === 'free' ? 'text-on-surface' : '' }}">{{ Auth::user()->maxPlants() === PHP_INT_MAX ? '∞' : Auth::user()->maxPlants() }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider {{ Auth::user()->role === 'free' ? 'text-on-surface-variant' : 'text-white/60' }}">Maks Tanaman</div>
                            </div>
                            <div class="rounded-xl p-3 text-center {{ Auth::user()->role === 'free' ? 'bg-surface-container-high' : 'bg-white/10' }}">
                                <div class="text-[18px] font-black {{ Auth::user()->role === 'free' ? 'text-on-surface' : '' }}">
                                    <span class="material-symbols-outlined text-[18px] {{ Auth::user()->canUseAutopilot() ? (Auth::user()->role === 'free' ? 'text-primary' : 'text-yellow-400') : 'text-on-surface-variant' }}">
                                        {{ Auth::user()->canUseAutopilot() ? 'check_circle' : 'cancel' }}
                                    </span>
                                </div>
                                <div class="text-[10px] font-bold uppercase tracking-wider {{ Auth::user()->role === 'free' ? 'text-on-surface-variant' : 'text-white/60' }}">Kalender Tanam</div>
                            </div>
                        </div>

                        {{-- Subscription Info (if active) --}}
                        @php $activeSub = Auth::user()->activeSubscription(); @endphp
                        @if($activeSub)
                            <div class="flex items-center justify-between text-[12px] {{ Auth::user()->role === 'free' ? 'text-on-surface-variant' : 'text-white/70' }} border-t {{ Auth::user()->role === 'free' ? 'border-outline-variant/20' : 'border-white/10' }} pt-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                    Berlaku sampai: <strong class="{{ Auth::user()->role === 'free' ? 'text-on-surface' : 'text-white' }}">{{ $activeSub->valid_until->format('d M Y') }}</strong>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">autorenew</span>
                                    {{ ucfirst($activeSub->billing_cycle) }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if(Auth::user()->role === 'free')
                            <a href="/checkout?plan=subur&from=settings" class="flex-1 flex items-center justify-center gap-2 bg-primary text-on-primary font-bold py-3 rounded-xl hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm text-[14px]">
                                <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                                Upgrade ke Subur
                            </a>
                            <a href="/checkout?plan=pro&from=settings" class="flex-1 flex items-center justify-center gap-2 bg-slate-800 text-white font-bold py-3 rounded-xl hover:bg-slate-700 active:scale-[0.98] transition-all shadow-sm text-[14px]">
                                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                                Upgrade ke Panen Raya
                            </a>
                        @elseif(Auth::user()->role === 'pro')
                            <a href="/checkout?plan=pro&from=settings" class="flex-1 flex items-center justify-center gap-2 bg-slate-800 text-white font-bold py-3 rounded-xl hover:bg-slate-700 active:scale-[0.98] transition-all shadow-sm text-[14px]">
                                <span class="material-symbols-outlined text-[18px]">upgrade</span>
                                Upgrade ke Panen Raya
                            </a>
                            <button type="button" id="btn-cancel-sub" class="flex-1 flex items-center justify-center gap-2 border-2 border-error/30 text-error font-bold py-3 rounded-xl hover:bg-error/5 active:scale-[0.98] transition-all text-[14px]">
                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                                Batalkan Langganan
                            </button>
                        @else
                            <button type="button" id="btn-cancel-sub" class="flex-1 flex items-center justify-center gap-2 border-2 border-error/30 text-error font-bold py-3 rounded-xl hover:bg-error/5 active:scale-[0.98] transition-all text-[14px]">
                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                                Batalkan Langganan
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Notifications Settings Box --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-[24px] font-bold text-on-surface mb-6">Notifications</h2>
                    <div class="space-y-[16px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-[16px] font-bold text-on-surface">Email Notifications</h3>
                                <p class="text-[13px] text-on-surface-variant">Terima email untuk jadwal perawatan tanaman Anda.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="email-notif-toggle" class="sr-only peer" {{ Auth::user()->email_notifications ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-outline-variant/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="h-px w-full bg-outline-variant/30"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-[16px] font-bold text-on-surface">Push Notifications</h3>
                                <p class="text-[13px] text-on-surface-variant">Dapatkan notifikasi langsung di perangkat Anda untuk peringatan kritis.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="push-notif-toggle" class="sr-only peer" {{ Auth::user()->push_notifications ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-outline-variant/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Security & Privacy Box --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-[24px] font-bold text-on-surface mb-6">Security & Privacy</h2>
                    <div class="space-y-[20px]">
                        <div class="flex flex-col gap-2">
                            <h3 class="text-[16px] font-bold text-on-surface">Riwayat Login</h3>
                            <div class="bg-surface-container-low rounded-[16px] p-4 border border-outline-variant/20 space-y-3">
                                <!-- Laptop/Computer -->
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-on-surface-variant">computer</span>
                                        <div>
                                            <p class="text-[14px] font-bold text-on-surface">Computer/Laptop</p>
                                            <p class="text-[12px] text-on-surface-variant">Sesi Saat Ini</p>
                                        </div>
                                    </div>
                                    <span class="bg-primary-container text-on-primary-container px-2 py-1 rounded text-[11px] font-bold">Saat ini</span>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-surface-container-high text-error hover:bg-error/10 border border-error/20 rounded-full px-6 py-3 font-bold text-[14px] transition-all">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                Logout dari Semua Perangkat
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Danger Zone (Delete Account) --}}
                <div class="bg-error-container/10 rounded-[24px] p-[24px] ambient-shadow-lg border border-error/20 hover:border-error/40 transition-colors duration-300">
                    <h2 class="text-[24px] font-bold text-error mb-2">Delete Account</h2>
                    <p class="text-[14px] text-on-surface-variant mb-6">Sekali Anda menghapus akun, semua data kebun dan pengaturan akan hilang selamanya. Tindakan ini tidak dapat dibatalkan.</p>
                    <form id="delete-account-form" action="{{ route('settings.account.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-error text-white px-6 py-3 rounded-full text-[14px] font-bold hover:bg-[#93000a] active:scale-95 transition-all duration-300 shadow-sm">
                            Hapus Akun Saya
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteAccountForm = document.getElementById('delete-account-form');
        if (deleteAccountForm) {
            deleteAccountForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Alert.modal.confirm('Hapus Akun', 'Apakah Anda yakin ingin menghapus akun Anda selamanya? Tindakan ini tidak dapat dibatalkan.', 'Ya, Hapus Akun', true)
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
            });
        }
        
        const locationInput = document.getElementById('garden-location');
        const manualProvince = document.getElementById('manual-province');
        const hiddenProvince = document.getElementById('hidden-province');
        const detectBtn = document.getElementById('btn-detect-location');
        
        // Avatar preview
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarIcon = document.getElementById('avatar-icon');
        
        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.classList.remove('hidden');
                        if (avatarIcon) avatarIcon.classList.add('hidden');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Handle manual province change
        if (manualProvince) {
            manualProvince.addEventListener('change', () => {
                if (manualProvince.value) {
                    locationInput.value = `${manualProvince.value}, Indonesia`;
                    hiddenProvince.value = manualProvince.value;
                } else {
                    locationInput.value = '';
                    hiddenProvince.value = '';
                }
            });
        }

        // Handle location detection
        if (detectBtn) {
            detectBtn.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    if (window.Alert) window.Alert.toast.error('Browser Anda tidak mendukung Geolocation.');
                    else alert('Browser Anda tidak mendukung Geolocation.');
                    return;
                }

                detectBtn.disabled = true;
                const originalContent = detectBtn.innerHTML;
                detectBtn.innerHTML = `<span class="material-symbols-outlined text-[20px] animate-spin">sync</span> Mendeteksi...`;

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        
                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10`, {
                                headers: {
                                    'Accept-Language': 'id, en'
                                }
                            });
                            
                            if (!response.ok) throw new Error('API error');
                            
                            const data = await response.json();
                            const address = data.address || {};
                            const city = address.city || address.town || address.municipality || address.city_district || address.county || 'Kota Terdeteksi';
                            const state = address.state || address.region || '';
                            
                            const formatted = state ? `${city}, ${state}` : city;
                            locationInput.value = `${formatted}, Indonesia`;

                            // Set hidden input
                            const finalProv = state || city;
                            hiddenProvince.value = finalProv;

                            // Automatically sync manual dropdown
                            if (state) {
                                const options = Array.from(manualProvince.options);
                                const matchingOption = options.find(opt => opt.value.toLowerCase() === state.toLowerCase() || state.toLowerCase().includes(opt.value.toLowerCase()));
                                if (matchingOption) {
                                    manualProvince.value = matchingOption.value;
                                }
                            }
                        } catch (err) {
                            console.error('Reverse geocoding error:', err);
                            if (window.Alert) window.Alert.toast.error('Gagal memformat lokasi.');
                        } finally {
                            detectBtn.disabled = false;
                            detectBtn.innerHTML = originalContent;
                        }
                    },
                    (error) => {
                        detectBtn.disabled = false;
                        detectBtn.innerHTML = originalContent;
                        let errMsg = 'Gagal mendeteksi lokasi.';
                        if (error.code === error.PERMISSION_DENIED) {
                            errMsg = 'Izin lokasi ditolak. Silakan pilih provinsi secara manual atau aktifkan GPS Anda.';
                        }
                        if (window.Alert) {
                            window.Alert.modal ? window.Alert.modal.error('Gagal Mendeteksi', errMsg) : window.Alert.toast.error(errMsg);
                        } else {
                            alert(errMsg);
                        }
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            });
        }

        // Handle Notifications API
        const emailToggle = document.getElementById('email-notif-toggle');
        const pushToggle = document.getElementById('push-notif-toggle');

        function updateNotifications(data) {
            fetch("{{ route('settings.notifications') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && window.Alert) {
                    window.Alert.toast.success(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                if (window.Alert) window.Alert.toast.error('Gagal menyimpan preferensi notifikasi.');
            });
        }

        if (emailToggle) {
            emailToggle.addEventListener('change', function() {
                updateNotifications({ email_notifications: this.checked ? 1 : 0 });
            });
        }

        if (pushToggle) {
            pushToggle.addEventListener('change', function() {
                updateNotifications({ push_notifications: this.checked ? 1 : 0 });
            });
        }

        // Cancel Subscription Handler
        const cancelBtn = document.getElementById('btn-cancel-sub');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', async () => {
                if (!confirm('Apakah Anda yakin ingin membatalkan langganan? Anda akan kembali ke Paket Bibit (Gratis) dan kehilangan akses ke fitur Jadwal Otomatis dan Weather Adjustment.')) {
                    return;
                }

                cancelBtn.disabled = true;
                cancelBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Membatalkan...';

                try {
                    const response = await fetch('/api/cancel-subscription', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (window.Alert) {
                            window.Alert.toast.success(data.message);
                        } else {
                            alert(data.message);
                        }
                        // Reload page to reflect changes
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        throw new Error(data.message || 'Gagal membatalkan langganan');
                    }
                } catch (error) {
                    cancelBtn.disabled = false;
                    cancelBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">cancel</span> Batalkan Langganan';
                    if (window.Alert) {
                        window.Alert.toast.error(error.message);
                    } else {
                        alert('Error: ' + error.message);
                    }
                }
            });
        }
    });
</script>
@endpush