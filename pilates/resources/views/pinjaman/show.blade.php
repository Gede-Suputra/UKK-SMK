<div class="space-y-4">
    <h3 class="text-lg font-semibold">Peminjaman #{{ $pinjaman->id }}</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-3 rounded-lg border bg-white dark:bg-zinc-800">
            <p class="text-sm text-zinc-500">Peminjam</p>
            <p class="font-medium">{{ optional($pinjaman->peminjam)->name ?? '-' }}</p>
            <p class="text-xs text-zinc-400">{{ optional($pinjaman->peminjam)->role ?? '' }}</p>
        </div>

        <div class="p-3 rounded-lg border bg-white dark:bg-zinc-800">
            <p class="text-sm text-zinc-500">Tanggal Pinjam</p>
            <p class="font-medium">{{ $pinjaman->tanggal_pinjam }}</p>
            <p class="text-sm text-zinc-500">Rencana Kembali</p>
            <p class="font-medium">{{ $pinjaman->tanggal_kembali_rencana }}</p>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-3 rounded-lg border bg-white dark:bg-zinc-800">
            <p class="text-sm text-zinc-500">Disetujui Oleh</p>
            <p class="font-medium">{{ optional($pinjaman->disetujuiBy)->name ?? '-' }}</p>
            <p class="text-xs text-zinc-400">{{ $pinjaman->disetujui_oleh ? ('ID: '.$pinjaman->disetujui_oleh) : '' }}</p>
        </div>

        <div class="p-3 rounded-lg border bg-white dark:bg-zinc-800">
            <p class="text-sm text-zinc-500">Diselesaikan Oleh</p>
            <p class="font-medium">{{ optional($pinjaman->diselesaikanBy)->name ?? '-' }}</p>
            <p class="text-xs text-zinc-400">{{ $pinjaman->tanggal_selesai ? $pinjaman->tanggal_selesai : '' }}</p>
        </div>
    </div>

    <div class="mt-4">
        <div class="p-3 rounded-lg border bg-white dark:bg-zinc-800 text-right">
            <p class="text-sm text-zinc-500">Total Denda</p>
            <p class="font-semibold text-lg text-rose-600">Rp {{ number_format($pinjaman->total_denda ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-zinc-400">Total denda yang tercatat untuk peminjaman ini.</p>
        </div>
    </div>

    <div class="p-3 rounded-lg border bg-white dark:bg-zinc-800">
        <p class="text-sm text-zinc-500">Detail Barang</p>
        <ul class="mt-2 space-y-2">
            @foreach($pinjaman->details as $d)
                <li class="flex items-center justify-between p-2 rounded-md bg-zinc-50 dark:bg-zinc-900/30">
                    <div>
                        <div class="font-medium">{{ optional($d->alat)->nama_alat ?? '—' }}</div>
                        <div class="text-xs text-zinc-400">Jumlah: {{ $d->jumlah }} • Status: {{ $d->status }}</div>
                    </div>
                    <div class="text-right text-xs text-zinc-400">
                        @if($d->status == 'dipinjam')
                            <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-800">Perlu dikembalikan</span>
                        @elseif($d->status == 'selesai')
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-800">Selesai</span>
                        @elseif($d->status == 'pending')
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">Menunggu persetujuan</span>
                        @elseif($d->status == 'disetujui')
                            <span class="px-2 py-1 rounded-full bg-sky-100 text-sky-800">Disetujui, menunggu pengambilan</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="flex justify-end gap-2">
        @if(($pinjaman->status ?? '') === 'dipinjam')
            <a href="#" data-modal-open data-modal-target="#pinjaman-modal" data-url="{{ route('pinjaman.returnForm', $pinjaman) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white">Catat Pengembalian</a>
        @endif

        <form method="POST" action="{{ route('pinjaman.changeStatus', $pinjaman) }}">
            @csrf
            <input type="hidden" name="status" value="selesai" />
            <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white">Batalkan</button>
        </form>
    </div>
</div>