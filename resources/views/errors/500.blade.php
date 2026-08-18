@extends('errors.layout')

@section('title', 'Kesalahan Server')
@section('code', '500')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#ba1a1a]">
    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
    <path d="M12 9v4"/>
    <path d="M12 17h.01"/>
</svg>
@endsection

@section('headline', 'Terjadi Kesalahan Server')

@section('message', 'Terjadi gangguan sementara pada sistem. Silakan muat ulang halaman atau coba beberapa saat lagi.')

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">refresh</span>
    <span>Muat Ulang Halaman</span>
</button>
@endsection
