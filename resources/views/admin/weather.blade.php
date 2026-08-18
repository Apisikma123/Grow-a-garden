@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[32px] font-bold text-on-surface tracking-tight">Aturan Cuaca Engine</h1>
            <p class="text-[14px] text-on-surface-variant">Penyesuaian tugas perawatan otomatis berdasarkan kondisi cuaca.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <button onclick="openRuleModal('weather')" class="flex items-center gap-2 bg-white border border-outline-variant/30 text-[#006c49] font-bold text-[14px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add_alert</span>
                Aturan Peringatan
            </button>
            <button onclick="openRuleModal('activity')" class="flex items-center gap-2 bg-[#006c49] text-white font-bold text-[14px] px-5 py-2.5 rounded-full hover:bg-[#005236] active:scale-[0.98] transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Aturan Modifikasi
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-primary/10 border border-primary/30 text-primary px-4 py-3 rounded-xl mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Aturan Modifikasi Aktivitas (ActivityWeatherRule) --}}
    <div>
        <h2 class="text-[20px] font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#006c49]">tune</span> Aturan Modifikasi Tugas (Activity Rules)
        </h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($activityRules as $rule)
            <div class="bg-surface-container-lowest rounded-[20px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:ambient-shadow-lg transition-shadow relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-surface-container-low flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">{{ str_contains(strtolower($rule->activity_type), 'water') ? 'water_drop' : 'eco' }}</span>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-bold text-on-surface capitalize">{{ $rule->activity_type }}</h3>
                            <div class="text-[12px] text-on-surface-variant font-medium">Jika {{ $rule->weather_field }} {{ $rule->operator }} {{ $rule->threshold }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick='editActivityRule(@json($rule))' class="text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <form action="{{ route('admin.weather.activity.destroy', $rule) }}" method="POST" class="inline delete-form">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(event, this.form, 'Hapus Aturan Modifikasi?', 'Apakah Anda yakin ingin menghapus aturan modifikasi aktivitas ini?')" class="text-on-surface-variant hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <p class="text-[13px] text-on-surface-variant mb-4 italic">"{{ $rule->message }}"</p>
                
                <div class="mt-auto flex items-center justify-between border-t border-outline-variant/20 pt-4">
                    <span class="text-[12px] font-bold {{ $rule->action == 'DITUNDA' ? 'text-error' : 'text-primary' }} bg-surface-container-low px-3 py-1 rounded-full">
                        Aksi: {{ $rule->action }}
                    </span>
                    <span class="text-[12px] font-bold {{ $rule->is_active ? 'text-[#006c49]' : 'text-on-surface-variant' }}">
                        {{ $rule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center text-on-surface-variant bg-surface-container-lowest rounded-[20px] border border-dashed border-outline-variant/40">
                Belum ada aturan modifikasi aktivitas.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Aturan Peringatan Cuaca (WeatherRule) --}}
    <div class="mt-8">
        <h2 class="text-[20px] font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-warning">warning</span> Peringatan Cuaca Ekstrem (Weather Alerts)
        </h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($weatherRules as $rule)
            <div class="bg-surface-container-lowest rounded-[20px] p-6 ambient-shadow border border-outline-variant/30 flex flex-col hover:ambient-shadow-lg transition-shadow relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $rule->severity == 'CRITICAL' ? 'bg-error/10 text-error' : 'bg-warning/10 text-warning' }} flex items-center justify-center">
                            <span class="material-symbols-outlined">campaign</span>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-bold text-on-surface">{{ $rule->name }}</h3>
                            <div class="text-[12px] text-on-surface-variant font-medium">Jika {{ $rule->weather_field }} {{ $rule->operator }} {{ $rule->threshold }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick='editWeatherRule(@json($rule))' class="text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <form action="{{ route('admin.weather.rules.destroy', $rule) }}" method="POST" class="inline delete-form">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(event, this.form, 'Hapus Peringatan Cuaca?', 'Apakah Anda yakin ingin menghapus aturan peringatan cuaca ini?')" class="text-on-surface-variant hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <p class="text-[13px] text-on-surface-variant mb-4 italic">"{{ $rule->message }}"</p>
                
                <div class="mt-auto flex items-center justify-between border-t border-outline-variant/20 pt-4">
                    <span class="text-[12px] font-bold uppercase tracking-wider {{ $rule->severity == 'CRITICAL' ? 'text-error' : ($rule->severity == 'HIGH' ? 'text-[#b45309]' : 'text-warning') }}">
                        Level: {{ $rule->severity }}
                    </span>
                    <span class="text-[12px] font-bold {{ $rule->is_active ? 'text-[#006c49]' : 'text-on-surface-variant' }}">
                        {{ $rule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center text-on-surface-variant bg-surface-container-lowest rounded-[20px] border border-dashed border-outline-variant/40">
                Belum ada peringatan cuaca.
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Modals --}}

{{-- Activity Rule Modal --}}
<div id="activityRuleModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-outline-variant/20 flex justify-between items-center">
            <h2 class="text-[20px] font-bold text-on-surface" id="activityModalTitle">Tambah Aturan Modifikasi</h2>
            <button onclick="closeRuleModal('activity')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="activityRuleForm" method="POST" action="{{ route('admin.weather.activity.store') }}" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="_method" id="activityMethod" value="POST">
                
                <div>
                    <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Tipe Aktivitas</label>
                    <input type="text" name="activity_type" id="ar_activity_type" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="cth: watering">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Kondisi Cuaca</label>
                        <select name="weather_field" id="ar_weather_field" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                            <option value="rain_probability">Peluang Hujan</option>
                            <option value="temperature">Suhu</option>
                            <option value="humidity">Kelembapan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Operator</label>
                        <select name="operator" id="ar_operator" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                            <option value=">">&gt;</option>
                            <option value="<">&lt;</option>
                            <option value=">=">&gt;=</option>
                            <option value="<=">&lt;=</option>
                            <option value="==">==</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Batas (Threshold)</label>
                        <input type="number" step="0.01" name="threshold" id="ar_threshold" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="cth: 70">
                    </div>
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Aksi pada Tugas</label>
                    <select name="action" id="ar_action" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                        <option value="DITUNDA">DITUNDA (Skip/Delay)</option>
                        <option value="TIDAK_DISARANKAN">TIDAK DISARANKAN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Pesan Penjelasan</label>
                    <textarea name="message" id="ar_message" required rows="3" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="cth: Penyiraman ditunda karena peluang hujan tinggi..."></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="ar_is_active" value="1" checked class="rounded border-outline-variant/40 text-primary focus:ring-primary">
                    <label for="ar_is_active" class="text-[13px] font-bold text-on-surface">Aturan Aktif</label>
                </div>
                <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-outline-variant/20">
                    <button type="button" onclick="closeRuleModal('activity')" class="px-5 py-2.5 rounded-lg font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#006c49] text-white font-bold hover:bg-[#005236] transition-colors">Simpan Aturan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Weather Alert Rule Modal --}}
