@extends('layouts.app')

@section('title', 'Daftar — Grow a Garden')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-surface px-5 py-12 relative overflow-hidden">
    {{-- Subtle background decoration --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[10%] left-[-5%] w-[40%] aspect-square rounded-full bg-secondary-fixed/[0.15] blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[30%] aspect-square rounded-full bg-primary-fixed/[0.15] blur-3xl"></div>
    </div>

    <div class="w-full max-w-[440px] bg-white rounded-[24px] p-8 md:p-10 ambient-shadow-lg relative z-10 border border-outline-variant/20 mt-10 md:mt-0">
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
            <h1 class="text-[28px] font-bold text-on-surface mb-2">Buat Akun</h1>
            <p class="text-sm text-on-surface-variant">Mulai perjalanan berkebun digital Anda hari ini.</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('register.post') }}" method="POST" class="flex flex-col gap-5">
            @csrf
            
            @if ($errors->any())
                <div class="bg-error-container/20 text-error text-sm p-3 rounded-[12px] border border-error/30 mb-2 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif
            {{-- Name Input --}}
            <div class="flex flex-col gap-2">
                <label for="name" class="text-sm font-semibold text-on-surface ml-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">person</span>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Green Thumb"
                        class="w-full bg-surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-4 py-3 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-colors placeholder:text-on-surface-variant/50"
                        required
                    />
                </div>
            </div>

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
                <label for="password" class="text-sm font-semibold text-on-surface ml-1">Kata Sandi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">lock</span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        class="w-full bg-surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-10 py-3 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-colors placeholder:text-on-surface-variant/50"
                        required
                    />
                    <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 hover:text-on-surface transition-colors toggle-password" data-target="password" aria-label="Toggle password visibility">
                        <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                    </button>
                </div>
            </div>

            {{-- Confirm Password Input --}}
            <div class="flex flex-col gap-2">
                <label for="password_confirmation" class="text-sm font-semibold text-on-surface ml-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">lock</span>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="••••••••"
                        class="w-full bg-surface-recessed border border-outline-variant rounded-[12px] pl-11 pr-10 py-3 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-colors placeholder:text-on-surface-variant/50"
                        required
                    />
                    <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 hover:text-on-surface transition-colors toggle-password" data-target="password_confirmation" aria-label="Toggle password visibility">
                        <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                    </button>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-primary text-on-primary rounded-full py-3.5 text-sm font-semibold hover:bg-primary/90 active:scale-[0.98] transition-all duration-200 mt-4 shadow-sm flex items-center justify-center gap-2">
                Daftar
                <span class="material-symbols-outlined text-[20px]">person_add</span>
            </button>
            
            {{-- Social Registration --}}
            <div class="flex flex-col gap-3 mt-2">
                <a href="/auth/google" class="w-full bg-white border-2 border-outline-variant/50 text-on-surface rounded-full py-3 text-sm font-semibold hover:bg-surface transition-all duration-200 flex items-center justify-center gap-3">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo" class="w-5 h-5" />
                    Sign up with Google
                </a>
            </div>
        </form>

        {{-- Footer --}}
        <p class="text-center text-sm font-medium text-on-surface-variant mt-8">
            Sudah punya akun? 
            <a href="/login" class="text-primary font-semibold hover:text-primary/80 transition-colors">Masuk</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtns = document.querySelectorAll('.toggle-password');
        
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = btn.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = btn.querySelector('.material-symbols-outlined');
                
                if (passwordInput && icon) {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    icon.textContent = isPassword ? 'visibility' : 'visibility_off';
                }
            });
        });
    });
</script>
@endpush
@endsection
