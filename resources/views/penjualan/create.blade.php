@extends('layouts.app')

@section('title', 'Catat Penjualan - Warung Gultik Nusantara')

@section('content')
    @php
        $porsiTerpilih = (int) old('jumlah_porsi', 0);
        $hargaAktif = (int) old('harga_satuan', $hargaDefault);
        $metodeTerpilih = old('metode_pembayaran', '');
        if (strtoupper((string) $metodeTerpilih) === 'TUNAI') {
            $metodeTerpilih = 'Tunai';
        }
    @endphp

    <section aria-labelledby="judul-catat-penjualan" class="warung-panel p-3 p-sm-4">
        <div class="warung-panel-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 id="judul-catat-penjualan" class="h4 fw-bold mb-1" style="font-weight: 800;">
                    Catat Penjualan
                </h2>
                <p class="mb-0" style="color: var(--warung-muted); font-size: 1rem;">
                    Ketuk jumlah porsi lalu pilih cara bayar.
                </p>
            </div>

            <button type="button"
                    class="btn-harga-toggle"
                    data-toggle-harga
                    aria-expanded="false">
                <i class="bi bi-sliders" style="color: var(--warung-green);" aria-hidden="true"></i>
                <span data-label-harga-header>Harga: {{ \App\Support\FormatUang::rupiah($hargaAktif) }}/porsi</span>
            </button>
        </div>

        <form method="POST" action="{{ route('penjualan.store') }}" novalidate data-penjualan-form>
            @csrf

            <input type="hidden" name="jumlah_porsi" value="{{ $porsiTerpilih }}" data-input-porsi>
            <input type="hidden" name="harga_satuan" value="{{ $hargaAktif }}" data-input-harga>

            {{-- Pengaturan Harga Satuan per Porsi (Collapsible) --}}
            <div class="mt-3 p-3 p-sm-4 rounded-4 d-none"
                 style="background-color: var(--warung-cream); border: 1px solid var(--warung-border);"
                 aria-label="Pengaturan harga per porsi"
                 data-panel-harga>
                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                    <h3 class="h5 fw-bold mb-0">Atur Harga Satuan per Porsi</h3>
                    <button type="button" class="btn btn-link fw-bold text-decoration-none p-2" style="color: var(--warung-muted);" data-tutup-harga>
                        Tutup
                    </button>
                </div>

                <div class="row g-2 mb-3">
                    @foreach ($presetHarga as $opsiHarga)
                        <div class="col-6 col-sm-3">
                            <button type="button"
                                    class="btn w-100 fw-bold font-mono-num border-2"
                                    style="min-height: 50px; border-radius: 0.75rem;"
                                    data-harga-preset="{{ $opsiHarga }}">
                                {{ \App\Support\FormatUang::rupiah($opsiHarga) }}
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <label for="input-harga-porsi" class="visually-hidden">Harga per porsi dalam Rupiah</label>
                    <input type="number"
                           id="input-harga-porsi"
                           inputmode="numeric"
                           min="1000"
                           step="500"
                           value="{{ $hargaAktif }}"
                           placeholder="Contoh: 15000"
                           class="form-control warung-input-lg font-mono-num fw-bold fs-5 flex-fill"
                           data-input-harga-manual>
                    <button type="button"
                            class="btn fw-bold text-white px-4"
                            style="min-height: 52px; border-radius: 0.75rem; background-color: var(--warung-text);"
                            data-simpan-harga>
                        Simpan Harga
                    </button>
                </div>
            </div>

            {{-- Bagian 1: Pilih Jumlah Porsi --}}
            <div class="mt-4">
                <div class="h5 fw-bold mb-2">1. Pilih Jumlah Porsi</div>

                <div class="porsi-grid">
                    @foreach ($presetPorsi as $angka)
                        <button type="button"
                                class="btn-porsi-preset {{ $porsiTerpilih === $angka ? 'is-selected' : '' }}"
                                data-porsi-preset="{{ $angka }}"
                                aria-pressed="{{ $porsiTerpilih === $angka ? 'true' : 'false' }}"
                                aria-label="Pilih {{ $angka }} porsi">
                            <span class="porsi-angka font-mono-num">{{ $angka }}</span>
                            <span class="porsi-label">Porsi</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-3 p-2 p-sm-3 warung-cream-box d-flex align-items-center justify-content-between gap-2">
                    <button type="button"
                            class="btn-stepper-minus"
                            data-porsi-kurang
                            aria-label="Kurangi jumlah porsi">
                        <i class="bi bi-dash-lg" aria-hidden="true"></i>
                    </button>

                    <div class="text-center px-2">
                        <div class="fw-bold" style="font-size: 0.85rem; color: var(--warung-muted);">Jumlah Dipilih</div>
                        <div class="h3 fw-bold font-mono-num mb-0 mt-1" style="font-weight: 800;" data-tampilan-porsi>
                            {{ $porsiTerpilih }} Porsi
                        </div>
                    </div>

                    <button type="button"
                            class="btn-stepper-plus"
                            data-porsi-tambah
                            aria-label="Tambah jumlah porsi">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- Bagian 2: Pilih Metode Pembayaran (Tunai / QRIS) --}}
            <div class="mt-4">
                <div class="h5 fw-bold mb-2">2. Pilih Metode Pembayaran</div>

                <div class="row g-3">
                    <div class="col-6">
                        <input type="radio"
                               id="metode_tunai"
                               name="metode_pembayaran"
                               value="Tunai"
                               class="radio-card-input"
                               data-metode-radio
                               {{ $metodeTerpilih === 'Tunai' ? 'checked' : '' }}>
                        <label for="metode_tunai" class="radio-metode-card is-tunai w-100">
                            <span class="d-inline-flex align-items-center gap-2">
                                <i class="bi bi-cash-stack fs-4" aria-hidden="true"></i>
                                <span class="fs-4 fw-bold" style="font-weight: 800;">TUNAI</span>
                                <i class="bi bi-check-lg fs-5 metode-check" aria-hidden="true"></i>
                            </span>
                            <span class="metode-sub">Bayar Uang Cash</span>
                        </label>
                    </div>

                    <div class="col-6">
                        <input type="radio"
                               id="metode_qris"
                               name="metode_pembayaran"
                               value="QRIS"
                               class="radio-card-input"
                               data-metode-radio
                               {{ $metodeTerpilih === 'QRIS' ? 'checked' : '' }}>
                        <label for="metode_qris" class="radio-metode-card is-qris w-100">
                            <span class="d-inline-flex align-items-center gap-2">
                                <i class="bi bi-qr-code fs-4" aria-hidden="true"></i>
                                <span class="fs-4 fw-bold" style="font-weight: 800;">QRIS</span>
                                <i class="bi bi-check-lg fs-5 metode-check" aria-hidden="true"></i>
                            </span>
                            <span class="metode-sub">Bayar Scan QRIS</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Total Harga Penjualan --}}
            <div class="mt-4 p-3 p-sm-4 warung-cream-box d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="fw-bold" style="color: var(--warung-muted);">Total Harga Penjualan</div>
                    <div class="fw-semibold" style="font-size: 0.95rem; color: var(--warung-muted);" data-rincian-tagihan>
                        {{ $porsiTerpilih }} porsi &times; {{ \App\Support\FormatUang::rupiah($hargaAktif) }}
                    </div>
                </div>
                <div class="h2 fw-bold font-mono-num mb-0" style="font-weight: 800;" data-total-tagihan>
                    {{ \App\Support\FormatUang::rupiah($porsiTerpilih * $hargaAktif) }}
                </div>
            </div>

            {{-- Pesan Validasi / Sukses --}}
            @if ($errors->any())
                <div role="alert" class="mt-3 warung-alert-error d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-circle fs-4 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('success') && session('form_feedback'))
                <div role="status" class="mt-3 warung-alert-success d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle fs-4 flex-shrink-0" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Tombol Simpan Penjualan --}}
            <div class="mt-3">
                <button type="submit" class="btn-simpan-utama">
                    <i class="bi bi-check-circle fs-4" aria-hidden="true"></i>
                    <span>Simpan Penjualan</span>
                </button>
            </div>
        </form>
    </section>
@endsection
