@php $isEdit = isset($alat) && $alat->exists; @endphp

<form id="alat-form" method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('alats.update', $alat) : route('alats.store') }}" autocomplete="off">

    @csrf
    @if($isEdit) @method('PUT') @endif

    <!-- Hidden dummy fields to discourage browser autofill -->
    <input type="text" name="__fake_username_alat" id="__fake_username_alat" autocomplete="off" style="position: absolute; left: -9999px; top: -9999px;" />
    <input type="password" name="__fake_password_alat" id="__fake_password_alat" autocomplete="off" style="position: absolute; left: -9999px; top: -9999px;" />

    <div class="space-y-6">

        <div class="flex items-center gap-5 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700">
            <div class="relative shrink-0">
                <div id="photo-preview" class="w-24 h-20 rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-100 to-violet-100 flex items-center justify-center border-2 border-dashed border-indigo-200 dark:border-indigo-700 transition-all">
                    @if($isEdit && $alat->path_foto)
                        <img src="{{ asset('storage/' . $alat->path_foto) }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-300 dark:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="5" width="18" height="14" rx="2" ry="2" />
                            <circle cx="8.5" cy="10.5" r="1.5" />
                            <path d="M21 15l-5-5-4 4-3-3-5 5" />
                        </svg>
                    @endif
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Foto Alat</p>
                <p class="text-xs text-zinc-400 mb-3">Format JPG, PNG. Maks 3MB.</p>
                <div class="flex items-center gap-2">
                    <label for="profile_photo_path" class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 text-xs font-medium transition-colors">Pilih Foto</label>
                    <button type="button" id="take-photo-btn" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs">Ambil Foto</button>
                </div>
                <input type="file" name="profile_photo_path" id="profile_photo_path" accept="image/*" capture="environment" class="hidden">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Nama Alat <span class="text-red-500">*</span></label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat ?? '') }}" required class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white">
                @error('nama_alat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="w-44">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Kategori</label>
                <select name="id_kategori" class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white">
                    <option value="">— Pilih Kategori —</option>
                    @foreach(($kategoris ?? []) as $kat)
                        <option value="{{ $kat->id }}" @if(old('id_kategori', $alat->id_kategori ?? '') == $kat->id) selected @endif>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Deskripsi</label>
            <input type="text" name="deskripsi" value="{{ old('deskripsi', $alat->deskripsi ?? '') }}" class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Jumlah Total</label>
                <input type="number" name="jumlah_total" min="0" value="{{ old('jumlah_total', $alat->jumlah_total ?? 0) }}" class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Jumlah Dipinjam</label>
                <input type="number" name="jumlah_dipinjam" min="0" value="{{ old('jumlah_dipinjam', $alat->jumlah_dipinjam ?? 0) }}" class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Jumlah Rusak</label>
                <input type="number" name="jumlah_rusak" min="0" value="{{ old('jumlah_rusak', $alat->jumlah_rusak ?? 0) }}" class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white">
            </div>
        </div>

        <div class="flex items-center justify-between pt-5 border-t border-zinc-100 dark:border-zinc-800">
            <button type="button" data-modal-close class="px-4 py-2.5 text-sm font-medium rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">Batal</button>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Alat' }}</button>
        </div>

    </div>
</form>

<!-- Camera modal inserted inside the AJAX modal body so the central modal.js can find it -->
<div id="camera-modal" class="hidden fixed inset-0 z-40 items-center justify-center p-4" style="background:rgba(0,0,0,0.6);">
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl w-full max-w-2xl max-h-[94vh] flex flex-col overflow-hidden p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Ambil Foto</h3>
            <button id="close-camera-btn" class="text-sm px-3 py-1 rounded border">Tutup</button>
        </div>
        <div class="flex-1 flex items-center justify-center">
            <video id="camera-video" autoplay playsinline class="w-full h-[60vh] bg-black rounded-lg object-cover"></video>
        </div>
        <div class="mt-3 flex items-center justify-center gap-3">
            <button id="capture-btn" class="px-4 py-2 rounded-lg bg-indigo-600 text-white">Ambil</button>
        </div>
    </div>
</div>
