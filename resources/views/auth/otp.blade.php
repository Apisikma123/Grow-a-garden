@extends('layouts.app')

@section('title', 'Verify OTP — Grow a Garden')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-surface px-5 py-12 relative overflow-hidden">
    {{-- Subtle background decoration --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[40%] aspect-square rounded-full bg-primary-fixed/[0.15] blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[30%] aspect-square rounded-full bg-secondary-fixed/[0.15] blur-3xl"></div>
    </div>

    <div class="w-full max-w-[500px] bg-white rounded-[24px] p-6 sm:p-8 md:p-10 ambient-shadow-lg relative z-10 border border-outline-variant/20">
        {{-- Header --}}
        <div class="text-center mb-10">
            <a href="/" class="inline-flex items-center gap-3 group mb-8">
                <img src="{{ asset('images/logo.jpg') }}" alt="Grow a Garden Logo" class="w-10 h-10 rounded-xl shadow-sm transition-transform duration-300 object-contain">
                <span class="text-2xl font-bold text-on-surface tracking-tight">Grow a Garden</span>
            </a>

            <div class="w-16 h-16 bg-primary-container/30 text-primary-container-on rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px] text-primary">mark_email_read</span>
            </div>
            <h1 class="text-[28px] font-bold text-on-surface mb-2">Check your email</h1>
            <p class="text-sm text-on-surface-variant">We sent a verification code to<br><span class="font-bold text-on-surface">{{ $email ?? 'your email' }}</span></p>
        </div>

        {{-- Form --}}
        <form action="{{ route('otp.verify') }}" method="POST" class="flex flex-col gap-8" id="otp-form">
            @csrf
            <input type="hidden" name="otp" id="otp-hidden">
            
            <div class="flex items-center justify-between gap-1 sm:gap-4 w-full" id="otp-inputs">
                {{-- Group 1 --}}
                <div class="flex flex-1 gap-1.5 sm:gap-2">
                    <input type="text" maxlength="1" class="w-full h-14 sm:h-16 text-center text-2xl font-bold bg-surface-recessed border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-on-surface" autofocus />
                    <input type="text" maxlength="1" class="w-full h-14 sm:h-16 text-center text-2xl font-bold bg-surface-recessed border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-on-surface" />
                    <input type="text" maxlength="1" class="w-full h-14 sm:h-16 text-center text-2xl font-bold bg-surface-recessed border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-on-surface" />
                </div>
                
                <span class="text-outline-variant/60 font-bold text-2xl mx-1">-</span>
                
                {{-- Group 2 --}}
                <div class="flex flex-1 gap-1.5 sm:gap-2">
                    <input type="text" maxlength="1" class="w-full h-14 sm:h-16 text-center text-2xl font-bold bg-surface-recessed border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-on-surface" />
                    <input type="text" maxlength="1" class="w-full h-14 sm:h-16 text-center text-2xl font-bold bg-surface-recessed border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-on-surface" />
                    <input type="text" maxlength="1" class="w-full h-14 sm:h-16 text-center text-2xl font-bold bg-surface-recessed border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-on-surface" />
                </div>
            </div>

            @error('otp')
                <p class="text-red-500 text-sm text-center -mt-4">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full bg-primary text-on-primary rounded-full py-4 text-sm font-semibold hover:bg-primary/90 active:scale-[0.98] transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                Verify Code
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-center text-sm font-medium text-on-surface-variant mt-8">
            Didn't receive the code? 
            <a href="/login" class="text-primary font-semibold hover:text-primary/80 transition-colors">Back to Login</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('#otp-inputs input');
        const form = document.getElementById('otp-form');
        const hiddenInput = document.getElementById('otp-hidden');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Allow only numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                if (e.target.value !== '') {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                    }
                }
            });
        });

        form.addEventListener('submit', (e) => {
            let otpValue = '';
            inputs.forEach(input => {
                otpValue += input.value;
            });
            hiddenInput.value = otpValue;
        });
    });
</script>
@endpush
@endsection
