<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PILATES — Pinjam Alat Desa</title>

    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#3b82f6',
                            dark: '#2563eb',
                            light: '#eff6ff',
                        }
                    },
                    animation: {
                        'slide-in': 'slideIn 0.8s ease forwards',
                        'fade-up': 'fadeUp 0.7s ease forwards',
                        'ken-burns': 'kenBurns 8s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        kenBurns: {
                            '0%': { transform: 'scale(1) translate(0, 0)' },
                            '100%': { transform: 'scale(1.08) translate(-1%, -1%)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ===== NAVBAR ===== */
        .navbar-blur {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255,255,255,0.92);
            border-bottom: 1px solid rgba(228,228,231,0.8);
        }
        .dark .navbar-blur {
            background: rgba(24,24,27,0.92);
            border-bottom: 1px solid rgba(63,63,70,0.6);
        }

        /* ===== HERO SLIDER ===== */
        .slide { display: none; }
        .slide.active { display: block; }

        .slide-overlay {
            background: linear-gradient(
                to bottom,
                rgba(15,23,42,0.15) 0%,
                rgba(15,23,42,0.55) 60%,
                rgba(15,23,42,0.82) 100%
            );
        }

        /* Ken Burns animation per slide */
        .slide.active .slide-img { animation: kenBurns 8s ease-in-out forwards; }

        @keyframes kenBurns {
            0% { transform: scale(1); }
            100% { transform: scale(1.07); }
        }

        /* Slide transition fade */
        .slide { opacity: 0; transition: opacity 0.7s ease; position: absolute; inset: 0; }
        .slide.active { opacity: 1; display: block; }
        #hero-wrapper { position: relative; }

        /* ===== HERO TEXT ===== */
        .hero-badge {
            background: rgba(59,130,246,0.18);
            border: 1px solid rgba(59,130,246,0.35);
            backdrop-filter: blur(8px);
        }

        /* ===== PROGRESS BAR ===== */
        .progress-bar {
            transition: width 0.1s linear;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
        }

        /* ===== FEATURES ===== */
        .feature-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px -4px rgba(59,130,246,0.14), 0 4px 12px -2px rgba(0,0,0,0.06);
        }

        /* ===== STEPS ===== */
        .step-line::after {
            content: '';
            position: absolute;
            top: 28px;
            left: calc(100% + 0px);
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #bfdbfe);
        }

        /* ===== SCROLL ANIMATIONS ===== */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* ===== STAT COUNTER ===== */
        .stat-card {
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            border: 1px solid #bfdbfe;
        }
        .dark .stat-card {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e293b 100%);
            border-color: #1d4ed8;
        }

        /* ===== CTA SECTION ===== */
        .cta-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 40%, #1e40af 100%);
        }
    </style>
</head>

