@extends('layouts.app')

@section('title', 'Log In — Grow a Garden')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-surface px-5 py-12 relative overflow-hidden">
    {{-- Subtle background decoration --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[40%] aspect-square rounded-full bg-primary-fixed/[0.15] blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[30%] aspect-square rounded-full bg-tertiary-fixed/[0.15] blur-3xl"></div>
    </div>

    <div class="w-full max-w-[440px] bg-white rounded-[24px] p-8 md:p-10 ambient-shadow-lg relative z-10 border border-outline-variant/20">
        {{-- Back Button --}}
        <div class="mb-2 flex justify-start">
            <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 group mb-6">
                <img src="{{ asset('images/logo.jpg') }}" alt="Grow a Garden Logo" class="w-10 h-10 rounded-xl shadow-sm transition-transform duration-300 object-contain">
                <span class="text-xl font-bold text-on-surface tracking-tight">Grow a Garden</span>
            </a>
            <h1 class="text-[28px] font-bold text-on-surface mb-2">Selamat Datang Kembali</h1>
            <p class="text-sm text-on-surface-variant">Masuk untuk memeriksa tanaman dan peta kebun Anda.</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-5">
            @csrf
            
            @if ($errors->any())
                <div class="bg-error-container/20 text-error text-sm p-3 rounded-[12px] border border-error/30 mb-2 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif
            {{-- Email Input --}}
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm font-semibold text-on-surface ml-1">Alamat Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">mail</span>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="gardener@example.com"
                        class="w-full bg-surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-4 py-3 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-colors placeholder:text-on-surface-variant/50"
                        required
                    />
                </div>
            </div>

            {{-- Password Input --}}
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between ml-1">
                    <label for="password" class="text-sm font-semibold text-on-surface">Kata Sandi</label>
                    <a href="/forgot-password" class="text-xs font-semibold text-primary hover:text-primary/80 transition-colors">Lupa Kata Sandi?</a>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">lock</span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        class="w-full bg-surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-4 py-3 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-colors placeholder:text-on-surface-variant/50"
                        required
                    />
                    <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 hover:text-on-surface transition-colors" aria-label="Toggle password visibility">
                        <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                    </button>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-3 mt-1 ml-1">
                <div class="relative flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="peer sr-only" />
                    <div class="w-5 h-5 border-2 border-outline rounded flex items-center justify-center peer-checked:bg-primary peer-checked:border-primary transition-colors cursor-pointer text-white">
                        <span class="material-symbols-outlined text-[16px] opacity-0 peer-checked:opacity-100 font-bold transition-opacity">check</span>
                    </div>
                </div>
                <label for="remember" class="text-sm font-medium text-on-surface-variant cursor-pointer select-none">Ingat saya selama 30 hari</label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-primary text-on-primary rounded-full py-3.5 text-sm font-semibold hover:bg-primary/90 active:scale-[0.98] transition-all duration-200 mt-2 shadow-sm flex items-center justify-center gap-2">
                Masuk
                <span class="material-symbols-outlined text-[20px]">login</span>
            </button>
        </form>

        {{-- Divider --}}
        <div class="flex items-center gap-4 my-8">
            <div class="flex-1 h-px bg-outline-variant/30"></div>
            <span class="text-xs font-medium text-on-surface-variant">ATAU</span>
            <div class="flex-1 h-px bg-outline-variant/30"></div>
        </div>

        {{-- Social Login --}}
        <div class="flex flex-col gap-3">
            <a href="/auth/google" class="w-full bg-white border-2 border-outline-variant/50 text-on-surface rounded-full py-3 text-sm font-semibold hover:bg-surface transition-all duration-200 flex items-center justify-center gap-3">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo" class="w-5 h-5" />
                Masuk dengan Google
            </a>
            <form action="/admin/dashboard" method="GET" class="w-full">
                <button type="submit" class="w-full bg-surface-container-high text-on-surface-variant rounded-full py-3 text-sm font-semibold hover:bg-surface-container-highest transition-all duration-200 flex items-center justify-center gap-2 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                    Masuk sebagai Admin (Demo)
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-sm font-medium text-on-surface-variant mt-8">
            Belum punya akun? 
            <a href="/register" class="text-primary font-semibold hover:text-primary/80 transition-colors">Daftar</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
    // Simple password toggle logic for the frontend demo
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.querySelector('button[aria-label="Toggle password visibility"]');
        const passwordInput = document.getElementById('password');
        const icon = toggleBtn.querySelector('.material-symbols-outlined');

        if(toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                icon.textContent = isPassword ? 'visibility' : 'visibility_off';
            });
        }
        
        // Make custom checkbox clickable
        const checkboxLabel = document.querySelector('label[for="remember"]');
        const customCheckbox = checkboxLabel.previousElementSibling.querySelector('.w-5');
        const hiddenInput = document.getElementById('remember');
        
        customCheckbox.addEventListener('click', () => {
            hiddenInput.checked = !hiddenInput.checked;
            // Dispatch change event to trigger CSS peer logic if needed, though Tailwind peer handles standard state changes
        });
    });
</script>
@endpush
@endsection
