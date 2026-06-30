@extends('errors.layout')

@section('title', 'Offline')
@section('code', 'OFFLINE')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-24 h-24 mx-auto text-[#006c49]">
    <path d="M2 2 22 22"/>
    <path d="M8.5 8.5C7.3 9.7 6 10.9 5 12c1.7 1.7 4 3 6.5 3 1.1 0 2.2-.2 3.2-.6"/>
    <path d="M16.8 16.8c1.6-1.5 2.9-3.3 3.2-5.3-2-2.5-5.2-4.1-8.7-4.1-1.3 0-2.5.3-3.6.8"/>
</svg>
@endsection

@section('headline', 'You\'re off the grid!')

@section('message', 'It seems you\'ve lost your internet connection. Check your network so we can sync your latest garden data.')

@section('actions')
<button onclick="window.location.reload()" class="bg-[#006c49] text-white px-8 py-4 rounded-[16px] font-semibold shadow-lg shadow-[#006c49]/20 hover:bg-[#005236] transition-all active:scale-95 w-full sm:w-auto text-center inline-flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
    Try Reconnecting
</button>
@endsection
