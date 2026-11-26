@extends('layouts.app')

@section('title', 'Catat Pengeluaran - Warung Gultik Nusantara')

@section('content')
    @include('kas._tabs', ['tabAktif' => 'pengeluaran'])

    <div class="row g-4 align-items-start">
        <div class="col-12 col-lg-7">
            <div class="card warung-card border-danger-subtle">
                <div class="card-body p-3 p-md-4">
                    <h2 class="h3 fw-bold mb-1 text-danger">
                        <i class="bi bi-bag-dash-fill me-2" aria-hidden="true"></i>
                        Catat Pengeluaran Operasional
                    </h2>
                    <p class="text-muted mb-4">Catat belanja bahan gultik, gas, atau keperluan warung lainnya.</p>

                    @include('kas._form-kas', [
                        'jenis' => 'pengeluaran',
                        'formAction' => route('pengeluaran.store'),
                        'submitLabel' => 'SIMPAN PENGELUARAN',
                        'kategoriList' => $kategoriList,
                        'presetNominal' => $presetNominal,
                        'presetKeterangan' => $presetKeterangan,
                    ])
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            @include('kas._riwayat-kas', [
                'jenis' => 'pengeluaran',
                'judulDaftar' => 'Daftar Pengeluaran Terbaru',
                'riwayat' => $riwayat,
                'totalHariIni' => $totalHariIni,
            ])
        </div>
    </div>
@endsection