<div id="weatherRuleModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-outline-variant/20 flex justify-between items-center">
            <h2 class="text-[20px] font-bold text-on-surface" id="weatherModalTitle">Tambah Peringatan Cuaca</h2>
            <button onclick="closeRuleModal('weather')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="weatherRuleForm" method="POST" action="{{ route('admin.weather.rules.store') }}" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="_method" id="weatherMethod" value="POST">
                
                <div>
                    <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Nama Peringatan</label>
                    <input type="text" name="name" id="wr_name" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="cth: Risiko Jamur (Kelembapan Tinggi)">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Kondisi Cuaca</label>
                        <select name="weather_field" id="wr_weather_field" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                            <option value="humidity">Kelembapan</option>
                            <option value="temperature">Suhu</option>
                            <option value="wind_speed">Kec. Angin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Operator</label>
                        <select name="operator" id="wr_operator" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                            <option value=">">&gt;</option>
                            <option value="<">&lt;</option>
                            <option value=">=">&gt;=</option>
                            <option value="<=">&lt;=</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Batas (Threshold)</label>
                        <input type="number" step="0.01" name="threshold" id="wr_threshold" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="cth: 80">
                    </div>
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Tingkat Bahaya (Severity)</label>
                    <select name="severity" id="wr_severity" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                        <option value="LOW">LOW</option>
                        <option value="MEDIUM">MEDIUM</option>
                        <option value="HIGH">HIGH</option>
                        <option value="CRITICAL">CRITICAL</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Pesan Peringatan</label>
                    <textarea name="message" id="wr_message" required rows="3" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="cth: Waspada kelembapan tinggi, risiko jamur..."></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="wr_is_active" value="1" checked class="rounded border-outline-variant/40 text-primary focus:ring-primary">
                    <label for="wr_is_active" class="text-[13px] font-bold text-on-surface">Aturan Aktif</label>
                </div>
                <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-outline-variant/20">
                    <button type="button" onclick="closeRuleModal('weather')" class="px-5 py-2.5 rounded-lg font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#006c49] text-white font-bold hover:bg-[#005236] transition-colors">Simpan Peringatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRuleModal(type) {
        if(type === 'activity') {
            document.getElementById('activityRuleForm').action = "{{ route('admin.weather.activity.store') }}";
            document.getElementById('activityMethod').value = "POST";
            document.getElementById('activityRuleForm').reset();
            document.getElementById('activityModalTitle').innerText = "Tambah Aturan Modifikasi";
            document.getElementById('activityRuleModal').classList.remove('hidden');
            document.getElementById('activityRuleModal').classList.add('flex');
        } else {
            document.getElementById('weatherRuleForm').action = "{{ route('admin.weather.rules.store') }}";
            document.getElementById('weatherMethod').value = "POST";
            document.getElementById('weatherRuleForm').reset();
            document.getElementById('weatherModalTitle').innerText = "Tambah Peringatan Cuaca";
            document.getElementById('weatherRuleModal').classList.remove('hidden');
            document.getElementById('weatherRuleModal').classList.add('flex');
        }
    }

    function closeRuleModal(type) {
        if(type === 'activity') {
            document.getElementById('activityRuleModal').classList.add('hidden');
            document.getElementById('activityRuleModal').classList.remove('flex');
        } else {
            document.getElementById('weatherRuleModal').classList.add('hidden');
            document.getElementById('weatherRuleModal').classList.remove('flex');
        }
    }

    function editActivityRule(rule) {
        document.getElementById('activityRuleForm').action = `/admin/weather/activity-rules/${rule.id}`;
        document.getElementById('activityMethod').value = "PUT";
        document.getElementById('activityModalTitle').innerText = "Edit Aturan Modifikasi";
        
        document.getElementById('ar_activity_type').value = rule.activity_type;
        document.getElementById('ar_weather_field').value = rule.weather_field;
        document.getElementById('ar_operator').value = rule.operator;
        document.getElementById('ar_threshold').value = rule.threshold;
        document.getElementById('ar_action').value = rule.action;
        document.getElementById('ar_message').value = rule.message;
        document.getElementById('ar_is_active').checked = rule.is_active;

        document.getElementById('activityRuleModal').classList.remove('hidden');
        document.getElementById('activityRuleModal').classList.add('flex');
    }

    function editWeatherRule(rule) {
        document.getElementById('weatherRuleForm').action = `/admin/weather/rules/${rule.id}`;
        document.getElementById('weatherMethod').value = "PUT";
        document.getElementById('weatherModalTitle').innerText = "Edit Peringatan Cuaca";
        
        document.getElementById('wr_name').value = rule.name;
        document.getElementById('wr_weather_field').value = rule.weather_field;
        document.getElementById('wr_operator').value = rule.operator;
        document.getElementById('wr_threshold').value = rule.threshold;
        document.getElementById('wr_severity').value = rule.severity;
        document.getElementById('wr_message').value = rule.message;
        document.getElementById('wr_is_active').checked = rule.is_active;

        document.getElementById('weatherRuleModal').classList.remove('hidden');
        document.getElementById('weatherRuleModal').classList.add('flex');
    }

    async function confirmDelete(event, form, title, text) {
        event.preventDefault();
        const result = await Alert.confirm(title || 'Konfirmasi Hapus', text || 'Apakah Anda yakin ingin menghapus data ini?', 'Ya, Hapus', true);
        if (result && result.isConfirmed) {
            form.submit();
        }
    }
</script>
@endsection
