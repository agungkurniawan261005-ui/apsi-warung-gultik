@php
    $isEdit = isset($record);
    $kategoriTerpilih = old('kategori', $isEdit ? $record->kategori : '');
    $nominalTerisi = old('nominal', $isEdit ? $record->nominal : '');
    $tanggalTerisi = old('tanggal', $isEdit ? $record->tanggal->toDateString() : now()->toDateString());
    $keteranganTerisi = old('keterangan', $isEdit ? $record->keterangan : '');
    $radioTheme = $jenis === 'pengeluaran' ? 'is-pengeluaran' : 'is-pemasukan';
    $colKategori = count($kategoriList) === 2 ? 'col-6' : 'col-12 col-sm-4';
@endphp

<form method="POST" action="{{ $formAction }}" novalidate data-kas-form>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- 1. Pilih Kategori --}}
    <div class="mb-4">
        <span class="form-label fs-5 fw-bold d-block mb-2">
            1. Pilih Kategori {{ $jenis === 'pengeluaran' ? 'Pengeluaran' : 'Pemasukan Tambahan' }}
        </span>
        <div class="row g-2">
            @foreach ($kategoriList as $idx => $kategori)
                <div class="{{ $colKategori }}">
                    <input type="radio"
                           id="kategori_{{ $jenis }}_{{ $idx }}"
                           name="kategori"
                           value="{{ $kategori }}"
                           class="pilihan-radio-input"
                           {{ $kategoriTerpilih === $kategori ? 'checked' : '' }}>
                    <label for="kategori_{{ $jenis }}_{{ $idx }}"
                           class="pilihan-radio-card is-compact {{ $radioTheme }} w-100">
                        <span class="fs-5 fw-bold">{{ $kategori }}</span>
                    </label>
                </div>
            @endforeach
        </div>
        @error('kategori')
            <div class="text-danger fw-bold fs-5 mt-2">
                <i class="bi bi-exclamation-circle-fill me-1" aria-hidden="true"></i>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- 2. Nominal dengan Tombol Cepat --}}
    <div class="mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <label for="nominal" class="form-label fs-5 fw-bold mb-0">2. Nominal (Rupiah)</label>
            <span class="fs-5 fw-bold tabular-angka {{ $jenis === 'pengeluaran' ? 'text-danger' : 'text-success' }}"
                  data-nominal-preview>
                Rp 0
            </span>
        </div>

        <div class="row g-2 mb-2">
            @foreach ($presetNominal as $angkaPreset)
                <div class="col-6 col-sm-4">
                    <button type="button"
                            class="btn btn-nominal-quick w-100 tabular-angka"
                            data-nominal-set="{{ $angkaPreset }}">
                        {{ \App\Support\FormatUang::rupiah($angkaPreset) }}
                    </button>
                </div>
            @endforeach
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="btn btn-outline-dark" data-nominal-add="5000">+ Rp 5.000</button>
            <button type="button" class="btn btn-outline-dark" data-nominal-add="10000">+ Rp 10.000</button>
            <button type="button" class="btn btn-outline-secondary ms-auto" data-nominal-reset>
                <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                Reset
            </button>
        </div>

        <div class="input-group input-group-lg">
            <span class="input-group-text fw-bold fs-4">Rp</span>
            <input type="number"
                   id="nominal"
                   name="nominal"
                   min="1"
                   inputmode="numeric"
                   placeholder="Masukkan nominal..."
                   value="{{ $nominalTerisi }}"
                   class="form-control form-control-lg fw-bold fs-3 tabular-angka @error('nominal') is-invalid @enderror"
                   data-input-nominal>
        </div>
        @error('nominal')
            <div class="text-danger fw-bold fs-5 mt-2">
                <i class="bi bi-exclamation-circle-fill me-1" aria-hidden="true"></i>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- 3. Tanggal & Keterangan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-5">
            <label for="tanggal" class="form-label fs-5 fw-bold">3. Tanggal</label>
            <input type="date"
                   id="tanggal"
                   name="tanggal"
                   value="{{ $tanggalTerisi }}"
                   class="form-control form-control-lg @error('tanggal') is-invalid @enderror">
            @error('tanggal')
                <div class="text-danger fw-bold mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-7">
            <label for="keterangan" class="form-label fs-5 fw-bold">4. Keterangan (Opsional)</label>
            <input type="text"
                   id="keterangan"
                   name="keterangan"
                   maxlength="255"
                   placeholder="Contoh: Beli daging / Gas LPG..."
                   value="{{ $keteranganTerisi }}"
                   class="form-control form-control-lg @error('keterangan') is-invalid @enderror"
                   data-input-keterangan>
            @error('keterangan')
                <div class="text-danger fw-bold mt-2">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Pilihan Cepat Keterangan --}}
    @if (! empty($presetKeterangan))
        <div class="mb-4">
            <div class="small fw-semibold text-muted mb-2">Pilihan cepat keterangan (ketuk untuk mengisi):</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($presetKeterangan as $teksKeterangan)
                    <button type="button"
                            class="btn btn-outline-secondary btn-chip-keterangan"
                            data-keterangan-preset="{{ $teksKeterangan }}">
                        {{ $teksKeterangan }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row gap-3">
        @if ($isEdit && isset($routeBatal))
            <a href="{{ $routeBatal }}" class="btn btn-outline-secondary btn-lg flex-fill">
                Batal
            </a>
        @endif
        <button type="submit" class="btn btn-warung-simpan flex-fill fw-bold shadow-sm">
            <i class="bi bi-check2-circle me-2" aria-hidden="true"></i>
            {{ $submitLabel }}
        </button>
    </div>
</form>
