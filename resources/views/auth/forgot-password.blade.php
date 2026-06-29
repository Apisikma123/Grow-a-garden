@extends('layouts.app')

@section('title', 'Forgot Password — Grow a Garden')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-surface px-5 py-12 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[40%] aspect-square rounded-full bg-tertiary-fixed/[0.15] blur-3xl"></div>
    </div>

    <div class="w-full max-w-[440px] bg-white rounded-[24px] p-8 md:p-10 ambient-shadow-lg relative z-10 border border-outline-variant/20">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 group mb-6">
                <img src="{{ asset('images/logo.jpg') }}" alt="Grow a Garden Logo" class="w-10 h-10 rounded-xl shadow-sm transition-transform duration-300 object-contain">
                <span class="text-xl font-bold text-on-surface tracking-tight">Grow a Garden</span>
            </a>
            <h1 class="text-[28px] font-bold text-on-surface mb-2">Reset Password</h1>
            <p class="text-sm text-on-surface-variant">Enter your email address and we'll send you instructions to reset your password.</p>
        </div>

        <form action="/otp" method="GET" class="flex flex-col gap-5">
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm font-semibold text-on-surface ml-1">Email Address</label>
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

            <button type="submit" class="w-full bg-primary text-on-primary rounded-full py-3.5 text-sm font-semibold hover:bg-primary/90 active:scale-[0.98] transition-all duration-200 shadow-sm mt-2">
                Send Reset Link
            </button>
        </form>

        @php
            $from = request()->query('from');
            $backLink = '/login';
            $backText = 'Back to Login';
            
            if ($from === 'settings') {
                $backLink = '/settings/password';
                $backText = 'Kembali ke Settings';
            } elseif ($from === 'admin_settings') {
                $backLink = '/admin/settings/password';
                $backText = 'Kembali ke Settings';
            }
        @endphp

        <a href="{{ $backLink }}" class="flex items-center justify-center gap-2 mt-8 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            {{ $backText }}
        </a>
    </div>
</div>
@endsection
