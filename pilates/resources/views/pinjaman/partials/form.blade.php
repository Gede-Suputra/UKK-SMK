@php $isEdit = isset($pinjaman) && $pinjaman->exists; @endphp

<form id="pinjaman-form" method="POST" action="{{ $isEdit ? route('pinjaman.update', $pinjaman) : route('pinjaman.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-8 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">

        <!-- Informasi Utama -->
        <div>
            <h3 class="text-lg font-semibold text-zinc-800 dark:text-white mb-4">Informasi Peminjaman</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1">Peminjam</label>
                    <select name="id_peminjam" required 
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                        <option value="">— Pilih Peminjam —</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @if(old('id_peminjam', $pinjaman->id_peminjam ?? '') == $u->id) selected @endif>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" 
                           value="{{ old('tanggal_pinjam', $pinjaman->tanggal_pinjam ?? date('Y-m-d')) }}" required
                           class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1">Tanggal Kembali Rencana</label>
                    <input type="date" name="tanggal_kembali_rencana" 
                           value="{{ old('tanggal_kembali_rencana', $pinjaman->tanggal_kembali_rencana ?? '') }}" required
                           class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" 
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                        @foreach(['pending','disetujui','dipinjam','selesai'] as $s)
                            <option value="{{ $s }}" @if(old('status', $pinjaman->status ?? 'pending') == $s) selected @endif>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Pesan -->
        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1">Pesan / Keterangan</label>
            <textarea name="pesan" rows="3" 
                      class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30">{{ old('pesan', $pinjaman->pesan ?? '') }}</textarea>
        </div>

        <!-- Detail Peminjaman -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-semibold text-zinc-800 dark:text-white">Detail Peminjaman</h4>
                <button type="button" id="add-row" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Item
                </button>
            </div>

            <div id="detail-rows" class="space-y-3">
                <!-- Existing rows (edit) -->
                @if($isEdit && $pinjaman->details && $pinjaman->details->isNotEmpty())
                    @foreach($pinjaman->details as $index => $detail)
                        @php
                            $alat = $alats->firstWhere('id', $detail->id_alat);
                            $aAvail = $alat ? ($alat->jumlah_total - ($alat->jumlah_rusak ?? 0) - ($alat->jumlah_dipinjam ?? 0)) : 0;
                            if($aAvail < 0) $aAvail = 0;
                        @endphp
                        <div class="detail-row flex items-center gap-3 p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border border-zinc-100 dark:border-zinc-700">
                            <div class="flex-1">
                                <select name="details[{{ $index }}][id_alat]" class="w-full px-3.5 py-2.5 rounded-xl border alat-select bg-white dark:bg-zinc-800">
                                    <option value="">— Pilih Alat —</option>
                                    @foreach($alats as $alatOpt)
                                        @php $optAvail = $alatOpt->jumlah_total - ($alatOpt->jumlah_rusak ?? 0) - ($alatOpt->jumlah_dipinjam ?? 0); if($optAvail < 0) $optAvail = 0; @endphp
                                        <option value="{{ $alatOpt->id }}" data-available="{{ $optAvail }}" {{ $alatOpt->id == $detail->id_alat ? 'selected' : '' }}>
                                            {{ $alatOpt->nama_alat }} — Tersedia: {{ $optAvail }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-xs text-zinc-500 mt-1.5">Tersedia: <span class="available-count">{{ $aAvail }}</span></div>
                            </div>

                            <div class="w-28">
                                <input type="number" name="details[{{ $index }}][jumlah]" min="1" value="{{ old("details.{$index}.jumlah", $detail->jumlah) }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border jumlah-input bg-white dark:bg-zinc-800">
                            </div>

                            <div>
                                <select name="details[{{ $index }}][status]" class="px-3.5 py-2.5 rounded-xl border bg-white dark:bg-zinc-800">
                                    @foreach(['dipinjam','kembali_sebagian','selesai'] as $ss)
                                        <option value="{{ $ss }}" {{ ($detail->status ?? 'dipinjam') === $ss ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$ss)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="button" class="remove-row px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <button type="button" data-modal-close 
                    class="px-6 py-3 text-sm font-medium rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                Batal
            </button>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Peminjaman' }}
            </button>
        </div>
    </div>
</form>

<!-- Template for New Row -->
<template id="detail-row-template">
    <div class="detail-row flex items-center gap-3 p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border border-zinc-100 dark:border-zinc-700">
        <div class="flex-1">
            <select name="details[__INDEX__][id_alat]" class="w-full px-3.5 py-2.5 rounded-xl border alat-select bg-white dark:bg-zinc-800">
                <option value="">— Pilih Alat —</option>
                @foreach($alats as $alat)
                    @php $aAvail = $alat->jumlah_total - ($alat->jumlah_rusak ?? 0) - ($alat->jumlah_dipinjam ?? 0); if($aAvail < 0) $aAvail = 0; @endphp
                    <option value="{{ $alat->id }}" data-available="{{ $aAvail }}">
                        {{ $alat->nama_alat }} — Tersedia: {{ $aAvail }}
                    </option>
                @endforeach
            </select>
            <div class="text-xs text-zinc-500 mt-1.5">Tersedia: <span class="available-count">0</span></div>
        </div>

        <div class="w-28">
            <input type="number" name="details[__INDEX__][jumlah]" min="1" value="1" 
                   class="w-full px-3.5 py-2.5 rounded-xl border jumlah-input bg-white dark:bg-zinc-800">
        </div>

        <div>
            <select name="details[__INDEX__][status]" class="px-3.5 py-2.5 rounded-xl border bg-white dark:bg-zinc-800">
                @foreach(['dipinjam','kembali_sebagian','selesai'] as $ss)
                    <option value="{{ $ss }}">{{ ucfirst(str_replace('_',' ',$ss)) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="button" class="remove-row px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium transition-colors">
                Hapus
            </button>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('detail-rows');
    const addBtn = document.getElementById('add-row');
    const template = document.getElementById('detail-row-template');

    function setAvailableInfo(row) {
        const select = row.querySelector('.alat-select');
        const input = row.querySelector('.jumlah-input');
        const availSpan = row.querySelector('.available-count');

        if (!select) return;

        const selectedOpt = select.selectedOptions[0];
        const available = parseInt(selectedOpt ? selectedOpt.dataset.available : select.dataset.available || 0, 10);

        if (availSpan) availSpan.textContent = available;
        if (input) {
            input.max = available || 999;
            if (parseInt(input.value) > available) {
                input.value = available > 0 ? available : 1;
            }
        }
    }

    // Existing rows are rendered server-side above when editing

    // Add Row
    addBtn.addEventListener('click', function() {
        let index = container.children.length;
        let html = template.innerHTML.replace(/__INDEX__/g, index);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newRow = tempDiv.firstElementChild;
        
        container.appendChild(newRow);
        setAvailableInfo(newRow);
    });

    // Event Delegation
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('alat-select')) {
            setAvailableInfo(e.target.closest('.detail-row'));
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.detail-row').remove();
        }
    });

    // Form validation before submit
    const form = document.getElementById('pinjaman-form');
    form.addEventListener('submit', function(e) {
        const rows = container.querySelectorAll('.detail-row');
        for (let row of rows) {
            const select = row.querySelector('.alat-select');
            const input = row.querySelector('.jumlah-input');
            if (!select || !input) continue;

            const selectedOpt = select.selectedOptions[0];
            const available = parseInt(selectedOpt?.dataset.available || 0, 10);
            const wanted = parseInt(input.value || 0, 10);

            if (wanted > available) {
                e.preventDefault();
                alert(`Stok tidak mencukupi untuk "${selectedOpt.textContent.split('—')[0].trim()}". Tersedia: ${available}`);
                return;
            }
        }
    });

    // Initialize existing rows (if any)
    container.querySelectorAll('.detail-row').forEach(setAvailableInfo);
});
</script>