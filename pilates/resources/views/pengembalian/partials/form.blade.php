@php $isEdit = isset($pengembalian);
@endphp

<form id="pengembalian-form" method="POST" action="{{ $isEdit ? route('pengembalian.update', $pengembalian) : route('pengembalian.store') }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-6 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-zinc-800 dark:text-white">Form Pengembalian</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-2 uppercase">Foto Bukti</label>
                <div class="flex items-center gap-3">
                    <div id="photo-preview" class="w-24 h-24 rounded-xl overflow-hidden bg-zinc-50 dark:bg-zinc-800/60 flex items-center justify-center">
                        @if($isEdit && ($pengembalian->foto ?? false))
                            <img src="{{ asset('storage/' . $pengembalian->foto) }}" class="w-full h-full object-cover" alt="Foto">
                        @else
                            <svg class="w-10 h-10 text-zinc-300" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V8l-6-5H5z"></path></svg>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="inline-flex items-center gap-2 px-3 py-2 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-sm font-medium cursor-pointer">
                            Pilih Foto
                            <input type="file" name="foto" accept="image/*" data-photo-target class="hidden">
                        </label>
                        <button type="button" id="take-photo-btn" class="px-3 py-2 rounded-xl bg-indigo-600 text-white text-sm">Ambil Foto</button>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-2 uppercase">Pesan (opsional)</label>
                <textarea name="pesan" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border bg-white dark:bg-zinc-800">{{ old('pesan', $pengembalian->pesan ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-700">
            <button type="button" data-modal-close class="px-4 py-2 rounded-xl border">Batal</button>
            <button type="submit" class="px-6 py-2 rounded-xl bg-indigo-600 text-white">Simpan</button>
        </div>
    </div>
</form>

<script>
document.addEventListener('change', function(e){
    const input = e.target.closest('input[type="file"][name="foto"]');
    if (!input) return;
    const preview = input.closest('form')?.querySelector('#photo-preview');
    if (!preview) return;
    if (input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = function(ev){ preview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`; }
        reader.readAsDataURL(input.files[0]);
    }
});
</script>
