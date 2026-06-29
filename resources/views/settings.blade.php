@extends('layouts.dashboard')

@section('title', 'Settings — Grow a Garden')
@section('description', 'Manage your account, profile, and subscription settings.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        <div>
            <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight mb-2">Settings</h1>
            <p class="text-[16px] text-on-surface-variant leading-[24px]">Kelola preferensi akun dan paket langganan Anda.</p>
        </div>

        <div class="max-w-[800px] w-full mx-auto">
            {{-- Main Settings Content --}}
            <div class="space-y-[24px]">
                
                {{-- Profile Settings Box --}}
                <div class="bg-surface rounded-[24px] p-[24px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-[24px] font-bold text-on-surface mb-6">Profile Settings</h2>
                    <div class="flex flex-col md:flex-row gap-[32px]">
                        <div class="flex flex-col items-center gap-4">
                            <div class="relative group cursor-pointer">
                                <div class="w-24 h-24 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden border-4 border-surface shadow-sm group-hover:border-primary-container transition-colors duration-300">
                                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant group-hover:scale-110 transition-transform duration-300">person</span>
                                </div>
                                <div class="absolute inset-0 bg-on-surface/20 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                                    <span class="material-symbols-outlined text-surface">photo_camera</span>
                                </div>
                            </div>
                            <button class="text-primary text-[14px] font-bold hover:opacity-80 transition-opacity">Ganti Foto</button>
                        </div>
                        <div class="flex-1 space-y-[16px]">
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Nama Lengkap</label>
                                <input type="text" value="Petani Urban" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            </div>
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Alamat Email</label>
                                <input type="email" value="petani@urbanfarming.com" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            </div>
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Nomor Telepon</label>
                                <input type="tel" value="081234567890" placeholder="08xxxxxxxxxx" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            </div>
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Lokasi Kebun (Weather Adjustment)</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">location_on</span>
                                        <input type="text" id="garden-location" placeholder="Pilih atau deteksi lokasi..." class="w-full surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" readonly>
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
                                    <option value="Aceh">Aceh</option>
                                    <option value="Sumatera Utara">Sumatera Utara</option>
                                    <option value="Sumatera Barat">Sumatera Barat</option>
                                    <option value="Riau">Riau</option>
                                    <option value="Kepulauan Riau">Kepulauan Riau</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Sumatera Selatan">Sumatera Selatan</option>
                                    <option value="Bangka Belitung">Bangka Belitung</option>
                                    <option value="Bengkulu">Bengkulu</option>
                                    <option value="Lampung">Lampung</option>
                                    <option value="DKI Jakarta">DKI Jakarta</option>
                                    <option value="Jawa Barat">Jawa Barat</option>
                                    <option value="Banten">Banten</option>
                                    <option value="Jawa Tengah">Jawa Tengah</option>
                                    <option value="DI Yogyakarta">DI Yogyakarta</option>
                                    <option value="Jawa Timur">Jawa Timur</option>
                                    <option value="Bali">Bali</option>
                                    <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                    <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                                    <option value="Kalimantan Barat">Kalimantan Barat</option>
                                    <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                    <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                    <option value="Kalimantan Timur">Kalimantan Timur</option>
                                    <option value="Kalimantan Utara">Kalimantan Utara</option>
                                    <option value="Sulawesi Utara">Sulawesi Utara</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                    <option value="Sulawesi Barat">Sulawesi Barat</option>
                                    <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                    <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                    <option value="Maluku">Maluku</option>
                                    <option value="Maluku Utara">Maluku Utara</option>
                                    <option value="Papua Barat">Papua Barat</option>
                                    <option value="Papua">Papua</option>
                                </select>
                            </div>
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2">Role Akun</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="bg-primary-container text-on-primary-container px-3 py-1.5 rounded-full text-[13px] font-bold tracking-wide">
                                        Free User
                                    </span>
                                </div>
                            </div>
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Password</label>
                                <div class="flex items-center justify-between surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 transition-all">
                                    <span class="text-[16px] text-on-surface-variant tracking-[0.2em] font-medium mt-1">••••••••</span>
                                    <a href="/settings/password" class="text-primary text-[14px] font-bold hover:underline active:scale-95 transition-all">
                                        Ganti Password
                                    </a>
                                </div>
                            </div>
                            <div class="pt-2">
                                <button class="bg-primary text-on-primary px-6 py-3 rounded-full text-[14px] font-bold hover:-translate-y-0.5 hover:shadow-lg active:scale-95 transition-all duration-300">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
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
                                <input type="checkbox" value="" class="sr-only peer" checked>
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
                                <input type="checkbox" value="" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-outline-variant/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Danger Zone (Delete Account) --}}
                <div class="bg-error-container/10 rounded-[24px] p-[24px] ambient-shadow-lg border border-error/20 hover:border-error/40 transition-colors duration-300">
                    <h2 class="text-[24px] font-bold text-error mb-2">Delete Account</h2>
                    <p class="text-[14px] text-on-surface-variant mb-6">Sekali Anda menghapus akun, semua data kebun dan pengaturan akan hilang selamanya. Tindakan ini tidak dapat dibatalkan.</p>
                    <button class="bg-error text-white px-6 py-3 rounded-full text-[14px] font-bold hover:bg-[#93000a] active:scale-95 transition-all duration-300 shadow-sm">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const locationInput = document.getElementById('garden-location');
        const manualProvince = document.getElementById('manual-province');
        const detectBtn = document.getElementById('btn-detect-location');
        const saveBtn = document.querySelector('button.bg-primary'); // profile save button

        // Load saved location
        const savedLoc = localStorage.getItem('garden_location');
        if (savedLoc) {
            try {
                const data = JSON.parse(savedLoc);
                locationInput.value = data.formatted || '';
                if (data.region) {
                    // select province if matched
                    const options = Array.from(manualProvince.options);
                    const matchingOption = options.find(opt => opt.value.toLowerCase() === data.region.toLowerCase() || data.region.toLowerCase().includes(opt.value.toLowerCase()));
                    if (matchingOption) {
                        manualProvince.value = matchingOption.value;
                    }
                }
            } catch(e) {
                console.error(e);
            }
        }

        // Handle manual change
        manualProvince.addEventListener('change', () => {
            if (manualProvince.value) {
                locationInput.value = `${manualProvince.value}, Indonesia`;
            } else {
                locationInput.value = '';
            }
        });

        // Handle location detection
        detectBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
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

                        // Automatically sync manual dropdown
                        if (state) {
                            const options = Array.from(manualProvince.options);
                            const matchingOption = options.find(opt => opt.value.toLowerCase() === state.toLowerCase() || state.toLowerCase().includes(opt.value.toLowerCase()));
                            if (matchingOption) {
                                manualProvince.value = matchingOption.value;
                            }
                        }
                        
                        // Temporarily hold detected info
                        detectBtn.dataset.detected = JSON.stringify({
                            lat,
                            lon,
                            city,
                            region: state || city,
                            country: address.country || 'Indonesia',
                            formatted: `${formatted}, Indonesia`
                        });
                    } catch (err) {
                        console.error('Reverse geocoding error:', err);
                        const formatted = `Koordinat: ${lat.toFixed(4)}, ${lon.toFixed(4)}`;
                        locationInput.value = formatted;
                        detectBtn.dataset.detected = JSON.stringify({
                            lat,
                            lon,
                            city: 'Koordinat',
                            region: '',
                            country: 'Indonesia',
                            formatted: formatted
                        });
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
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        errMsg = 'Informasi lokasi tidak tersedia.';
                    } else if (error.code === error.TIMEOUT) {
                        errMsg = 'Waktu permintaan lokasi habis.';
                    }
                    alert(errMsg);
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        });

        // Save changes handler
        saveBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.GardenLoader) window.GardenLoader.show('Menyimpan preferensi...');

            let locationData = null;
            if (detectBtn.dataset.detected) {
                locationData = JSON.parse(detectBtn.dataset.detected);
            } else if (locationInput.value) {
                const provVal = manualProvince.value || locationInput.value.split(',')[0];
                locationData = {
                    lat: null,
                    lon: null,
                    city: provVal,
                    region: provVal,
                    country: 'Indonesia',
                    formatted: locationInput.value
                };
            }

            if (locationData) {
                localStorage.setItem('garden_location', JSON.stringify(locationData));
            } else {
                localStorage.removeItem('garden_location');
            }

            setTimeout(() => {
                if (window.GardenLoader) window.GardenLoader.hide();
                
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-6 right-6 z-[99999] bg-[#006c49] text-white px-6 py-3.5 rounded-[16px] font-bold text-sm shadow-xl flex items-center gap-2 transform translate-y-20 opacity-0 transition-all duration-300';
                toast.innerHTML = `
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>Pengaturan profil berhasil disimpan!</span>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.remove('translate-y-20', 'opacity-0');
                }, 50);

                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);

            }, 1000);
        });
    });
</script>
@endpush
