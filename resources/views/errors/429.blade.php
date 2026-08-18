@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')

@section('icon')
<span class="material-symbols-outlined text-[36px]">speed</span>
@endsection

@section('headline', 'Batas Permintaan Terlampaui')

@section('message', 'Anda telah melakukan terlalu banyak permintaan dalam waktu singkat. Harap tunggu beberapa saat sebelum mencoba lagi.')

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">refresh</span>
    <span>Coba Lagi</span>
</button>
@endsection
