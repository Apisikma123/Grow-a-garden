@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-8">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
        <div class="flex flex-col gap-2">
            <h1 class="text-[32px] font-black text-on-surface tracking-tight">Template Pertumbuhan & Perawatan</h1>
            <p class="text-[15px] text-on-surface-variant max-w-[600px] leading-relaxed">Konfigurasi siklus hidup otomatis, instruksi perawatan, dan aturan cuaca untuk setiap tanaman.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <button class="flex items-center gap-2 bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-[#10b981]">filter_list</span>
                Filter Tampilan
            </button>
            <div class="relative group">
                <button class="flex items-center gap-2 bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px] text-[#10b981]">sort</span>
                    Urutkan
                </button>
                <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-outline-variant/20 rounded-xl shadow-lg hidden group-hover:block z-50">
                    <div class="py-2">
                        <a href="?sort=newest" class="block px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest">Terbaru</a>
                        <a href="?sort=name" class="block px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest">Nama (A-Z)</a>
                        <a href="?sort=category" class="block px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest">Kategori</a>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.plants') }}" class="flex items-center gap-2 bg-[#006c49] text-white font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-[#005c3a] transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Data Tanaman
            </a>
        </div>
    </div>

    {{-- Grid of Templates --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8">
        
        @foreach($templates as $template)
        <div class="searchable-item bg-white rounded-[32px] p-7 ambient-shadow border border-outline-variant/20 flex flex-col gap-6 hover:ambient-shadow-lg transition-shadow">
            
            {{-- Top Info --}}
            <div class="flex gap-5 relative">
                <div class="w-20 h-20 shrink-0 rounded-[20px] overflow-hidden bg-surface-container-high shadow-sm border border-outline-variant/20 flex items-center justify-center font-bold text-on-surface-variant text-[24px]">
                    {{ strtoupper(substr($template->name_id, 0, 2)) }}
                </div>
                <div class="flex-1 flex flex-col justify-center pt-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-[#10b981]/10 text-[#006c49]">{{ $template->category->name }}</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase bg-surface-container-high text-on-surface-variant">{{ $template->harvest_start_day }}-{{ $template->harvest_end_day }} DAYS</span>
                    </div>
                    <h3 class="text-[22px] font-bold text-on-surface leading-tight mb-1">{{ $template->name_id }}</h3>
                    <div class="flex items-center gap-1.5 text-[12px] text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-[14px]">science</span>
                        {{ $template->scientific_name }}
                    </div>
                </div>
                <button class="absolute top-0 right-0 text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[24px]">more_horiz</span>
                </button>
            </div>

            {{-- Middle: Lifecycle Milestones --}}
            <div class="bg-surface-container-low/50 rounded-[24px] p-6 flex flex-col">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-[18px] text-[#10b981]">insights</span>
                    <span class="text-[11px] font-black tracking-widest text-on-surface uppercase">LIFECYCLE MILESTONES</span>
                </div>
                
                <div class="relative flex justify-between items-start w-full px-4 mb-4">
                    {{-- Connecting Lines --}}
                    <div class="absolute top-[20px] left-[15%] right-[50%] h-[2px] bg-[#10b981] z-0"></div>
                    <div class="absolute top-[20px] left-[50%] right-[15%] h-[2px] bg-outline-variant/30 z-0"></div>
                    
                    {{-- Milestone 1 (Germination) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3">
                        <div class="w-10 h-10 rounded-full bg-[#10b981] text-white flex items-center justify-center shadow-md ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">eco</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Germination</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY 0-{{ $template->germination_day ?? 0 }}</div>
                        </div>
                    </div>
                    
                    {{-- Milestone 2 (Seedling) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-[#10b981] text-[#10b981] flex items-center justify-center shadow-sm ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">psychiatry</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Seedling</div>
                            @php
                                $seedlingEnd = ($template->germination_day ?? 0) + ($template->seedling_day ?? 0);
                            @endphp
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY {{ $template->germination_day ?? 0 }}-{{ $seedlingEnd }}</div>
                        </div>
                    </div>
                    
                    {{-- Milestone 3 (Vegetative/Harvest) --}}
                    <div class="flex flex-col items-center gap-3 relative z-10 w-1/3 opacity-50">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-outline-variant/40 text-on-surface-variant flex items-center justify-center shadow-sm ring-4 ring-white">
                            <span class="material-symbols-outlined text-[20px]">yard</span>
                        </div>
                        <div class="text-center">
                            <div class="text-[12px] font-bold text-on-surface mb-0.5">Harvest</div>
                            <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">DAY {{ $template->harvest_start_day }}+</div>
                        </div>
                    </div>
                </div>
                
                {{-- Care Rules Section --}}
                @if($template->care_rules && count($template->care_rules) > 0)
                <div class="mt-4 pt-4 border-t border-outline-variant/20">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-[16px] text-[#006c49]">assignment</span>
                        <span class="text-[11px] font-black tracking-widest text-on-surface uppercase">CARE INSTRUCTIONS</span>
                    </div>
                    <ul class="flex flex-col gap-2">
                        @foreach($template->care_rules as $key => $rule)
                            <li class="flex items-start gap-2 text-[12px] text-on-surface-variant leading-relaxed">
                                <span class="material-symbols-outlined text-[14px] text-primary mt-0.5">
                                    @if(str_contains($key, 'water')) water_drop
                                    @elseif(str_contains($key, 'fertilizer')) nutrition
                                    @elseif(str_contains($key, 'pruning')) content_cut
                                    @elseif(str_contains($key, 'staking')) fence
                                    @elseif(str_contains($key, 'harvest')) local_florist
                                    @else check_circle
                                    @endif
                                </span>
                                <div>
                                    <strong class="capitalize text-on-surface">{{ str_replace('_', ' ', $key) }}:</strong> 
                                    {{ $rule }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            {{-- Bottom Actions --}}
            <div class="flex items-center justify-between pt-2">
                {{-- pH & Info --}}
                <div class="flex items-center gap-3 text-on-surface-variant text-[12px] font-bold">
                    <span>pH Ideal: {{ $template->soil_ph_min }} - {{ $template->soil_ph_max }}</span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    <button onclick="deleteTemplate({{ $template->id }})" class="p-2 text-outline hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                    <button onclick="openCareModal({{ $template->id }}, {{ json_encode($template->care_rules ?? new stdClass()) }})" class="bg-[#006c49] text-white px-5 py-2 rounded-xl text-[13px] font-bold hover:bg-[#005c3a] transition-colors shadow-sm">
                        Edit Template
                    </button>
                </div>
            </div>
        </div>
        @endforeach

    </div>

</div>

{{-- Edit Care Rules Modal --}}
<div id="careModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-outline-variant/20 flex justify-between items-center">
            <h2 class="text-[20px] font-bold text-on-surface">Edit Instruksi Perawatan</h2>
            <button onclick="closeCareModal()" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="careForm" class="flex flex-col gap-4">
                <input type="hidden" id="edit_plant_id">
                
                <div id="rulesList" class="flex flex-col gap-3">
                    <!-- Rules dynamically inserted here -->
                </div>

                <button type="button" onclick="addRuleField()" class="flex items-center gap-2 justify-center w-full py-2 border-2 border-dashed border-outline-variant/50 rounded-lg text-on-surface-variant font-bold text-[13px] hover:bg-surface-container-lowest transition-colors mt-2">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Aturan
                </button>
            </form>
        </div>
        <div class="p-6 border-t border-outline-variant/20 flex justify-end gap-3 bg-surface-container-lowest">
            <button onclick="closeCareModal()" class="px-5 py-2.5 rounded-lg font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors">Batal</button>
            <button onclick="saveCareRules()" class="px-5 py-2.5 rounded-lg bg-[#006c49] text-white font-bold hover:bg-[#005c3a] transition-colors">Simpan</button>
        </div>
    </div>
</div>

<script>
    const careModal = document.getElementById('careModal');
    const rulesList = document.getElementById('rulesList');

    function openCareModal(plantId, rules) {
        document.getElementById('edit_plant_id').value = plantId;
        rulesList.innerHTML = '';
        
        if (rules && Object.keys(rules).length > 0) {
            for (const [key, value] of Object.entries(rules)) {
                addRuleField(key, value);
            }
        } else {
            addRuleField('', '');
        }

        careModal.classList.remove('hidden');
        careModal.classList.add('flex');
    }

    function closeCareModal() {
        careModal.classList.add('hidden');
        careModal.classList.remove('flex');
    }

    function addRuleField(key = '', value = '') {
        const id = Date.now() + Math.random();
        const div = document.createElement('div');
        div.className = 'flex gap-2 items-start rule-item';
        div.innerHTML = `
            <input type="text" placeholder="Tipe (cth: watering)" value="${key}" class="rule-key w-1/3 px-3 py-2 rounded-lg border border-outline-variant/40 text-[13px] focus:ring-2 focus:ring-[#006c49] outline-none">
            <textarea placeholder="Instruksi perawatan..." class="rule-value flex-1 px-3 py-2 rounded-lg border border-outline-variant/40 text-[13px] focus:ring-2 focus:ring-[#006c49] outline-none min-h-[40px] resize-y">${value}</textarea>
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-on-surface-variant hover:text-error transition-colors mt-0.5">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        `;
        rulesList.appendChild(div);
    }

    async function saveCareRules() {
        const id = document.getElementById('edit_plant_id').value;
        const items = document.querySelectorAll('.rule-item');
        let rulesObj = {};
        
        items.forEach(item => {
            const k = item.querySelector('.rule-key').value.trim();
            const v = item.querySelector('.rule-value').value.trim();
            if (k && v) {
                rulesObj[k] = v;
            }
        });

        try {
            const res = await fetch(`/api/admin/plants/${id}/care-rules`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ care_rules: rulesObj })
            });

            if (res.ok) {
                window.location.reload();
            } else {
                alert('Gagal menyimpan aturan perawatan.');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan saat menyimpan.');
        }
    }

    async function deleteTemplate(id) {
        if (!confirm('Peringatan: Menghapus template ini akan menghapus data dari Katalog Tanaman juga. Lanjutkan?')) return;
        
        try {
            const res = await fetch(`/api/admin/plants/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            if (res.ok) {
                window.location.reload();
            } else {
                alert('Gagal menghapus template.');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan.');
        }
    }
</script>
@endsection
