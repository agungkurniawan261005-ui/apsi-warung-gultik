@extends('layouts.app')

@section('title', 'Riwayat Penjualan - Warung Gultik Nusantara')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="h3 fw-bold mb-1">
                <i class="bi bi-clock-history me-2 text-primary-warung" aria-hidden="true"></i>
                Riwayat Penjualan
            </h2>
            <p class="text-muted mb-0">Daftar transaksi penjualan yang pernah tercatat di Warung Gultik Nusantara.</p>
        </div>
        <a href="{{ route('penjualan.create') }}" class="btn btn-warung-primary btn-lg">
            <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
            Catat Penjualan
        </a>
    </div>

    @include('partials.period-filter', [
        'routeName' => 'penjualan.index',
        'filter' => $filter,
        'showMetodeFilter' => true,
        'metodeList' => $metodeList,
        'metodeAktif' => $metodeAktif,
        'extraQuery' => $metodeAktif !== '' ? ['metode' => $metodeAktif] : [],
    ])

    <div class="card warung-card">
        <div class="card-body p-3 p-md-4">
            @if ($penjualans->isEmpty())
                <div class="empty-state-box">
                    <i class="bi bi-receipt-cutoff fs-1 text-muted d-block mb-2" aria-hidden="true"></i>
                    <h3 class="h4 fw-bold text-muted mb-2">Tidak ada riwayat penjualan</h3>
                    <p class="text-muted mb-3">Belum ada transaksi penjualan yang tercatat pada filter ini.</p>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-warung-primary btn-lg">
                        <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                        Catat Penjualan Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-warung align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu Transaksi</th>
                                <th>Jumlah Porsi</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                                <th>Metode</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualans as $index => $item)
                                <tr>
                                    <td class="fw-semibold">{{ $penjualans->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $item->waktu_transaksi->translatedFormat('d M Y') }}</div>
                                        <div class="small text-muted">{{ $item->waktu_transaksi->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="fw-bold">{{ $item->jumlah_porsi }} porsi</td>
                                    <td class="tabular-angka">{{ \App\Support\FormatUang::rupiah($item->harga_satuan) }}</td>
                                    <td class="fw-bold tabular-angka text-dark">
                                        {{ \App\Support\FormatUang::rupiah($item->total_harga) }}
                                    </td>
                                    <td>
                                        <span class="badge-metode {{ $item->metode_pembayaran === 'Tunai' ? 'badge-tunai' : 'badge-qris' }}">
                                            {{ $item->metode_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('penjualan.edit', $item) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-pencil-square me-1" aria-hidden="true"></i>
                                                Ubah
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalKonfirmasiHapus"
                                                    data-delete-action="{{ route('penjualan.destroy', $item) }}"
                                                    data-delete-detail="Penjualan {{ $item->jumlah_porsi }} porsi ({{ \App\Support\FormatUang::rupiah($item->total_harga) }} - {{ $item->metode_pembayaran }})">
                                                <i class="bi bi-trash me-1" aria-hidden="true"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($penjualans->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $penjualans->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
