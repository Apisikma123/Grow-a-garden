@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-24 h-24 mx-auto">
    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
</svg>
@endsection

@section('headline', 'Looks like you\'ve wandered into an empty plot.')

@section('message', 'We can\'t seem to find the page you\'re looking for. The seeds might not have been planted here yet, or the page was uprooted.')

@section('actions')
<a href="/dashboard" class="bg-[#006c49] text-white px-8 py-4 rounded-[16px] font-semibold shadow-lg shadow-[#006c49]/20 hover:bg-[#005236] transition-all active:scale-95 w-full sm:w-auto text-center inline-flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    Return to Dashboard
</a>
@endsection
