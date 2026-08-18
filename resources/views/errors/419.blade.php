@extends('errors.layout')

@section('title', 'Sesi Berakhir')
@section('code', '419')

@section('icon')
<span class="material-symbols-outlined text-[36px]">timer_off</span>
@endsection

@section('headline', 'Halaman Telah Kadaluarsa')

@section('message', 'Sesi keamanan Anda telah berakhir karena tidak ada aktivitas. Silakan muat ulang halaman dan coba kembali.')

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">refresh</span>
    <span>Muat Ulang Halaman</span>
</button>
@endsection
