@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight">Katalog Tanaman</h1>
            <p class="text-[14px] text-on-surface-variant">Kelola database tanaman global termasuk taksonomi, kondisi ideal, dan status.</p>
        </div>
        <button onclick="openPlantModal()" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-5 py-2.5 rounded-lg hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Tambah Tanaman Baru
        </button>
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
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">pH Ideal</th>
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
                            <span class="text-[12px] font-medium text-on-surface-variant">{{ $template->harvest_start_day }} HST</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-[12px] font-medium text-on-surface-variant">{{ $template->soil_ph_min }} - {{ $template->soil_ph_max }}</span>
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
                        <td colspan="6" class="py-8 text-center text-on-surface-variant">
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
<div id="plantModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-container-lowest w-full max-w-2xl rounded-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-outline-variant/20 flex justify-between items-center">
            <h2 class="text-[20px] font-bold text-on-surface" id="plantModalTitle">Tambah Tanaman</h2>
            <button onclick="closePlantModal()" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="plantForm" class="flex flex-col gap-5">
                <input type="hidden" id="plant_id">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Nama Tanaman (Indonesia)</label>
                        <input type="text" id="name_id" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Nama Ilmiah</label>
                        <input type="text" id="scientific_name" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Kategori</label>
                        <select id="category_id" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <h3 class="text-[14px] font-bold text-on-surface border-b border-outline-variant/20 pb-2 mt-2">Siklus Pertumbuhan (HST)</h3>
                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Semai (Germination)</label>
                        <input type="number" id="germination_day" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="Hari ke-">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Persemaian (Seedling)</label>
                        <input type="number" id="seedling_day" class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="Hari ke-">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">Awal Panen (Harvest)</label>
                        <input type="number" id="harvest_start_day" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="Hari ke-">
                    </div>
                </div>

                <h3 class="text-[14px] font-bold text-on-surface border-b border-outline-variant/20 pb-2 mt-2">Kondisi Lingkungan</h3>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">pH Tanah Minimal</label>
                        <input type="number" step="0.1" id="soil_ph_min" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="Misal: 5.5">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-on-surface-variant mb-1">pH Tanah Maksimal</label>
                        <input type="number" step="0.1" id="soil_ph_max" required class="w-full px-3 py-2 border border-outline-variant/40 rounded-xl focus:ring-2 focus:ring-primary outline-none" placeholder="Misal: 6.5">
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-outline-variant/20 flex justify-end gap-3 bg-surface-container-lowest">
            <button type="button" onclick="closePlantModal()" class="px-5 py-2.5 rounded-lg font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors">Batal</button>
            <button type="button" onclick="savePlant()" class="px-5 py-2.5 rounded-lg bg-[#006c49] text-white font-bold hover:bg-[#005236] transition-colors">Simpan Data</button>
        </div>
    </div>
</div>

<script>
    function openPlantModal() {
        document.getElementById('plantForm').reset();
        document.getElementById('plant_id').value = '';
        document.getElementById('plantModalTitle').innerText = 'Tambah Tanaman Baru';
        document.getElementById('plantModal').classList.remove('hidden');
        document.getElementById('plantModal').classList.add('flex');
    }

    function editPlantModal(plant) {
        document.getElementById('plant_id').value = plant.id;
        document.getElementById('name_id').value = plant.name_id;
        document.getElementById('scientific_name').value = plant.scientific_name;
        document.getElementById('category_id').value = plant.category_id;
        document.getElementById('germination_day').value = plant.germination_day;
        document.getElementById('seedling_day').value = plant.seedling_day;
        document.getElementById('harvest_start_day').value = plant.harvest_start_day;
        document.getElementById('soil_ph_min').value = plant.soil_ph_min;
        document.getElementById('soil_ph_max').value = plant.soil_ph_max;

        document.getElementById('plantModalTitle').innerText = 'Edit Tanaman';
        document.getElementById('plantModal').classList.remove('hidden');
        document.getElementById('plantModal').classList.add('flex');
    }

    function closePlantModal() {
        document.getElementById('plantModal').classList.add('hidden');
        document.getElementById('plantModal').classList.remove('flex');
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
                window.location.reload();
            } else {
                alert('Gagal menyimpan data tanaman.');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan sistem.');
        }
    }

    async function deletePlant(id) {
        if (!confirm('Hapus tanaman ini dari database? Semua kebun yang menggunakan tanaman ini mungkin terpengaruh.')) return;
        
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
                alert('Gagal menghapus tanaman.');
            }
        } catch (e) {
            console.error(e);
        }
    }
</script>
@endsection
