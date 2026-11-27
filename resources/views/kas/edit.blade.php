@extends('layouts.app')

@section('title', 'Ubah ' . $jenisLabel . ' - Warung Gultik Nusantara')

@section('content')
    @php
        $kategoriTerpilih = old('kategori', $record->kategori);
        $nominalTerisi = old('nominal', $record->nominal);
        $tanggalTerisi = old('tanggal', $record->tanggal->toDateString());
        $keteranganTerisi = old('keterangan', $record->keterangan);
    @endphp

    <section class="warung-panel p-3 p-sm-4">
        <div class="warung-panel-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h4 fw-bold mb-1" style="font-weight: 800;">Ubah Catatan {{ $jenisLabel }}</h2>
                <p class="mb-0" style="color: var(--warung-muted);">Perbaiki kategori, nominal, tanggal, atau keterangan catatan kas.</p>
            </div>
            <a href="{{ $routeBatal }}" class="btn btn-outline-secondary fw-bold px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ $routeUpdate }}" novalidate data-kas-form>
            @csrf
            @method('PUT')

            <div class="mt-4">
                <label for="kategori" class="h5 fw-bold mb-2 d-block">Kategori</label>
                <select id="kategori" name="kategori" class="form-select warung-input-lg">
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ $kategoriTerpilih === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label for="input-nominal-kas" class="h5 fw-bold mb-2 d-block">Nominal (Rupiah)</label>
                <div class="row g-2 mb-3">
                    @foreach ($presetNominal as $nilai)
                        <div class="col-6 col-sm-3">
                            <button type="button" class="btn-nominal-preset font-mono-num w-100" data-nominal-set="{{ $nilai }}">
                                {{ \App\Support\FormatUang::rupiah($nilai) }}
                            </button>
                        </div>
                    @endforeach
                </div>
                <div class="position-relative">
                    <span aria-hidden="true" class="position-absolute top-50 translate-middle-y fw-bold fs-4" style="left: 1rem; color: var(--warung-muted);">Rp</span>
                    <input type="number"
                           id="input-nominal-kas"
                           name="nominal"
                           min="1"
                           value="{{ $nominalTerisi }}"
                           class="form-control warung-input-lg font-mono-num fw-bold fs-4"
                           style="padding-left: 3.35rem; min-height: 60px;"
                           data-input-nominal>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-12 col-sm-5">
                    <label for="tanggal" class="h5 fw-bold mb-2 d-block">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ $tanggalTerisi }}" class="form-control warung-input-lg">
                </div>
                <div class="col-12 col-sm-7">
                    <label for="input-keterangan-kas" class="h5 fw-bold mb-2 d-block">Keterangan</label>
                    <input type="text" id="input-keterangan-kas" name="keterangan" maxlength="255" value="{{ $keteranganTerisi }}" class="form-control warung-input-lg" data-input-keterangan>
                </div>
            </div>

            @if ($errors->any())
                <div role="alert" class="mt-4 warung-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 d-flex flex-column flex-sm-row gap-2">
                <a href="{{ $routeBatal }}" class="btn btn-outline-secondary fw-bold flex-fill d-flex align-items-center justify-content-center" style="min-height: 58px; border-radius: 0.75rem;">
                    Batal
                </a>
                <button type="submit" class="btn-simpan-utama flex-fill {{ $jenis === 'pengeluaran' ? 'is-pengeluaran' : 'is-pemasukan' }}">
                    <i class="bi bi-check-circle fs-4" aria-hidden="true"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </section>
@endsection
