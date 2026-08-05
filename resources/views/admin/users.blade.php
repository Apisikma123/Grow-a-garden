@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col gap-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-1 mb-2">
        <h1 class="text-[28px] font-bold text-on-surface tracking-tight">Manajemen Pengguna</h1>
        <p class="text-[14px] text-on-surface-variant">Kelola anggota komunitas, atur akses, dan pantau keterlibatan.</p>
    </div>

    {{-- Main Container --}}
    <div class="bg-surface-container-lowest rounded-[12px] ambient-shadow border border-outline-variant/30 flex flex-col overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="p-5 flex justify-between items-center border-b border-outline-variant/20">
            {{-- Filter Dropdown --}}
            <button class="flex items-center gap-2 bg-surface-container-high hover:bg-surface-container-highest transition-colors px-4 py-2 rounded-md text-[13px] font-semibold text-on-surface">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                All Membership Levels
                <span class="material-symbols-outlined text-[18px] ml-2">expand_more</span>
            </button>

            {{-- Export Button --}}
            <button class="flex items-center gap-2 px-4 py-2 rounded-md border border-secondary text-secondary font-bold text-[13px] hover:bg-secondary/5 transition-colors">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Ekspor
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto w-full no-scrollbar pb-32">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/20 text-left">
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase w-[35%]">Pengguna</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Peran</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Plots</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-on-surface-variant tracking-wider uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($users as $user)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary-container/40 text-primary-container font-bold text-[13px] flex items-center justify-center shrink-0 uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-[14px] font-bold text-on-surface">{{ $user->name }}</div>
                                    <div class="text-[12px] text-on-surface-variant">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-blue-500 text-white">Admin</span>
                            @elseif($user->role === 'premium')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#fd9e70] text-white">Premium</span>
                            @elseif($user->role === 'pro')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#10b981] text-white">Pro</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-surface-container-highest text-on-surface-variant">Free</span>
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
                            <div id="dropdown-{{ $user->id }}" class="hidden absolute right-6 top-10 w-40 bg-white rounded-xl shadow-lg border border-outline-variant/20 z-20 py-2">
                                <button onclick="changeRole({{ $user->id }}, 'free')" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest hover:text-primary transition-colors">Ubah ke Free</button>
                                <button onclick="changeRole({{ $user->id }}, 'pro')" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest hover:text-primary transition-colors">Ubah ke Pro</button>
                                <button onclick="changeRole({{ $user->id }}, 'premium')" class="w-full text-left px-4 py-2 text-[13px] text-on-surface hover:bg-surface-container-lowest hover:text-primary transition-colors">Ubah ke Premium</button>
                                <hr class="my-1 border-outline-variant/20">
                                <button onclick="deleteUser({{ $user->id }})" class="w-full text-left px-4 py-2 text-[13px] text-red-500 hover:bg-red-50 transition-colors">Hapus Akun</button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-5 flex justify-between items-center border-t border-outline-variant/20 bg-surface-container-lowest">
            <div class="text-[13px] text-on-surface-variant font-medium">
                Showing 1 to 3 of 45 entries
            </div>
            
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 rounded-md text-[13px] font-medium text-on-surface-variant hover:bg-surface-container-high transition-colors">Prev</button>
                <button class="w-8 h-8 rounded-md bg-[#006c49] text-white text-[13px] font-bold flex items-center justify-center shadow-sm">1</button>
                <button class="w-8 h-8 rounded-md text-[13px] font-medium text-on-surface hover:bg-surface-container-high transition-colors flex items-center justify-center">2</button>
                <button class="px-3 py-1.5 rounded-md text-[13px] font-medium text-on-surface hover:bg-surface-container-high transition-colors">Next</button>
            </div>
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
                window.location.reload();
            } else {
                const data = await response.json();
                alert(data.error || 'Failed to update role');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    }

    async function deleteUser(userId) {
        if (!confirm('Apakah Anda yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.')) return;
        
        try {
            const response = await fetch(`/api/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Gagal menghapus pengguna');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus');
        }
    }
</script>
@endpush
@endsection
