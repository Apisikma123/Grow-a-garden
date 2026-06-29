@forelse($templates as $template)
    <div class="bg-white rounded-[32px] p-7 ambient-shadow border border-outline-variant/20 flex flex-col gap-6 hover:ambient-shadow-lg transition-shadow relative">
        
        {{-- Top Info --}}
        <div class="flex gap-5 relative">
            <div class="w-20 h-20 shrink-0 rounded-[20px] overflow-hidden bg-surface-container-high shadow-sm border border-outline-variant/20">
                <img src="{{ $template->image ?: 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=150&h=150&fit=crop' }}" class="w-full h-full object-cover" alt="{{ $template->name }}">
            </div>
            <div class="flex-1 flex flex-col justify-center pt-1">
                <div class="flex items-center gap-2 mb-2">
                    @php
                        $cat = strtoupper($template->category);
                        $bg = 'bg-[#10b981]/10 text-[#006c49]';
                        if (str_contains($cat, 'CHILI') || str_contains($cat, 'CAPSICUM')) {
                            $bg = 'bg-[#fd9e70]/20 text-[#944a23]';
                        } elseif (str_contains($cat, 'LEAFY') || str_contains($cat, 'GREEN')) {
                            $bg = 'bg-[#0284c7]/10 text-[#0369a1]';
                        } elseif (str_contains($cat, 'HERB')) {
                            $bg = 'bg-[#8b5cf6]/10 text-[#6d28d9]';
                        } elseif (str_contains($cat, 'FRUIT')) {
                            $bg = 'bg-[#e11d48]/10 text-[#be123c]';
                        } elseif (str_contains($cat, 'OTHER') || str_contains($cat, 'OTHERS')) {
                            $bg = 'bg-surface-container-high text-on-surface-variant';
                        }
                    @endphp
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase {{ $bg }}">{{ $template->category }}</span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-surface-container-high text-on-surface-variant">{{ $template->duration_min }}-{{ $template->duration_max }} DAYS</span>
                </div>
                <h3 class="text-[22px] font-bold text-on-surface leading-tight mb-1">{{ $template->name }}</h3>
                <div class="flex items-center gap-1.5 text-[12px] text-on-surface-variant font-medium">
                    <span class="material-symbols-outlined text-[14px]">history</span>
                    Last edited {{ $template->updated_at->diffForHumans() }}
                </div>
            </div>
            <button class="absolute top-0 right-0 text-on-surface-variant hover:text-on-surface transition-colors" onclick="toggleCardMenu({{ $template->id }}, event)">
                <span class="material-symbols-outlined text-[24px]">more_horiz</span>
            </button>
            <div id="card-menu-{{ $template->id }}" class="hidden absolute top-8 right-0 bg-white border border-outline-variant/30 rounded-xl shadow-lg py-1.5 z-20 min-w-[130px] ambient-shadow">
                <button onclick="editTemplate({{ json_encode($template->load('stages')) }})" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-low transition-colors font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-primary">edit</span> Edit
                </button>
                <button onclick="duplicateTemplate({{ $template->id }})" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-low transition-colors font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-secondary">content_copy</span> Duplicate
                </button>
                <div class="h-px bg-outline-variant/30 my-1"></div>
                <button onclick="confirmDeleteTemplate({{ $template->id }}, '{{ addslashes($template->name) }}')" class="w-full text-left px-4 py-2 text-[13px] text-error hover:bg-error/5 transition-colors font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">delete</span> Delete
                </button>
            </div>
        </div>

        {{-- Middle: Lifecycle Milestones --}}
        <div class="bg-surface-container-low/50 rounded-[24px] p-6 flex flex-col">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-[18px] text-[#10b981]">insights</span>
                <span class="text-[11px] font-black tracking-widest text-on-surface uppercase">LIFECYCLE MILESTONES</span>
            </div>
            
            <div class="relative flex justify-between items-start w-full px-4">
                @php
                    $stagesCount = $template->stages->count();
                @endphp
                
                @if($stagesCount > 0)
                    {{-- Connecting Lines --}}
                    <div class="absolute top-[20px] left-[10%] right-[10%] h-[2px] bg-outline-variant/30 z-0"></div>
                    
                    @if($stagesCount > 1)
                        @php
                            $activeWidth = 80 / ($stagesCount - 1);
                        @endphp
                        <div class="absolute top-[20px] left-[10%] h-[2px] bg-[#10b981] z-0" style="width: {{ $activeWidth }}%;"></div>
                    @endif
                    
                    @foreach($template->stages as $index => $stage)
                        @php
                            if ($index === 0) {
                                $circleClass = 'bg-[#10b981] text-white shadow-md ring-4 ring-white';
                                $itemClass = '';
                            } elseif ($index === 1) {
                                $circleClass = 'bg-white border-2 border-[#10b981] text-[#10b981] shadow-sm ring-4 ring-white';
                                $itemClass = '';
                            } else {
                                $circleClass = 'bg-white border-2 border-outline-variant/40 text-on-surface-variant shadow-sm ring-4 ring-white';
                                $itemClass = 'opacity-50';
                            }
                        @endphp
                        
                        <div class="flex flex-col items-center gap-3 relative z-10 {{ $itemClass }}" style="width: calc(100% / {{ $stagesCount }});">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $circleClass }}">
                                <span class="material-symbols-outlined text-[20px]">{{ $stage->icon ?: 'eco' }}</span>
                            </div>
                            <div class="text-center px-1">
                                <div class="text-[12px] font-bold text-on-surface mb-0.5 truncate max-w-[85px]" title="{{ $stage->stage_name }}">{{ $stage->stage_name }}</div>
                                <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">
                                    DAY {{ $stage->start_day }}-{{ $stage->end_day }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center w-full py-4 text-on-surface-variant text-[13px] font-semibold">No milestones configured</div>
                @endif
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="flex items-center justify-between pt-2">
            {{-- Avatars --}}
            <div class="flex items-center -space-x-2">
                @if($template->category === 'Nightshade')
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-primary-container text-on-primary-container text-[10px] font-bold flex items-center justify-center">TJ</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-200 text-blue-800 text-[10px] font-bold flex items-center justify-center">AM</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-surface-container-high text-on-surface-variant text-[10px] font-bold flex items-center justify-center">+4</div>
                @else
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-200 text-blue-800 text-[10px] font-bold flex items-center justify-center">SL</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-pink-200 text-pink-800 text-[10px] font-bold flex items-center justify-center">KR</div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                <button onclick="confirmDeleteTemplate({{ $template->id }}, '{{ addslashes($template->name) }}')" class="p-2 text-outline hover:text-error transition-colors" title="Delete">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                </button>
                <button onclick="duplicateTemplate({{ $template->id }})" class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-surface-container-highest hover:bg-outline-variant/40 text-on-surface font-bold text-[13px] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                    Duplicate
                </button>
                <button onclick="editTemplate({{ json_encode($template->load('stages')) }})" class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#10b981]/15 text-[#006c49] hover:bg-[#10b981]/25 font-bold text-[13px] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit
                </button>
            </div>
        </div>

    </div>
@empty
    <div class="col-span-full bg-white rounded-[32px] p-12 text-center border border-outline-variant/20 ambient-shadow flex flex-col items-center gap-4">
        <span class="material-symbols-outlined text-[64px] text-outline/40">assignment</span>
        <div class="flex flex-col gap-1">
            <h3 class="text-[20px] font-bold text-on-surface">No Templates Yet</h3>
            <p class="text-[14px] text-on-surface-variant max-w-[400px]">Create growth templates to start managing plant lifecycles.</p>
        </div>
        <button onclick="openCreateModal()" class="flex items-center gap-2 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-[14px] px-6 py-3 rounded-full transition-colors shadow-sm mt-2">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Create First Template
        </button>
    </div>
@endforelse
