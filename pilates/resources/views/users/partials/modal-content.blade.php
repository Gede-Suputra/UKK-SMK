<div class="p-6 bg-white dark:bg-gray-800 rounded-2xl">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        @if($user->profile_photo_path)
            <img 
                src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                alt="Profile Photo"
                class="w-20 h-20 rounded-2xl object-cover ring-2 ring-gray-200 dark:ring-zinc-700"
            >
        @else
            <div class="w-20 h-20 bg-gray-100 dark:bg-zinc-800 rounded-2xl flex items-center justify-center text-4xl font-semibold text-gray-400 dark:text-gray-500">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-base text-gray-600 dark:text-gray-400">{{ $user->role ?? 'User' }}</p>
        </div>
    </div>

    <!-- Content - Disamakan dengan Form Edit -->
    <div class="space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wider">EMAIL</label>
            <div class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200">
                {{ $user->email }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wider">NOMOR TELEPON</label>
            <div class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200">
                {{ $user->phone ?? '-' }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wider">ALAMAT</label>
            <div class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 leading-relaxed min-h-[52px]">
                {{ $user->address ?? '-' }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-zinc-700">
            
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wider">ROLE</label>
                <div class="inline-block px-5 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-800 text-purple-700 dark:text-purple-300 font-medium">
                    {{ $user->role ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wider">EMAIL VERIFIED</label>
                @if($user->email_verified_at)
                    <div class="inline-flex items-center gap-2 px-5 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-800 text-green-700 dark:text-green-400 font-medium">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        Terverifikasi
                    </div>
                @else
                    <div class="inline-flex items-center px-5 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-800 text-red-700 dark:text-red-400 font-medium">
                        Belum Terverifikasi
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>