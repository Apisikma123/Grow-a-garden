@extends('layouts.dashboard')

@section('title', 'Activity Log — Grow a Garden')
@section('description', 'Riwayat lengkap aktivitas perawatan kebun Anda.')

@section('dashboard-content')
    <div class="flex flex-col gap-[24px] pb-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
            <div>
                <h1 class="text-[32px] md:text-[40px] font-bold text-on-surface tracking-tight leading-tight mb-1">Activity Log</h1>
                <p class="text-[16px] text-on-surface-variant">Pantau jejak langkah hijau Anda di kebun.</p>
            </div>
            


        </div>

        {{-- Timeline Content --}}
        <div class="bg-surface rounded-[32px] p-6 md:p-10 ambient-shadow-lg border border-outline-variant/30 relative">
            @if($activities->isEmpty())
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-surface-container-high rounded-full flex items-center justify-center mx-auto mb-6 text-on-surface-variant">
                        <span class="material-symbols-outlined text-[48px]">history_toggle_off</span>
                    </div>
                    <h3 class="text-[20px] font-bold text-on-surface mb-2">Belum Ada Aktivitas</h3>
                    <p class="text-on-surface-variant">Aktivitas perawatan kebun Anda akan muncul di sini setelah Anda menyelesaikan tugas.</p>
                </div>
            @else
                <div class="relative max-w-4xl mx-auto">
                    {{-- Continuous Line --}}
                    <div class="absolute left-6 md:left-[50%] top-6 bottom-6 w-0.5 bg-outline-variant/30 hidden md:block"></div>
                    <div class="absolute left-8 top-6 bottom-6 w-0.5 bg-outline-variant/30 md:hidden"></div>

                    <div class="space-y-8">
                        @foreach($activities as $index => $activity)
                            @php
                                $isEven = $index % 2 === 0;
                                
                                $bgClass = 'bg-[#ecfdf5]';
                                $textClass = 'text-[#059669]';
                                $borderClass = 'border-[#34d399]';
                                $icon = 'eco';
                                
                                if($activity->status === 'SKIPPED') {
                                    $bgClass = 'bg-surface-container-high';
                                    $textClass = 'text-on-surface-variant';
                                    $borderClass = 'border-outline-variant';
                                    $icon = 'block';
                                } elseif($activity->eventType && str_contains(strtolower($activity->eventType->code), 'water')) {
                                    $bgClass = 'bg-[#eff6ff]';
                                    $textClass = 'text-[#2563eb]';
                                    $borderClass = 'border-[#60a5fa]';
                                    $icon = 'water_drop';
                                } elseif($activity->eventType && str_contains(strtolower($activity->eventType->code), 'pest')) {
                                    $bgClass = 'bg-[#fff7ed]';
                                    $textClass = 'text-[#ea580c]';
                                    $borderClass = 'border-[#fb923c]';
                                    $icon = 'bug_report';
                                }
                                
                                $date = $activity->completed_at ? $activity->completed_at->isoFormat('D MMM YYYY, H:mm') : $activity->updated_at->isoFormat('D MMM YYYY, H:mm');
                            @endphp

                            <div class="relative flex items-center md:justify-between flex-col md:flex-row gap-6 md:gap-0 group">
                                {{-- Timeline Dot (Desktop center, Mobile left) --}}
                                <div class="absolute left-8 md:left-[50%] -translate-x-1/2 w-4 h-4 rounded-full border-4 {{ $borderClass }} bg-white z-10 group-hover:scale-150 transition-transform duration-300 shadow-sm md:block hidden"></div>
                                
                                <div class="absolute left-8 -translate-x-1/2 w-4 h-4 rounded-full border-4 {{ $borderClass }} bg-white z-10 group-hover:scale-150 transition-transform duration-300 shadow-sm md:hidden"></div>

                                {{-- Card Item (Left for even, Right for odd on Desktop) --}}
                                <div class="w-full md:w-[calc(50%-40px)] ml-16 md:ml-0 {{ $isEven ? 'md:pr-10 md:text-right' : 'md:pl-10 md:ml-auto' }}">
                                    <div class="bg-white rounded-[24px] p-6 shadow-sm hover:shadow-lg transition-shadow border border-outline-variant/30 flex flex-col {{ $isEven ? 'md:items-end' : 'md:items-start' }}">
                                        <div class="flex items-center gap-3 mb-3 {{ $isEven ? 'md:flex-row-reverse' : '' }}">
                                            <div class="w-12 h-12 rounded-full {{ $bgClass }} {{ $textClass }} flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined">{{ $icon }}</span>
                                            </div>
                                            <div class="{{ $isEven ? 'md:text-right' : 'md:text-left' }}">
                                                <h3 class="text-[16px] font-bold text-on-surface">{{ $activity->eventType->label ?? $activity->message ?? 'Perawatan' }}</h3>
                                                <p class="text-[13px] text-on-surface-variant font-medium">{{ $date }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-surface-container-lowest rounded-xl p-3 w-full border border-outline-variant/20">
                                            <p class="text-[14px] text-on-surface-variant mb-1">
                                                <span class="font-semibold text-on-surface">{{ $activity->plant->garden->name ?? 'Kebun' }}</span> • {{ $activity->plant->plantTemplate->name_id }}
                                            </p>
                                            @if($activity->status === 'SKIPPED')
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-error bg-error-container/30 px-2 py-0.5 rounded-md">
                                                    <span class="material-symbols-outlined text-[12px]">cancel</span> Dilewati
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#059669] bg-[#ecfdf5] px-2 py-0.5 rounded-md">
                                                    <span class="material-symbols-outlined text-[12px]">check_circle</span> Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>



                
                {{-- Pagination --}}
                @if(method_exists($activities, 'hasPages') && $activities->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $activities->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
