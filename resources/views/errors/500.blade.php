@extends('errors.layout')

@section('title', 'Kesalahan Server')
@section('code', '500')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-24 h-24 mx-auto text-[#ba1a1a]">
    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
    <path d="M12 9v4"/>
    <path d="M12 17h.01"/>
</svg>
@endsection

@section('headline', 'Oops! Our watering system broke.')

@section('message', 'Something went wrong on our end. Our master gardeners have been notified and are working to fix the issue. Please try again in a little while.')

@section('actions')
<button onclick="window.location.reload()" class="bg-[#006c49] text-white px-8 py-4 rounded-[16px] font-semibold shadow-lg shadow-[#006c49]/20 hover:bg-[#005236] transition-all active:scale-95 w-full sm:w-auto text-center inline-flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
    Refresh Page
</button>
<a href="mailto:support@growagarden.com" class="border-2 border-[#6c7a71] text-[#3c4a42] px-8 py-4 rounded-[16px] font-semibold hover:bg-[#6c7a71]/5 transition-all active:scale-95 w-full sm:w-auto text-center inline-flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    Contact Support
</a>
@endsection
