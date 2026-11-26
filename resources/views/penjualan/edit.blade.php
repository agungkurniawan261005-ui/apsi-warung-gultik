@extends('layouts.app')

@section('title', 'Ubah Catatan Penjualan - Warung Gultik Nusantara')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card warung-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                        <div>
                            <h2 class="h3 fw-bold mb-1">
                                <i class="bi bi-pencil-square me-2 text-primary-warung" aria-hidden="true"></i>
                                Ubah Catatan Penjualan
                            </h2>
                            <p class="text-muted mb-0">Perbaiki jumlah porsi, harga satuan, atau metode pembayaran.</p>
                        </div>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-dark btn-lg">
                            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                            Kembali
                        </a>
                    </div>

                    <form method="POST" action="{{ route('penjualan.update', $penjualan) }}" novalidate data-penjualan-form>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="jumlah_porsi" class="form-label fs-5 fw-bold mb-2">Jumlah Porsi (Mangkok)</label>
                            <div class="porsi-quick-grid mb-3">
                                @foreach ($presetPorsi as $angka)
                                    <button type="button" class="btn-porsi-quick" data-porsi-preset="{{ $angka }}">
                                        {{ $angka }} Porsi
                                    </button>
                                @endforeach
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-dark btn-stepper" data-porsi-kurang aria-label="Kurangi 1 porsi">&minus;</button>
                                <input type="number"
                                       id="jumlah_porsi"
                                       name="jumlah_porsi"
                                       min="1"
                                       inputmode="numeric"
                                       value="{{ old('jumlah_porsi', $penjualan->jumlah_porsi) }}"
                                       class="form-control input-porsi-besar tabular-angka @error('jumlah_porsi') is-invalid @enderror"
                                       data-input-porsi>
                                <button type="button" class="btn btn-outline-dark btn-stepper" data-porsi-tambah aria-label="Tambah 1 porsi">&plus;</button>
                            </div>
                            @error('jumlah_porsi')
                                <div class="text-danger fw-bold fs-5 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="harga_satuan" class="form-label fs-5 fw-bold">Harga Per Porsi (Rp)</label>
                                <input type="number"
                                       id="harga_satuan"
                                       name="harga_satuan"
                                       min="500"
                                       step="500"
                                       inputmode="numeric"
                                       value="{{ old('harga_satuan', $penjualan->harga_satuan) }}"
                                       class="form-control form-control-lg tabular-angka @error('harga_satuan') is-invalid @enderror"
                                       data-input-harga>
                                @error('harga_satuan')
                                    <div class="text-danger fw-bold mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="waktu_transaksi" class="form-label fs-5 fw-bold">Waktu Transaksi</label>
                                <input type="datetime-local"
                                       id="waktu_transaksi"
                                       name="waktu_transaksi"
                                       value="{{ old('waktu_transaksi', $penjualan->waktu_transaksi->format('Y-m-d\TH:i')) }}"
                                       class="form-control form-control-lg @error('waktu_transaksi') is-invalid @enderror">
                                @error('waktu_transaksi')
                                    <div class="text-danger fw-bold mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="total-bayar-box mb-4">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                                <div>
                                    <div class="fw-bold text-uppercase text-muted small">Total Harga Otomatis</div>
                                    <div class="fw-semibold text-dark" data-rincian-hitung></div>
                                </div>
                                <div class="total-bayar-angka tabular-angka" data-total-bayar>Rp 0</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="form-label fs-5 fw-bold d-block mb-2">Metode Pembayaran</span>
                            <div class="row g-3">
                                @php $metodeTerpilih = old('metode_pembayaran', $penjualan->metode_pembayaran); @endphp
                                <div class="col-6">
                                    <input type="radio" id="metode_tunai" name="metode_pembayaran" value="Tunai" class="pilihan-radio-input" {{ $metodeTerpilih === 'Tunai' ? 'checked' : '' }}>
                                    <label for="metode_tunai" class="pilihan-radio-card is-tunai w-100">
                                        <i class="bi bi-cash-stack fs-1 mb-1" aria-hidden="true"></i>
                                        <span class="fs-4 fw-bold">TUNAI</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" id="metode_qris" name="metode_pembayaran" value="QRIS" class="pilihan-radio-input" {{ $metodeTerpilih === 'QRIS' ? 'checked' : '' }}>
                                    <label for="metode_qris" class="pilihan-radio-card is-qris w-100">
                                        <i class="bi bi-qr-code-scan fs-1 mb-1" aria-hidden="true"></i>
                                        <span class="fs-4 fw-bold">QRIS</span>
                                    </label>
                                </div>
                            </div>
                            @error('metode_pembayaran')
                                <div class="text-danger fw-bold fs-5 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary btn-lg flex-fill">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-warung-simpan flex-fill fw-bold">
                                <i class="bi bi-check2-circle me-2" aria-hidden="true"></i>
                                SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
