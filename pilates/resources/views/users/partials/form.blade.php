@php $isEdit = isset($user) && $user->exists; @endphp

<form id="user-form" method="POST" enctype="multipart/form-data"
      action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}">

    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-6">

        {{-- Foto Profil --}}
        <div class="flex items-center gap-5 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700">
            {{-- Preview --}}
            <div class="relative shrink-0">
                <div id="photo-preview"
                     class="w-20 h-20 rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-100 to-violet-100 dark:from-indigo-900/40 dark:to-violet-900/40 flex items-center justify-center border-2 border-dashed border-indigo-200 dark:border-indigo-700 transition-all">
                    @if($isEdit && $user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-300 dark:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    @endif
                </div>
            </div>

            {{-- Upload Info --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Foto Profil</p>
                <p class="text-xs text-zinc-400 mb-3">Format JPG, PNG atau GIF. Maksimal 3MB.</p>
                <label for="profile_photo_path"
                       class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 text-xs font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Pilih Foto
                </label>
                <button type="button" id="take-photo-btn" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 text-xs font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l3-3h4l3 3h4v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    </svg>
                    Ambil Foto
                </button>
                <input type="file" name="profile_photo_path" id="profile_photo_path"
                       accept="image/*" class="hidden" onchange="previewImage(this)">

                <!-- Camera modal/overlay -->
                <div id="camera-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black/60">
                    <div class="w-full max-w-lg bg-white dark:bg-zinc-900 rounded-xl overflow-hidden shadow-lg">
                        <div class="p-4">
                            <video id="camera-video" autoplay playsinline class="w-full h-64 bg-black rounded-md"></video>
                            <div class="flex items-center justify-end gap-2 mt-3">
                                <button type="button" id="capture-btn" class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Ambil</button>
                                <button type="button" id="close-camera-btn" class="px-4 py-2 rounded-lg border">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-zinc-100 dark:bg-zinc-800"></div>
            <span class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400">Informasi Akun</span>
            <div class="h-px flex-1 bg-zinc-100 dark:bg-zinc-800"></div>
        </div>

        {{-- Input Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Nama --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">
                    Nama Lengkap <span class="text-red-500 normal-case">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                       placeholder="Masukkan nama lengkap"
                       class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-all" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">
                    Email <span class="text-red-500 normal-case">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                       placeholder="contoh@email.com"
                       class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-all" required>
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Role <span class="text-red-500 normal-case">*</span></label>
                <select name="role" class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-all" required>
                    @php $selectedRole = old('role', $user->role ?? 'user'); @endphp
                    <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ $selectedRole === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="user" {{ $selectedRole === 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">
                    Password
                    @if(!$isEdit) <span class="text-red-500 normal-case">*</span>
                    @else <span class="normal-case font-normal text-zinc-400">(kosongkan jika tidak diubah)</span>
                    @endif
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password-field"
                           placeholder="{{ $isEdit ? '••••••••' : 'Min. 8 karakter' }}"
                           class="w-full px-3.5 py-2.5 pr-10 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-all" {{ !$isEdit ? 'required' : '' }}>
                    <button type="button" data-toggle-pwd="password-field" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- NOTE: Password confirmation removed by request --}}

            {{-- Telepon --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">No. Telepon</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/70 text-zinc-400 text-sm">+62</span>
                    <input type="text" name="phone" value="{{ old('phone', ltrim($user->phone ?? '', '+620')) }}"
                           placeholder="8xx-xxxx-xxxx"
                           class="flex-1 px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-r-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-all">
                </div>
                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Alamat --}}
            <div class="space-y-1.5 md:col-span-2">
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Alamat</label>
                <textarea name="address" rows="3"
                          placeholder="Jl. Contoh No. 1, Kota, Provinsi"
                          class="w-full px-3.5 py-2.5 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white placeholder-zinc-300 dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-all resize-none">{{ old('address', $user->address ?? '') }}</textarea>
                @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-between pt-5 border-t border-zinc-100 dark:border-zinc-800">
            <button type="button" onclick="history.back()"
                    class="px-4 py-2.5 text-sm font-medium rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                Batal
            </button>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
            </button>
        </div>

    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            preview.classList.remove('border-dashed');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePwd(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;
    const isCurrentlyPassword = input.type === 'password';
    input.type = isCurrentlyPassword ? 'text' : 'password';
    // update button opacity and icon
    btn.style.opacity = isCurrentlyPassword ? '1' : '0.5';
    const eyeSvg = '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>\
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>\
        </svg>';
    const eyeOffSvg = '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/>\
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.94 10.94A3 3 0 0113.06 13.06"/>\
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c1.16 0 2.273.187 3.313.53"/>\
        </svg>';
    btn.innerHTML = isCurrentlyPassword ? eyeOffSvg : eyeSvg;
}
<!-- Inline form scripts removed; modal component handles preview and password toggles -->

// Camera capture helpers
let _cameraStream = null;
function openCamera() {
    const modal = document.getElementById('camera-modal');
    const video = document.getElementById('camera-video');
    if (!modal || !video || !navigator.mediaDevices) return alert('Kamera tidak tersedia.');
    modal.classList.remove('hidden');
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(stream => {
            _cameraStream = stream;
            video.srcObject = stream;
            video.play();
        })
        .catch(err => {
            modal.classList.add('hidden');
            alert('Tidak dapat mengakses kamera: ' + (err.message || err));
        });
}

function closeCamera() {
    const modal = document.getElementById('camera-modal');
    const video = document.getElementById('camera-video');
    if (video) {
        video.pause();
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(t => t.stop());
            video.srcObject = null;
        }
    }
    _cameraStream = null;
    if (modal) modal.classList.add('hidden');
}

function captureImage() {
    const video = document.getElementById('camera-video');
    if (!video) return;
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    canvas.toBlob(function(blob) {
        if (!blob) return alert('Gagal mengambil foto.');
        const file = new File([blob], 'capture.jpg', { type: 'image/jpeg' });
        const input = document.getElementById('profile_photo_path');
        if (input) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            // update preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photo-preview');
                if (preview) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.classList.remove('border-dashed');
                }
            }
            reader.readAsDataURL(file);
        }
        closeCamera();
    }, 'image/jpeg', 0.95);
}

// wire buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('#take-photo-btn')) {
        openCamera();
    }
    if (e.target.closest('#close-camera-btn')) {
        closeCamera();
    }
    if (e.target.closest('#capture-btn')) {
        captureImage();
    }
});
// Camera handlers moved to modal component for AJAX-loaded forms