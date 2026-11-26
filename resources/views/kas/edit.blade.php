@extends('layouts.app')

@section('title', 'Ubah ' . $jenisLabel . ' - Warung Gultik Nusantara')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card warung-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                        <div>
                            <h2 class="h3 fw-bold mb-1">
                                <i class="bi bi-pencil-square me-2 text-primary-warung" aria-hidden="true"></i>
                                Ubah Catatan {{ $jenisLabel }}
                            </h2>
                            <p class="text-muted mb-0">Perbaiki kategori, nominal, tanggal, atau keterangan jika diperlukan.</p>
                        </div>
                        <a href="{{ $routeBatal }}" class="btn btn-outline-dark btn-lg">
                            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                            Kembali
                        </a>
                    </div>

                    @include('kas._form-kas', [
                        'jenis' => $jenis,
                        'record' => $record,
                        'formAction' => $routeUpdate,
                        'routeBatal' => $routeBatal,
                        'submitLabel' => 'SIMPAN PERUBAHAN',
                        'kategoriList' => $kategoriList,
                        'presetNominal' => $presetNominal,
                        'presetKeterangan' => $presetKeterangan,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
