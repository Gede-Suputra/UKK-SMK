<x-layouts.app>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-sm font-semibold text-zinc-900 dark:text-white tracking-tight">Users</h1>
            <p class="text-xs text-zinc-400 mt-0.5">Kelola semua pengguna terdaftar</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 hover:opacity-80 transition-opacity">
            <svg class="size-3" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="8" y1="2" x2="8" y2="14"/><line x1="2" y1="8" x2="14" y2="8"/>
            </svg>
            Tambah
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 px-3.5 py-2.5 rounded-lg border text-xs font-medium
                    bg-emerald-50 border-emerald-200 text-emerald-700
                    dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide text-zinc-400 w-14">ID</th>
                        <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide text-zinc-400">Name</th>
                        <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide text-zinc-400">Email</th>
                        <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide text-zinc-400">Phone</th>
                        <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide text-zinc-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/70">
                    @foreach($users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="px-3 py-2.5 font-mono text-[11px] text-zinc-400">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="size-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-[10px] font-semibold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(mb_substr($user->name, 0, 1)) }}{{ strtoupper(mb_substr(strstr($user->name, ' '), 1, 1) ?: '') }}
                                </div>
                                <span class="font-medium text-zinc-800 dark:text-zinc-100 text-[13px] truncate max-w-[130px]">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-2.5 text-zinc-500 dark:text-zinc-400 truncate max-w-[180px]">{{ $user->email }}</td>
                        <td class="px-3 py-2.5 text-zinc-400 dark:text-zinc-500">{{ $user->phone ?? '-' }}</td>
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-1">
                                <!-- View Button -->
                                <a href="#"
                                   data-modal-open
                                   data-modal-target="#user-modal"
                                   data-url="{{ route('users.show', $user) }}"
                                   class="px-2 py-1 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 dark:hover:bg-blue-950/30 dark:hover:text-blue-400 text-[11.5px] font-medium transition-colors">
                                    View
                                </a>
                                <!-- Edit Button -->
                                <a href="#"
                                   data-modal-open
                                   data-modal-target="#user-modal"
                                   data-url="{{ route('users.edit', $user) }}"
                                   class="px-2 py-1 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-700 dark:hover:bg-amber-950/30 dark:hover:text-amber-400 text-[11.5px] font-medium transition-colors">
                                    Edit
                                </a>
                                <!-- Delete -->
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" data-confirm="Hapus user ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2 py-1 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-red-50 hover:border-red-200 hover:text-red-600 dark:hover:bg-red-950/30 dark:hover:text-red-400 text-[11.5px] font-medium transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-3 py-2.5 border-t border-zinc-100 dark:border-zinc-800 text-xs text-zinc-400">
            {{ $users->links() }}
        </div>
    </div>

    {{-- MODAL --}}
    <x-modal id="user-modal" title="Detail User" />

    @stack('scripts')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Dynamic Title + Modal Handler
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-modal-open]');
        if (!btn) return;

        const modal = document.getElementById('user-modal');
        if (!modal) return;

        const url = btn.getAttribute('data-url');
        const titleEl = modal.querySelector('h3');

        if (titleEl) {
            if (url.includes('/edit')) {
                titleEl.textContent = 'Edit User';
            } else {
                titleEl.textContent = 'Detail User';
            }
        }
    });

    // SweetAlert for Delete
    document.addEventListener('submit', function(e){
        const form = e.target.closest('form[data-confirm]');
        if (!form) return;
        
        e.preventDefault();
        const msg = form.getAttribute('data-confirm') || 'Yakin ingin menghapus?';

        Swal.fire({
            title: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    </script>

</x-layouts.app>