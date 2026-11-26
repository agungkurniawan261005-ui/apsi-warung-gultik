<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pencatatan Warung Gultik Nusantara')</title>

    {{-- Bootstrap 5.3 & Bootstrap Icons via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- CSS Kustom Ramah Lansia --}}
    <link href="{{ asset('css/warung.css') }}" rel="stylesheet">
</head>
<body>
    <header class="warung-header shadow-sm">
        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                <a href="{{ route('penjualan.create') }}" class="text-decoration-none d-flex align-items-center gap-3">
                    <span class="warung-logo-badge" aria-hidden="true">
                        <i class="bi bi-shop-window"></i>
                    </span>
                    <div>
                        <h1 class="h3 mb-0 fw-bold text-white">Warung Gultik Nusantara</h1>
                        <p class="mb-0 warung-subtitle">Sistem Pencatatan Penjualan &amp; Keuangan Harian</p>
                    </div>
                </a>
                <div class="warung-date-pill">
                    <i class="bi bi-calendar3 me-2" aria-hidden="true"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>

            {{-- Navigasi Utama Selalu Terlihat (Tanpa Menu Tersembunyi) --}}
            <nav aria-label="Menu Utama">
                <div class="warung-nav-grid">
                    <a href="{{ route('penjualan.create') }}"
                       class="warung-nav-btn {{ request()->routeIs('penjualan.create') ? 'is-active' : '' }}">
                        <i class="bi bi-plus-circle-fill" aria-hidden="true"></i>
                        <span>Catat Penjualan</span>
                    </a>
                    <a href="{{ route('pengeluaran.index') }}"
                       class="warung-nav-btn {{ request()->routeIs('pengeluaran.*', 'pemasukan.*') ? 'is-active' : '' }}">
                        <i class="bi bi-wallet2" aria-hidden="true"></i>
                        <span>Masuk &amp; Keluar</span>
                    </a>
                    <a href="{{ route('laporan.rekap-penjualan') }}"
                       class="warung-nav-btn {{ request()->routeIs('laporan.rekap-penjualan') ? 'is-active' : '' }}">
                        <i class="bi bi-bar-chart-fill" aria-hidden="true"></i>
                        <span>Rekap Penjualan</span>
                    </a>
                    <a href="{{ route('laporan.keuangan') }}"
                       class="warung-nav-btn {{ request()->routeIs('laporan.keuangan') ? 'is-active' : '' }}">
                        <i class="bi bi-journal-text" aria-hidden="true"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                    <a href="{{ route('penjualan.index') }}"
                       class="warung-nav-btn {{ request()->routeIs('penjualan.index', 'penjualan.edit') ? 'is-active' : '' }}">
                        <i class="bi bi-clock-history" aria-hidden="true"></i>
                        <span>Riwayat Penjualan</span>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main class="container py-4">
        @include('partials.alert')
        @yield('content')
    </main>

    <footer class="container pb-4 pt-2 text-center text-muted small">
        <hr class="mb-3">
        <span>Sistem Pencatatan Warung Gultik Nusantara &bull; Mode Kasir &amp; Pembukuan Sederhana</span>
    </footer>

    {{-- Modal Konfirmasi Hapus Ramah Lansia --}}
    <div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-labelledby="modalKonfirmasiHapusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-2 border-danger">
                <div class="modal-header bg-danger text-white">
                    <h2 class="modal-title h4 fw-bold" id="modalKonfirmasiHapusLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                        Konfirmasi Hapus Data
                    </h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="fs-5 mb-2">Apakah Anda yakin ingin menghapus catatan berikut?</p>
                    <p class="fs-5 fw-bold text-danger mb-0" id="modalKonfirmasiHapusDetail"></p>
                </div>
                <div class="modal-footer p-3 d-flex gap-2 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-lg flex-fill" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <form id="formKonfirmasiHapus" method="POST" action="" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold">
                            <i class="bi bi-trash-fill me-1" aria-hidden="true"></i>
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/warung.js') }}"></script>
</body>
</html>
