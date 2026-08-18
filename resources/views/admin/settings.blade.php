@extends('layouts.admin')

@section('title', 'Settings — Admin Grow a Garden')

@section('admin-content')
<div class="flex flex-col gap-[24px] pb-10">
    <div>
        <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight mb-2">Settings</h1>
        <p class="text-[16px] text-on-surface-variant leading-[24px]">Kelola preferensi akun admin, audit log, dan keamanan sistem Anda.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-900 px-5 py-4 rounded-2xl flex items-center gap-3 text-[14px] font-bold shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-emerald-700">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-900 px-5 py-4 rounded-2xl flex flex-col gap-1 text-[14px] font-bold shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-red-700">error</span>
                <span>Terjadi kesalahan pada data yang diisi:</span>
            </div>
            <ul class="list-disc list-inside text-[13px] font-normal pl-6">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-[800px] w-full mx-auto">
        {{-- Main Settings Content --}}
        <div class="space-y-[24px]">
            
            {{-- Profile Settings Box --}}
            <div class="bg-surface rounded-[24px] p-[24px] md:p-[32px] ambient-shadow-lg border border-outline-variant/20 hover:shadow-xl transition-shadow duration-300">
                <h2 class="text-[24px] font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[28px]">manage_accounts</span>
                    Profile Settings
                </h2>

                <form action="{{ route('settings.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-[32px]">
                        {{-- Avatar Upload Section --}}
                        <div class="flex flex-col items-center gap-4 shrink-0">
                            <div class="relative group cursor-pointer" onclick="document.getElementById('admin-avatar-input').click()">
                                <div class="relative w-24 h-24 rounded-full bg-surface-container-high overflow-hidden border-4 border-surface shadow-md group-hover:border-primary transition-colors duration-300">
                                    @php
                                        $adminAvatar = Auth::user()->avatar ? (filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar)) : null;
                                    @endphp
                                    <img id="admin-avatar-preview" src="{{ $adminAvatar ?? '' }}" alt="Avatar" class="w-full h-full object-cover {{ $adminAvatar ? 'block' : 'hidden' }}" style="{{ $adminAvatar ? '' : 'display: none;' }}">
                                    <div id="admin-avatar-icon" class="absolute inset-0 flex items-center justify-center {{ $adminAvatar ? 'hidden' : 'flex' }}" style="{{ $adminAvatar ? 'display: none;' : '' }}">
                                        <span class="material-symbols-outlined text-[40px] text-on-surface-variant group-hover:scale-110 transition-transform duration-300">person</span>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-on-surface/30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-xs">
                                    <span class="material-symbols-outlined text-white text-[24px]">photo_camera</span>
                                </div>
                            </div>
                            <input type="file" id="admin-avatar-input" name="avatar" class="hidden" accept="image/jpeg,image/png,image/webp">
                            <button type="button" onclick="document.getElementById('admin-avatar-input').click()" class="text-primary text-[14px] font-bold hover:underline transition-all">Ganti Foto Profil</button>
                        </div>

                        {{-- Form Inputs Section --}}
                        <div class="flex-1 space-y-[18px]">
                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2 group-focus-within:text-primary transition-colors">Nama Lengkap <span class="text-error">*</span></label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 text-[15px] text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            </div>

                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2">Alamat Email</label>
                                <input type="email" value="{{ Auth::user()->email }}" class="w-full surface-recessed border border-outline-variant/50 bg-surface-container-lowest rounded-[12px] px-4 py-3 text-[15px] text-on-surface-variant focus:outline-none transition-all cursor-not-allowed" readonly>
                            </div>

                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2">Role Hak Akses</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="bg-yellow-400 text-yellow-950 px-3.5 py-1.5 rounded-full text-[13px] font-extrabold tracking-wide flex items-center gap-1.5 shadow-xs border border-yellow-500/30">
                                        <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                                        {{ strtoupper(Auth::user()->role ?? 'ADMIN') }}
                                    </span>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-[14px] font-bold text-on-surface mb-2">Keamanan Password</label>
                                <div class="flex items-center justify-between surface-recessed border border-outline-variant rounded-[12px] px-4 py-3 transition-all">
                                    <span class="text-[16px] text-on-surface-variant tracking-[0.2em] font-medium mt-0.5">••••••••••••</span>
                                    <a href="{{ route('settings.password') }}" class="text-primary text-[14px] font-bold hover:underline active:scale-95 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                                        Ganti Password
                                    </a>
                                </div>
                            </div>

                            <input type="hidden" name="language" value="{{ Auth::user()->language ?? 'id' }}">

                            <div class="pt-3">
                                <button type="submit" class="bg-primary text-on-primary px-7 py-3 rounded-full text-[14px] font-bold hover:bg-primary/90 hover:shadow-md active:scale-95 transition-all duration-300 cursor-pointer shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Danger Zone (Delete Admin Account) --}}
            <div class="bg-error-container/10 rounded-[24px] p-[24px] md:p-[32px] ambient-shadow-lg border border-error/20 hover:border-error/40 transition-colors duration-300">
                <h2 class="text-[24px] font-bold text-error mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[28px]">warning</span>
                    Delete Account
                </h2>
                <p class="text-[14px] text-on-surface-variant mb-6 leading-relaxed">Sekali Anda menghapus akun admin, semua hak akses dan konfigurasi sistem yang terhubung dengan Anda akan dicabut. Tindakan ini tidak dapat dibatalkan.</p>
                <form id="delete-admin-account-form" action="{{ route('settings.account.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-error text-white px-6 py-3 rounded-full text-[14px] font-bold hover:bg-[#93000a] active:scale-95 transition-all duration-300 shadow-sm cursor-pointer">
                        Hapus Akun Admin
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Reusable Cropper Modal --}}
<x-cropper-modal />
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Admin Profile Avatar Cropper (1:1 Ratio)
        if (window.ProfileCropper) {
            ProfileCropper.attach('admin-avatar-input', 'admin-avatar-preview', 'admin-avatar-icon');
        }

        // Delete Admin Account Confirm
        const deleteForm = document.getElementById('delete-admin-account-form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const result = await Alert.confirm('Hapus Akun Admin', 'Apakah Anda yakin ingin menghapus akun admin Anda selamanya? Semua hak akses akan dicabut.', 'Ya, Hapus Akun', true);
                if (result && result.isConfirmed) {
                    this.submit();
                }
            });
        }
    });
</script>
@endpush