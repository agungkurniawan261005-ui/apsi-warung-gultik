<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pencatatan Warung Gultik Nusantara')</title>

    {{-- Google Fonts: Plus Jakarta Sans & IBM Plex Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 & Bootstrap Icons via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- CSS Kustom --}}
    <link href="{{ asset('css/warung.css') }}" rel="stylesheet">
</head>
<body>
    @php
        $isJualan = request()->routeIs('penjualan.create');
        $isKas = request()->routeIs('kas.*', 'pengeluaran.*', 'pemasukan.*');
        $isRekap = request()->routeIs('laporan.*');
        $isRiwayat = request()->routeIs('penjualan.index', 'penjualan.edit');
    @endphp

    {{-- Top Bar Sticky --}}
    <header class="warung-topbar">
        <div class="container container-warung h-100 px-3 px-sm-4 d-flex align-items-center justify-content-between gap-4">
            <a href="{{ route('penjualan.create') }}" class="warung-brand d-inline-flex align-items-center gap-2">
                <i class="bi bi-shop fs-5" aria-hidden="true"></i>
                <span>Warung Gultik Nusantara</span>
            </a>

            <nav aria-label="Navigasi Utama Desktop" class="d-none d-lg-flex align-items-center gap-4">
                <a href="{{ route('penjualan.create') }}"
                   aria-current="{{ $isJualan ? 'page' : 'false' }}"
                   class="warung-desktop-link {{ $isJualan ? 'is-active' : '' }}">
                    Catat Penjualan
                </a>
                <a href="{{ route('kas.index') }}"
                   aria-current="{{ $isKas ? 'page' : 'false' }}"
                   class="warung-desktop-link {{ $isKas ? 'is-active' : '' }}">
                    Pemasukan &amp; Keluar
                </a>
                <a href="{{ route('laporan.rekap') }}"
                   aria-current="{{ $isRekap ? 'page' : 'false' }}"
                   class="warung-desktop-link {{ $isRekap ? 'is-active' : '' }}">
                    Rekap &amp; Keuangan
                </a>
                <a href="{{ route('penjualan.index') }}"
                   aria-current="{{ $isRiwayat ? 'page' : 'false' }}"
                   class="warung-desktop-link {{ $isRiwayat ? 'is-active' : '' }}">
                    Riwayat Penjualan
                </a>
            </nav>

            <div class="d-none d-lg-flex align-items-center">
                <a href="{{ route('laporan.rekap') }}" class="btn-rekap-topbar">
                    Rekap Hari Ini
                </a>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main id="utama" class="container container-warung px-3 px-sm-4 pt-3 pt-sm-4">
        <div class="mb-3 mb-sm-4">
            <h1 class="h4 h3-sm fw-bold mb-1" style="font-weight: 800;">
                Sistem Pencatatan Penjualan dan Keuangan Warung Gultik Nusantara
            </h1>
            <p class="mb-0 fw-medium" style="color: var(--warung-muted); font-size: 0.95rem;">
                Hari ini: {{ now()->translatedFormat('d M Y') }} &middot; Tombol besar &amp; mudah dibaca
            </p>
        </div>

        {{-- Notifikasi Global (mis. dari Sidebar Demo/Reset atau Update/Hapus) --}}
        @if (session('success') && ! session('form_feedback'))
            <div role="status" class="warung-alert-success d-flex align-items-center justify-content-between gap-3 mb-4">
                <span>{{ session('success') }}</span>
                <a href="{{ url()->current() }}" class="btn btn-sm bg-white border border-success-subtle fw-bold text-success text-decoration-none px-3 py-2 rounded-3">
                    Tutup
                </a>
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-7 col-xl-8">
                @yield('content')
            </div>
            <div class="col-12 col-lg-5 col-xl-4">
                @include('partials.ringkasan-samping')
            </div>
        </div>
    </main>

    {{-- Navigasi Bawah Mobile (4 Menu: Jualan, Catat Kas, Rekap, Riwayat) --}}
    <nav aria-label="Navigasi Bawah Mobile" class="d-lg-none warung-bottom-nav">
        <div class="warung-bottom-grid">
            <a href="{{ route('penjualan.create') }}"
               aria-current="{{ $isJualan ? 'page' : 'false' }}"
               class="warung-bottom-link {{ $isJualan ? 'is-active' : '' }}">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
                <span>Jualan</span>
            </a>

            <a href="{{ route('kas.index') }}"
               aria-current="{{ $isKas ? 'page' : 'false' }}"
               class="warung-bottom-link {{ $isKas ? 'is-active' : '' }}">
                <i class="bi bi-wallet2" aria-hidden="true"></i>
                <span>Catat Kas</span>
            </a>

            <a href="{{ route('laporan.rekap') }}"
               aria-current="{{ $isRekap ? 'page' : 'false' }}"
               class="warung-bottom-link {{ $isRekap ? 'is-active' : '' }}">
                <i class="bi bi-bar-chart" aria-hidden="true"></i>
                <span>Rekap</span>
            </a>

            <a href="{{ route('penjualan.index') }}"
               aria-current="{{ $isRiwayat ? 'page' : 'false' }}"
               class="warung-bottom-link {{ $isRiwayat ? 'is-active' : '' }}">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                <span>Riwayat</span>
            </a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/warung.js') }}"></script>
</body>
</html>
