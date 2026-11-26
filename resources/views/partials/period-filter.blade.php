@php
    $presets = [
        'hari_ini' => 'Hari Ini',
        'minggu_ini' => '7 Hari Terakhir',
        'bulan_ini' => 'Bulan Ini',
        'semua' => 'Semua Data',
    ];
    $extraQuery = $extraQuery ?? [];
    $showMetodeFilter = $showMetodeFilter ?? false;
    $colTanggal = $showMetodeFilter ? 'col-12 col-sm-6 col-lg-3' : 'col-12 col-sm-6 col-md-4';
    $colTombol = $showMetodeFilter ? 'col-12 col-sm-6 col-lg-3' : 'col-12 col-md-4';
@endphp

<div class="card warung-card mb-4">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h2 class="h5 fw-bold mb-1">
                    <i class="bi bi-funnel-fill me-2 text-primary-warung" aria-hidden="true"></i>
                    Pilih Periode Tanggal
                </h2>
                <div class="text-muted fs-6">
                    Menampilkan: <strong class="text-dark">{{ $filter->label }}</strong>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @foreach ($presets as $key => $label)
                    <a href="{{ route($routeName, array_merge($extraQuery, ['periode' => $key])) }}"
                       class="btn btn-lg {{ $filter->periode === $key ? 'btn-warung-primary' : 'btn-outline-dark' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <form method="GET" action="{{ route($routeName) }}" class="row g-3 align-items-end pt-3 border-top">
            <input type="hidden" name="periode" value="kustom">

            <div class="{{ $colTanggal }}">
                <label for="tanggal_mulai" class="form-label fw-bold">Dari Tanggal</label>
                <input type="date"
                       id="tanggal_mulai"
                       name="tanggal_mulai"
                       value="{{ $filter->inputMulai() }}"
                       class="form-control form-control-lg">
            </div>

            <div class="{{ $colTanggal }}">
                <label for="tanggal_selesai" class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date"
                       id="tanggal_selesai"
                       name="tanggal_selesai"
                       value="{{ $filter->inputSelesai() }}"
                       class="form-control form-control-lg">
            </div>

            @if ($showMetodeFilter)
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="filter_metode" class="form-label fw-bold">Metode Bayar</label>
                    <select id="filter_metode" name="metode" class="form-select form-select-lg">
                        <option value="">Semua Metode</option>
                        @foreach ($metodeList as $itemMetode)
                            <option value="{{ $itemMetode }}" {{ ($metodeAktif ?? '') === $itemMetode ? 'selected' : '' }}>
                                {{ $itemMetode }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="{{ $colTombol }}">
                <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold">
                    <i class="bi bi-search me-2" aria-hidden="true"></i>
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>
