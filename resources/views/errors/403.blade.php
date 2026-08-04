@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-24 h-24 mx-auto text-[#006c49]">
    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
</svg>
@endsection

@section('headline', 'Private Garden Area.')

@section('message', 'You don\'t have the right permissions to access this specific garden plot or settings page. Please log in with the correct account.')

@section('actions')
<a href="javascript:history.back()" class="bg-[#006c49] text-white px-8 py-4 rounded-[16px] font-semibold shadow-lg shadow-[#006c49]/20 hover:bg-[#005236] transition-all active:scale-95 w-full sm:w-auto text-center inline-flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    Back to Safety
</a>
@endsection
