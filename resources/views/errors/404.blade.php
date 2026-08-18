@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')

@section('icon')
<span class="material-symbols-outlined text-[36px]">energy_savings_leaf</span>
@endsection

@section('headline', 'Sepertinya Anda Tersesat')

@section('message', 'Halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau belum ditanam di sini.')

@section('actions')
<a href="/dashboard" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    <span>Kembali ke Beranda</span>
</a>
@endsection
