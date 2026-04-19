@php $isEdit = isset($kategori) && $kategori->exists; @endphp

<form id="kategori-form" method="POST" action="{{ $isEdit ? route('kategori-alat.update', $kategori) : route('kategori-alat.store') }}" autocomplete="off">

    @csrf
    @if($isEdit) @method('PUT') @endif

    <!-- Hidden dummy fields to discourage browser autofill -->
    <input type="text" name="__fake_username_kategori" id="__fake_username_kategori" autocomplete="off" style="position: absolute; left: -9999px; top: -9999px;" />
    <input type="password" name="__fake_password_kategori" id="__fake_password_kategori" autocomplete="off" style="position: absolute; left: -9999px; top: -9999px;" />

    <div class="space-y-6">

        <div class="flex items-center gap-3 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700">
            <div class="flex-1 min-w-0">

                <div class="grid grid-cols-1 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Nama Kategori <span class="text-red-500 normal-case">*</span></label>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" placeholder="Masukkan nama kategori"
                               class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all" required>
                        @error('nama_kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-zinc-100 dark:border-zinc-800">
            <button type="button" data-modal-close
                    class="px-4 py-2.5 text-sm font-medium rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">Batal</button>

            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kategori' }}
            </button>
        </div>

    </div>
</form>
