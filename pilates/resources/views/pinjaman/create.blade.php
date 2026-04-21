<form id="pinjaman-form" method="POST" action="{{ route('pinjaman.store') }}">
    @csrf

    <div class="space-y-6 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

        <h3 class="text-xl font-semibold text-zinc-800 dark:text-white">Tambah Peminjaman Baru</h3>

        <!-- Informasi Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1">Peminjam</label>
                <select name="id_peminjam" required 
                        class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 focus:outline-none focus:border-indigo-500 text-sm">
                    <option value="">— Pilih Peminjam —</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} {{ $u->role ? "({$u->role})" : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 focus:outline-none focus:border-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1">Rencana Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali_rencana" 
                       value="{{ date('Y-m-d', strtotime('+7 days')) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 focus:outline-none focus:border-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1">Catatan</label>
                <input type="text" name="pesan" placeholder="Keterangan tambahan (opsional)"
                       class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 focus:outline-none focus:border-indigo-500 text-sm">
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <label class="text-xs font-semibold text-zinc-500">DETAIL BARANG DIPINJAM</label>
                <button type="button" id="add-row"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all">
                    <span class="text-lg leading-none">+</span>
                    Tambah Item
                </button>
            </div>

            <div id="detail-rows" class="space-y-3">
                <!-- Dynamic rows akan dimuat di sini -->
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <button type="button" data-modal-close 
                    class="px-6 py-3 text-sm font-medium rounded-xl border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                Batal
            </button>
            <button type="submit" 
                    class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                Simpan Peminjaman
            </button>
        </div>
    </div>
</form>

<!-- Template untuk Row Baru -->
<template id="detail-row-template">
    <div class="detail-row p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border border-zinc-200 dark:border-zinc-700">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Nama Alat -->
            <div class="md:col-span-6">
                <label class="block text-xs text-zinc-500 mb-1">Nama Alat</label>
                <select name="details[__INDEX__][alat_id]" 
                        class="alat-select w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 focus:outline-none focus:border-indigo-500 text-sm" 
                        data-available="0">
                    <option value="">— Pilih Alat —</option>
                    @foreach($alats as $a)
                        @php 
                            $avail = $a->jumlah_total - ($a->jumlah_rusak ?? 0) - ($a->jumlah_dipinjam ?? 0); 
                        @endphp
                        <option value="{{ $a->id }}" data-available="{{ $avail }}">
                            {{ $a->nama_alat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah -->
            <div class="md:col-span-2">
                <label class="block text-xs text-zinc-500 mb-1">Jumlah</label>
                <input type="number" name="details[__INDEX__][jumlah]" value="1" min="1"
                       class="jumlah-input w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 focus:outline-none focus:border-indigo-500 text-sm">
            </div>

            <!-- Tersedia -->
            <div class="md:col-span-2">
                <label class="block text-xs text-zinc-500 mb-1">Tersedia</label>
                <div class="available-count px-4 py-3 text-sm font-medium text-emerald-600 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-xl">
                    0
                </div>
            </div>

            <!-- Hapus -->
            <div class="md:col-span-2">
                <button type="button" class="remove-row w-full h-[52px] px-4 py-3 text-sm font-medium bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-xl transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('detail-rows');
    const template = document.getElementById('detail-row-template');
    const addBtn = document.getElementById('add-row');

    function updateAvailableInfo(row) {
        const select = row.querySelector('.alat-select');
        const countEl = row.querySelector('.available-count');
        const input = row.querySelector('.jumlah-input');

        if (!select || !countEl) return;

        const selectedOption = select.options[select.selectedIndex];
        const available = parseInt(selectedOption?.dataset.available || 0);

        countEl.textContent = available;
        if (input) input.max = available || 999;
    }

    // Tambah Row Baru
    addBtn.addEventListener('click', function () {
        let index = container.children.length;
        let html = template.innerHTML.replace(/__INDEX__/g, index);

        const temp = document.createElement('div');
        temp.innerHTML = html;
        const newRow = temp.firstElementChild;

        container.appendChild(newRow);
        updateAvailableInfo(newRow);
    });

    // Event Delegation
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('alat-select')) {
            updateAvailableInfo(e.target.closest('.detail-row'));
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.detail-row').remove();
        }
    });

    // Validasi sebelum submit
    document.getElementById('pinjaman-form').addEventListener('submit', function (e) {
        const rows = container.querySelectorAll('.detail-row');
        for (let row of rows) {
            const select = row.querySelector('.alat-select');
            const input = row.querySelector('.jumlah-input');
            if (!select || !input) continue;

            const available = parseInt(select.selectedOptions[0]?.dataset.available || 0);
            const jumlah = parseInt(input.value || 0);

            if (jumlah > available) {
                e.preventDefault();
                alert(`Jumlah melebihi stok yang tersedia! Maksimal: ${available}`);
                return;
            }
        }
    });
});
</script>