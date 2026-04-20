
<x-layouts.app>

    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-semibold text-zinc-900 dark:text-white tracking-tight">Manajemen Peminjaman</h1>
        <p class="text-sm text-zinc-500 mt-1">{{ $items->total() }} peminjaman</p>
    </div>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <form method="GET" action="" class="flex items-center gap-3 w-full md:w-auto">
            <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="Cari peminjam, status atau kategori..."
                   class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none" />
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach(['pending','approved','active','returned','cancelled'] as $s)
                    <option value="{{ $s }}" {{ (isset($status) && $status == $s) ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white">Cari</button>
        </form>

        <div class="w-full md:w-auto flex justify-start md:justify-end items-center gap-3">
            @if(!empty($pendingCount) && $pendingCount > 0)
                <button id="pending-open" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold rounded-lg bg-yellow-400 hover:bg-yellow-500 text-zinc-900 transition-colors shadow-sm">
                    Belum dilihat: {{ $pendingCount }}
                </button>
            @endif
            <div>
            <a href="#" data-modal-open data-modal-target="#pinjaman-modal" data-url="{{ route('pinjaman.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white transition-colors shadow-sm">
                Tambah Peminjaman
            </a>
            </div>
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
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Peminjam</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Status</th>
                        <th class="px-4 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/60">
                    @foreach($items as $p)
                    <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors group">
                        <td class="px-4 py-3.5 font-mono text-[11px] text-zinc-400">#{{ $p->id }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                @if(optional($p->peminjam)->profile_photo_path)
                                    <img src="{{ asset('storage/' . $p->peminjam->profile_photo_path) }}" class="size-8 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-700">
                                @else
                                    <div class="size-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[11px] font-bold text-white shrink-0 shadow-sm">
                                        {{ strtoupper(mb_substr(optional($p->peminjam)->name ?? '-', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[13px] font-medium text-zinc-800 dark:text-zinc-100 truncate max-w-[200px]">{{ $p->peminjam->name ?? '—' }}</p>
                                    <p class="text-[11px] text-zinc-400">{{ $p->peminjam->role ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-zinc-500">{{ $p->tanggal_pinjam }}</td>
                        <td class="px-4 py-3.5 text-[13px] text-zinc-400">
                            <form method="POST" action="{{ route('pinjaman.changeStatus', $p) }}" class="inline-block status-form">
                                @csrf
                                <select name="status" class="px-3 py-1 rounded-lg text-sm" onchange="if(confirm('Ubah status?')) this.form.submit();">
                                    @foreach(['pending','approved','active','returned','cancelled'] as $ss)
                                        <option value="{{ $ss }}" {{ $p->status === $ss ? 'selected' : '' }}>{{ ucfirst($ss) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="#" data-modal-open data-modal-target="#pinjaman-modal" data-url="{{ route('pinjaman.show', $p) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-[11.5px] font-medium transition-colors">Detail</a>
                                <a href="#" data-modal-open data-modal-target="#pinjaman-modal" data-url="{{ route('pinjaman.edit', $p) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11.5px] font-medium transition-colors">Edit</a>
                                <form action="{{ route('pinjaman.destroy', $p) }}" method="POST" class="inline-block" data-confirm="Hapus pinjaman ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-[11.5px] font-medium transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($items->isEmpty())
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <p class="text-sm font-medium text-zinc-500">Belum ada peminjaman</p>
                                <p class="text-xs text-zinc-400">Tambahkan peminjaman baru menggunakan tombol di atas</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <p class="text-xs text-zinc-400">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }}</p>
            <div class="text-xs text-zinc-400">{{ $items->links() }}</div>
        </div>
        @endif
    </div>

    <x-modal id="pinjaman-modal" title="Peminjaman" />
    <x-modal id="pending-modal" title="Peminjaman Pending" />

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-modal-open]');
        if (!btn) return;
        const modal = document.getElementById('pinjaman-modal');
        if (!modal) return;
        const url = btn.getAttribute('data-url');
        const titleEl = modal.querySelector('h3');
        if (titleEl) {
            titleEl.textContent = url.includes('/edit') ? 'Edit Peminjaman' : 'Detail Peminjaman';
        }
    });

    // Open pending modal and load list
    document.getElementById('pending-open')?.addEventListener('click', function(){
        const modalEl = document.getElementById('pending-modal');
        if (!modalEl) return;
        const url = '{{ route('pinjaman.pendingList') }}';
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const body = modalEl.querySelector('.modal-body') || modalEl.querySelector('.p-4') || modalEl;
                // try to set content inside modal
                body.innerHTML = html;
                // open modal via existing data-modal-open mechanism
                const openBtn = document.createElement('button'); openBtn.setAttribute('data-modal-open',''); openBtn.setAttribute('data-modal-target','#pending-modal'); document.body.appendChild(openBtn); openBtn.click(); openBtn.remove();
            });
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
