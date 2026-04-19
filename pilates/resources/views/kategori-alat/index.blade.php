<x-layouts.app>

    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-semibold text-zinc-900 dark:text-white tracking-tight">Manajemen Kategori</h1>
        <p class="text-sm text-zinc-500 mt-1">{{ $kategori->total() }} kategori</p>
    </div>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <form method="GET" action="" class="flex items-center gap-3 w-full md:w-auto">
            <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="Cari id atau nama kategori..."
                   class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none" />
            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white">Cari</button>
        </form>

        <div class="w-full md:w-auto flex justify-start md:justify-end">
            <a href="#" data-modal-open data-modal-target="#kategori-modal" data-url="{{ route('kategori-alat.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white transition-colors shadow-sm">
                <svg class="size-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <line x1="8" y1="2" x2="8" y2="14" /><line x1="2" y1="8" x2="14" y2="8" />
                </svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium bg-emerald-50 border-emerald-200 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/70 dark:bg-zinc-800/40">
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400 w-16">ID</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Nama Kategori</th>
                        <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/60">
                    @foreach($kategori as $k)
                    <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors group">
                        <td class="px-4 py-3.5 font-mono text-[11px] text-zinc-400">#{{ $k->id }}</td>
                        <td class="px-4 py-3.5">{{ $k->nama_kategori }}</td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="#" data-modal-open data-modal-target="#kategori-modal" data-url="{{ route('kategori-alat.show', $k) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-sky-50 hover:border-sky-200 hover:text-sky-700 text-[11.5px] font-medium transition-colors">Detail</a>
                                <a href="#" data-modal-open data-modal-target="#kategori-modal" data-url="{{ route('kategori-alat.edit', $k) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-700 text-[11.5px] font-medium transition-colors">Edit</a>
                                <form action="{{ route('kategori-alat.destroy', $k) }}" method="POST" class="inline-block" data-confirm="Hapus kategori ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-red-50 hover:border-red-200 hover:text-red-600 text-[11.5px] font-medium transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($kategori->isEmpty())
                    <tr>
                        <td colspan="4" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <p class="text-sm font-medium text-zinc-500">Belum ada kategori</p>
                                <p class="text-xs text-zinc-400">Tambahkan kategori baru menggunakan tombol di atas</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($kategori->hasPages())
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <p class="text-xs text-zinc-400">Menampilkan {{ $kategori->firstItem() }}–{{ $kategori->lastItem() }} dari {{ $kategori->total() }}</p>
            <div class="text-xs text-zinc-400">{{ $kategori->links() }}</div>
        </div>
        @endif
    </div>

    <x-modal id="kategori-modal" title="Kategori" />

    @stack('scripts')

</x-layouts.app>
