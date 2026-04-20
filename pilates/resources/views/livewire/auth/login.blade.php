<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;
    public bool $showPassword = false;

    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) return;

        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="w-full max-w-md login-card">

    {{-- ══════════════════════════════════
         LOGO & IDENTITAS INSTANSI
    ══════════════════════════════════ --}}
    <div class="flex flex-col items-center mb-8 logo-ring">

        {{-- Cincin dekoratif + logo --}}
        <div class="relative mb-4">
            {{-- Ring luar --}}
            <div class="w-24 h-24 rounded-full bg-white dark:bg-zinc-800 shadow-lg border-4 border-blue-100 dark:border-blue-900/50 flex items-center justify-center">
                {{-- Ganti src ini dengan logo instansi Anda --}}
                {{-- <img src="{{ asset('images/logo-desa.png') }}" alt="Logo" class="w-14 h-14 object-contain"> --}}

                {{-- Placeholder logo (hapus ini jika sudah ada gambar) --}}
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-inner">
                    <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                    </svg>
                </div>
            </div>

            {{-- Titik dekoratif --}}
            <span class="absolute top-1 right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white dark:border-zinc-900"></span>
        </div>

        {{-- Nama Instansi --}}
        <h1 class="text-xl font-bold text-zinc-900 dark:text-white text-center leading-tight">
            PILATES
        </h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 text-center">
           Pinjam Alat Desa
        </p>

        {{-- Garis pembatas --}}
        <div class="flex items-center gap-3 mt-5 w-full">
            <div class="h-px flex-1 bg-zinc-200 dark:bg-zinc-700"></div>
            <span class="text-xs font-semibold uppercase tracking-widest text-zinc-400">Masuk ke PILATES</span>
            <div class="h-px flex-1 bg-zinc-200 dark:bg-zinc-700"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════
         CARD LOGIN
    ══════════════════════════════════ --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 sm:p-8">

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-5">

            {{-- ── Email ── --}}
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="size-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        wire:model="email"
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="nama@desa.go.id"
                        class="w-full pl-11 pr-4 py-3 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 dark:focus:border-blue-500 transition-all @error('email') border-red-400 dark:border-red-500 bg-red-50 dark:bg-red-950/20 @enderror"
                    >
                </div>
                @error('email')
                    <p class="flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400 font-medium">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ── Password ── --}}
            <div class="space-y-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="size-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <input
                        wire:model="password"
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        x-data="{ show: false }"
                        :type="show ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-11 pr-12 py-3 text-sm border border-zinc-200 dark:border-zinc-700 rounded-xl bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 dark:focus:border-blue-500 transition-all @error('password') border-red-400 dark:border-red-500 @enderror"
                        x-data="{ show: false }"
                    >
                    {{-- Toggle Show/Hide Password --}}
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors"
                        aria-label="Tampilkan/sembunyikan kata sandi"
                    >
                        <svg id="eye-icon" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-off-icon" class="size-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400 font-medium">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ── Ingat Saya ── --}}
            <div class="flex items-center gap-3">
                <input
                    wire:model="remember"
                    id="remember"
                    type="checkbox"
                    class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500/30 focus:ring-2 cursor-pointer"
                >
                <label for="remember" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 cursor-pointer select-none">
                    Ingat saya di perangkat ini
                </label>
            </div>

            {{-- ── Tombol Masuk ── --}}
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-base font-semibold rounded-xl transition-colors shadow-sm disabled:opacity-60 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
            >
                {{-- Loading State --}}
                <span wire:loading wire:target="login">
                    <svg class="animate-spin size-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                {{-- Default State --}}
                <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Masuk
                </span>
            </button>

        </form>
    </div>

    {{-- ── Info bawah ── --}}
    <div class="mt-6 text-center space-y-2">
        <p class="text-sm text-zinc-500 dark:text-zinc-400">
            Kendala Akses? 
            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Hubungi Administrator Desa</span>
        </p>
        <p class="text-xs text-zinc-400 dark:text-zinc-600">
            &copy; {{ date('Y') }} PILATES · Hak Cipta Dilindungi
        </p>
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeOn  = document.getElementById('eye-icon');
    const eyeOff = document.getElementById('eye-off-icon');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    eyeOn.classList.toggle('hidden', isHidden);
    eyeOff.classList.toggle('hidden', !isHidden);
}
</script>