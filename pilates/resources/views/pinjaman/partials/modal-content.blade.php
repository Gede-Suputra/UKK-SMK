<div class="bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden">
    <div class="relative px-6 pt-6 pb-5 bg-gradient-to-br from-indigo-50 via-white to-violet-50 dark:from-indigo-950/30 dark:via-zinc-900 dark:to-violet-950/20 border-b border-zinc-100 dark:border-zinc-800">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-xl font-bold text-white shadow-md ring-4 ring-white dark:ring-zinc-800">
                {{ strtoupper(substr($pinjaman->peminjam->name ?? '-', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white truncate">Peminjaman #{{ $pinjaman->id }}</h2>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                        {{ $pinjaman->peminjam->name ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 py-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Tanggal Pinjam</p>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $pinjaman->tanggal_pinjam }}</p>
            </div>
            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Rencana Kembali</p>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $pinjaman->tanggal_kembali_rencana }}</p>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-sm font-semibold">Items</h4>
            @foreach($pinjaman->details as $d)
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                    <div>
                        <p class="text-sm font-medium">{{ $d->alat->nama_alat ?? '—' }}</p>
                        <p class="text-xs text-zinc-400">Jumlah: {{ $d->jumlah }} — Status: {{ $d->status }}</p>
                    </div>
                    <div class="text-sm text-zinc-400">&nbsp;</div>
                </div>
            @endforeach
        </div>

        @if($pinjaman->pesan)
            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-400 mb-0.5">Pesan</p>
                <p class="text-sm">{{ $pinjaman->pesan }}</p>
            </div>
        @endif
    </div>

    <div class="px-6 pb-6 flex items-center justify-end gap-2 border-t border-zinc-100 dark:border-zinc-800 pt-4">
        @php $role = auth()->user()->role ?? null; @endphp
        @if($pinjaman->status === 'pending' && in_array($role, ['admin','petugas']))
            <form method="POST" action="{{ route('pinjaman.changeStatus', $pinjaman) }}" class="inline-block">
                @csrf
                <input type="hidden" name="status" value="disetujui">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium">Setujui</button>
            </form>
        @endif

        @if(in_array($pinjaman->status, ['disetujui','pending']))
            <form method="POST" action="{{ route('pinjaman.changeStatus', $pinjaman) }}" class="inline-block">
                @csrf
                <input type="hidden" name="status" value="dipinjam">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-medium">Ambil Barang</button>
            </form>
        @endif

        <a href="#" data-modal-open data-modal-target="#pinjaman-modal" data-url="{{ route('pinjaman.edit', $pinjaman) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-700 text-sm font-medium transition-colors">Edit</a>

        <a href="#" data-modal-open data-modal-target="#pinjaman-modal" data-url="{{ route('pinjaman.returnForm', $pinjaman) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium">Pengembalian</a>
    </div>
</div>
