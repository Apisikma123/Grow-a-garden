@extends('errors.layout')

@section('title', 'Tidak Diizinkan')
@section('code', '401')

@section('icon')
<span class="material-symbols-outlined text-[36px]">key</span>
@endsection

@section('headline', 'Sesi Belum Terautentikasi')

@section('message', 'Anda perlu masuk terlebih dahulu untuk mengakses halaman ini. Silakan login kembali ke akun Anda.')

@section('actions')
<a href="/login" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">login</span>
    <span>Masuk ke Akun</span>
</a>
@endsection
