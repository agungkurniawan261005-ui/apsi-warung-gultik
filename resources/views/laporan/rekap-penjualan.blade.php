@extends('layouts.app')

@section('title', 'Rekap Penjualan - Warung Gultik Nusantara')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="h3 fw-bold mb-1">
                <i class="bi bi-bar-chart-fill me-2 text-primary-warung" aria-hidden="true"></i>
                Rekap Penjualan
            </h2>
            <p class="text-muted mb-0">Ringkasan total porsi terjual, omzet penjualan, serta pemisahan Tunai dan QRIS.</p>
        </div>
        <a href="{{ route('penjualan.index', ['periode' => $filter->periode, 'tanggal_mulai' => $filter->inputMulai(), 'tanggal_selesai' => $filter->inputSelesai()]) }}"
           class="btn btn-outline-dark btn-lg">
            <i class="bi bi-clock-history me-2" aria-hidden="true"></i>
            Lihat Riwayat
        </a>
    </div>

    @include('partials.period-filter', [
        'routeName' => 'laporan.rekap-penjualan',
        'filter' => $filter,
    ])

    @if ($rekap['total_transaksi'] === 0)
        <div class="card warung-card">
            <div class="card-body p-4">
                <div class="empty-state-box">
                    <i class="bi bi-bar-chart fs-1 text-muted d-block mb-2" aria-hidden="true"></i>
                    <h3 class="h4 fw-bold text-muted mb-2">Data belum tersedia</h3>
                    <p class="text-muted mb-3">Belum ada transaksi penjualan pada periode <strong>{{ $filter->label }}</strong>.</p>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-warung-primary btn-lg">
                        <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                        Catat Penjualan Sekarang
                    </a>
                </div>
            </div>
        </div>
    @else
        {{-- Ringkasan Utama --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <div class="card warung-card h-100">
                    <div class="card-body p-4">
                        <div class="text-muted fw-bold text-uppercase small mb-1">Total Porsi Terjual</div>
                        <div class="stat-card-value text-primary-warung tabular-angka">
                            {{ number_format($rekap['total_porsi'], 0, ',', '.') }} Porsi
                        </div>
                        <div class="fs-5 text-muted mt-2">
                            Dari <strong>{{ number_format($rekap['total_transaksi'], 0, ',', '.') }}</strong> transaksi penjualan
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card warung-card h-100 border-success-subtle">
                    <div class="card-body p-4">
                        <div class="text-muted fw-bold text-uppercase small mb-1">Total Omzet Penjualan</div>
                        <div class="stat-card-value text-success tabular-angka">
                            {{ \App\Support\FormatUang::rupiah($rekap['total_omzet']) }}
                        </div>
                        <div class="fs-5 text-muted mt-2">
                            Akumulasi penjualan Tunai + QRIS
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemisahan Metode Pembayaran Tunai vs QRIS --}}
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="card warung-card h-100" style="border-color: #16a34a;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge-metode badge-tunai fs-5">
                                <i class="bi bi-cash-stack me-2" aria-hidden="true"></i>
                                Penjualan Tunai
                            </span>
                            <span class="fw-bold text-muted">
                                {{ $rekap['total_omzet'] > 0 ? round(($rekap['tunai_omzet'] / $rekap['total_omzet']) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="stat-card-value text-success tabular-angka mb-2">
                            {{ \App\Support\FormatUang::rupiah($rekap['tunai_omzet']) }}
                        </div>
                        <div class="fs-5 text-dark">
                            <strong>{{ number_format($rekap['tunai_porsi'], 0, ',', '.') }} porsi</strong>
                            ({{ number_format($rekap['tunai_transaksi'], 0, ',', '.') }} transaksi)
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card warung-card h-100" style="border-color: #2563eb;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge-metode badge-qris fs-5">
                                <i class="bi bi-qr-code-scan me-2" aria-hidden="true"></i>
                                Penjualan QRIS
                            </span>
                            <span class="fw-bold text-muted">
                                {{ $rekap['total_omzet'] > 0 ? round(($rekap['qris_omzet'] / $rekap['total_omzet']) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="stat-card-value tabular-angka mb-2" style="color: var(--warung-blue);">
                            {{ \App\Support\FormatUang::rupiah($rekap['qris_omzet']) }}
                        </div>
                        <div class="fs-5 text-dark">
                            <strong>{{ number_format($rekap['qris_porsi'], 0, ',', '.') }} porsi</strong>
                            ({{ number_format($rekap['qris_transaksi'], 0, ',', '.') }} transaksi)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
