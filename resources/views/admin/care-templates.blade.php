@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-8">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
        <div class="flex flex-col gap-2">
            <h1 class="text-[32px] font-black text-on-surface tracking-tight">Template Pertumbuhan & Perawatan</h1>
            <p class="text-[15px] text-on-surface-variant max-w-[600px] leading-relaxed">Konfigurasi siklus hidup otomatis, instruksi perawatan, dan aturan cuaca untuk setiap tanaman.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <form action="{{ route('admin.care-templates') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari template tanaman..." class="pl-9 pr-4 py-2 bg-surface-container-highest border border-outline-variant/30 rounded-lg text-[13px] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary w-56" onchange="this.form.submit()">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-on-surface-variant">search</span>
                </div>
                <select name="sort" onchange="this.form.submit()" class="px-3 py-2 bg-surface-container-highest border border-outline-variant/30 rounded-lg text-[13px] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer font-medium">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Kategori</option>
                </select>
                @if(request('search') || request('sort'))
                    <a href="{{ route('admin.care-templates') }}" class="p-2 text-on-surface-variant hover:text-error transition-colors flex items-center gap-1 text-[12px] font-semibold" title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                        Reset
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.plants') }}" class="flex items-center gap-2 bg-[#006c49] text-white font-bold text-[13px] px-5 py-2.5 rounded-lg hover:bg-[#005c3a] transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Data Tanaman
            </a>
        </div>
    </div>

    {{-- Grid of Templates --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8">
        
        @forelse($templates as $template)
        <div class="searchable-item bg-white rounded-[32px] p-7 ambient-shadow border border-outline-variant/20 flex flex-col gap-6 hover:ambient-shadow-lg transition-shadow" data-search="{{ $template->name_id }} {{ $template->scientific_name }}">
            
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
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center text-on-surface-variant border border-outline-variant/30">
            <span class="material-symbols-outlined text-[48px] mb-2 opacity-40">assignment</span>
            <p class="font-bold text-on-surface">Tidak ada template tanaman yang ditemukan.</p>
        </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    @if($templates->hasPages())
    <div class="p-5 bg-surface-container-lowest rounded-2xl border border-outline-variant/30 ambient-shadow">
        {{ $templates->links() }}
    </div>
    @endif

</div>

{{-- Edit Care Rules Modal --}}
<div id="careModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeCareModal()"></div>
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="w-full max-w-2xl bg-surface-container-lowest rounded-3xl p-6 sm:p-8 ambient-shadow-lg relative z-10 border border-outline-variant/30 flex flex-col gap-6 text-left">
            <div class="flex items-center justify-between pb-4 border-b border-outline-variant/20">
                <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">assignment</span> Edit Instruksi Perawatan
                </h2>
                <button onclick="closeCareModal()" class="text-on-surface-variant hover:text-on-surface p-1">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            {{-- Quick-Add Preset Buttons --}}
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-[#006c49]">bolt</span>
                    <span class="text-[11px] font-black tracking-widest text-on-surface uppercase">TAMBAH CEPAT ATURAN</span>
                </div>
                <div class="flex flex-wrap gap-2" id="quickAddButtons">
                    <button type="button" onclick="quickAddRule('watering')" class="quick-add-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#006c49]/20 bg-[#006c49]/5 text-[#006c49] text-[12px] font-bold hover:bg-[#006c49]/15 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">water_drop</span> Menyiram
                    </button>
                    <button type="button" onclick="quickAddRule('fertilizer')" class="quick-add-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#006c49]/20 bg-[#006c49]/5 text-[#006c49] text-[12px] font-bold hover:bg-[#006c49]/15 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">nutrition</span> Memupuk
                    </button>
                    <button type="button" onclick="quickAddRule('pruning')" class="quick-add-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#006c49]/20 bg-[#006c49]/5 text-[#006c49] text-[12px] font-bold hover:bg-[#006c49]/15 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">content_cut</span> Pangkas
                    </button>
                    <button type="button" onclick="quickAddRule('pest_check')" class="quick-add-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#006c49]/20 bg-[#006c49]/5 text-[#006c49] text-[12px] font-bold hover:bg-[#006c49]/15 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">bug_report</span> Cek Hama
                    </button>
                    <button type="button" onclick="quickAddRule('staking')" class="quick-add-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#006c49]/20 bg-[#006c49]/5 text-[#006c49] text-[12px] font-bold hover:bg-[#006c49]/15 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">fence</span> Ajir
                    </button>
                    <button type="button" onclick="quickAddRule('weeding')" class="quick-add-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#006c49]/20 bg-[#006c49]/5 text-[#006c49] text-[12px] font-bold hover:bg-[#006c49]/15 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">grass</span> Gulma
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto max-h-[50vh] pr-1">
                <form id="careForm" class="flex flex-col gap-4">
                    <input type="hidden" id="edit_plant_id">
                    
                    <div id="rulesList" class="flex flex-col gap-3">
                        <!-- Rules dynamically inserted here -->
                    </div>

                    <button type="button" onclick="addRuleField()" class="flex items-center gap-2 justify-center w-full py-2.5 border-2 border-dashed border-outline-variant/50 rounded-xl text-on-surface-variant font-bold text-sm hover:bg-surface-container-highest transition-colors mt-2">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Aturan Manual
                    </button>
                </form>
            </div>
            <div class="pt-4 border-t border-outline-variant/20 flex justify-end gap-3 w-full">
                <button onclick="closeCareModal()" class="px-5 py-2.5 rounded-xl font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors text-sm">Batal</button>
                <button onclick="saveCareRules()" class="px-5 py-2.5 rounded-xl bg-[#006c49] text-white font-bold hover:bg-[#005c3a] transition-colors text-sm shadow-sm">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const careModal = document.getElementById('careModal');
    const rulesList = document.getElementById('rulesList');

    // Default rule presets mapped to their display info & default instructions
    const RULE_PRESETS = {
        'watering':    { label: 'Menyiram',        icon: 'water_drop',  color: '#0288d1', defaultText: 'Siram setiap 2-3 hari sekali (atau saat media tanam 2 cm bagian atas mulai kering). Hindari genangan berlebih agar akar tidak busuk.' },
        'fertilizer':  { label: 'Memupuk',         icon: 'nutrition',   color: '#2e7d32', defaultText: 'Pemupukan setiap 7 hari dengan pupuk NPK atau organik cair terlarut sesuai fase pertumbuhan.' },
        'pruning':     { label: 'Pemangkasan',     icon: 'content_cut', color: '#6d4c41', defaultText: 'Pangkas daun tua menguning & tunas air setiap 14 hari untuk aerasi dan fokus energi nutrisi.' },
        'pest_check':  { label: 'Inspeksi Hama',   icon: 'bug_report',  color: '#c62828', defaultText: 'Inspeksi hama setiap 7 hari: periksa bagian bawah daun dari kutu kebul, tungau, atau ulat.' },
        'staking':     { label: 'Pemasangan Ajir',  icon: 'fence',       color: '#5d4037', defaultText: 'Pasang ajir dan periksa ikatan longgar setiap 14 hari agar tanaman kokoh dan tegak.' },
        'weeding':     { label: 'Penyiangan Gulma', icon: 'grass',       color: '#558b2f', defaultText: 'Penyiangan gulma liar dan penggemburan tanah sekitar perakaran setiap 12 hari.' },
        'drainage':    { label: 'Cek Drainase',     icon: 'water_damage',color: '#0277bd', defaultText: 'Pemeriksaan kelancaran lubang drainase pot dan sanitasi genangan air setiap 7 hari.' },
        'fungus_check':{ label: 'Sanitasi Jamur',   icon: 'coronavirus', color: '#ad1457', defaultText: 'Cek gejala bercak daun & jamur antraknosa setiap 10 hari, buang daun bergejala.' },
        'neem_spray':  { label: 'Pestisida Nabati', icon: 'spray',       color: '#00695c', defaultText: 'Penyemprotan preventif ekstrak daun mimba atau sabun insektisida setiap 14 hari sore hari.' },
    };

    function getRulePreset(key) {
        const k = key.toLowerCase();
        for (const [presetKey, preset] of Object.entries(RULE_PRESETS)) {
            if (k === presetKey || k.includes(presetKey) || presetKey.includes(k)) return { key: presetKey, ...preset };
        }
        // Fuzzy match for legacy keys
        if (k.includes('water') || k.includes('siram')) return { key: 'watering', ...RULE_PRESETS['watering'] };
        if (k.includes('fertiliz') || k.includes('pupuk')) return { key: 'fertilizer', ...RULE_PRESETS['fertilizer'] };
        if (k.includes('prun') || k.includes('pangkas') || k.includes('rempel')) return { key: 'pruning', ...RULE_PRESETS['pruning'] };
        if (k.includes('pest') || k.includes('hama')) return { key: 'pest_check', ...RULE_PRESETS['pest_check'] };
        if (k.includes('stak') || k.includes('ajir')) return { key: 'staking', ...RULE_PRESETS['staking'] };
        if (k.includes('weed') || k.includes('gulma')) return { key: 'weeding', ...RULE_PRESETS['weeding'] };
        if (k.includes('drain')) return { key: 'drainage', ...RULE_PRESETS['drainage'] };
        if (k.includes('fungus') || k.includes('jamur')) return { key: 'fungus_check', ...RULE_PRESETS['fungus_check'] };
        if (k.includes('neem') || k.includes('pestisida')) return { key: 'neem_spray', ...RULE_PRESETS['neem_spray'] };
        return null;
    }

    function getActiveRuleKeys() {
        const keys = [];
        document.querySelectorAll('.rule-item .rule-key').forEach(el => {
            if (el.value) keys.push(el.value);
        });
        return keys;
    }

    function updateQuickAddButtons() {
        const activeKeys = getActiveRuleKeys();
        document.querySelectorAll('.quick-add-btn').forEach(btn => {
            const onclickStr = btn.getAttribute('onclick') || '';
            const match = onclickStr.match(/quickAddRule\('([^']+)'/);
            if (match) {
                const ruleKey = match[1];
                if (activeKeys.includes(ruleKey)) {
                    btn.classList.add('opacity-40', 'pointer-events-none');
                    btn.disabled = true;
                } else {
                    btn.classList.remove('opacity-40', 'pointer-events-none');
                    btn.disabled = false;
                }
            }
        });
    }

    function quickAddRule(key, defaultValue = '') {
        // Prevent duplicate
        if (getActiveRuleKeys().includes(key)) {
            Alert.toast.warning('Aturan ini sudah ditambahkan!');
            return;
        }
        const text = defaultValue || (RULE_PRESETS[key]?.defaultText || '');
        addRuleField(key, text);
        updateQuickAddButtons();
    }

    function openCareModal(plantId, rules) {
        document.getElementById('edit_plant_id').value = plantId;
        rulesList.innerHTML = '';
        
        if (rules && Object.keys(rules).length > 0) {
            for (const [key, value] of Object.entries(rules)) {
                addRuleField(key, value);
            }
        }

        updateQuickAddButtons();
        careModal.classList.remove('hidden');
    }

    function closeCareModal() {
        careModal.classList.add('hidden');
    }

    function buildDropdownOptions(selectedKey = '') {
        let options = `<option value="" disabled ${!selectedKey ? 'selected' : ''}>— Pilih Tipe —</option>`;
        for (const [key, preset] of Object.entries(RULE_PRESETS)) {
            const sel = (key === selectedKey) ? 'selected' : '';
            options += `<option value="${key}" ${sel}>${preset.label}</option>`;
        }
        return options;
    }

    function addRuleField(key = '', value = '') {
        const preset = key ? getRulePreset(key) : null;
        const resolvedKey = preset ? preset.key : key;
        const icon = preset ? preset.icon : 'check_circle';
        const iconColor = preset ? preset.color : '#006c49';
        const initialText = value !== '' ? value : (preset?.defaultText || '');

        const div = document.createElement('div');
        div.className = 'flex gap-2 items-start rule-item bg-surface-container-low/50 rounded-2xl p-3 border border-outline-variant/15';
        div.innerHTML = `
            <div class="flex items-center justify-center w-9 h-9 rounded-xl shrink-0 mt-0.5" style="background: ${iconColor}15; color: ${iconColor};">
                <span class="material-symbols-outlined text-[20px] rule-icon">${icon}</span>
            </div>
            <div class="flex-1 flex flex-col gap-2">
                <select onchange="onRuleTypeChange(this)" class="rule-key px-3 py-2 rounded-lg border border-outline-variant/40 text-[13px] font-bold focus:ring-2 focus:ring-[#006c49] outline-none bg-white cursor-pointer">
                    ${buildDropdownOptions(resolvedKey)}
                </select>
                <textarea placeholder="Instruksi perawatan detail..." class="rule-value w-full px-3 py-2 rounded-lg border border-outline-variant/40 text-[13px] focus:ring-2 focus:ring-[#006c49] outline-none min-h-[48px] resize-y leading-relaxed">${initialText}</textarea>
            </div>
            <button type="button" onclick="removeRule(this)" class="p-2 text-on-surface-variant hover:text-error transition-colors mt-0.5 shrink-0">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        `;
        rulesList.appendChild(div);

        // Smooth entrance animation
        div.style.opacity = '0';
        div.style.transform = 'translateY(-8px)';
        requestAnimationFrame(() => {
            div.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            div.style.opacity = '1';
            div.style.transform = 'translateY(0)';
        });
    }

    function onRuleTypeChange(selectEl) {
        const key = selectEl.value;
        const preset = RULE_PRESETS[key];
        const ruleItem = selectEl.closest('.rule-item');
        const iconEl = ruleItem.querySelector('.rule-icon');
        const iconWrap = iconEl.closest('div');
        const textarea = ruleItem.querySelector('.rule-value');

        if (preset) {
            iconEl.textContent = preset.icon;
            iconWrap.style.background = preset.color + '15';
            iconWrap.style.color = preset.color;
            if (!textarea.value.trim() && preset.defaultText) {
                textarea.value = preset.defaultText;
            }
        }
        updateQuickAddButtons();
    }

    function removeRule(btn) {
        const item = btn.closest('.rule-item');
        item.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
        item.style.opacity = '0';
        item.style.transform = 'translateX(12px)';
        setTimeout(() => {
            item.remove();
            updateQuickAddButtons();
        }, 150);
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
                Alert.toast.success('Aturan perawatan berhasil disimpan!');
                setTimeout(() => window.location.reload(), 800);
            } else {
                Alert.toast.error('Gagal menyimpan aturan perawatan.');
            }
        } catch (e) {
            console.error(e);
            Alert.toast.error('Terjadi kesalahan saat menyimpan.');
        }
    }

    async function deleteTemplate(id) {
        const result = await Alert.modal.confirm('Peringatan Penghapusan', 'Menghapus template ini akan menghapus data dari Katalog Tanaman juga. Lanjutkan?', 'Ya, Hapus', true);
        if (!result.isConfirmed) return;
        
        try {
            const res = await fetch(`/api/admin/plants/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            if (res.ok) {
                Alert.toast.success('Template berhasil dihapus');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                Alert.modal.error('Gagal', 'Gagal menghapus template.');
            }
        } catch (e) {
            console.error(e);
            Alert.modal.error('Error', 'Terjadi kesalahan saat menghapus.');
        }
    }
</script>
@endsection

