@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight">Katalog Tanaman</h1>
            <p class="text-[14px] text-on-surface-variant">Kelola database tanaman global termasuk taksonomi, kondisi ideal, dan status.</p>
        </div>
        <button onclick="openModal('add')" class="flex items-center gap-2 bg-primary text-on-primary font-bold text-[14px] px-5 py-2.5 rounded-lg hover:bg-primary/90 active:scale-[0.98] transition-all shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Tambah Tanaman Baru
        </button>
    </div>

    {{-- Filters Toolbar --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.plants') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search plants..." class="px-4 py-2 rounded-lg border border-outline-variant/40 text-[13px] text-on-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <select name="category" class="px-4 py-2 rounded-lg border border-outline-variant/40 text-[13px] text-on-surface focus:ring-2 focus:ring-primary outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2 rounded-lg bg-surface-container-high text-on-surface font-bold text-[13px] hover:bg-surface-container-highest transition-colors">
                Filter
            </button>
            @if(request()->has('search') || request()->has('category'))
                <a href="{{ route('admin.plants') }}" class="px-4 py-2 text-[13px] text-primary hover:underline">Clear</a>
            @endif
        </form>
        
        <div class="px-5 py-2 rounded-lg border border-outline-variant/20 bg-surface-container-lowest text-on-surface-variant font-medium text-[13px] shrink-0">
            {{ $plants->total() }} items found
        </div>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-surface-container-lowest rounded-[12px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
        
        {{-- Table --}}
        <div class="overflow-x-auto w-full no-scrollbar">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/20 text-left">
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Preview</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase w-[30%]">Plant Details</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Kategori</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Color Code</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    
                    @foreach($plants as $plant)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container-high border border-outline-variant/20 flex items-center justify-center font-bold text-on-surface-variant text-[14px]">
                                {{ substr($plant->name_id, 0, 2) }}
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[14px] font-bold text-on-surface mb-0.5">{{ $plant->name_id }}</div>
                            <div class="text-[12px] italic text-on-surface-variant">{{ $plant->scientific_name }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/30 bg-surface text-[11px] font-semibold text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">eco</span>
                                {{ $plant->category->name }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full {{ $plant->category_id == 1 ? 'bg-[#10b981]' : 'bg-[#E53A3A]' }} shadow-sm"></div>
                                <span class="text-[10px] font-bold text-on-surface-variant tracking-wider">{{ $plant->category_id == 1 ? 'Daun' : 'Buah' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#10b981]/10 text-[#006c49]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></div>
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button onclick='openModal("edit", @json($plant))' class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button onclick="deletePlant({{ $plant->id }})" class="p-2 text-on-surface-variant hover:text-error hover:bg-error/5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-5 flex justify-between items-center border-t border-outline-variant/20 bg-surface-container-lowest">
            <div class="text-[13px] text-on-surface-variant font-medium">
                Showing {{ $plants->firstItem() ?? 0 }} to {{ $plants->lastItem() ?? 0 }} of {{ $plants->total() }} entries
            </div>
            
            <div class="flex items-center gap-2 text-on-surface-variant">
                {{ $plants->links() }}
            </div>
        </div>

    </div>

</div>

{{-- Add / Edit Modal --}}
<div id="plantModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-container-lowest w-full max-w-2xl rounded-2xl shadow-xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-outline-variant/20 flex justify-between items-center">
            <h2 id="modalTitle" class="text-[20px] font-bold text-on-surface">Tambah Tanaman Baru</h2>
            <button onclick="closeModal()" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="plantForm" class="flex flex-col gap-4">
                <input type="hidden" id="plant_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">Nama Tanaman (ID)</label>
                        <input type="text" id="name_id" required class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">Nama Ilmiah</label>
                        <input type="text" id="scientific_name" required class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[13px] font-semibold text-on-surface">Kategori</label>
                    <select id="category_id" required class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">Germinasi (Hari)</label>
                        <input type="number" id="germination_day" class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">Pembibitan (Hari)</label>
                        <input type="number" id="seedling_day" class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">Mulai Panen (HST)</label>
                        <input type="number" id="harvest_start_day" required class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">pH Min</label>
                        <input type="number" step="0.1" id="soil_ph_min" required class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[13px] font-semibold text-on-surface">pH Max</label>
                        <input type="number" step="0.1" id="soil_ph_max" required class="px-4 py-2 rounded-lg border border-outline-variant/40 focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>

            </form>
        </div>
        <div class="p-6 border-t border-outline-variant/20 flex justify-end gap-3 bg-surface-container-lowest">
            <button onclick="closeModal()" class="px-5 py-2.5 rounded-lg font-bold text-on-surface-variant hover:bg-surface-container-highest transition-colors">Batal</button>
            <button onclick="savePlant()" class="px-5 py-2.5 rounded-lg bg-primary text-on-primary font-bold hover:bg-primary/90 transition-colors">Simpan</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('plantModal');
    let currentMode = 'add';

    function openModal(mode, plant = null) {
        currentMode = mode;
        document.getElementById('modalTitle').innerText = mode === 'add' ? 'Tambah Tanaman Baru' : 'Edit Tanaman';
        
        if (mode === 'add') {
            document.getElementById('plantForm').reset();
            document.getElementById('plant_id').value = '';
        } else if (plant) {
            document.getElementById('plant_id').value = plant.id;
            document.getElementById('name_id').value = plant.name_id;
            document.getElementById('scientific_name').value = plant.scientific_name;
            document.getElementById('category_id').value = plant.category_id;
            document.getElementById('germination_day').value = plant.germination_day;
            document.getElementById('seedling_day').value = plant.seedling_day;
            document.getElementById('harvest_start_day').value = plant.harvest_start_day;
            document.getElementById('soil_ph_min').value = plant.soil_ph_min;
            document.getElementById('soil_ph_max').value = plant.soil_ph_max;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function savePlant() {
        const id = document.getElementById('plant_id').value;
        const data = {
            name_id: document.getElementById('name_id').value,
            scientific_name: document.getElementById('scientific_name').value,
            category_id: document.getElementById('category_id').value,
            germination_day: document.getElementById('germination_day').value,
            seedling_day: document.getElementById('seedling_day').value,
            harvest_start_day: document.getElementById('harvest_start_day').value,
            soil_ph_min: document.getElementById('soil_ph_min').value,
            soil_ph_max: document.getElementById('soil_ph_max').value,
        };

        const url = currentMode === 'add' ? '/api/admin/plants' : `/api/admin/plants/${id}`;
        const method = currentMode === 'add' ? 'POST' : 'PUT';

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
                alert('Gagal menyimpan tanaman.');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan.');
        }
    }

    async function deletePlant(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus tanaman ini?')) return;
        
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
            alert('Terjadi kesalahan.');
        }
    }
</script>
@endsection
