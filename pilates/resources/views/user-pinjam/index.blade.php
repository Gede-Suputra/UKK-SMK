<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — PILATES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#3b82f6', dark: '#2563eb', light: '#eff6ff' }
                    },
                    keyframes: {
                        fadeUp:   { '0%': { opacity:'0', transform:'translateY(16px)' }, '100%': { opacity:'1', transform:'translateY(0)' } },
                        slideIn:  { '0%': { opacity:'0', transform:'translateX(-12px)' }, '100%': { opacity:'1', transform:'translateX(0)' } },
                        scaleIn:  { '0%': { opacity:'0', transform:'scale(0.95)' }, '100%': { opacity:'1', transform:'scale(1)' } },
                        shimmer:  { '0%':{ backgroundPosition:'-200% 0' }, '100%':{ backgroundPosition:'200% 0' } },
                        toastIn:  { '0%': { opacity:'0', transform:'translateY(12px) scale(0.97)' }, '100%': { opacity:'1', transform:'translateY(0) scale(1)' } },
                        toastOut: { '0%': { opacity:'1', transform:'translateY(0) scale(1)' }, '100%': { opacity:'0', transform:'translateY(8px) scale(0.97)' } },
                    },
                    animation: {
                        fadeUp: 'fadeUp 0.5s ease forwards',
                        slideIn:'slideIn 0.4s ease forwards',
                        scaleIn:'scaleIn 0.3s ease forwards',
                        shimmer:'shimmer 2s linear infinite',
                        toastIn:'toastIn 0.35s ease forwards',
                        toastOut:'toastOut 0.3s ease forwards',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Navbar ── */
        .navbar-blur {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255,255,255,0.93);
            border-bottom: 1px solid rgba(228,228,231,0.8);
        }
        .dark .navbar-blur {
            background: rgba(18,18,23,0.93);
            border-bottom: 1px solid rgba(63,63,70,0.6);
        }

        /* ── Stepper nav ── */
        .step-nav-item { cursor: pointer; }
        .step-nav-item.active .step-circle {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.18);
        }
        .step-nav-item.done .step-circle {
            background: #10b981;
            border-color: #10b981;
            color: #fff;
        }
        .step-nav-item.idle .step-circle {
            background: #fff;
            border-color: #d4d4d8;
            color: #a1a1aa;
        }
        .dark .step-nav-item.idle .step-circle {
            background: #27272a;
            border-color: #52525b;
        }
        .step-connector { flex: 1; height: 2px; background: #e4e4e7; border-radius: 2px; transition: background 0.4s; }
        .step-connector.done { background: #10b981; }
        .dark .step-connector { background: #3f3f46; }

        /* ── Cards hover ── */
        .card-hover { transition: transform 0.22s ease, box-shadow 0.22s ease; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 28px -4px rgba(59,130,246,0.12), 0 4px 10px -2px rgba(0,0,0,0.06); }

        /* ── Status badge ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; }

        /* ── Skeleton ── */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 8px;
        }
        .dark .skeleton { background: linear-gradient(90deg, #27272a 25%, #3f3f46 50%, #27272a 75%); background-size: 200% 100%; }

        /* ── Floating cart ── */
        #floating-cart {
            transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s ease;
        }
        #floating-cart.hidden-cart { transform: translateY(100px); opacity: 0; pointer-events: none; }

        /* ── Toast ── */
        .toast { animation: toastIn 0.35s ease forwards; }
        .toast.hiding { animation: toastOut 0.3s ease forwards; }

        /* ── Reveal on scroll ── */
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.55s ease, transform 0.55s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-d1 { transition-delay: 0.08s; }
        .reveal-d2 { transition-delay: 0.16s; }
        .reveal-d3 { transition-delay: 0.24s; }
        .reveal-d4 { transition-delay: 0.32s; }

        /* ── Alat card selected ring ── */
        .alat-card.selected { outline: 2px solid #3b82f6; outline-offset: 2px; }

        /* ── Quantity stepper ── */
        .qty-btn { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; transition: background 0.15s; cursor: pointer; }

        /* ── Page section tabs (mobile) ── */
        .tab-btn { flex: 1; padding: 10px 4px; font-size: 13px; font-weight: 600; border-radius: 10px; transition: all 0.2s; text-align: center; }
        .tab-btn.active { background: #3b82f6; color: #fff; box-shadow: 0 2px 8px rgba(59,130,246,0.3); }
        .tab-btn:not(.active) { color: #71717a; }

        /* hide section on mobile tab switch */
        .tab-section { display: block; }
        @media (max-width: 767px) {
            .tab-section { display: none; }
            .tab-section.tab-active { display: block; }
        }
        @media (min-width: 768px) {
            .tab-section { display: block !important; }
        }

        /* ── Number input hide arrows ── */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
        input[type=number] { -moz-appearance: textfield; }

        /* ── Popover filter ── */
        #filter-panel { transition: opacity 0.2s, transform 0.2s; }
        #filter-panel.hidden { opacity:0; pointer-events:none; transform: translateY(-6px); }
    </style>
</head>

<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-white antialiased">

    <!-- ─────────────────────────── -->
    <!--  NAVBAR                     -->
    <!-- ─────────────────────────── -->
    <header class="navbar-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16 gap-4">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center shadow-sm">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="hidden sm:block">
                    <div class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">PILATES</div>
                    <div class="text-xs text-zinc-400 leading-tight">Pinjam Alat Desa</div>
                </div>
            </a>

            <!-- Quick nav (desktop) -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="#pinjaman-aktif" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-primary hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Peminjaman Saya</a>
                <a href="#katalog" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-primary hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Katalog Alat</a>
                <a href="#form-pinjam" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-primary hover:bg-primary-light dark:hover:bg-zinc-800 rounded-lg transition-all">Ajukan Pinjam</a>
            </nav>

            <!-- Right -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Dark toggle -->
                <button onclick="toggleDark()" class="w-9 h-9 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all flex-shrink-0">
                    <svg class="w-4.5 h-4.5 hidden dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                <!-- User avatar + name -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <span class="hidden sm:block text-sm font-semibold text-zinc-700 dark:text-zinc-200 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 h-9 text-sm font-medium rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>


    <!-- ─────────────────────────── -->
    <!--  GREETING BANNER            -->
    <!-- ─────────────────────────── -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-500 text-white px-4 sm:px-6 lg:px-8 py-5">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="animate-fadeUp">
                <p class="text-blue-100 text-sm font-medium mb-0.5" id="greeting-time">Selamat datang kembali</p>
                <h1 class="text-xl font-bold">{{ auth()->user()->name }} 👋</h1>
            </div>
            <div class="flex items-center gap-3 flex-wrap animate-fadeUp" style="animation-delay:0.1s">
                @php
                    $totalAktif = $pinjamans->count();
                @endphp
                <div class="flex items-center gap-2 bg-white/15 border border-white/20 rounded-xl px-4 py-2 backdrop-blur-sm">
                    <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-sm font-semibold">{{ $totalAktif }} Peminjaman Aktif</span>
                </div>
                <a href="#form-pinjam" class="inline-flex items-center gap-2 bg-white text-primary font-bold text-sm px-4 py-2 rounded-xl hover:bg-blue-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Pinjam Sekarang
                </a>
            </div>
        </div>
    </div>


    <!-- ─────────────────────────── -->
    <!--  MOBILE TAB SWITCHER        -->
    <!-- ─────────────────────────── -->
    <div class="md:hidden sticky top-16 z-40 bg-white dark:bg-zinc-900 border-b border-zinc-100 dark:border-zinc-800 px-4 py-2">
        <div class="flex gap-1 bg-zinc-100 dark:bg-zinc-800 p-1 rounded-xl">
            <button onclick="switchTab('tab-aktif')" id="btn-tab-aktif" class="tab-btn active">
                📋 Aktif
            </button>
            <button onclick="switchTab('tab-katalog')" id="btn-tab-katalog" class="tab-btn">
                🔧 Katalog
            </button>
            <button onclick="switchTab('tab-form')" id="btn-tab-form" class="tab-btn">
                📝 Ajukan
            </button>
        </div>
    </div>


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">


        <!-- ───────────────────── -->
        <!--  SECTION 1: AKTIF     -->
        <!-- ───────────────────── -->
        <section id="pinjaman-aktif" class="tab-section tab-active" data-tab="tab-aktif">

            <div class="flex items-center justify-between mb-5 reveal">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Peminjaman Aktif Saya</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Pantau status semua peminjaman Anda</p>
                </div>
                @if($pinjamans->count() > 0)
                <span class="badge bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                    {{ $pinjamans->count() }} aktif
                </span>
                @endif
            </div>

            @if($pinjamans->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pinjamans as $i => $p)
                @php
                    $statusMap = [
                        'menunggu'   => ['label'=>'Menunggu', 'bg'=>'bg-amber-100 dark:bg-amber-900/30', 'txt'=>'text-amber-700 dark:text-amber-300', 'dot'=>'bg-amber-400'],
                        'disetujui'  => ['label'=>'Disetujui', 'bg'=>'bg-emerald-100 dark:bg-emerald-900/30', 'txt'=>'text-emerald-700 dark:text-emerald-300', 'dot'=>'bg-emerald-400'],
                        'dipinjam'   => ['label'=>'Sedang Dipinjam', 'bg'=>'bg-blue-100 dark:bg-blue-900/30', 'txt'=>'text-blue-700 dark:text-blue-300', 'dot'=>'bg-blue-400'],
                        'dikembalikan'=>['label'=>'Dikembalikan', 'bg'=>'bg-zinc-100 dark:bg-zinc-700', 'txt'=>'text-zinc-600 dark:text-zinc-300', 'dot'=>'bg-zinc-400'],
                        'ditolak'    => ['label'=>'Ditolak', 'bg'=>'bg-red-100 dark:bg-red-900/30', 'txt'=>'text-red-700 dark:text-red-300', 'dot'=>'bg-red-400'],
                    ];
                    $s = $statusMap[$p->status] ?? ['label'=>ucfirst($p->status), 'bg'=>'bg-zinc-100', 'txt'=>'text-zinc-600', 'dot'=>'bg-zinc-400'];
                @endphp
                <div class="card-hover bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-100 dark:border-zinc-800 overflow-hidden reveal reveal-d{{ ($i % 4) + 1 }}">

                    <!-- Card top accent -->
                    <div class="h-1 w-full {{ str_contains($s['bg'], 'blue') ? 'bg-blue-400' : (str_contains($s['bg'], 'emerald') ? 'bg-emerald-400' : (str_contains($s['bg'], 'amber') ? 'bg-amber-400' : (str_contains($s['bg'], 'red') ? 'bg-red-400' : 'bg-zinc-300'))) }}"></div>

                    <div class="p-5">
                        <!-- Header row -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-xs font-medium text-zinc-400 dark:text-zinc-500 mb-1">Pinjaman</p>
                                <p class="text-base font-bold text-zinc-900 dark:text-white">#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <span class="badge {{ $s['bg'] }} {{ $s['txt'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                                {{ $s['label'] }}
                            </span>
                        </div>

                        <!-- Date info -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <div class="w-7 h-7 rounded-lg bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <div class="w-7 h-7 rounded-lg bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span>Kembali {{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Items summary -->
                        <div class="flex items-center gap-2 mb-4 p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700">
                            <svg class="w-4 h-4 text-zinc-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ $p->details->count() }} jenis alat dipinjam</span>
                        </div>

                                <!-- Action -->
                                <button type="button" data-pinjaman='@json($p->toArray())' onclick="showPinjamanDetail(JSON.parse(this.dataset.pinjaman))"
                                    class="w-full inline-flex items-center justify-center gap-2 h-10 rounded-xl bg-primary-light dark:bg-blue-900/20 text-primary dark:text-blue-300 text-sm font-semibold hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Detail
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            @else
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center p-12 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-center reveal">
                <div class="w-16 h-16 rounded-2xl bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-base font-bold text-zinc-700 dark:text-zinc-300 mb-1">Belum ada peminjaman aktif</p>
                <p class="text-sm text-zinc-400 dark:text-zinc-500 mb-5">Pilih alat dari katalog dan ajukan peminjaman pertama Anda</p>
                <a href="#katalog" class="inline-flex items-center gap-2 px-5 h-11 bg-primary hover:bg-primary-dark text-white font-semibold text-sm rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Lihat Katalog Alat
                </a>
            </div>
            @endif
        </section>


        <!-- ───────────────────── -->
        <!--  SECTION 2: KATALOG   -->
        <!-- ───────────────────── -->
        <section id="katalog" class="tab-section" data-tab="tab-katalog">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5 reveal">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Katalog Alat Tersedia</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Pilih alat untuk ditambahkan ke keranjang pinjam</p>
                </div>
                <!-- Cart badge shortcut -->
                <button onclick="scrollToForm()" id="cart-header-btn"
                        class="hidden items-center gap-2 px-4 h-10 bg-primary text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-primary-dark transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span id="cart-count-header">0 alat dipilih</span>
                </button>
            </div>

            <!-- Search & Filter Bar -->
            <div class="mb-5 reveal">
                <form method="GET" id="search-form" class="flex flex-col sm:flex-row gap-3">
                    <!-- Search input -->
                    <div class="relative flex-1">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" name="q" value="{{ $q ?? '' }}"
                               id="search-input"
                               placeholder="Cari nama alat..."
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm font-medium placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" />
                    </div>

                    <!-- Category select -->
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <select name="kategori"
                                class="pl-10 pr-8 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all appearance-none cursor-pointer w-full sm:w-auto">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ ($kategori == $k->id) ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 h-12 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl transition-all shadow-sm flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                </form>
            </div>

            <!-- Alat Grid -->
            @if($alats->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="alat-grid">
                @foreach($alats as $i => $alat)
                    @php
                    $available = $alat->jumlah_total - ($alat->jumlah_rusak ?? 0) - ($alat->jumlah_dipinjam ?? 0);
                    $isAvailable = $available > 0;
                    $imageUrl = $alat->path_foto ? asset('storage/' . $alat->path_foto) : null;
                    $pct = $alat->jumlah_total > 0 ? round(($available / $alat->jumlah_total) * 100) : 0;
                @endphp
                <div id="alat-card-{{ $alat->id }}"
                     class="alat-card card-hover bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-100 dark:border-zinc-800 overflow-hidden reveal reveal-d{{ ($i % 4) + 1 }}">

                    <!-- Image / placeholder -->
                    <div class="relative h-40 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-700 shadow-sm flex items-center justify-center">
                                    <svg class="w-6 h-6 text-zinc-300 dark:text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <span class="text-xs text-zinc-400">Tidak ada foto</span>
                            </div>
                        @endif

                        <!-- Status overlay badge -->
                        <div class="absolute top-3 right-3">
                            <span class="badge {{ $isAvailable ? 'bg-emerald-500 text-white shadow-lg' : 'bg-red-500 text-white shadow-lg' }}">
                                {{ $isAvailable ? '✓ Tersedia' : '✕ Habis' }}
                            </span>
                        </div>

                        <!-- Category pill -->
                        @if($alat->kategori)
                        <div class="absolute bottom-3 left-3">
                            <span class="badge bg-black/50 text-white backdrop-blur-sm text-xs">{{ $alat->kategori->nama_kategori }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1 line-clamp-1">{{ $alat->nama_alat }}</h3>

                        @if($alat->deskripsi)
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3 line-clamp-2 leading-relaxed">{{ $alat->deskripsi }}</p>
                        @endif

                        <!-- Availability bar -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">Ketersediaan</span>
                                <span class="text-xs font-bold {{ $isAvailable ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">{{ $available }}/{{ $alat->jumlah_total }}</span>
                            </div>
                            <div class="h-1.5 w-full bg-zinc-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 {{ $pct > 50 ? 'bg-emerald-400' : ($pct > 20 ? 'bg-amber-400' : 'bg-red-400') }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex gap-2">
                            <button type="button"
                                    data-nama="{{ e($alat->nama_alat) }}"
                                    data-deskripsi="{{ e($alat->deskripsi ?? '') }}"
                                    data-image="{{ $imageUrl ?? '' }}"
                                    data-kategori="{{ e($alat->kategori->nama_kategori ?? '') }}"
                                    data-available="{{ $available }}"
                                    data-total="{{ $alat->jumlah_total }}"
                                    onclick="showDetail({
                                        nama: this.dataset.nama,
                                        deskripsi: this.dataset.deskripsi,
                                        image: this.dataset.image,
                                        kategori: this.dataset.kategori,
                                        available: parseInt(this.dataset.available || '0'),
                                        total: parseInt(this.dataset.total || '0')
                                    })"
                                    class="flex-none w-10 h-10 flex items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-500 hover:text-primary hover:border-primary hover:bg-primary-light dark:hover:bg-blue-900/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>

                            <button type="button"
                                    id="select-btn-{{ $alat->id }}"
                                    onclick="toggleAlat({{ $alat->id }}, '{{ e($alat->nama_alat) }}', {{ $available }})"
                                    {{ !$isAvailable ? 'disabled' : '' }}
                                    class="flex-1 h-10 rounded-xl text-sm font-semibold transition-all
                                    {{ $isAvailable
                                        ? 'bg-primary hover:bg-primary-dark text-white shadow-sm hover:shadow-md dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white'
                                        : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-400 cursor-not-allowed' }}">
                                <span id="select-label-{{ $alat->id }}">{{ $isAvailable ? 'Pilih Alat' : 'Tidak Tersedia' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @else
            <div class="flex flex-col items-center justify-center p-12 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-center reveal">
                <div class="w-16 h-16 rounded-2xl bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <p class="text-base font-bold text-zinc-700 dark:text-zinc-300">Tidak ada alat yang ditemukan</p>
                <p class="text-sm text-zinc-400 mt-1">Coba ubah kata kunci atau kategori pencarian</p>
            </div>
            @endif
        </section>


        <!-- ───────────────────── -->
        <!--  SECTION 3: FORM      -->
        <!-- ───────────────────── -->
        <section id="form-pinjam" class="tab-section pb-32 md:pb-8" data-tab="tab-form">

            <div class="flex items-center justify-between mb-5 reveal">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Ajukan Peminjaman Baru</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Lengkapi data di bawah untuk mengajukan permohonan</p>
                </div>
            </div>

            <!-- Stepper indicator -->
            <div class="hidden sm:flex items-center mb-8 reveal">
                @foreach([['01','Pilih Alat'],['02','Isi Detail'],['03','Kirim']] as $si => $step)
                <div class="flex items-center {{ $si > 0 ? 'flex-1' : '' }}">
                    @if($si > 0)
                    <div class="step-connector {{ $si <= 1 ? '' : '' }}" id="connector-{{ $si }}"></div>
                    @endif
                    <div class="step-nav-item idle flex flex-col items-center gap-1.5" id="step-{{ $si + 1 }}" onclick="scrollToStep({{ $si + 1 }})">
                        <div class="step-circle w-9 h-9 rounded-full border-2 flex items-center justify-center text-sm font-bold transition-all">{{ $step[0] }}</div>
                        <span class="text-xs font-semibold text-zinc-500 whitespace-nowrap">{{ $step[1] }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden reveal">
                <form id="pinjaman-form" method="POST" action="{{ route('pinjaman.store') }}">
                    @csrf

                    <!-- ── Part A: Alat Dipilih ── -->
                    <div class="p-6 border-b border-zinc-100 dark:border-zinc-800" id="part-alat">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-primary-light dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary dark:text-blue-300 text-sm font-bold">1</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Alat yang Dipilih</h3>
                                <p class="text-xs text-zinc-400">Pilih dari katalog di atas, atau klik + untuk tambah</p>
                            </div>
                        </div>

                        <!-- Cart items -->
                        <div id="detail-items" class="space-y-2.5"></div>

                        <!-- Empty cart state -->
                        <div id="no-items" class="flex flex-col items-center py-8 gap-3">
                            <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center">
                                <svg class="w-6 h-6 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-zinc-500 dark:text-zinc-400">Keranjang masih kosong</p>
                                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Kembali ke katalog dan pilih alat yang ingin dipinjam</p>
                            </div>
                            <a href="#katalog" onclick="if(window.innerWidth<768)switchTab('tab-katalog')"
                               class="inline-flex items-center gap-2 px-4 h-9 bg-primary-light dark:bg-blue-900/20 text-primary dark:text-blue-300 text-sm font-semibold rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                                Ke Katalog
                            </a>
                        </div>
                    </div>

                    <!-- ── Part B: Detail Pinjam ── -->
                    <div class="p-6 border-b border-zinc-100 dark:border-zinc-800" id="part-detail">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center flex-shrink-0">
                                <span class="text-amber-600 dark:text-amber-400 text-sm font-bold">2</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Detail Peminjaman</h3>
                                <p class="text-xs text-zinc-400">Isi informasi jadwal dan keperluan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Peminjam -->
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-200 mb-1.5">
                                    Peminjam
                                </label>
                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ auth()->user()->name }}</span>
                                </div>
                                <input type="hidden" name="id_peminjam" value="{{ auth()->id() }}" />
                            </div>

                            <!-- Spacer on mobile -->
                            <div class="hidden sm:block"></div>

                            <!-- Tanggal Pinjam -->
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-200 mb-1.5">
                                    Tanggal Pinjam <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <input type="date" name="tanggal_pinjam" id="tgl-pinjam"
                                           value="{{ date('Y-m-d') }}" required
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"/>
                                </div>
                            </div>

                            <!-- Tanggal Kembali -->
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-200 mb-1.5">
                                    Rencana Tanggal Kembali <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <input type="date" name="tanggal_kembali_rencana" id="tgl-kembali"
                                           required
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"/>
                                </div>
                                <p id="durasi-hint" class="text-xs text-zinc-400 mt-1 h-4"></p>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-200 mb-1.5">
                                Keperluan / Keterangan
                            </label>
                            <div class="relative">
                                <textarea name="pesan" id="pesan-input" rows="3"
                                          placeholder="Contoh: Untuk panen padi di lahan belakang RT 04..."
                                          maxlength="300"
                                          oninput="updateCharCount(this)"
                                          class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm font-medium placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all resize-none"></textarea>
                            </div>
                            <div class="flex justify-end mt-1">
                                <span id="char-count" class="text-xs text-zinc-400">0/300</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── Part C: Submit ── -->
                    <div class="p-6 bg-zinc-50 dark:bg-zinc-800/50" id="part-submit">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 dark:text-emerald-400 text-sm font-bold">3</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Konfirmasi & Kirim</h3>
                                <p class="text-xs text-zinc-400">Periksa kembali sebelum mengirim permohonan</p>
                            </div>
                        </div>

                        <!-- Summary box -->
                        <div id="summary-box" class="hidden mb-5 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/10">
                            <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3">Ringkasan Permohonan</h4>
                            <div id="summary-content" class="space-y-1.5 text-sm text-blue-700 dark:text-blue-300"></div>
                        </div>

                        <!-- Validation notice -->
                        <div id="validation-notice" class="hidden mb-4 p-3.5 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 items-start gap-2.5">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p id="validation-text" class="text-sm font-medium text-red-600 dark:text-red-400"></p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit" id="submit-btn" disabled
                                    class="flex-1 inline-flex items-center justify-center gap-2 h-12 bg-primary disabled:bg-zinc-200 dark:disabled:bg-zinc-700 disabled:text-zinc-400 dark:disabled:text-zinc-500 hover:bg-primary-dark text-white font-bold text-base rounded-xl transition-all shadow-sm hover:shadow-md disabled:cursor-not-allowed disabled:shadow-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ajukan Peminjaman
                            </button>
                            <button type="button" onclick="resetForm()"
                                    class="sm:flex-none sm:w-auto inline-flex items-center justify-center gap-2 h-12 px-6 border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 font-semibold text-sm rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    </main>


    <!-- ─────────────────────────── -->
    <!--  FLOATING CART (mobile)     -->
    <!-- ─────────────────────────── -->
    <div id="floating-cart" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden-cart md:hidden">
        <button onclick="scrollToForm(); if(window.innerWidth<768)switchTab('tab-form')"
                class="inline-flex items-center gap-3 px-5 h-14 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-2xl shadow-2xl transition-all">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span id="floating-cart-count" class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">0</span>
            </div>
            <span id="floating-cart-label">0 alat dipilih</span>
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>


    <!-- ─────────────────────────── -->
    <!--  DETAIL MODAL               -->
    <!-- ─────────────────────────── -->
    <div id="alat-modal" class="fixed inset-0 z-[60] hidden">
        <!-- Backdrop -->
        <div id="modal-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetail()"></div>

        <!-- Panel -->
        <div class="absolute inset-0 flex items-end sm:items-center justify-center p-0 sm:p-4" onclick="closeDetail()">
            <div class="relative w-full sm:max-w-lg bg-white dark:bg-zinc-900 rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden animate-scaleIn" onclick="event.stopPropagation()">

                <!-- Handle (mobile) -->
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                </div>

                <!-- Image -->
                <div id="modal-img-wrap" class="w-full h-48 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden"></div>

                <!-- Content -->
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <h3 id="modal-nama" class="text-lg font-bold text-zinc-900 dark:text-white"></h3>
                            <p id="modal-kategori" class="text-sm text-zinc-400 mt-0.5"></p>
                        </div>
                        <button onclick="closeDetail()" class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <p id="modal-deskripsi" class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed"></p>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700">
                            <p class="text-xs text-zinc-400 mb-0.5">Tersedia</p>
                            <p id="modal-available" class="text-lg font-bold text-emerald-600 dark:text-emerald-400"></p>
                        </div>
                        <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700">
                            <p class="text-xs text-zinc-400 mb-0.5">Total Unit</p>
                            <p id="modal-total" class="text-lg font-bold text-zinc-700 dark:text-zinc-200"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- User profile summary -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm flex items-center gap-4">
                        @php
                            $profileUrl = auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : null;
                        @endphp
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            @if($profileUrl)
                                <img src="{{ $profileUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg font-semibold text-zinc-700 dark:text-zinc-200">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-lg">{{ auth()->user()->name }}</h3>
                                    <p class="text-sm text-zinc-500">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->phone)
                                        <p class="text-sm text-zinc-500">Tel: {{ auth()->user()->phone }}</p>
                                    @endif
                                </div>
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <div class="text-xs text-zinc-400">Total Pinjaman</div>
                                        <div class="font-semibold text-lg">{{ $stats['total'] ?? 0 }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-zinc-400">Aktif</div>
                                        <div class="font-semibold text-lg">{{ $stats['active'] ?? 0 }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-zinc-400">Sedang Dipinjam</div>
                                        <div class="font-semibold text-lg">{{ $stats['dipinjam'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


    <!-- ─────────────────────────── -->
    <!--  TOAST CONTAINER            -->
    <!-- ─────────────────────────── -->
    <div id="toast-container" class="fixed top-20 right-4 z-[70] flex flex-col gap-2 pointer-events-none"></div>


    @livewireScripts
    @fluxScripts

    <script>
    // ── Dark mode ──
    function toggleDark() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    }
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }

    // ── Greeting ──
    function setGreeting() {
        const h = new Date().getHours();
        const el = document.getElementById('greeting-time');
        if (!el) return;
        if (h < 11) el.textContent = 'Selamat pagi ☀️';
        else if (h < 15) el.textContent = 'Selamat siang 🌤️';
        else if (h < 18) el.textContent = 'Selamat sore 🌇';
        else el.textContent = 'Selamat malam 🌙';
    }
    setGreeting();

    // ── Mobile tab switcher ──
    function switchTab(tabId) {
        document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('tab-active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelector(`[data-tab="${tabId}"]`).classList.add('tab-active');
        document.getElementById('btn-' + tabId).classList.add('active');
        window.scrollTo({ top: 60, behavior: 'smooth' });
    }

    // ── Scroll reveals ──
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // ── Date range validation & durasi hint ──
    const tglPinjam  = document.getElementById('tgl-pinjam');
    const tglKembali = document.getElementById('tgl-kembali');
    const durasiHint = document.getElementById('durasi-hint');

    tglPinjam.addEventListener('change', () => {
        tglKembali.min = tglPinjam.value;
        if (tglKembali.value && tglKembali.value < tglPinjam.value) {
            tglKembali.value = '';
        }
        updateDurasi();
        updateSummary();
    });
    tglKembali.addEventListener('change', () => {
        updateDurasi();
        updateSummary();
    });

    function updateDurasi() {
        if (!tglPinjam.value || !tglKembali.value) { durasiHint.textContent = ''; return; }
        const diff = Math.round((new Date(tglKembali.value) - new Date(tglPinjam.value)) / 86400000);
        if (diff < 0) { durasiHint.textContent = '⚠️ Tanggal kembali harus setelah tanggal pinjam'; durasiHint.className = 'text-xs text-red-500 mt-1 h-4'; return; }
        durasiHint.textContent = `✓ Durasi: ${diff} hari`;
        durasiHint.className = 'text-xs text-emerald-500 mt-1 h-4';
    }

    // ── Char count ──
    function updateCharCount(el) {
        document.getElementById('char-count').textContent = el.value.length + '/300';
    }

    // ── Cart / Selected items ──
    let selectedItems = [];

    function toggleAlat(id, nama, available) {
        const exists = selectedItems.findIndex(i => i.alat_id == id);
        if (exists >= 0) {
            selectedItems.splice(exists, 1);
            showToast(`"${nama}" dihapus dari keranjang`, 'info');
        } else {
            selectedItems.push({ alat_id: id, nama, available, jumlah: 1 });
            showToast(`"${nama}" ditambahkan ke keranjang`, 'success');
        }
        renderItems();
        updateSelectButtons();
        updateFloatingCart();
        updateSummary();
        updateStepIndicator();
    }

    function updateSelectButtons() {
        selectedItems.forEach(item => {
            const btn = document.getElementById(`select-btn-${item.alat_id}`);
            const lbl = document.getElementById(`select-label-${item.alat_id}`);
            const card = document.getElementById(`alat-card-${item.alat_id}`);
            if (btn) {
                btn.classList.remove('bg-primary');
                btn.classList.remove('hover:bg-primary-dark');
                btn.classList.add('bg-emerald-500');
                btn.classList.add('hover:bg-emerald-600');
                // ensure dark variants
                btn.classList.add('dark:bg-emerald-500');
                btn.classList.remove('dark:bg-blue-600');
            }
            if (lbl) lbl.textContent = '✓ Terpilih';
            if (card) card.classList.add('selected');
        });

        // reset unselected
        document.querySelectorAll('[id^="select-btn-"]').forEach(btn => {
            const id = btn.id.replace('select-btn-', '');
            if (!selectedItems.find(i => i.alat_id == id) && !btn.disabled) {
                btn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600', 'dark:bg-emerald-500');
                btn.classList.add('bg-primary', 'hover:bg-primary-dark');
                // dark fallback for primary
                btn.classList.add('dark:bg-blue-600');
                const lbl = document.getElementById(`select-label-${id}`);
                if (lbl) lbl.textContent = 'Pilih Alat';
                const card = document.getElementById(`alat-card-${id}`);
                if (card) card.classList.remove('selected');
            }
        });
    }

    function removeItem(index) {
        const removed = selectedItems.splice(index, 1)[0];
        showToast(`"${removed.nama}" dihapus`, 'info');
        renderItems();
        updateSelectButtons();
        updateFloatingCart();
        updateSummary();
        updateStepIndicator();
    }

    function updateJumlah(index, val) {
        val = Math.max(1, Math.min(parseInt(val) || 1, selectedItems[index].available));
        selectedItems[index].jumlah = val;
        renderItems();
        updateSummary();
    }

    function renderItems() {
        const container = document.getElementById('detail-items');
        const noItems   = document.getElementById('no-items');
        const submitBtn = document.getElementById('submit-btn');
        const cartHeaderBtn = document.getElementById('cart-header-btn');

        // clear old hidden inputs
        document.querySelectorAll('[name^="details["]').forEach(el => el.remove());

        if (selectedItems.length === 0) {
            container.innerHTML = '';
            noItems.style.display = 'flex';
            submitBtn.disabled = true;
            if (cartHeaderBtn) cartHeaderBtn.classList.add('hidden');
            return;
        }

        noItems.style.display = 'none';
        submitBtn.disabled = false;
        if (cartHeaderBtn) {
            cartHeaderBtn.classList.remove('hidden');
            cartHeaderBtn.classList.add('inline-flex');
            document.getElementById('cart-count-header').textContent = `${selectedItems.length} alat dipilih`;
        }

        container.innerHTML = selectedItems.map((item, idx) => `
            <div class="flex items-center gap-3 p-3.5 rounded-xl border border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 group">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">${item.nama}</p>
                    <p class="text-xs text-zinc-400">Maks. ${item.available} unit</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <button type="button" onclick="updateJumlah(${idx}, ${item.jumlah - 1})"
                            class="qty-btn bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 text-zinc-600 dark:text-zinc-300 hover:border-primary hover:text-primary">
                        −
                    </button>
                    <input type="number" min="1" max="${item.available}" value="${item.jumlah}"
                           onchange="updateJumlah(${idx}, this.value)"
                           class="w-12 h-8 text-center text-sm font-bold rounded-lg border border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
                    <button type="button" onclick="updateJumlah(${idx}, ${item.jumlah + 1})"
                            class="qty-btn bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 text-zinc-600 dark:text-zinc-300 hover:border-primary hover:text-primary">
                        +
                    </button>
                </div>
                <button type="button" onclick="removeItem(${idx})"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        `).join('');

        // inject hidden inputs
        const form = document.getElementById('pinjaman-form');
        selectedItems.forEach((item, idx) => {
            ['alat_id','jumlah'].forEach(field => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = `details[${idx}][${field}]`;
                inp.value = field === 'alat_id' ? item.alat_id : item.jumlah;
                form.appendChild(inp);
            });
        });
    }

    function updateFloatingCart() {
        const fc = document.getElementById('floating-cart');
        const fcount = document.getElementById('floating-cart-count');
        const flabel = document.getElementById('floating-cart-label');
        fcount.textContent = selectedItems.length;
        flabel.textContent = `${selectedItems.length} alat dipilih`;
        if (selectedItems.length > 0) {
            fc.classList.remove('hidden-cart');
        } else {
            fc.classList.add('hidden-cart');
        }
    }

    function updateSummary() {
        const box = document.getElementById('summary-box');
        const content = document.getElementById('summary-content');
        if (!box || selectedItems.length === 0) { if (box) box.classList.add('hidden'); return; }
        box.classList.remove('hidden');
        const lines = selectedItems.map(i => `<div class="flex justify-between"><span>${i.nama}</span><span class="font-bold">${i.jumlah} unit</span></div>`);
        if (tglPinjam.value) lines.push(`<div class="flex justify-between border-t border-blue-100 dark:border-blue-800 pt-2 mt-1"><span>Pinjam</span><span class="font-bold">${tglPinjam.value}</span></div>`);
        if (tglKembali.value) lines.push(`<div class="flex justify-between"><span>Kembali</span><span class="font-bold">${tglKembali.value}</span></div>`);
        content.innerHTML = lines.join('');
    }

    function updateStepIndicator() {
        const s1 = document.getElementById('step-1');
        const s2 = document.getElementById('step-2');
        const c1 = document.getElementById('connector-1');
        if (!s1) return;
        if (selectedItems.length > 0) {
            s1.className = 'step-nav-item done flex flex-col items-center gap-1.5';
            if (c1) c1.classList.add('done');
        } else {
            s1.className = 'step-nav-item idle flex flex-col items-center gap-1.5';
            if (c1) c1.classList.remove('done');
        }
    }

    function resetForm() {
        selectedItems = [];
        renderItems();
        updateSelectButtons();
        updateFloatingCart();
        updateSummary();
        updateStepIndicator();
        document.getElementById('pinjaman-form').reset();
        document.getElementById('tgl-pinjam').value = new Date().toISOString().split('T')[0];
        document.getElementById('tgl-kembali').value = '';
        durasiHint.textContent = '';
        document.getElementById('char-count').textContent = '0/300';
        showToast('Form berhasil direset', 'info');
    }

    function scrollToForm() {
        document.getElementById('form-pinjam').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    function scrollToStep(n) {}

    // init
    renderItems();

    // ── Toast ──
    function showToast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        const icons = {
            success: `<div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>`,
            info:    `<div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>`,
            error:   `<div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></div>`,
        };
        const toast = document.createElement('div');
        toast.className = 'toast pointer-events-auto flex items-center gap-3 px-4 py-3 bg-white dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700 rounded-xl shadow-lg max-w-xs';
        toast.innerHTML = (icons[type] || icons.info) + `<p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex-1">${msg}</p>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 350);
        }, 2800);
    }

    // ── Alat detail modal ──
    function showDetail(ds) {
        const modal = document.getElementById('alat-modal');
        document.getElementById('modal-nama').textContent      = ds.nama || '';
        document.getElementById('modal-deskripsi').textContent = ds.deskripsi || 'Tidak ada deskripsi.';
        document.getElementById('modal-kategori').textContent  = ds.kategori ? `Kategori: ${ds.kategori}` : '';
        document.getElementById('modal-available').textContent = ds.available || '0';
        document.getElementById('modal-total').textContent     = ds.total || '0';

        const wrap = document.getElementById('modal-img-wrap');
        wrap.innerHTML = ds.image
            ? `<img src="${ds.image}" class="w-full h-full object-cover" />`
            : `<div class="flex items-center justify-center h-full gap-2 flex-col">
                <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="text-xs text-zinc-400">Tidak ada foto</span>
               </div>`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.addEventListener('keydown', escClose);
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        const modal = document.getElementById('alat-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.removeEventListener('keydown', escClose);
        document.body.style.overflow = '';
    }

    function escClose(e) { if (e.key === 'Escape') closeDetail(); }
    // AJAX submit handler: post and refresh with Swal
    (function(){
        const form = document.getElementById('pinjaman-form');
        if (!form) return;
        form.addEventListener('submit', async function(ev){
            ev.preventDefault();
            renderItems();
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            const url = form.action;
            const fd = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            try {
                const res = await fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token }, credentials: 'same-origin' });
                let data = null;
                try { data = await res.json(); } catch(e) { data = null; }
                if (res.ok && data && data.success) {
                    if (window.Swal) {
                        Swal.fire({ icon:'success', title: 'Pinjaman berhasil diajukan', timer:1400, showConfirmButton:false }).then(()=> location.reload());
                    } else {
                        showToast('Pinjaman berhasil diajukan', 'success');
                        setTimeout(()=> location.reload(), 1200);
                    }
                    return;
                }
                if (res.status === 422 && data && data.errors) {
                    const msgs = Object.values(data.errors).flat().join('\n');
                    if (window.Swal) Swal.fire({ icon:'error', title:'Validasi gagal', text: msgs }); else alert(msgs);
                    submitBtn.disabled = false;
                    return;
                }
                // fallback
                if (window.Swal) Swal.fire({ icon: res.ok ? 'success' : 'error', title: data?.message || (res.ok? 'Selesai' : 'Gagal') }).then(()=> location.reload());
                else { alert(data?.message || (res.ok? 'Selesai' : 'Gagal')); location.reload(); }
            } catch(err) {
                console.error(err);
                if (window.Swal) Swal.fire({ icon:'error', title:'Terjadi kesalahan', text: err.message || '' }); else alert('Terjadi kesalahan');
                submitBtn.disabled = false;
            }
        });
    })();

    // ── Session success flash ──
    @if(session('success'))
    (function(){
        const msg = {!! json_encode(session('success')) !!};
        if (window.Swal) {
            Swal.fire({ icon:'success', title: msg || 'Berhasil', timer:1800, showConfirmButton:false }).then(() => location.reload());
        } else {
            showToast(msg || 'Permohonan berhasil dikirim!', 'success');
            setTimeout(() => location.reload(), 2000);
        }
    })();
    @endif
    </script>
    <!-- Modal: Pinjaman Detail -->
    <div id="pinjaman-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div id="pinjaman-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="relative max-w-3xl w-full mx-4">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden shadow-xl">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-start justify-between">
                    <div>
                        <h3 id="pinjaman-modal-title" class="text-lg font-semibold text-zinc-900 dark:text-white">Detail Peminjaman</h3>
                        <p id="pinjaman-modal-sub" class="text-sm text-zinc-500 dark:text-zinc-400 mt-1"></p>
                    </div>
                    <div>
                        <button type="button" onclick="closePinjamanDetail()" class="px-3 py-1 rounded bg-zinc-100 dark:bg-zinc-800">Tutup</button>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    <div id="pinjaman-modal-body">
                        <!-- details injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPinjamanDetail(data) {
            // data is a JS object representing the pinjaman
            const modal = document.getElementById('pinjaman-modal');
            const title = document.getElementById('pinjaman-modal-title');
            const sub = document.getElementById('pinjaman-modal-sub');
            const body = document.getElementById('pinjaman-modal-body');

            title.textContent = `Peminjaman #${data.id}`;
            sub.textContent = `Tanggal: ${data.tanggal_pinjam} — Kembali: ${data.tanggal_kembali_rencana} | Status: ${data.status}`;

            const details = data.details || [];
            if (details.length === 0) {
                body.innerHTML = '<p class="text-sm text-zinc-500">Tidak ada rincian.</p>';
            } else {
                body.innerHTML = details.map(d => {
                    const nama = (d.alat && d.alat.nama_alat) ? d.alat.nama_alat : (d.nama_alat ?? '—');
                    const jumlah = d.jumlah ?? d.qty ?? 0;
                    return `<div class="flex items-center justify-between p-2 rounded-lg border border-zinc-100 dark:border-zinc-800">
                        <div>
                            <div class="font-semibold text-sm">${nama}</div>
                            <div class="text-xs text-zinc-500">ID detail: ${d.id}</div>
                        </div>
                        <div class="text-sm font-bold">${jumlah} unit</div>
                    </div>`;
                }).join('');
            }

            modal.classList.remove('hidden');
            document.getElementById('pinjaman-modal-backdrop').onclick = closePinjamanDetail;
            document.addEventListener('keydown', pinjamanEsc);
        }

        function closePinjamanDetail() {
            const modal = document.getElementById('pinjaman-modal');
            modal.classList.add('hidden');
            document.removeEventListener('keydown', pinjamanEsc);
        }

        function pinjamanEsc(e) { if (e.key === 'Escape') closePinjamanDetail(); }
    </script>
</body>
</html>