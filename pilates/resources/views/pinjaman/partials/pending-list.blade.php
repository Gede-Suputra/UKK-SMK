<div class="space-y-3">
    @if($pending->isEmpty())
        <p class="text-sm text-zinc-500">Tidak ada pinjaman pending.</p>
    @else
        @foreach($pending as $p)
            <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 flex items-start justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate">#{{ $p->id }} — {{ $p->peminjam->name ?? '—' }}</p>
                    <p class="text-xs text-zinc-400">Tanggal: {{ $p->tanggal_pinjam }} — {{ $p->tanggal_kembali_rencana }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('pinjaman.changeStatus', $p) }}" class="inline-block">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="px-3 py-1.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-sm">Setuju</button>
                    </form>
                    <form method="POST" action="{{ route('pinjaman.changeStatus', $p) }}" class="inline-block">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="px-3 py-1.5 rounded bg-red-600 hover:bg-red-700 text-white text-sm">Tolak</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>