@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-[24px] pb-10">
    <div>
        <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight mb-2">Settings</h1>
        <p class="text-[16px] text-on-surface-variant leading-[24px]">Kelola preferensi akun admin dan profil Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-[24px]">
        {{-- Sidebar Nav for Settings --}}
        <div class="lg:col-span-1 space-y-[8px]">
            <button class="w-full text-left px-4 py-3 bg-surface-container-high text-on-surface rounded-[16px] text-[16px] font-bold flex items-center gap-3 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">person</span>
                Profile
            </button>
            <button class="w-full text-left px-4 py-3 text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface rounded-[16px] text-[16px] font-bold flex items-center gap-3 transition-colors">
                <span class="material-symbols-outlined text-[20px]">notifications</span>
                Notifications
            </button>

            <div class="h-px w-full bg-outline-variant/30 my-[16px]"></div>
            <button class="w-full text-left px-4 py-3 text-error hover:bg-error-container/50 rounded-[16px] text-[16px] font-bold flex items-center gap-3 transition-colors">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Log Out
            </button>
        </div>

        {{-- Main Settings Content --}}
        <div class="lg:col-span-3 space-y-[24px]">
            
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
                            <input type="text" value="Admin User" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary hover:border-outline transition-all">
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Alamat Email</label>
                            <input type="email" value="admin@growagarden.com" class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[16px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary hover:border-outline transition-all">
                        </div>
                        <div class="pt-2">
                            <button class="bg-primary text-on-primary px-6 py-3 rounded-full text-[14px] font-bold hover:-translate-y-0.5 hover:shadow-lg active:scale-95 transition-all duration-300">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
