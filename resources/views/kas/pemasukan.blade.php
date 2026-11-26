@extends('layouts.app')

@section('title', 'Catat Pemasukan Tambahan - Warung Gultik Nusantara')

@section('content')
    @include('kas._tabs', ['tabAktif' => 'pemasukan'])

    <div class="alert alert-info border-2 shadow-sm d-flex align-items-start gap-3 mb-4" role="note">
        <i class="bi bi-info-circle-fill fs-3 flex-shrink-0" aria-hidden="true"></i>
        <div class="fs-5">
            <strong>Catatan Penting:</strong> Pendapatan dari penjualan gultik sudah dihitung otomatis dari menu
            <strong>Catat Penjualan</strong>. Menu ini khusus untuk mencatat <strong>Tambahan Modal</strong> atau
            <strong>Pendapatan Lain-lain</strong> di luar penjualan agar tidak terhitung ganda.
        </div>
    </div>

    <div class="row g-4 align-items-start">
        <div class="col-12 col-lg-7">
            <div class="card warung-card">
                <div class="card-body p-3 p-md-4">
                    <h2 class="h3 fw-bold mb-1" style="color: var(--warung-teal);">
                        <i class="bi bi-cash-coin me-2" aria-hidden="true"></i>
                        Catat Pemasukan Non-Penjualan
                    </h2>
                    <p class="text-muted mb-4">Gunakan untuk tambahan modal kasir atau pemasukan di luar jualan porsi.</p>

                    @include('kas._form-kas', [
                        'jenis' => 'pemasukan',
                        'formAction' => route('pemasukan.store'),
                        'submitLabel' => 'SIMPAN PEMASUKAN TAMBAHAN',
                        'kategoriList' => $kategoriList,
                        'presetNominal' => $presetNominal,
                        'presetKeterangan' => $presetKeterangan,
                    ])
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            @include('kas._riwayat-kas', [
                'jenis' => 'pemasukan',
                'judulDaftar' => 'Daftar Pemasukan Tambahan',
                'riwayat' => $riwayat,
                'totalHariIni' => $totalHariIni,
            ])
        </div>
    </div>
@endsection
