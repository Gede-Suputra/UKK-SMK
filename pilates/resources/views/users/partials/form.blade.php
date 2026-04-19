@php $isEdit = isset($user) && $user->exists; @endphp

<form id="user-form" method="POST" enctype="multipart/form-data" 
      action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}">

    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-6">

        <!-- Foto Profil -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Profil</label>
            
            <div class="flex items-center gap-4">
                <!-- Preview Area -->
                <div id="photo-preview" class="w-24 h-24 bg-gray-100 dark:bg-zinc-800 rounded-2xl flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-zinc-600 overflow-hidden">
                    @if($isEdit && $user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7" />
                        </svg>
                    @endif
                </div>

                <!-- Tombol Pilih Foto -->
                <div>
                    <label for="profile_photo_path" 
                           class="cursor-pointer px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium inline-flex items-center gap-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v-4m0 0l4 4m-4-4l4-4m12 0v4m0 0l-4-4m4 4l-4 4" />
                        </svg>
                        Pilih Foto Baru
                    </label>
                    <input type="file" 
                           name="profile_photo_path" 
                           id="profile_photo_path" 
                           accept="image/*" 
                           class="hidden"
                           onchange="previewImage(this)">
                </div>
            </div>
        </div>

        <!-- Input Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl dark:bg-zinc-800 dark:text-white" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl dark:bg-zinc-800 dark:text-white" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password {{ !$isEdit ? '<span class="text-red-500">*</span>' : '(kosongkan jika tidak diubah)' }}</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl dark:bg-zinc-800 dark:text-white" {{ !$isEdit ? 'required' : '' }}>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl dark:bg-zinc-800 dark:text-white" {{ !$isEdit ? 'required' : '' }}>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl dark:bg-zinc-800 dark:text-white">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Alamat</label>
                <textarea name="address" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl dark:bg-zinc-800 dark:text-white">{{ old('address', $user->address ?? '') }}</textarea>
            </div>
        </div>

        <!-- TOMBOL UPDATE / SIMPAN -->
        <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-zinc-700">
            <button type="submit" 
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $isEdit ? 'Update User' : 'Simpan User' }}
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
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>