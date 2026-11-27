@extends('layouts.app')

@section('title', 'Catat Pemasukan & Pengeluaran - Warung Gultik Nusantara')

@section('content')
    @php
        $jenisSaatIni = old('jenis_kas', $jenisAktif ?? 'pengeluaran');
        $formAction = $jenisSaatIni === 'pemasukan' ? route('pemasukan.store') : route('pengeluaran.store');
        $nominalTerisi = old('nominal', '');
        $keteranganTerisi = old('keterangan', '');
    @endphp

    <section aria-labelledby="judul-catat-kas" class="warung-panel p-3 p-sm-4">
        <div class="warung-panel-header">
            <h2 id="judul-catat-kas" class="h4 fw-bold mb-1" style="font-weight: 800;">
                Catat Pemasukan Lain &amp; Pengeluaran
            </h2>
            <p class="mb-0" style="color: var(--warung-muted); font-size: 1rem;">
                Catat belanja bahan, gas, atau pemasukan di luar jualan mangkok.
            </p>
        </div>

        <form method="POST" action="{{ $formAction }}" novalidate data-kas-form>
            @csrf

            {{-- Bagian 1: Pilih Jenis Catatan --}}
            <div class="mt-4">
                <div class="h5 fw-bold mb-3">1. Pilih Jenis Catatan</div>

                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <input type="radio"
                               id="jenis_pengeluaran"
                               name="jenis_kas"
                               value="pengeluaran"
                               class="radio-card-input"
                               data-kas-jenis
                               data-action-url="{{ route('pengeluaran.store') }}"
                               {{ $jenisSaatIni === 'pengeluaran' ? 'checked' : '' }}>
                        <label for="jenis_pengeluaran" class="radio-kas-card is-pengeluaran w-100">
                            <span class="d-flex align-items-center gap-3">
                                <i class="bi bi-arrow-up-circle fs-2 flex-shrink-0" aria-hidden="true"></i>
                                <span>
                                    <span class="d-block fs-5 fw-bold" style="font-weight: 800; line-height: 1.25;">
                                        Pengeluaran Operasional
                                    </span>
                                    <span class="d-block kas-sub mt-1">
                                        Beli bahan, gas, bumbu, plastik
                                    </span>
                                </span>
                            </span>
                            <i class="bi bi-check-lg fs-4 flex-shrink-0 kas-check" aria-hidden="true"></i>
                        </label>
                    </div>

                    <div class="col-12 col-sm-6">
                        <input type="radio"
                               id="jenis_pemasukan"
                               name="jenis_kas"
                               value="pemasukan"
                               class="radio-card-input"
                               data-kas-jenis
                               data-action-url="{{ route('pemasukan.store') }}"
                               {{ $jenisSaatIni === 'pemasukan' ? 'checked' : '' }}>
                        <label for="jenis_pemasukan" class="radio-kas-card is-pemasukan w-100">
                            <span class="d-flex align-items-center gap-3">
                                <i class="bi bi-arrow-down-circle fs-2 flex-shrink-0" aria-hidden="true"></i>
                                <span>
                                    <span class="d-block fs-5 fw-bold" style="font-weight: 800; line-height: 1.25;">
                                        Pemasukan Tambahan (Non-Penjualan)
                                    </span>
                                    <span class="d-block kas-sub mt-1">
                                        Titipan minuman atau pemasukan lain
                                    </span>
                                </span>
                            </span>
                            <i class="bi bi-check-lg fs-4 flex-shrink-0 kas-check" aria-hidden="true"></i>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Pilih atau Isi Nominal --}}
            <div class="mt-4 pt-2">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                    <label for="input-nominal-kas" class="h5 fw-bold mb-0">
                        2. Pilih atau Isi Nominal
                    </label>
                    <button type="button"
                            class="btn btn-link fw-bold text-decoration-none px-3 py-2 d-none"
                            style="color: var(--warung-muted);"
                            data-nominal-reset>
                        <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>
                        Kosongkan
                    </button>
                </div>

                <div class="row g-2 g-sm-3">
                    @foreach ($presetNominal as $nilai)
                        <div class="col-6 col-sm-3">
                            <button type="button"
                                    class="btn-nominal-preset font-mono-num w-100"
                                    data-nominal-set="{{ $nilai }}">
                                {{ \App\Support\FormatUang::rupiah($nilai) }}
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 position-relative">
                    <span aria-hidden="true"
                          class="position-absolute top-50 translate-middle-y fw-bold fs-4"
                          style="left: 1rem; color: var(--warung-muted);">
                        Rp
                    </span>
                    <input type="number"
                           id="input-nominal-kas"
                           name="nominal"
                           inputmode="numeric"
                           min="0"
                           step="1000"
                           value="{{ $nominalTerisi }}"
                           placeholder="Ketik nominal jika berbeda, misal 35000"
                           class="form-control warung-input-lg font-mono-num fw-bold fs-4"
                           style="padding-left: 3.35rem; min-height: 60px;"
                           data-input-nominal>
                </div>
            </div>

            {{-- Bagian 3: Keterangan Singkat (Opsional) --}}
            <div class="mt-4 pt-2">
                <label for="input-keterangan-kas" class="h5 fw-bold d-block mb-3">
                    3. Keterangan Singkat (Opsional)
                </label>

                <div class="d-flex flex-wrap gap-2 mb-3 {{ $jenisSaatIni === 'pengeluaran' ? '' : 'd-none' }}" data-chips-pengeluaran>
                    @foreach ($presetKeteranganPengeluaran as $item)
                        <button type="button" class="btn-chip-ket" data-keterangan-preset="{{ $item }}">
                            {{ $item }}
                        </button>
                    @endforeach
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3 {{ $jenisSaatIni === 'pemasukan' ? '' : 'd-none' }}" data-chips-pemasukan>
                    @foreach ($presetKeteranganPemasukan as $item)
                        <button type="button" class="btn-chip-ket" data-keterangan-preset="{{ $item }}">
                            {{ $item }}
                        </button>
                    @endforeach
                </div>

                <input type="text"
                       id="input-keterangan-kas"
                       name="keterangan"
                       maxlength="255"
                       value="{{ $keteranganTerisi }}"
                       placeholder="Atau tulis catatan singkat di sini (boleh dikosongkan)"
                       class="form-control warung-input-lg"
                       data-input-keterangan>
            </div>

            {{-- Pesan Validasi / Sukses --}}
            @if ($errors->any())
                <div role="alert" class="mt-4 warung-alert-error d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-circle fs-3 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('success') && session('form_feedback'))
                <div role="status" class="mt-4 warung-alert-success d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle fs-3 flex-shrink-0" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Tombol Simpan Kas --}}
            <div class="mt-4">
                <button type="submit"
                        class="btn-simpan-utama {{ $jenisSaatIni === 'pemasukan' ? 'is-pemasukan' : 'is-pengeluaran' }}"
                        data-kas-submit>
                    <i class="bi bi-check-circle fs-3" aria-hidden="true"></i>
                    <span data-kas-submit-label>
                        {{ $jenisSaatIni === 'pemasukan' ? 'Simpan Pemasukan' : 'Simpan Pengeluaran' }}
                    </span>
                </button>
            </div>
        </form>
    </section>
@endsection
