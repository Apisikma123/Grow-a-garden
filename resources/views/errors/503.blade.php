@extends('errors.layout')

@section('title', 'Layanan Dalam Pemeliharaan')
@section('code', '503')

@section('icon')
<span class="material-symbols-outlined text-[36px]">construction</span>
@endsection

@section('headline', 'Sistem Sedang Dipelihara')

@section('message', 'Kami sedang melakukan peningkatan performa dan perawatan berkala. Sistem akan segera kembali normal.')

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">refresh</span>
    <span>Cek Pembaruan</span>
</button>
@endsection
