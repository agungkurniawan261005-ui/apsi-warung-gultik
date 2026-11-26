@extends('layouts.app')

@section('title', 'Laporan Keuangan - Warung Gultik Nusantara')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="h3 fw-bold mb-1">
                <i class="bi bi-journal-text me-2 text-primary-warung" aria-hidden="true"></i>
                Laporan Pemasukan &amp; Pengeluaran
            </h2>
            <p class="text-muted mb-0">Gabungan otomatis penjualan gultik, pemasukan non-penjualan, dan pengeluaran warung.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pengeluaran.index') }}" class="btn btn-outline-danger btn-lg">
                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                Catat Pengeluaran
            </a>
            <a href="{{ route('pemasukan.index') }}" class="btn btn-outline-success btn-lg">
                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                Pemasukan Tambahan
            </a>
        </div>
    </div>

    @include('partials.period-filter', [
        'routeName' => 'laporan.keuangan',
        'filter' => $filter,
    ])

    @if ($isKosong)
        <div class="card warung-card">
            <div class="card-body p-4">
                <div class="empty-state-box">
                    <i class="bi bi-journal-x fs-1 text-muted d-block mb-2" aria-hidden="true"></i>
                    <h3 class="h4 fw-bold text-muted mb-2">Data belum tersedia</h3>
                    <p class="text-muted mb-3">Belum ada catatan penjualan, pemasukan tambahan, maupun pengeluaran pada periode <strong>{{ $filter->label }}</strong>.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('penjualan.create') }}" class="btn btn-warung-primary btn-lg">Catat Penjualan</a>
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-outline-danger btn-lg">Catat Pengeluaran</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Ringkasan Formula Gabungan --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card warung-card h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="small fw-bold text-uppercase text-muted">1. Pendapatan Penjualan (Otomatis)</div>
                        <div class="stat-card-value text-success tabular-angka mt-2">
                            + {{ \App\Support\FormatUang::rupiah($totalPenjualan) }}
                        </div>
                        <div class="small text-muted mt-2">
                            Otomatis dari {{ $rekapPenjualan['total_porsi'] }} porsi terjual
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card warung-card h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="small fw-bold text-uppercase text-muted">2. Pemasukan Non-Penjualan</div>
                        <div class="stat-card-value tabular-angka mt-2" style="color: var(--warung-teal);">
                            + {{ \App\Support\FormatUang::rupiah($totalPemasukanLain) }}
                        </div>
                        <div class="small text-muted mt-2">
                            {{ $pemasukanList->count() }} catatan modal / lainnya
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card warung-card h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="small fw-bold text-uppercase text-muted">3. Total Pengeluaran</div>
                        <div class="stat-card-value text-danger tabular-angka mt-2">
                            &minus; {{ \App\Support\FormatUang::rupiah($totalPengeluaran) }}
                        </div>
                        <div class="small text-muted mt-2">
                            {{ $pengeluaranList->count() }} catatan belanja &amp; operasional
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card warung-card h-100 {{ $saldoBersih >= 0 ? 'border-success' : 'border-danger' }}" style="border-width: 3px;">
                    <div class="card-body p-3 p-md-4">
                        <div class="small fw-bold text-uppercase text-muted">4. Saldo / Kas Bersih</div>
                        <div class="stat-card-value tabular-angka mt-2 {{ $saldoBersih >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ \App\Support\FormatUang::rupiah($saldoBersih) }}
                        </div>
                        <div class="small text-muted mt-2">
                            (Penjualan + Pemasukan Lain) &minus; Pengeluaran
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Per Kategori --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card warung-card h-100">
                    <div class="card-body p-3 p-md-4">
                        <h3 class="h4 fw-bold mb-3" style="color: var(--warung-teal);">
                            <i class="bi bi-arrow-down-left-circle-fill me-2" aria-hidden="true"></i>
                            Rincian Pemasukan Non-Penjualan
                        </h3>
                        @if ($pemasukanList->isEmpty())
                            <p class="text-muted mb-0">Tidak ada pemasukan tambahan pada periode ini.</p>
                        @else
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($pemasukanPerKategori as $kat => $subtotal)
                                    <span class="badge bg-light text-dark border fs-6 py-2 px-3">
                                        {{ $kat }}: <strong class="tabular-angka">{{ \App\Support\FormatUang::rupiah($subtotal) }}</strong>
                                    </span>
                                @endforeach
                            </div>
                            <div class="table-responsive">
                                <table class="table table-warung align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori &amp; Keterangan</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pemasukanList as $item)
                                            <tr>
                                                <td class="text-nowrap">{{ $item->tanggal->translatedFormat('d M Y') }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $item->kategori }}</div>
                                                    @if ($item->keterangan)
                                                        <div class="small text-muted">{{ $item->keterangan }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold tabular-angka text-success">
                                                    {{ \App\Support\FormatUang::rupiah($item->nominal) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card warung-card h-100">
                    <div class="card-body p-3 p-md-4">
                        <h3 class="h4 fw-bold text-danger mb-3">
                            <i class="bi bi-arrow-up-right-circle-fill me-2" aria-hidden="true"></i>
                            Rincian Pengeluaran Operasional
                        </h3>
                        @if ($pengeluaranList->isEmpty())
                            <p class="text-muted mb-0">Tidak ada catatan pengeluaran pada periode ini.</p>
                        @else
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($pengeluaranPerKategori as $kat => $subtotal)
                                    <span class="badge bg-light text-dark border fs-6 py-2 px-3">
                                        {{ $kat }}: <strong class="tabular-angka">{{ \App\Support\FormatUang::rupiah($subtotal) }}</strong>
                                    </span>
                                @endforeach
                            </div>
                            <div class="table-responsive">
                                <table class="table table-warung align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kategori &amp; Keterangan</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengeluaranList as $item)
                                            <tr>
                                                <td class="text-nowrap">{{ $item->tanggal->translatedFormat('d M Y') }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $item->kategori }}</div>
                                                    @if ($item->keterangan)
                                                        <div class="small text-muted">{{ $item->keterangan }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold tabular-angka text-danger">
                                                    {{ \App\Support\FormatUang::rupiah($item->nominal) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
