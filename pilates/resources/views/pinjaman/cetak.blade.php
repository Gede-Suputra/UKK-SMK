<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cetak Peminjaman #{{ $pinjaman->id }}</title>
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Georgia", "Times New Roman", serif;
            font-size: 11pt;
            color: #1a1a1a;
            background: #f0ede8;
            padding: 24px;
        }

        /* ── Paper Container ── */
        .page {
            background: #fff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 24px;
            padding: 18mm 20mm 20mm;
            box-shadow: 0 4px 32px rgba(0,0,0,.15);
            position: relative;
        }

        /* ── Kepala Surat (Letterhead) ── */
        .letterhead {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #1a3a5c;
            margin-bottom: 4px;
        }

        .letterhead-logo {
            width: 64px;
            height: 64px;
            flex-shrink: 0;
            background: #1a3a5c;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: -1px;
            font-family: "Arial Black", sans-serif;
        }

        .letterhead-text {
            flex: 1;
        }

        .letterhead-text .org-name {
            font-family: "Arial Black", "Arial Bold", sans-serif;
            font-size: 15pt;
            font-weight: 900;
            color: #1a3a5c;
            letter-spacing: .5px;
            line-height: 1.2;
        }

        .letterhead-text .org-sub {
            font-size: 9.5pt;
            color: #555;
            margin-top: 2px;
            letter-spacing: .2px;
        }

        .letterhead-text .org-address {
            font-size: 8.5pt;
            color: #777;
            margin-top: 4px;
            line-height: 1.5;
        }

        .letterhead-divider {
            height: 2px;
            background: linear-gradient(90deg, #1a3a5c 0%, #4a8fc1 60%, transparent 100%);
            margin-bottom: 16px;
        }

        /* ── Judul Dokumen ── */
        .doc-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .doc-title {
            font-family: "Arial Black", sans-serif;
            font-size: 13pt;
            color: #1a3a5c;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .doc-number {
            background: #1a3a5c;
            color: #fff;
            padding: 4px 14px;
            border-radius: 3px;
            font-size: 10pt;
            font-family: "Courier New", monospace;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* ── Info Grid (Meta) ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 1px solid #c8d6e5;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 18px;
            font-size: 10pt;
        }

        .info-cell {
            padding: 7px 12px;
            border-bottom: 1px solid #c8d6e5;
            border-right: 1px solid #c8d6e5;
            display: flex;
            gap: 6px;
        }

        .info-cell:nth-child(even) { border-right: none; }
        .info-cell:nth-last-child(1),
        .info-cell:nth-last-child(2) { border-bottom: none; }

        .info-cell .lbl {
            font-weight: bold;
            color: #1a3a5c;
            white-space: nowrap;
            min-width: 120px;
        }

        .info-cell .val { color: #222; }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .badge-active    { background: #d4edda; color: #1a6b35; }
        .badge-late      { background: #f8d7da; color: #7a1a22; }
        .badge-returned  { background: #d1ecf1; color: #0c5460; }
        .badge-default   { background: #e9ecef; color: #495057; }

        /* ── Tabel Detail ── */
        .section-title {
            font-family: "Arial Black", sans-serif;
            font-size: 9.5pt;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #1a3a5c;
            border-left: 4px solid #1a3a5c;
            padding-left: 8px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        thead tr {
            background: #1a3a5c;
            color: #fff;
        }

        thead th {
            padding: 8px 10px;
            text-align: left;
            font-family: "Arial", sans-serif;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: .5px;
            border: 1px solid #1a3a5c;
        }

        thead th.right { text-align: right; }
        thead th.center { text-align: center; }

        tbody tr { background: #fff; }
        tbody tr:nth-child(even) { background: #f5f8fb; }

        tbody td {
            padding: 7px 10px;
            border: 1px solid #d0dce8;
            vertical-align: middle;
            color: #222;
        }

        tbody td.right  { text-align: right; font-family: "Courier New", monospace; }
        tbody td.center { text-align: center; }

        /* zebra stripe softer on print */
        tfoot tr { background: #eaf0f7; }
        tfoot td {
            padding: 8px 10px;
            border: 1px solid #c8d6e5;
            font-weight: bold;
            color: #1a3a5c;
        }

        /* ── Denda Block ── */
        .denda-block {
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
        }

        .denda-box {
            border: 2px solid #1a3a5c;
            border-radius: 4px;
            overflow: hidden;
            min-width: 260px;
        }

        .denda-box .denda-header {
            background: #1a3a5c;
            color: #fff;
            padding: 6px 14px;
            font-family: "Arial Black", sans-serif;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .denda-box .denda-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 14px;
            font-size: 10.5pt;
            border-top: 1px solid #c8d6e5;
        }

        .denda-box .denda-row.total {
            background: #f5f8fb;
            font-weight: bold;
            font-size: 11.5pt;
            color: #1a3a5c;
        }

        /* ── Tanda Tangan ── */
        .signature-section {
            margin-top: 28px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            font-size: 9.5pt;
        }

        .sig-box { text-align: center; }

        .sig-box .sig-title {
            font-weight: bold;
            color: #1a3a5c;
            margin-bottom: 54px;
            font-family: "Arial", sans-serif;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .sig-box .sig-line {
            border-top: 1.5px solid #555;
            padding-top: 5px;
            color: #444;
        }

        .sig-box .sig-note {
            color: #888;
            font-size: 8pt;
            margin-top: 2px;
        }

        /* ── Footer halaman ── */
        .page-footer {
            margin-top: 24px;
            padding-top: 8px;
            border-top: 1px solid #c8d6e5;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #999;
        }

        /* ── Tombol cetak (screen only) ── */
        .action-bar {
            width: 210mm;
            margin: 0 auto 16px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 9px 22px;
            border: none;
            border-radius: 4px;
            font-size: 10pt;
            cursor: pointer;
            font-family: "Arial", sans-serif;
            font-weight: bold;
        }

        .btn-primary {
            background: #1a3a5c;
            color: #fff;
        }

        .btn-secondary {
            background: #ddd;
            color: #333;
        }

        /* ══════════════════════════════════════════
           PRINT RULES
        ══════════════════════════════════════════ */
        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }

            .action-bar { display: none; }

            .page {
                box-shadow: none;
                margin: 0;
                padding: 12mm 15mm 15mm;
                width: 100%;
                min-height: auto;
                page-break-after: always;
            }

            /* Kepala surat muncul di setiap halaman */
            .letterhead,
            .letterhead-divider {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Jaga agar baris tabel tidak terpotong */
            tbody tr {
                page-break-inside: avoid;
            }

            /* Tandatangan selalu bersama di halaman terakhir */
            .signature-section {
                page-break-inside: avoid;
            }

            /* Warna tabel tetap ada saat cetak */
            thead tr,
            .doc-number,
            .denda-box .denda-header,
            .section-title,
            .sig-box .sig-title {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            tbody tr:nth-child(even) {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    {{-- ── Tombol aksi (hanya tampil di layar) ── --}}
    <div class="action-bar">
        <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak Dokumen</button>
        <button class="btn btn-secondary" onclick="window.close()">✕ Tutup</button>
    </div>

    <div class="page">

        {{-- ══ KEPALA SURAT ══ --}}
        <div class="letterhead">
            <div class="letterhead-logo">
                {{-- Ganti dengan tag <img> jika punya logo --}}
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;border-radius:6px"> --}}
                PS
            </div>
            <div class="letterhead-text">
                <div class="org-name">PILATES</div>
                <div class="org-sub">Divisi Pengelolaan Sarana &amp; Prasarana Desa Kapuk</div>
                <div class="org-address">
                    Jl. Contoh No. 123, Kota Anda, Provinsi — Telp. (0361) 000-0000
                    &nbsp;|&nbsp; Email: peminjaman@instansi.id
                    &nbsp;|&nbsp; Web: www.instansi.id
                </div>
            </div>
        </div>
        <div class="letterhead-divider"></div>

        {{-- ══ JUDUL DOKUMEN ══ --}}
        <div class="doc-title-row">
            <div class="doc-title">Manajemen Peminjaman</div>
            <div class="doc-number">No. PIN-{{ str_pad($pinjaman->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        {{-- ══ INFORMASI META ══ --}}
        <div class="info-grid">
            <div class="info-cell">
                <span class="lbl">Peminjam</span>
                <span class="val">{{ optional($pinjaman->peminjam)->name ?? '-' }}
                    @if(optional($pinjaman->peminjam)->role)
                        <div style="font-size:9pt;color:#666;margin-top:4px">{{ $pinjaman->peminjam->role }}</div>
                    @endif
                </span>
            </div>
            <div class="info-cell">
                <span class="lbl">Tanggal Cetak</span>
                <span class="val">{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</span>
            </div>
            <div class="info-cell">
                <span class="lbl">Tanggal Pinjam</span>
                <span class="val">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-cell">
                <span class="lbl">Rencana Kembali</span>
                <span class="val">{{ \Carbon\Carbon::parse($pinjaman->tanggal_kembali_rencana)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-cell">
                <span class="lbl">Status</span>
                <span class="val">
                    @php
                        $st = strtolower($pinjaman->status ?? '');
                        $badgeClass = match(true) {
                            str_contains($st, 'aktif') || str_contains($st, 'dipinjam')  => 'badge-active',
                            str_contains($st, 'terlambat') || str_contains($st, 'denda') => 'badge-late',
                            str_contains($st, 'kembali') || str_contains($st, 'selesai') => 'badge-returned',
                            default => 'badge-default',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $pinjaman->status }}</span>
                </span>
            </div>
            <div class="info-cell">
                <span class="lbl">Jumlah Item</span>
                <span class="val">{{ $pinjaman->details->count() }} alat</span>
            </div>
        </div>

        {{-- ══ TABEL DETAIL ALAT ══ --}}
        <div class="section-title">Daftar Alat yang Dipinjam</div>

        <table>
            <thead>
                <tr>
                    <th style="width:40px" class="center">No.</th>
                    <th>Nama Alat</th>
                    <th style="width:80px" class="right">Jumlah</th>
                    <th style="width:130px">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pinjaman->details as $i => $det)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ optional($det->alat)->nama_alat ?? ($det->id_alat ?? '-') }}</td>
                    <td class="right">{{ number_format($det->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $ds = strtolower($det->status ?? '');
                            $dc = match(true) {
                                str_contains($ds, 'kembali') || str_contains($ds, 'selesai') => 'badge-returned',
                                str_contains($ds, 'terlambat') => 'badge-late',
                                str_contains($ds, 'aktif') || str_contains($ds, 'pinjam')  => 'badge-active',
                                default => 'badge-default',
                            };
                        @endphp
                        <span class="badge {{ $dc }}">{{ $det->status }}</span>
                    </td>
                    <td style="color:#888; font-size:9pt">{{ $det->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="center" style="color:#aaa; font-style:italic; padding:20px">
                        Tidak ada data alat.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right; font-size:9pt; color:#555">Total Alat:</td>
                    <td class="right">{{ $pinjaman->details->sum('jumlah') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        {{-- ══ RINGKASAN DENDA ══ --}}
        <div class="denda-block">
            <div class="denda-box">
                <div class="denda-header">Ringkasan Biaya</div>
                <div class="denda-row">
                    <span>Total Denda Keterlambatan</span>
                    <span>Rp {{ number_format($pinjaman->total_denda ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="denda-row total">
                    <span>Grand Total</span>
                    <span>Rp {{ number_format($pinjaman->total_denda ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- ══ TANDA TANGAN ══ --}}
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Peminjam</div>
                <div class="sig-line">{{ optional($pinjaman->peminjam)->name ?? '___________________' }}</div>
                <div class="sig-note">Nama &amp; Tanda Tangan</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Petugas</div>
                <div class="sig-line">___________________</div>
                <div class="sig-note">Nama &amp; Tanda Tangan</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Mengetahui</div>
                <div class="sig-line">___________________</div>
                <div class="sig-note">Kepala / Supervisor</div>
            </div>
        </div>

        {{-- ══ FOOTER HALAMAN ══ --}}
        <div class="page-footer">
            <span>Dokumen ini dicetak secara otomatis oleh Sistem Peminjaman Alat</span>
            <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>

    </div>{{-- /.page --}}

    <script>
        // Auto-print setelah halaman siap
        window.onload = function() {
            setTimeout(() => window.print(), 300);
        };
    </script>
</body>
</html>