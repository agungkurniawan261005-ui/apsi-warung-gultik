@extends('layouts.app')

@section('title', 'Catat Penjualan - Warung Gultik Nusantara')

@section('content')
    @php
        $hargaTerisi = old('harga_satuan', $hargaDefault);
        $bukaUbahHarga = $errors->has('harga_satuan') || ((int) $hargaTerisi !== (int) $hargaDefault);
    @endphp

    {{-- Ringkasan Singkat Hari Ini --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card warung-card h-100">
                <div class="card-body p-3">
                    <div class="text-muted fw-semibold">Porsi Terjual Hari Ini</div>
                    <div class="stat-card-value text-primary-warung tabular-angka mt-1">
                        {{ $ringkasanHariIni['total_porsi'] }} Porsi
                    </div>
                    <div class="small text-muted mt-1">{{ $ringkasanHariIni['total_transaksi'] }} transaksi</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="card warung-card h-100">
                <div class="card-body p-3">
                    <div class="text-muted fw-semibold">Total Omzet Hari Ini</div>
                    <div class="stat-card-value text-success tabular-angka mt-1">
                        {{ \App\Support\FormatUang::rupiah($ringkasanHariIni['total_omzet']) }}
                    </div>
                    <div class="small text-muted mt-1">
                        Tunai: {{ \App\Support\FormatUang::rupiah($ringkasanHariIni['tunai_omzet']) }}
                        &bull;
                        QRIS: {{ \App\Support\FormatUang::rupiah($ringkasanHariIni['qris_omzet']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3 d-grid">
            <a href="{{ route('laporan.rekap-penjualan') }}" class="btn btn-outline-dark btn-lg h-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-bar-chart-fill" aria-hidden="true"></i>
                <span>Lihat Rekap Lengkap</span>
            </a>
        </div>
    </div>

    <div class="row g-4 align-items-start">
        {{-- Kolom Kiri: Form Utama Catat Penjualan (UC-01 & UC-02) --}}
        <div class="col-12 col-lg-7">
            <div class="card warung-card">
                <div class="card-body p-3 p-md-4">
                    <h2 class="h3 fw-bold mb-1">
                        <i class="bi bi-cart-plus-fill me-2 text-primary-warung" aria-hidden="true"></i>
                        Catat Penjualan Baru
                    </h2>
                    <p class="text-muted mb-4">Cukup ketuk jumlah porsi dan pilih cara bayar, lalu tekan simpan.</p>

                    <form method="POST" action="{{ route('penjualan.store') }}" novalidate data-penjualan-form>
                        @csrf

                        {{-- 1. Pilih Jumlah Porsi --}}
                        <div class="mb-4">
                            <label for="jumlah_porsi" class="form-label fs-5 fw-bold mb-2">
                                1. Jumlah Porsi (Mangkok)
                            </label>

                            <div class="porsi-quick-grid mb-3">
                                @foreach ($presetPorsi as $angka)
                                    <button type="button"
                                            class="btn-porsi-quick"
                                            data-porsi-preset="{{ $angka }}">
                                        {{ $angka }} Porsi
                                    </button>
                                @endforeach
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button"
                                        class="btn btn-outline-dark btn-stepper"
                                        data-porsi-kurang
                                        aria-label="Kurangi 1 porsi">
                                    &minus;
                                </button>
                                <input type="number"
                                       id="jumlah_porsi"
                                       name="jumlah_porsi"
                                       min="1"
                                       inputmode="numeric"
                                       placeholder="0"
                                       value="{{ old('jumlah_porsi') }}"
                                       class="form-control input-porsi-besar tabular-angka @error('jumlah_porsi') is-invalid @enderror"
                                       data-input-porsi>
                                <button type="button"
                                        class="btn btn-outline-dark btn-stepper"
                                        data-porsi-tambah
                                        aria-label="Tambah 1 porsi">
                                    &plus;
                                </button>
                            </div>

                            @error('jumlah_porsi')
                                <div class="text-danger fw-bold fs-5 mt-2">
                                    <i class="bi bi-exclamation-circle-fill me-1" aria-hidden="true"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Pengaturan Harga Satuan (Default Rp 15.000, bisa diubah via tombol) --}}
                        <div class="mb-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                <span class="fw-semibold text-muted">
                                    Harga per porsi standar: <strong class="text-dark">{{ \App\Support\FormatUang::rupiah($hargaDefault) }}</strong>
                                </span>
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        data-toggle-harga
                                        aria-expanded="{{ $bukaUbahHarga ? 'true' : 'false' }}">
                                    <i class="bi bi-pencil-square me-1" aria-hidden="true"></i>
                                    Ubah Harga
                                </button>
                            </div>

                            <div class="card bg-light border-2 p-3 {{ $bukaUbahHarga ? '' : 'd-none' }}" data-box-harga>
                                <label for="harga_satuan" class="form-label fw-bold">Harga Per Porsi (Rp)</label>
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <input type="number"
                                           id="harga_satuan"
                                           name="harga_satuan"
                                           min="500"
                                           step="500"
                                           inputmode="numeric"
                                           value="{{ $hargaTerisi }}"
                                           class="form-control form-control-lg tabular-angka @error('harga_satuan') is-invalid @enderror"
                                           data-input-harga>
                                    <button type="button"
                                            class="btn btn-outline-dark"
                                            data-reset-harga
                                            data-default-harga="{{ $hargaDefault }}">
                                        Pakai {{ \App\Support\FormatUang::rupiah($hargaDefault) }}
                                    </button>
                                </div>
                                @error('harga_satuan')
                                    <div class="text-danger fw-bold mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Total Harga Otomatis --}}
                        <div class="total-bayar-box mb-4">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                                <div>
                                    <div class="fw-bold text-uppercase text-muted small">Total Harga Otomatis</div>
                                    <div class="fw-semibold text-dark" data-rincian-hitung>
                                        Pilih atau masukkan jumlah porsi
                                    </div>
                                </div>
                                <div class="total-bayar-angka tabular-angka" data-total-bayar>
                                    Rp 0
                                </div>
                            </div>
                        </div>

                        {{-- 2. Pilih Metode Pembayaran (UC-02) --}}
                        <div class="mb-4">
                            <span class="form-label fs-5 fw-bold d-block mb-2">
                                2. Pilih Metode Pembayaran
                            </span>

                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio"
                                           id="metode_tunai"
                                           name="metode_pembayaran"
                                           value="Tunai"
                                           class="pilihan-radio-input"
                                           {{ old('metode_pembayaran') === 'Tunai' ? 'checked' : '' }}>
                                    <label for="metode_tunai" class="pilihan-radio-card is-tunai w-100">
                                        <i class="bi bi-cash-stack fs-1 mb-1" aria-hidden="true"></i>
                                        <span class="fs-4 fw-bold">TUNAI</span>
                                        <span class="small">Uang Cash</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio"
                                           id="metode_qris"
                                           name="metode_pembayaran"
                                           value="QRIS"
                                           class="pilihan-radio-input"
                                           {{ old('metode_pembayaran') === 'QRIS' ? 'checked' : '' }}>
                                    <label for="metode_qris" class="pilihan-radio-card is-qris w-100">
                                        <i class="bi bi-qr-code-scan fs-1 mb-1" aria-hidden="true"></i>
                                        <span class="fs-4 fw-bold">QRIS</span>
                                        <span class="small">Scan QR</span>
                                    </label>
                                </div>
                            </div>

                            @error('metode_pembayaran')
                                <div class="text-danger fw-bold fs-5 mt-2">
                                    <i class="bi bi-exclamation-circle-fill me-1" aria-hidden="true"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Simpan Utama --}}
                        <button type="submit" class="btn btn-warung-simpan w-100 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-2" aria-hidden="true"></i>
                            SIMPAN PENJUALAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: 5 Transaksi Terakhir Hari Ini --}}
        <div class="col-12 col-lg-5">
            <div class="card warung-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <h2 class="h4 fw-bold mb-0">
                            <i class="bi bi-receipt me-2 text-primary-warung" aria-hidden="true"></i>
                            Transaksi Terbaru Hari Ini
                        </h2>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-dark">
                            Semua
                        </a>
                    </div>

                    @if ($penjualanTerbaru->isEmpty())
                        <div class="empty-state-box py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2" aria-hidden="true"></i>
                            <div class="fs-5 fw-bold text-muted">Tidak ada riwayat penjualan</div>
                            <p class="mb-0 text-muted mt-1">Transaksi yang disimpan hari ini akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($penjualanTerbaru as $item)
                                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <div class="fw-bold fs-5">
                                            {{ $item->jumlah_porsi }} Porsi
                                            <span class="badge-metode ms-1 {{ $item->metode_pembayaran === 'Tunai' ? 'badge-tunai' : 'badge-qris' }}">
                                                {{ $item->metode_pembayaran }}
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            Pukul {{ $item->waktu_transaksi->format('H:i') }} WIB
                                            &bull; {{ \App\Support\FormatUang::rupiah($item->harga_satuan) }}/porsi
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold fs-5 tabular-angka text-dark">
                                            {{ \App\Support\FormatUang::rupiah($item->total_harga) }}
                                        </div>
                                        <a href="{{ route('penjualan.edit', $item) }}" class="small fw-semibold text-decoration-none">
                                            Ubah
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
