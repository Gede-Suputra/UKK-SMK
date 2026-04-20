<x-layouts.app>

    {{-- Header / Hero --}}
    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-semibold text-zinc-900 dark:text-white tracking-tight">Manajemen Pengguna</h1>
        <p class="text-sm text-zinc-500 mt-1">{{ $users->total() }} pengguna terdaftar</p>
    </div>

    {{-- Controls: search, role filter, add button (single row) --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <form method="GET" action="" class="flex items-center gap-3 w-full md:w-auto">
            <input type="search" name="q" value="{{ request()->get('q') }}" placeholder="Cari nama atau email..."
                   class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none" />
            <select name="role" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none">
                <option value="">Semua peran</option>
                <option value="admin" {{ request()->get('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ request()->get('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="user" {{ request()->get('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white">Cari</button>
        </form>

        <div class="w-full md:w-auto flex justify-start md:justify-end">
            <a href="#" data-modal-open data-modal-target="#user-modal" data-url="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white transition-colors shadow-sm">
                <svg class="size-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="8" y1="2" x2="8" y2="14"/><line x1="2" y1="8" x2="14" y2="8"/>
                </svg>
                Tambah Pengguna
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium
                    bg-emerald-50 border-emerald-200 text-emerald-700
                    dark:bg-emerald-950/30 dark:border-emerald-800/60 dark:text-emerald-400">
            <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/70 dark:bg-zinc-800/40">
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400 w-16">ID</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Pengguna</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Email</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Telepon</th>
                        <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/60">
                    @foreach($users as $user)
                    <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors group">
                        <td class="px-4 py-3.5 font-mono text-[11px] text-zinc-400">
                            #{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                         class="size-8 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-700">
                                @else
                                    <div class="size-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[11px] font-bold text-white shrink-0 shadow-sm">
                                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}{{ strtoupper(mb_substr(strstr($user->name, ' '), 1, 1) ?: '') }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[13px] font-medium text-zinc-800 dark:text-zinc-100 truncate max-w-[140px]">{{ $user->name }}</p>
                                    <p class="text-[11px] text-zinc-400">{{ $user->role ?? 'User' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-zinc-500 dark:text-zinc-400 truncate max-w-[200px]">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-zinc-400 dark:text-zinc-500">
                            {{ $user->phone ?? '—' }}
                        </td>
                        
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="#"
                                   data-modal-open
                                   data-modal-target="#user-modal"
                                   data-url="{{ route('users.show', $user) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-[11.5px] font-medium transition-colors">
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                                <a href="#"
                                   data-modal-open
                                   data-modal-target="#user-modal"
                                   data-url="{{ route('users.edit', $user) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11.5px] font-medium transition-colors">
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" data-confirm="Hapus user ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-[11.5px] font-medium transition-colors">
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($users->isEmpty())
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="size-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <svg class="size-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Belum ada pengguna</p>
                                <p class="text-xs text-zinc-400">Mulai dengan menambahkan pengguna baru</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <p class="text-xs text-zinc-400">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </p>
            <div class="text-xs text-zinc-400">
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- MODAL --}}
    <x-modal id="user-modal" title="Detail Pengguna" />
    <x-modal id="photo-modal" title="Foto Pengguna" size="lg" />

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-modal-open]');
        if (!btn) return;
        const modal = document.getElementById('user-modal');
        if (!modal) return;
        const url = btn.getAttribute('data-url');
        const titleEl = modal.querySelector('h3');
        if (titleEl) {
            titleEl.textContent = url.includes('/edit') ? 'Edit Pengguna' : 'Detail Pengguna';
        }
    });

    document.addEventListener('submit', function(e) {
        const form = e.target.closest('form[data-confirm]');
        if (!form) return;
        e.preventDefault();
        const msg = form.getAttribute('data-confirm') || 'Yakin ingin menghapus?';
        Swal.fire({
            title: msg,
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            borderRadius: '16px',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
    </script>

</x-layouts.app>