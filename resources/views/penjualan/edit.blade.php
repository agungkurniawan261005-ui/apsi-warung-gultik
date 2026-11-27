@extends('layouts.app')

@section('title', 'Ubah Catatan Penjualan - Warung Gultik Nusantara')

@section('content')
    @php
        $porsiTerpilih = (int) old('jumlah_porsi', $penjualan->jumlah_porsi);
        $hargaAktif = (int) old('harga_satuan', $penjualan->harga_satuan);
        $metodeTerpilih = old('metode_pembayaran', $penjualan->metode_pembayaran);
    @endphp

    <section class="warung-panel p-3 p-sm-4">
        <div class="warung-panel-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h4 fw-bold mb-1" style="font-weight: 800;">Ubah Catatan Penjualan</h2>
                <p class="mb-0" style="color: var(--warung-muted);">Perbaiki jumlah porsi, harga satuan, atau metode pembayaran.</p>
            </div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary fw-bold px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('penjualan.update', $penjualan) }}" novalidate data-penjualan-form>
            @csrf
            @method('PUT')
            <input type="hidden" name="jumlah_porsi" value="{{ $porsiTerpilih }}" data-input-porsi>

            <div class="mt-4">
                <div class="h5 fw-bold mb-2">Jumlah Porsi</div>
                <div class="porsi-grid">
                    @foreach ($presetPorsi as $angka)
                        <button type="button"
                                class="btn-porsi-preset {{ $porsiTerpilih === $angka ? 'is-selected' : '' }}"
                                data-porsi-preset="{{ $angka }}">
                            <span class="porsi-angka font-mono-num">{{ $angka }}</span>
                            <span class="porsi-label">Porsi</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-3 p-2 p-sm-3 warung-cream-box d-flex align-items-center justify-content-between gap-2">
                    <button type="button" class="btn-stepper-minus" data-porsi-kurang aria-label="Kurangi jumlah porsi">
                        <i class="bi bi-dash-lg" aria-hidden="true"></i>
                    </button>
                    <div class="text-center px-2">
                        <div class="fw-bold" style="font-size: 0.85rem; color: var(--warung-muted);">Jumlah Dipilih</div>
                        <div class="h3 fw-bold font-mono-num mb-0 mt-1" style="font-weight: 800;" data-tampilan-porsi>
                            {{ $porsiTerpilih }} Porsi
                        </div>
                    </div>
                    <button type="button" class="btn-stepper-plus" data-porsi-tambah aria-label="Tambah jumlah porsi">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-12 col-sm-6">
                    <label for="harga_satuan" class="h5 fw-bold mb-2 d-block">Harga Per Porsi (Rp)</label>
                    <input type="number"
                           id="harga_satuan"
                           name="harga_satuan"
                           min="500"
                           step="500"
                           value="{{ $hargaAktif }}"
                           class="form-control warung-input-lg font-mono-num fw-bold"
                           data-input-harga>
                </div>
                <div class="col-12 col-sm-6">
                    <label for="waktu_transaksi" class="h5 fw-bold mb-2 d-block">Waktu Transaksi</label>
                    <input type="datetime-local"
                           id="waktu_transaksi"
                           name="waktu_transaksi"
                           value="{{ old('waktu_transaksi', $penjualan->waktu_transaksi->format('Y-m-d\TH:i')) }}"
                           class="form-control warung-input-lg">
                </div>
            </div>

            <div class="mt-4">
                <div class="h5 fw-bold mb-2">Metode Pembayaran</div>
                <div class="row g-3">
                    <div class="col-6">
                        <input type="radio" id="edit_tunai" name="metode_pembayaran" value="Tunai" class="radio-card-input" data-metode-radio {{ $metodeTerpilih === 'Tunai' ? 'checked' : '' }}>
                        <label for="edit_tunai" class="radio-metode-card is-tunai w-100">
                            <span class="fs-4 fw-bold">TUNAI</span>
                        </label>
                    </div>
                    <div class="col-6">
                        <input type="radio" id="edit_qris" name="metode_pembayaran" value="QRIS" class="radio-card-input" data-metode-radio {{ $metodeTerpilih === 'QRIS' ? 'checked' : '' }}>
                        <label for="edit_qris" class="radio-metode-card is-qris w-100">
                            <span class="fs-4 fw-bold">QRIS</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 p-sm-4 warung-cream-box d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="fw-bold" style="color: var(--warung-muted);">Total Harga Penjualan</div>
                    <div class="fw-semibold" style="font-size: 0.95rem; color: var(--warung-muted);" data-rincian-tagihan></div>
                </div>
                <div class="h2 fw-bold font-mono-num mb-0" style="font-weight: 800;" data-total-tagihan></div>
            </div>

            @if ($errors->any())
                <div role="alert" class="mt-3 warung-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 d-flex flex-column flex-sm-row gap-2">
                <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary fw-bold flex-fill d-flex align-items-center justify-content-center" style="min-height: 58px; border-radius: 0.75rem;">
                    Batal
                </a>
                <button type="submit" class="btn-simpan-utama flex-fill">
                    <i class="bi bi-check-circle fs-4" aria-hidden="true"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </section>
@endsection