<body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-white antialiased">

    <!-- ─────────────────────────────── -->
    <!--  NAVBAR                         -->
    <!-- ─────────────────────────────── -->
    <nav id="navbar" class="navbar-blur fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-md group-hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-base font-bold text-zinc-900 dark:text-white leading-tight">PILATES</div>
                        <div class="text-xs font-medium text-zinc-400 leading-tight">Pinjam Alat Desa</div>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="#fitur" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-primary hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Fitur</a>
                    <a href="#cara-pakai" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-primary hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Cara Pakai</a>
                    <a href="#kontak" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-primary hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Kontak</a>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Dark mode toggle -->
                    <button onclick="toggleDark()" class="w-10 h-10 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all" aria-label="Toggle dark mode">
                        <svg id="icon-sun" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        <svg id="icon-moon" class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-5 h-11 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-lg transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 h-11 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-lg transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Masuk
                            </a>
                        @endauth
                    @endif

                    <!-- Mobile hamburger -->
                    <button id="menu-toggle" onclick="toggleMenu()" class="md:hidden w-10 h-10 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-zinc-100 dark:border-zinc-800 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-lg px-4 py-3 space-y-1">
            <a href="#fitur" onclick="toggleMenu()" class="block px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Fitur</a>
            <a href="#cara-pakai" onclick="toggleMenu()" class="block px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Cara Pakai</a>
            <a href="#kontak" onclick="toggleMenu()" class="block px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Kontak</a>
        </div>
    </nav>


    <!-- ─────────────────────────────── -->
    <!--  HERO SLIDER                    -->
    <!-- ─────────────────────────────── -->
    <section id="hero-wrapper" class="relative h-screen min-h-[600px] overflow-hidden">

        <!-- Slide 1 -->
        <div class="slide active" data-slide="0">
            <div class="slide-img absolute inset-0 bg-cover bg-center"
                 style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600&q=80')">
            </div>
            <div class="slide-overlay absolute inset-0"></div>
        </div>

        <!-- Slide 2 -->
        <div class="slide" data-slide="1">
            <div class="slide-img absolute inset-0 bg-cover bg-center"
                 style="background-image: url('https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=1600&q=80')">
            </div>
            <div class="slide-overlay absolute inset-0"></div>
        </div>

        <!-- Slide 3 -->
        <div class="slide" data-slide="2">
            <div class="slide-img absolute inset-0 bg-cover bg-center"
                 style="background-image: url('https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1600&q=80')">
            </div>
            <div class="slide-overlay absolute inset-0"></div>
        </div>

        <!-- Hero Content Overlay -->
        <div class="relative z-10 h-full flex flex-col justify-end pb-20 px-4 sm:px-8 lg:px-16 max-w-7xl mx-auto">

            <!-- Badge -->
            <div class="hero-badge inline-flex items-center gap-2 px-4 py-2 rounded-full w-fit mb-5 animate-fade-up" style="animation-delay:0.1s">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-white text-sm font-semibold tracking-wide">Sistem Aktif · Desa Digital</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight max-w-3xl animate-slide-in">
                Pinjam Alat Desa<br>
                <span class="text-blue-300">Mudah &amp; Transparan</span>
            </h1>

            <p class="mt-4 text-lg sm:text-xl text-white/80 max-w-xl font-medium leading-relaxed animate-fade-up" style="animation-delay:0.2s">
                Platform digital untuk peminjaman aset dan peralatan desa. Cukup daftar, ajukan, dan ambil — tanpa antre panjang.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-8 flex flex-wrap gap-4 animate-fade-up" style="animation-delay:0.35s">
                @if (Route::has('login'))
                    @guest
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-7 h-12 bg-primary hover:bg-primary-dark text-white font-semibold text-base rounded-xl transition-all shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Mulai Pinjam
                        </a>
                    @endguest
                @endif
                <a href="#cara-pakai"
                   class="inline-flex items-center gap-2 px-7 h-12 bg-white/15 hover:bg-white/25 border border-white/30 text-white font-semibold text-base rounded-xl transition-all backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Cara Pakai
                </a>
            </div>
        </div>

        <!-- Slide Indicators & Progress -->
        <div class="absolute bottom-8 right-8 z-20 flex flex-col items-end gap-3">
            <!-- Dots -->
            <div class="flex gap-2" id="slide-dots">
                <button onclick="goToSlide(0)" class="slide-dot w-2 h-2 rounded-full bg-white transition-all duration-300 opacity-100"></button>
                <button onclick="goToSlide(1)" class="slide-dot w-2 h-2 rounded-full bg-white/40 transition-all duration-300"></button>
                <button onclick="goToSlide(2)" class="slide-dot w-2 h-2 rounded-full bg-white/40 transition-all duration-300"></button>
            </div>
            <!-- Prev/Next -->
            <div class="flex gap-2">
                <button onclick="prevSlide()" class="w-10 h-10 rounded-xl bg-white/15 border border-white/25 flex items-center justify-center text-white hover:bg-white/30 transition-all backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="nextSlide()" class="w-10 h-10 rounded-xl bg-white/15 border border-white/25 flex items-center justify-center text-white hover:bg-white/30 transition-all backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Progress bar -->
        <div class="absolute bottom-0 left-0 right-0 z-20 h-0.5 bg-white/10">
            <div id="progress-bar" class="progress-bar h-full" style="width:0%"></div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-1 animate-bounce opacity-60">
            <span class="text-white text-xs font-medium">Gulir ke bawah</span>
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </section>


    <!-- ─────────────────────────────── -->
    <!--  STATS STRIP                    -->
    <!-- ─────────────────────────────── -->
    <section class="bg-white dark:bg-zinc-900 border-b border-zinc-100 dark:border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ([
                    ['250+', 'Alat Tersedia', '#3b82f6', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['1.2K', 'Transaksi Selesai', '#10b981', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['38', 'Dusun Terdaftar', '#f59e0b', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['98%', 'Kepuasan Warga', '#8b5cf6', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ] as [$val, $label, $color, $icon])
                <div class="stat-card rounded-2xl p-5 reveal">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:{{ $color }}1a">
                            <svg class="w-5 h-5" fill="none" stroke="{{ $color }}" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-extrabold text-zinc-900 dark:text-white leading-tight">{{ $val }}</div>
                            <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $label }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- ─────────────────────────────── -->
    <!--  KEUNGGULAN / FITUR             -->
    <!-- ─────────────────────────────── -->
    <section id="fitur" class="py-20 bg-zinc-50 dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary-light dark:bg-blue-900/30 text-primary dark:text-blue-300 text-sm font-semibold rounded-full mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Keunggulan Platform
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white leading-tight">
                    Kenapa Melalui PILATES?
                </h2>
                <p class="mt-3 text-base text-zinc-500 dark:text-zinc-400 leading-relaxed">
                    Dirancang khusus untuk kemudahan warga desa — dari peminjaman hingga pengembalian, semua serba digital.
                </p>
            </div>

            <!-- Feature Cards Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ([
                    [
                        'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                        'title' => 'Akses Kapan Saja',
                        'desc' => 'Ajukan peminjaman lewat HP atau komputer, 24 jam sehari tanpa perlu datang ke kantor desa.',
                        'color' => '#3b82f6',
                        'bg' => '#eff6ff',
                        'delay' => '0s'
                    ],
                    [
                        'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                        'title' => 'Status Real-Time',
                        'desc' => 'Pantau status permohonan secara langsung. Disetujui, ditolak, atau sedang diproses — semua bisa dipantau.',
                        'color' => '#10b981',
                        'bg' => '#ecfdf5',
                        'delay' => '0.1s'
                    ],
                    [
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                        'title' => 'Riwayat Lengkap',
                        'desc' => 'Semua data peminjaman tercatat rapi dan bisa diakses kapan saja sebagai bukti dan referensi.',
                        'color' => '#f59e0b',
                        'bg' => '#fffbeb',
                        'delay' => '0.2s'
                    ],
                    [
                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                        'title' => 'Manajemen Warga',
                        'desc' => 'Perangkat desa dapat mengelola data warga, verifikasi identitas, dan kontrol akses dengan mudah.',
                        'color' => '#8b5cf6',
                        'bg' => '#f5f3ff',
                        'delay' => '0.3s'
                    ],
                    [
                        'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                        'title' => 'Notifikasi Otomatis',
                        'desc' => 'Warga mendapat pemberitahuan otomatis saat status peminjaman berubah. Tidak ada yang terlewat.',
                        'color' => '#ef4444',
                        'bg' => '#fef2f2',
                        'delay' => '0.4s'
                    ],
                    [
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                        'title' => 'Laporan &amp; Statistik',
                        'desc' => 'Dashboard admin dengan grafik pemakaian alat, laporan bulanan, dan analisis data untuk pengambilan keputusan.',
                        'color' => '#0ea5e9',
                        'bg' => '#f0f9ff',
                        'delay' => '0.5s'
                    ],
                ] as $feature)
                <div class="feature-card bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800 reveal" style="transition-delay:{{ $feature['delay'] }}">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 flex-shrink-0" style="background:{{ $feature['bg'] }}">
                        <svg class="w-6 h-6" fill="none" stroke="{{ $feature['color'] }}" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2">{!! $feature['title'] !!}</h3>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- ─────────────────────────────── -->
    <!--  CARA PAKAI (STEPS)             -->
    <!-- ─────────────────────────────── -->
    <section id="cara-pakai" class="py-20 bg-white dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-sm font-semibold rounded-full mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    Panduan Penggunaan
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white leading-tight">
                    Cara Pakai PILATES
                </h2>
                <p class="mt-3 text-base text-zinc-500 dark:text-zinc-400 leading-relaxed">
                    Hanya 5 langkah mudah untuk meminjam peralatan desa dari mana saja.
                </p>
            </div>

            <!-- Steps -->
            <div class="relative">

                <!-- Desktop connecting line -->
                <div class="hidden lg:block absolute top-10 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-blue-200 via-blue-400 to-blue-200 dark:from-blue-900 dark:via-blue-600 dark:to-blue-900 z-0"></div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-8 relative z-10">
                    @foreach ([
                        ['01', 'Daftar Akun', 'Buat akun menggunakan NIK dan data diri. Verifikasi dilakukan oleh perangkat desa.', '#3b82f6', 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                        ['02', 'Pilih Alat', 'Telusuri katalog alat yang tersedia. Filter berdasarkan jenis, kondisi, dan ketersediaan.', '#10b981', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                        ['03', 'Ajukan Pinjam', 'Isi formulir peminjaman: tanggal, durasi, dan tujuan penggunaan. Kirim permohonan.', '#f59e0b', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['04', 'Tunggu Approval', 'Perangkat desa memverifikasi dan menyetujui permintaan. Notifikasi dikirim ke akun Anda.', '#8b5cf6', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['05', 'Ambil & Kembalikan', 'Ambil alat sesuai jadwal, gunakan dengan baik, dan kembalikan tepat waktu.', '#ef4444', 'M5 13l4 4L19 7'],
                    ] as $i => [$num, $title, $desc, $color, $icon])
                    <div class="flex flex-col items-center text-center reveal" style="transition-delay:{{ $i * 0.1 }}s">
                        <!-- Step Circle -->
                        <div class="relative mb-5">
                            <div class="w-20 h-20 rounded-full flex items-center justify-center shadow-lg border-4 border-white dark:border-zinc-900"
                                 style="background: linear-gradient(135deg, {{ $color }}, {{ $color }}cc)">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                                </svg>
                            </div>
                            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-white dark:bg-zinc-900 border-2 flex items-center justify-center shadow-sm" style="border-color:{{ $color }}">
                                <span class="text-xs font-extrabold" style="color:{{ $color }}">{{ $num }}</span>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2">{{ $title }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-[180px]">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- CTA below steps -->
            <div class="mt-14 text-center reveal">
                @if (Route::has('login'))
                    @guest
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-8 h-12 bg-primary hover:bg-primary-dark text-white font-semibold text-base rounded-xl transition-all shadow-lg hover:shadow-blue-500/25 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Daftar Sekarang — Gratis!
                        </a>
                    @endguest
                @endif
                <p class="mt-3 text-sm text-zinc-400">Sudah punya akun?
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
                    @endif
                </p>
            </div>
        </div>
    </section>


    <!-- ─────────────────────────────── -->
    <!--  CTA BANNER                     -->
    <!-- ─────────────────────────────── -->
    <section class="py-16 cta-gradient relative overflow-hidden">
        <!-- Decorative circles -->
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-24 -left-12 w-80 h-80 bg-white/5 rounded-full"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                Siap Meminjam Peralatan Desa?
            </h2>
            <p class="text-blue-100 text-base font-medium mb-8 max-w-xl mx-auto leading-relaxed">
                Bergabunglah dengan ratusan warga yang sudah merasakan kemudahan sistem peminjaman digital desa kami.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                @if (Route::has('login'))
                    @guest
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-8 h-12 bg-white text-primary font-bold text-base rounded-xl hover:bg-blue-50 transition-all shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Masuk Sekarang
                        </a>
                    @endguest
                @endif
                <a href="#kontak"
                   class="inline-flex items-center gap-2 px-8 h-12 bg-white/15 border border-white/30 text-white font-semibold text-base rounded-xl hover:bg-white/25 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>


    <!-- ─────────────────────────────── -->
    <!--  FOOTER / KONTAK                -->
    <!-- ─────────────────────────────── -->
    <footer id="kontak" class="bg-zinc-900 dark:bg-zinc-950 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

                <!-- Brand -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold leading-tight">PILATES</div>
                            <div class="text-xs text-zinc-400 leading-tight">Pinjam Alat Desa</div>
                        </div>
                    </div>
                    <p class="text-sm text-zinc-400 leading-relaxed max-w-sm mb-5">
                        Sistem informasi peminjaman aset dan peralatan desa berbasis digital. Transparan, mudah, dan dapat diakses oleh seluruh warga.
                    </p>
                    <!-- Social links -->
                    <div class="flex gap-3">
                        @foreach ([
                            ['Facebook', 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                            ['Instagram', 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 19.5h11a3 3 0 003-3v-11a3 3 0 00-3-3h-11a3 3 0 00-3 3v11a3 3 0 003 3z'],
                            ['WhatsApp', 'M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z'],
                        ] as [$soc, $path])
                        <a href="#" aria-label="{{ $soc }}" class="w-9 h-9 rounded-lg bg-zinc-800 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Menu -->
                <div>
                    <h4 class="text-sm font-bold text-white mb-5 uppercase tracking-wider">Menu</h4>
                    <ul class="space-y-3">
                        @foreach (['Beranda' => '#', 'Fitur' => '#fitur', 'Cara Pakai' => '#cara-pakai', 'Kontak' => '#kontak'] as $label => $href)
                        <li>
                            <a href="{{ $href }}" class="text-sm text-zinc-400 hover:text-primary transition-colors inline-flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                {{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-sm font-bold text-white mb-5 uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Alamat</div>
                                <div class="text-sm text-zinc-400 leading-relaxed">Kantor Desa, Jl. Merdeka No. 1<br>Kabupaten _____, Provinsi _____</div>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Telepon</div>
                                <a href="tel:+62xxx" class="text-sm text-zinc-400 hover:text-primary transition-colors">+62 xxx-xxxx-xxxx</a>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Email</div>
                                <a href="mailto:admin@pilates-desa.id" class="text-sm text-zinc-400 hover:text-primary transition-colors">admin@pilates-desa.id</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Jam Operasional</div>
                                <div class="text-sm text-zinc-400">Senin – Jumat: 08.00 – 16.00<br>Sabtu: 08.00 – 12.00</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-zinc-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-zinc-500">
                    &copy; {{ date('Y') }} <span class="text-zinc-300 font-semibold">PILATES</span> — Pinjam Alat Desa. Hak cipta dilindungi.
                </p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-sm text-zinc-400">Sistem berjalan normal</span>
                </div>
            </div>
        </div>
    </footer>


    <!-- ─────────────────────────────── -->
    <!--  JAVASCRIPT                     -->
    <!-- ─────────────────────────────── -->
    <script>
        // ── Dark Mode ──
        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
        // Init theme
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        // ── Mobile Menu ──
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // ── Navbar scroll shrink ──
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-md');
            } else {
                nav.classList.remove('shadow-md');
            }
        });

        // ── Slider ──
        let currentSlide = 0;
        const totalSlides = 3;
        let progressInterval;
        let autoInterval;
        let progressValue = 0;
        const SLIDE_DURATION = 6000; // ms

        function showSlide(index) {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.slide-dot');

            slides.forEach((s, i) => {
                s.classList.remove('active');
                if (dots[i]) {
                    dots[i].classList.remove('opacity-100', 'w-6');
                    dots[i].classList.add('opacity-40');
                }
            });

            slides[index].classList.add('active');
            if (dots[index]) {
                dots[index].classList.add('opacity-100', 'w-6');
                dots[index].classList.remove('opacity-40');
            }

            currentSlide = index;
            resetProgress();
        }

        function nextSlide() { showSlide((currentSlide + 1) % totalSlides); }
        function prevSlide() { showSlide((currentSlide - 1 + totalSlides) % totalSlides); }
        function goToSlide(i) { showSlide(i); }

        function resetProgress() {
            clearInterval(progressInterval);
            clearInterval(autoInterval);
            progressValue = 0;
            document.getElementById('progress-bar').style.width = '0%';

            const step = 100 / (SLIDE_DURATION / 100);
            progressInterval = setInterval(() => {
                progressValue += step;
                if (progressValue >= 100) progressValue = 100;
                document.getElementById('progress-bar').style.width = progressValue + '%';
            }, 100);

            autoInterval = setTimeout(() => nextSlide(), SLIDE_DURATION);
        }

        // Init slider
        showSlide(0);

        // ── Scroll reveal ──
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    </script>
</body>
</html>