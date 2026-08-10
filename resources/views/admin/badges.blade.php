@extends('layouts.admin')

@section('title', 'Kelola Badge & Prestasi — Admin')
@section('description', 'Manajemen badge, prestasi, dan pemberian pencapaian pengguna.')

@section('admin-content')
    <div class="flex flex-col gap-6 pb-10">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a] tracking-tight mb-1">Kelola Badge & Prestasi</h1>
                <p class="text-sm text-slate-500 font-medium">Buat badge baru atau berikan apresiasi prestasi secara manual kepada pengguna.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('award-badge-modal').classList.remove('hidden')" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-all shadow-sm flex items-center gap-2 active:scale-95 cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                    Berikan Badge ke User
                </button>

                <button onclick="document.getElementById('create-badge-modal').classList.remove('hidden')" class="bg-[#006c49] hover:bg-[#005236] text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-all shadow-sm flex items-center gap-2 active:scale-95 cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Tambah Badge
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold border border-emerald-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 text-blue-700 px-4 py-3 rounded-xl text-sm font-bold border border-blue-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">info</span>
                {{ session('info') }}
            </div>
        @endif

        {{-- Badge List Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($badges as $badge)
                <div class="bg-white rounded-2xl p-6 ambient-shadow border border-slate-200 flex flex-col justify-between relative overflow-hidden group hover:border-[#006c49]/40 transition-all">
                    <div class="flex items-start justify-between mb-4 z-10">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-[32px]">{{ $badge->icon_url ?? 'military_tech' }}</span>
                        </div>
                        <span class="bg-slate-100 text-slate-700 font-bold text-xs px-3 py-1 rounded-full">
                            {{ $badge->users_count }} Pemilik
                        </span>
                    </div>

                    <div class="z-10 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $badge->name }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">{{ $badge->description }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 z-10">
                        <button onclick="editBadge({{ json_encode($badge) }})" class="p-2 text-slate-600 hover:text-[#006c49] hover:bg-slate-50 rounded-lg transition-colors" title="Edit Badge">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>

                        <form action="{{ route('admin.badges.destroy', $badge->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus badge ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Badge">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-40">workspace_premium</span>
                    <p class="font-bold text-slate-700">Belum ada badge yang dibuat</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Create Badge Modal --}}
    <div id="create-badge-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="document.getElementById('create-badge-modal').classList.add('hidden')"></div>
        <div class="min-h-screen px-4 py-8 flex items-center justify-center">
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg relative z-10">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#006c49]">workspace_premium</span> Tambah Badge Baru
                    </h3>
                    <button onclick="document.getElementById('create-badge-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('admin.badges.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Badge</label>
                        <input type="text" name="name" required placeholder="Contoh: Panen Raya" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ikon (Material Symbol)</label>
                        <input type="text" name="icon_url" required placeholder="Contoh: military_tech, water_drop, eco" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi / Syarat</label>
                        <textarea name="description" rows="3" required placeholder="Jelaskan syarat mendapatkan badge ini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="document.getElementById('create-badge-modal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#006c49] hover:bg-[#005236]">Simpan Badge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Badge Modal --}}
    <div id="edit-badge-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="document.getElementById('edit-badge-modal').classList.add('hidden')"></div>
        <div class="min-h-screen px-4 py-8 flex items-center justify-center">
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg relative z-10">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#006c49]">edit</span> Edit Badge
                    </h3>
                    <button onclick="document.getElementById('edit-badge-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form id="edit-badge-form" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Badge</label>
                        <input type="text" id="edit-name" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ikon (Material Symbol)</label>
                        <input type="text" id="edit-icon_url" name="icon_url" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi / Syarat</label>
                        <textarea id="edit-description" name="description" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="document.getElementById('edit-badge-modal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#006c49] hover:bg-[#005236]">Perbarui Badge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Award Badge Modal --}}
    <div id="award-badge-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="document.getElementById('award-badge-modal').classList.add('hidden')"></div>
        <div class="min-h-screen px-4 py-8 flex items-center justify-center">
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 md:p-8 ambient-shadow-lg relative z-10">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500">military_tech</span> Berikan Badge Manual
                    </h3>
                    <button onclick="document.getElementById('award-badge-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('admin.badges.award') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Pengguna</label>
                        <select name="user_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm">
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Badge</label>
                        <select name="badge_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#006c49] text-sm">
                            <option value="">-- Pilih Badge --</option>
                            @foreach($badges as $badge)
                                <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="document.getElementById('award-badge-modal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600">Berikan Badge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editBadge(badge) {
            document.getElementById('edit-badge-form').action = '/admin/badges/' + badge.id;
            document.getElementById('edit-name').value = badge.name;
            document.getElementById('edit-icon_url').value = badge.icon_url || '';
            document.getElementById('edit-description').value = badge.description || '';
            document.getElementById('edit-badge-modal').classList.remove('hidden');
        }
    </script>
@endsection
