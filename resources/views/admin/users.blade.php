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
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pengguna</option>
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
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-[#944a23] text-white shadow-2xs">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-primary text-on-primary shadow-2xs">Admin</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-surface-container-highest text-on-surface-variant border border-outline-variant/30">Pengguna</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-[13px] text-on-surface-variant font-medium">
                            @if(in_array($user->role, ['admin', 'super_admin']))
                                <span class="font-bold text-on-surface-variant/60">-</span>
                            @else
                                {{ $user->gardens_count }} Kebun
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2 text-[13px] font-bold text-on-surface">
                                <div class="w-2 h-2 rounded-full bg-[#10b981]"></div>
                                Aktif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right relative">
                            @if($user->id === auth()->id())
                                <span class="text-[11px] text-on-surface-variant font-bold bg-surface-container-high px-2 py-1 rounded-full">Akun Anda</span>
                            @elseif($user->role === 'super_admin')
                                <span class="text-[11px] font-black text-[#944a23] bg-[#944a23]/10 px-2.5 py-1 rounded-full uppercase tracking-wider">Super Admin</span>
                            @else
                                @php
                                    $isSuperAdmin = auth()->user()->role === 'super_admin';
                                    $canChangeRole = $isSuperAdmin;
                                    $canDelete = $isSuperAdmin || ($user->role === 'user');
                                @endphp

                                @if($canChangeRole || $canDelete)
                                <button type="button" class="btn-user-action text-on-surface-variant hover:text-primary p-1.5 rounded-lg hover:bg-surface-container-high transition-colors focus:outline-none" onclick="toggleDropdown(event, {{ $user->id }})">
                                    <span class="material-symbols-outlined text-[20px] pointer-events-none">more_horiz</span>
                                </button>
                                
                                {{-- Dropdown Action Menu --}}
                                <div id="dropdown-{{ $user->id }}" class="hidden absolute right-6 top-10 w-48 bg-white rounded-xl shadow-xl border border-outline-variant/30 z-50 py-2 text-left">
                                    @if($canChangeRole)
                                        @if($user->role === 'user')
                                            <button type="button" onclick="changeRole({{ $user->id }}, 'admin')" class="w-full text-left px-4 py-2.5 text-[13px] font-medium text-on-surface hover:bg-primary/10 hover:text-primary transition-colors flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                                Jadikan Admin
                                            </button>
                                        @elseif($user->role === 'admin')
                                            <button type="button" onclick="changeRole({{ $user->id }}, 'user')" class="w-full text-left px-4 py-2.5 text-[13px] font-medium text-on-surface hover:bg-surface-container-high hover:text-primary transition-colors flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px]">person</span>
                                                Ubah ke Pengguna
                                            </button>
                                        @endif
                                    @endif

                                    @if($canDelete)
                                        @if($canChangeRole)
                                            <hr class="my-1 border-outline-variant/20">
                                        @endif
                                        <button type="button" onclick="deleteUser({{ $user->id }})" class="w-full text-left px-4 py-2.5 text-[13px] font-medium text-[#ba1a1a] hover:bg-[#ba1a1a]/10 transition-colors flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                            Hapus Akun
                                        </button>
                                    @endif
                                </div>
                                @else
                                    <span class="text-[12px] text-on-surface-variant/50 font-bold">-</span>
                                @endif
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
    function toggleDropdown(event, id) {
        if (event) event.stopPropagation();
        const dropdown = document.getElementById('dropdown-' + id);
        if (!dropdown) return;
        const isCurrentlyHidden = dropdown.classList.contains('hidden');

        // Hide all other dropdowns
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        if (isCurrentlyHidden) {
            dropdown.classList.remove('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[id^="dropdown-"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        }
    });

    async function changeRole(userId, newRole) {
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

            const response = await fetch(`/api/admin/users/${userId}/role`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ role: newRole })
            });
            
            const data = await response.json();
            if (response.ok && data.success) {
                if (window.Alert && Alert.toast) {
                    Alert.toast.success(data.message || 'Role pengguna berhasil diperbarui!');
                } else {
                    alert(data.message || 'Role pengguna berhasil diperbarui!');
                }
                setTimeout(() => window.location.reload(), 700);
            } else {
                if (window.Alert && Alert.modal) {
                    Alert.modal.error('Gagal', data.error || 'Gagal memperbarui role pengguna.');
                } else {
                    alert(data.error || 'Gagal memperbarui role pengguna.');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.Alert && Alert.modal) {
                Alert.modal.error('Gagal', 'Terjadi kesalahan sistem.');
            } else {
                alert('Terjadi kesalahan sistem.');
            }
        }
    }

    async function deleteUser(userId) {
        let isConfirmed = false;
        if (window.Alert && Alert.modal && Alert.modal.confirm) {
            const result = await Alert.modal.confirm('Hapus Akun Pengguna?', 'Apakah Anda yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.', 'Ya, Hapus', true);
            isConfirmed = result.isConfirmed;
        } else {
            isConfirmed = confirm('Apakah Anda yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.');
        }
        if (!isConfirmed) return;
        
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

            const response = await fetch(`/api/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });
            
            const data = await response.json();
            if (response.ok && data.success) {
                if (window.Alert && Alert.toast) {
                    Alert.toast.success('Akun berhasil dihapus');
                } else {
                    alert('Akun berhasil dihapus');
                }
                setTimeout(() => window.location.reload(), 700);
            } else {
                if (window.Alert && Alert.modal) {
                    Alert.modal.error('Gagal', data.error || 'Gagal menghapus akun pengguna.');
                } else {
                    alert(data.error || 'Gagal menghapus akun pengguna.');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.Alert && Alert.modal) {
                Alert.modal.error('Error', 'Terjadi kesalahan sistem.');
            } else {
                alert('Terjadi kesalahan sistem.');
            }
        }
    }
</script>
@endpush
@endsection
