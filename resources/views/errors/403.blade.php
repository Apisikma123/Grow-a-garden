@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('code', '403')

@section('icon')
<span class="material-symbols-outlined text-[36px]">lock</span>
@endsection

@section('headline', 'Area Kebun Privat')

@section('message', 'Anda tidak memiliki izin untuk mengakses halaman atau pengaturan ini. Silakan masuk dengan akun yang sesuai.')

@section('actions')
<a href="javascript:history.back()" class="inline-flex items-center justify-center gap-2.5 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer text-center" style="white-space: nowrap;">
    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    <span>Kembali ke Halaman Sebelumnya</span>
</a>
@endsection
