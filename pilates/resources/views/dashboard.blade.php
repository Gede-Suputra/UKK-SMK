<x-layouts.app>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Dashboard PILATES</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Ringkasan sistem peminjaman alat desa</p>
        </div>

        <!-- Main Stats Grid -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Kategori -->
            <flux:card class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Kategori</div>
                    <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/30">
                        <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalKategori }}</div>
                <a href="{{ route('kategori-alat.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" wire:navigate>
                    Lihat Kategori →
                </a>
            </flux:card>

            <!-- Total Alat -->
            <flux:card class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Alat</div>
                    <div class="rounded-lg bg-purple-100 p-2 dark:bg-purple-900/30">
                        <svg class="size-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m0-10L4 17m8 4l8-4" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalAlat }}</div>
                <a href="{{ route('alats.index') }}" class="text-xs text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300" wire:navigate>
                    Lihat Alat →
                </a>
            </flux:card>

            <!-- Total Pinjaman -->
            <flux:card class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Pinjaman</div>
                    <div class="rounded-lg bg-emerald-100 p-2 dark:bg-emerald-900/30">
                        <svg class="size-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalPinjaman }}</div>
                <a href="{{ route('pinjaman.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300" wire:navigate>
                    Lihat Pinjaman →
                </a>
            </flux:card>

            <!-- Alat Tersedia -->
            <flux:card class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Alat Tersedia</div>
                    <div class="rounded-lg bg-orange-100 p-2 dark:bg-orange-900/30">
                        <svg class="size-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalAlatTersedia }}</div>
                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    Rusak: {{ $totalAlatRusak }} | Dipinjam: {{ $totalAlatDipinjam }}
                </div>
            </flux:card>
        </div>

        <!-- Second Row: Pinjaman Status & Top Alat -->
        <div class="grid gap-4 lg:grid-cols-2">
            <!-- Pinjaman Status -->
            <flux:card class="flex flex-col gap-4">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Status Pinjaman</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-amber-400"></div>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Menunggu Persetujuan</span>
                        </div>
                        <span class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $pinjamanPending }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-blue-400"></div>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Disetujui</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $pinjamanDisetujui }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/20">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-cyan-400"></div>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Sedang Dipinjam</span>
                        </div>
                        <span class="text-lg font-bold text-cyan-600 dark:text-cyan-400">{{ $pinjamanDipinjam }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-emerald-400"></div>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Selesai</span>
                        </div>
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $pinjamanSelesai }}</span>
                    </div>
                </div>
            </flux:card>

            <!-- Top Alat -->
            <flux:card class="flex flex-col gap-4">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Alat Paling Sering Dipinjam</h2>
                <div class="space-y-2">
                    @forelse($topAlat as $alat)
                    <div class="flex items-center justify-between gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $alat->nama_alat }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $alat->kategori->nama_kategori ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold">
                            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-lg">{{ $alat->jumlah_pinjaman ?? 0 }}x</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                        <p class="text-sm">Belum ada data peminjaman</p>
                    </div>
                    @endforelse
                </div>
            </flux:card>
        </div>

        <!-- Recent Pinjamans -->
        <flux:card class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Peminjaman Terbaru</h2>
                <a href="{{ route('pinjaman.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" wire:navigate>
                    Lihat Semua
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-4 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">ID</th>
                            <th class="px-4 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">Peminjam</th>
                            <th class="px-4 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">Tgl Pinjam</th>
                            <th class="px-4 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($recentPinjamans as $pinjaman)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                            <td class="px-4 py-3 text-zinc-900 dark:text-white font-medium">#{{ str_pad($pinjaman->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">{{ $pinjaman->peminjam->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $pinjaman->status === 'pending' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' : '' }}
                                    {{ $pinjaman->status === 'disetujui' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : '' }}
                                    {{ $pinjaman->status === 'dipinjam' ? 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300' : '' }}
                                    {{ $pinjaman->status === 'selesai' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : '' }}
                                ">
                                    {{ ucfirst($pinjaman->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                <p>Belum ada peminjaman</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </flux:card>
    </div>
</x-layouts.app>
