@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-2">
        <div class="flex flex-col gap-1">
            <h1 class="text-[28px] font-bold text-on-surface tracking-tight">Manajemen Pengguna</h1>
            <p class="text-[14px] text-on-surface-variant">Kelola anggota komunitas, atur akses, dan pantau keterlibatan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <form action="{{ route('admin.users') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="pl-9 pr-4 py-2 bg-surface-container-highest border border-outline-variant/30 rounded-lg text-[13px] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary w-64" onchange="this.form.submit()">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-on-surface-variant">search</span>
                </div>
                <select name="role" onchange="this.form.submit()" class="px-3 py-2 bg-surface-container-highest border border-outline-variant/30 rounded-lg text-[13px] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer font-medium">
                    <option value="">Semua Peran (Role)</option>
                    <option value="free" {{ request('role') == 'free' ? 'selected' : '' }}>Bibit (Gratis)</option>
                    <option value="pro" {{ request('role') == 'pro' ? 'selected' : '' }}>Subur (Pro)</option>
                    <option value="premium" {{ request('role') == 'premium' ? 'selected' : '' }}>Panen Raya (Premium)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users') }}" class="p-2 text-on-surface-variant hover:text-error transition-colors flex items-center gap-1 text-[12px] font-semibold" title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-surface-container-lowest rounded-[12px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="p-5 flex justify-between items-center border-b border-outline-variant/20">
            <h2 class="text-[16px] font-bold text-on-surface">Daftar Pengguna Aktif</h2>
            <div class="text-xs text-on-surface-variant font-medium">Total: {{ $users->total() }} Pengguna</div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto w-full no-scrollbar pb-32">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/20 text-left">
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase w-[35%]">Pengguna</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Peran</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Kebun</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($users as $user)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary-container/30 overflow-hidden flex items-center justify-center shrink-0 border border-outline-variant/30">
                                    @if($user->avatar)
                                        <img src="{{ filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover" alt="{{ $user->name }}">
                                    @else
                                        <span class="text-primary font-bold text-[13px] uppercase">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-[14px] font-bold text-on-surface">{{ $user->name }}</div>
                                    <div class="text-[12px] text-on-surface-variant">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-primary text-on-primary shadow-2xs">Admin</span>
                            @elseif($user->role === 'premium')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-secondary text-on-secondary shadow-2xs">Panen Raya (Premium)</span>
                            @elseif($user->role === 'pro')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#10b981] text-white shadow-2xs">Subur (Pro)</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-surface-container-highest text-on-surface-variant border border-outline-variant/30">Bibit (Gratis)</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-[13px] text-on-surface-variant font-medium">
                            {{ $user->gardens_count }} Kebun
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2 text-[13px] font-bold text-on-surface">
                                <div class="w-2 h-2 rounded-full bg-[#10b981]"></div>
                                Aktif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right relative">
                            @if($user->role !== 'admin')
                            <button class="btn-user-action text-on-surface-variant hover:text-primary transition-colors focus:outline-none" onclick="toggleDropdown({{ $user->id }})">
                                <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                            </button>
                            
                            {{-- Dropdown Action Menu --}}
                            <div id="dropdown-{{ $user->id }}" class="hidden absolute right-6 top-10 w-48 bg-white rounded-xl shadow-lg border border-outline-variant/20 z-20 py-2">
                                <button onclick="changeRole({{ $user->id }}, 'free')" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest hover:text-primary transition-colors">Ubah ke Bibit (Free)</button>
                                <button onclick="changeRole({{ $user->id }}, 'pro')" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest hover:text-primary transition-colors">Ubah ke Subur (Pro)</button>
                                <button onclick="changeRole({{ $user->id }}, 'premium')" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest hover:text-primary transition-colors">Ubah ke Panen Raya</button>
                                <hr class="my-1 border-outline-variant/20">
                                <button onclick="deleteUser({{ $user->id }})" class="w-full text-left px-4 py-2 text-[13px] text-red-500 hover:bg-red-50 transition-colors">Hapus Akun</button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-on-surface-variant font-medium text-sm">
                            Tidak ada pengguna yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-5 border-t border-outline-variant/20 bg-surface-container-lowest">
            {{ $users->links() }}
        </div>

    </div>

</div>

@push('scripts')
<script>
    function toggleDropdown(id) {
        // Hide all other dropdowns
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if(el.id !== 'dropdown-' + id) el.classList.add('hidden');
        });
        
        const dropdown = document.getElementById('dropdown-' + id);
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('td')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        }
    });

    async function changeRole(userId, newRole) {
        try {
            const response = await fetch(`/api/admin/users/${userId}/role`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ role: newRole })
            });
            
            if (response.ok) {
                Alert.toast.success('Role pengguna berhasil diperbarui!');
                setTimeout(() => window.location.reload(), 800);
            } else {
                const data = await response.json();
                Alert.error('Gagal', data.error || 'Gagal memperbarui role pengguna');
            }
        } catch (error) {
            console.error('Error:', error);
            Alert.error('Error', 'Terjadi kesalahan saat memperbarui role.');
        }
    }

    async function deleteUser(userId) {
        const result = await Alert.modal.confirm('Hapus Akun Pengguna?', 'Apakah Anda yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.', 'Ya, Hapus', true);
        if (!result.isConfirmed) return;
        
        try {
            const response = await fetch(`/api/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                Alert.toast.success('Akun berhasil dihapus');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                const data = await response.json();
                Alert.modal.error('Gagal', data.error || 'Failed to delete user');
            }
        } catch (error) {
            console.error('Error:', error);
            Alert.modal.error('Error', 'An error occurred');
        }
    }
</script>
@endpush
@endsection
