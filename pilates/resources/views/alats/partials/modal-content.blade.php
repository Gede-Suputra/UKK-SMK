<div class="bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden">

    <div class="relative px-6 pt-6 pb-5 bg-gradient-to-br from-indigo-50 via-white to-violet-50 dark:from-indigo-950/30 dark:via-zinc-900 dark:to-violet-950/20 border-b border-zinc-100 dark:border-zinc-800">
        <div class="flex items-center gap-4">
            @if($alat->path_foto)
                <a href="#" data-modal-open data-modal-target="#photo-modal" data-photo-src="{{ asset('storage/' . $alat->path_foto) }}" class="block">
                    <img src="{{ asset('storage/' . $alat->path_foto) }}" alt="Foto {{ $alat->nama_alat }}" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white dark:ring-zinc-800 shadow-md">
                </a>
            @else
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-xl font-bold text-white shadow-md ring-4 ring-white dark:ring-zinc-800">
                    {{ strtoupper(substr($alat->nama_alat, 0, 1)) }}
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white truncate">{{ $alat->nama_alat }}</h2>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                        <svg class="size-2.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        {{ $alat->kategori->nama_kategori ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 py-5 space-y-4">

        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
            <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Jumlah Total</p>
                <p class="text-sm text-zinc-800 dark:text-zinc-100 font-medium">{{ $alat->jumlah_total }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <div class="size-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="size-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Dipinjam</p>
                    <p class="text-sm text-zinc-800 dark:text-zinc-100 font-medium">{{ $alat->jumlah_dipinjam }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <div class="size-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="size-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Rusak</p>
                    <p class="text-sm text-zinc-800 dark:text-zinc-100 font-medium">{{ $alat->jumlah_rusak }}</p>
                </div>
            </div>
        </div>

        @if($alat->deskripsi)
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <div class="size-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                    <svg class="size-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Deskripsi</p>
                    <p class="text-sm text-zinc-800 dark:text-zinc-100 leading-relaxed">{{ $alat->deskripsi }}</p>
                </div>
            </div>
        @endif

    </div>

    <div class="px-6 pb-6 flex items-center justify-end gap-2 border-t border-zinc-100 dark:border-zinc-800 pt-4">
        <a href="#" data-modal-open data-modal-target="#alat-modal" data-url="{{ route('alats.edit', $alat) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-700 text-sm font-medium transition-colors">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Alat
        </a>
    </div>

</div>
