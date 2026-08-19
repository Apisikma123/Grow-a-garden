@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight">Katalog Tanaman</h1>
            <p class="text-[14px] text-on-surface-variant">Kelola database tanaman global termasuk taksonomi, kondisi ideal, dan status.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <form action="{{ route('admin.plants') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nama ilmiah..." class="pl-9 pr-4 py-2 bg-surface-container-highest border border-outline-variant/30 rounded-lg text-[13px] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary w-64" onchange="this.form.submit()">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-on-surface-variant">search</span>
                </div>
                <select name="category_id" onchange="this.form.submit()" class="px-3 py-2 bg-surface-container-highest border border-outline-variant/30 rounded-lg text-[13px] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.plants') }}" class="p-2 text-on-surface-variant hover:text-error transition-colors flex items-center gap-1 text-[12px] font-semibold" title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                        Reset
                    </a>
                @endif
            </form>
            <button onclick="openCategoryModal()" class="flex items-center gap-2 bg-secondary text-on-secondary font-bold text-[14px] px-4 py-2.5 rounded-lg hover:bg-secondary/90 active:scale-[0.98] transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">category</span>
                Tambah Kategori
            </button>
            <button onclick="openPlantModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-5 py-2.5 rounded-lg hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Tambah Tanaman Baru
            </button>
        </div>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-surface-container-lowest rounded-[12px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
        
        {{-- Table --}}
        <div class="overflow-x-auto w-full no-scrollbar">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/20 text-left">
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Nama Tanaman</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Nama Ilmiah</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Kategori</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Masa Panen</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($plants as $template)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="text-[14px] font-bold text-on-surface">{{ $template->name_id }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[12px] italic text-on-surface-variant">{{ $template->scientific_name ?? 'Unknown' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface text-[11px] font-semibold text-on-surface-variant">
                                {{ $template->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-[12px] font-medium text-on-surface-variant">{{ $template->harvest_start_day }} Hari</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button onclick='editPlantModal(@json($template))' class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button onclick="deletePlant({{ $template->id }})" class="p-2 text-on-surface-variant hover:text-error hover:bg-error/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-on-surface-variant">
                            Belum ada tanaman di katalog.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-5 border-t border-outline-variant/20 bg-surface-container-lowest">
            {{ $plants->links() }}
        </div>

    </div>
</div>

{{-- Modal Form Tambah/Edit --}}
<div id="plantModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closePlantModal()"></div>
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="w-full max-w-2xl bg-surface-container-lowest rounded-3xl p-6 sm:p-8 ambient-shadow-lg relative z-10 border border-outline-variant/30 flex flex-col gap-6 text-left">
            <div class="flex items-center justify-between pb-4 border-b border-outline-variant/20">
                <h2 class="text-xl font-bold text-on-surface flex items-center gap-2" id="plantModalTitle">
                    <span class="material-symbols-outlined text-primary">local_florist</span> Tambah Tanaman Baru
                </h2>
                <button onclick="closePlantModal()" class="text-on-surface-variant hover:text-on-surface p-1">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>
            
            <form id="plantForm" class="flex flex-col gap-5 w-full">
                <input type="hidden" id="plant_id">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 w-full">
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Tanaman (Indonesia)</label>
                        <input type="text" id="name_id" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Nama Ilmiah</label>
                        <input type="text" id="scientific_name" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Kategori</label>
                        <select id="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <h3 class="text-sm font-bold text-on-surface border-b border-outline-variant/20 pb-2 mt-2 w-full">Siklus Pertumbuhan (Hari Tanam)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 w-full">
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant mb-1">Semai</label>
                        <input type="number" id="germination_day" class="w-full px-3 py-2 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface" placeholder="Hari ke-">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant mb-1">Persemaian</label>
                        <input type="number" id="seedling_day" class="w-full px-3 py-2 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface" placeholder="Hari ke-">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant mb-1">Awal Panen</label>
                        <input type="number" id="harvest_start_day" required class="w-full px-3 py-2 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface" placeholder="Hari ke-">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant mb-1">Akhir Panen</label>
                        <input type="number" id="harvest_end_day" required class="w-full px-3 py-2 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface" placeholder="Hari ke-">
                    </div>
                </div>

                <h3 class="text-sm font-bold text-on-surface border-b border-outline-variant/20 pb-2 mt-2 w-full">Kondisi Lingkungan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant mb-1">pH Tanah Minimal</label>
                        <input type="number" step="0.1" id="soil_ph_min" required class="w-full px-3 py-2 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface" placeholder="Misal: 5.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant mb-1">pH Tanah Maksimal</label>
                        <input type="number" step="0.1" id="soil_ph_max" required class="w-full px-3 py-2 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface" placeholder="Misal: 6.5">
                    </div>
                </div>

                <div class="pt-4 border-t border-outline-variant/20 flex justify-end gap-3 w-full">
                    <button type="button" onclick="closePlantModal()" class="px-5 py-2.5 rounded-xl font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors text-sm">Batal</button>
                    <button type="button" onclick="savePlant()" class="px-5 py-2.5 rounded-xl bg-[#006c49] text-white font-bold hover:bg-[#005c3a] transition-colors text-sm shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Kelola / Tambah Kategori --}}
<div id="categoryModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeCategoryModal()"></div>
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="w-full max-w-lg bg-surface-container-lowest rounded-3xl p-6 sm:p-8 ambient-shadow-lg relative z-10 border border-outline-variant/30 flex flex-col gap-6 text-left">
            <div class="flex items-center justify-between pb-4 border-b border-outline-variant/20">
                <h3 class="text-xl font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#006c49]">category</span> Kelola Kategori Tanaman
                </h3>
                <button onclick="closeCategoryModal()" class="text-on-surface-variant hover:text-on-surface p-1">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            {{-- Form Tambah Kategori Baru --}}
            <form id="categoryForm" onsubmit="event.preventDefault(); saveCategory();" class="flex flex-col gap-2 w-full">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Kategori Baru</label>
                <div class="flex items-center gap-2 w-full">
                    <input type="text" id="category_name" placeholder="Misal: Sayuran Buah, Rempah" required class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-outline-variant/40 bg-surface focus:outline-none focus:border-primary text-sm text-on-surface">
                    <button type="submit" class="bg-[#006c49] text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-[#005c3a] transition-all shrink-0 shadow-sm">
                        Tambah
                    </button>
                </div>
            </form>

            {{-- Daftar Kategori Saat Ini --}}
            <div class="flex flex-col gap-3 w-full">
                <h4 class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Daftar Kategori</h4>
                <div class="flex flex-col divide-y divide-outline-variant/20 max-h-[260px] overflow-y-auto pr-1 rounded-xl border border-outline-variant/20 px-4 bg-surface/50">
                    @forelse($categories as $cat)
                        <div class="py-3 flex justify-between items-center text-sm w-full gap-2">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="font-semibold text-on-surface truncate">{{ $cat->name }}</span>
                                <span class="text-xs bg-surface-container-high text-on-surface-variant px-2.5 py-0.5 rounded-full font-medium shrink-0">
                                    {{ $cat->templates_count ?? 0 }} tanaman
                                </span>
                            </div>
                            @if(($cat->templates_count ?? 0) === 0)
                                <button onclick="deleteCategory({{ $cat->id }})" class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors rounded-lg shrink-0" title="Hapus Kategori">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            @else
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant/40 cursor-not-allowed shrink-0 p-1.5" title="Kategori sedang digunakan oleh tanaman">lock</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-on-surface-variant py-6 text-center w-full">Belum ada kategori.</div>
                    @endforelse
                </div>
            </div>

            <div class="pt-2 flex justify-end w-full">
                <button type="button" onclick="closeCategoryModal()" class="px-5 py-2.5 rounded-xl font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors text-sm">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openPlantModal() {
        document.getElementById('plantForm').reset();
        document.getElementById('plant_id').value = '';
        document.getElementById('plantModalTitle').innerHTML = '<span class="material-symbols-outlined text-primary">local_florist</span> Tambah Tanaman Baru';
        document.getElementById('plantModal').classList.remove('hidden');
    }

    function editPlantModal(plant) {
        document.getElementById('plant_id').value = plant.id;
        document.getElementById('name_id').value = plant.name_id;
        document.getElementById('scientific_name').value = plant.scientific_name;
        document.getElementById('category_id').value = plant.category_id;
        document.getElementById('germination_day').value = plant.germination_day || '';
        document.getElementById('seedling_day').value = plant.seedling_day || '';
        document.getElementById('harvest_start_day').value = plant.harvest_start_day;
        document.getElementById('harvest_end_day').value = plant.harvest_end_day;
        document.getElementById('soil_ph_min').value = plant.soil_ph_min;
        document.getElementById('soil_ph_max').value = plant.soil_ph_max;

        document.getElementById('plantModalTitle').innerHTML = '<span class="material-symbols-outlined text-primary">edit</span> Edit Tanaman';
        document.getElementById('plantModal').classList.remove('hidden');
    }

    function closePlantModal() {
        document.getElementById('plantModal').classList.add('hidden');
    }

    async function savePlant() {
        const id = document.getElementById('plant_id').value;
        const data = {
            name_id: document.getElementById('name_id').value,
            scientific_name: document.getElementById('scientific_name').value,
            category_id: document.getElementById('category_id').value,
            germination_day: document.getElementById('germination_day').value || null,
            seedling_day: document.getElementById('seedling_day').value || null,
            harvest_start_day: document.getElementById('harvest_start_day').value,
            harvest_end_day: document.getElementById('harvest_end_day').value,
            soil_ph_min: document.getElementById('soil_ph_min').value,
            soil_ph_max: document.getElementById('soil_ph_max').value,
        };

        const method = id ? 'PUT' : 'POST';
        const url = id ? `/api/admin/plants/${id}` : `/api/admin/plants`;

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                Alert.toast.success('Tanaman berhasil disimpan!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                const errorData = await res.json();
                Alert.modal.error('Gagal Menyimpan', errorData.message || 'Terjadi kesalahan pada input data.');
            }
        } catch (e) {
            console.error(e);
            Alert.modal.error('Error Sistem', 'Terjadi kesalahan saat menyambung ke server.');
        }
    }

    async function deletePlant(id) {
        const result = await Alert.modal.confirm('Hapus Tanaman?', 'Semua kebun yang menggunakan tanaman ini mungkin terpengaruh. Aksi ini permanen.', 'Ya, Hapus', true);
        if (!result.isConfirmed) return;
        
        try {
            const res = await fetch(`/api/admin/plants/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (res.ok) {
                Alert.toast.success('Tanaman berhasil dihapus!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                Alert.modal.error('Gagal', 'Tidak dapat menghapus tanaman.');
            }
        } catch (e) {
            console.error(e);
            Alert.modal.error('Error Sistem', 'Terjadi kesalahan sistem.');
        }
    }

    function openCategoryModal() {
        document.getElementById('categoryForm').reset();
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    async function saveCategory() {
        const nameInput = document.getElementById('category_name');
        const name = nameInput.value.trim();
        if (!name) return;

        try {
            const res = await fetch('/api/admin/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: name })
            });

            const data = await res.json();
            if (res.ok) {
                Alert.toast.success('Kategori berhasil ditambahkan!');
                setTimeout(() => window.location.reload(), 800);
            } else {
                Alert.modal.error('Gagal Tambah Kategori', data.message || 'Nama kategori sudah ada atau tidak valid.');
            }
        } catch (e) {
            console.error(e);
            Alert.modal.error('Error Sistem', 'Terjadi kesalahan saat menyambung ke server.');
        }
    }

    async function deleteCategory(id) {
        const result = await Alert.modal.confirm('Hapus Kategori?', 'Kategori yang dihapus tidak bisa dikembalikan.', 'Ya, Hapus', true);
        if (!result.isConfirmed) return;

        try {
            const res = await fetch(`/api/admin/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await res.json();
            if (res.ok) {
                Alert.toast.success('Kategori berhasil dihapus!');
                setTimeout(() => window.location.reload(), 800);
            } else {
                Alert.modal.error('Gagal Hapus', data.message || 'Tidak dapat menghapus kategori.');
            }
        } catch (e) {
            console.error(e);
            Alert.modal.error('Error Sistem', 'Terjadi kesalahan sistem.');
        }
    }
</script>
@endsection
