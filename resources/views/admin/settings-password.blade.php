@extends('layouts.admin')

@section('title', 'Ganti Password Admin — Grow a Garden')
@section('description', 'Perbarui password akun admin Anda.')

@section('admin-content')
    <div class="flex flex-col gap-[24px] pb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="/admin/settings" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-outline-variant/30 hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-[32px] md:text-[48px] font-bold text-on-surface tracking-tight leading-tight">Ganti Password</h1>
            </div>
            <p class="text-[16px] text-on-surface-variant leading-[24px] ml-[52px]">Pastikan akun admin Anda tetap aman dengan menggunakan password yang kuat.</p>
        </div>

        <div class="max-w-[700px] w-full ml-[52px]">
            <div class="bg-surface rounded-[24px] p-8 ambient-shadow-lg border border-outline-variant/20">
                <form action="/admin/settings/password" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Password Lama --}}
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-[14px] font-bold text-on-surface ml-1 group-focus-within:text-primary transition-colors">Password Lama</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none group-focus-within:text-primary transition-colors">key</span>
                            <input type="password" name="old_password" placeholder="Masukkan password saat ini" required
                                class="w-full bg-[#F1F5F2] border border-outline-variant rounded-[12px] pl-12 pr-4 py-3.5 text-[15px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-on-surface-variant/50">
                        </div>
                        <div class="flex justify-end mt-1">
                            <a href="/forgot-password?from=admin_settings" class="text-primary text-[13px] font-bold hover:underline">Lupa Password?</a>
                        </div>
                    </div>

                    <div class="h-px w-full bg-outline-variant/30 md:col-span-2"></div>

                    {{-- Password Baru --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-[14px] font-bold text-on-surface ml-1 group-focus-within:text-primary transition-colors">Password Baru</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none group-focus-within:text-primary transition-colors">lock</span>
                            <input type="password" name="new_password" placeholder="Minimal 8 karakter" required
                                class="w-full bg-[#F1F5F2] border border-outline-variant rounded-[12px] pl-12 pr-4 py-3.5 text-[15px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-on-surface-variant/50">
                        </div>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-[14px] font-bold text-on-surface ml-1 group-focus-within:text-primary transition-colors">Konfirmasi Password Baru</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none group-focus-within:text-primary transition-colors">lock</span>
                            <input type="password" name="new_password_confirmation" placeholder="Ketik ulang password baru" required
                                class="w-full bg-[#F1F5F2] border border-outline-variant rounded-[12px] pl-12 pr-4 py-3.5 text-[15px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-on-surface-variant/50">
                        </div>
                    </div>

                    <div class="pt-4 flex items-center gap-3 md:col-span-2">
                        <button type="submit" class="flex-1 bg-primary text-on-primary py-3.5 rounded-full text-[14px] font-bold hover:bg-primary/90 hover:shadow-md active:scale-[0.98] transition-all duration-200">
                            Perbarui Password
                        </button>
                        <a href="/admin/settings" class="flex-1 text-center text-primary font-bold py-3.5 hover:bg-primary/5 rounded-full transition-colors text-[14px]">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
