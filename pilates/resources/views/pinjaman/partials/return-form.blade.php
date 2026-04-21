<form id="return-form" method="POST" action="{{ route('pinjaman.storeReturn', $pinjaman) }}" enctype="multipart/form-data">
    @csrf

    <div class="space-y-6 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

        <!-- Header -->
        <div class="flex items-center gap-3 pb-4 border-b border-zinc-200 dark:border-zinc-700">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Pengembalian Peminjaman</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">#{{ str_pad($pinjaman->id, 4, '0', STR_PAD_LEFT) }} — {{ $pinjaman->peminjam->name ?? '-' }}</p>
            </div>
        </div>

        @php
            $hasRemaining = false;
        @endphp

        <div class="space-y-4">
            @foreach($pinjaman->details as $i => $d)
                @php
                    $returned = $d->pengembalians()->sum('jumlah_kembali');
                    $remaining = max(0, $d->jumlah - $returned);
                @endphp
                @if($remaining <= 0) @continue @endif

                @php $hasRemaining = true; @endphp

                <div class="p-5 rounded-2xl bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-800/50 dark:to-zinc-800/30 border border-zinc-200 dark:border-zinc-700 shadow-sm hover:shadow-md transition-shadow">
                    <!-- Header Item -->
                    <div class="flex justify-between items-start gap-4 mb-5 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex-1">
                            <p class="font-bold text-zinc-900 dark:text-white text-base">{{ $d->alat->nama_alat ?? '-' }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $d->alat->kategori->nama_kategori ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Sisa Dikembalikan</p>
                            <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $remaining }}/{{ $d->jumlah }}</p>
                        </div>
                    </div>

                    <input type="hidden" name="details[{{ $i }}][id_detail_pinjaman]" value="{{ $d->id }}">

                    <!-- Grid Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Jumlah Kembali -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider mb-2">Jumlah Dikembalikan *</label>
                            <input type="number" name="details[{{ $i }}][jumlah_kembali]" 
                                   min="1" max="{{ $remaining }}" value="{{ $remaining }}" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 text-sm font-medium">
                        </div>

                        <!-- Tanggal Kembali -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider mb-2">Tanggal Kembali *</label>
                            <input type="date" name="details[{{ $i }}][tanggal_kembali]" 
                                   value="{{ date('Y-m-d') }}" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 text-sm font-medium">
                        </div>

                        <!-- Kondisi -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider mb-2">Kondisi Barang *</label>
                            <select name="details[{{ $i }}][kondisi]" required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 text-sm font-medium">
                                <option value="baik">✓ Baik</option>
                                <option value="rusak">⚠ Rusak (akan ditambah ke rusak)</option>
                                <option value="hilang">✕ Hilang (akan ditambah ke rusak)</option>
                            </select>
                        </div>

                        <!-- Foto -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider mb-2">Foto Bukti</label>
                            <input type="file" name="details[{{ $i }}][foto]" accept="image/*" 
                                   data-preview-target="#preview-{{ $i }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">
                            <div id="preview-{{ $i }}" 
                                 class="mt-2 w-20 h-20 rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-800 border border-dashed border-zinc-300 dark:border-zinc-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider mb-2">Catatan Pengembalian</label>
                        <textarea name="details[{{ $i }}][pesan]" rows="2" 
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 text-sm"
                                  placeholder="Kondisi, kerusakan, atau keterangan lainnya..."></textarea>
                    </div>
                </div>
            @endforeach
        </div>

        @if(!$hasRemaining)
            <div class="p-6 text-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                <svg class="w-8 h-8 mx-auto mb-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Semua barang pada peminjaman ini sudah dikembalikan.</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-5 border-t border-zinc-200 dark:border-zinc-700">
            <button type="button" data-modal-close 
                    class="px-6 py-3 text-sm font-semibold rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                Batal
            </button>
            @if($hasRemaining)
            <button type="submit" 
                    class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Pengembalian
            </button>
            @endif
        </div>
    </div>
</form>

<!-- Preview Image Script -->
<script>
document.querySelectorAll('input[data-preview-target]').forEach(function(input) {
    input.addEventListener('change', function() {
        const file = this.files[0];
        const targetSelector = this.getAttribute('data-preview-target');
        const previewContainer = document.querySelector(targetSelector);
        
        if (!previewContainer || !file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    });
});
</script>
