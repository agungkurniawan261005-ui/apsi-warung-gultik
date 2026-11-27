@extends('layouts.app')

@section('title', 'Rekap & Keuangan - Warung Gultik Nusantara')

@section('content')
    <div class="d-flex flex-column gap-4">
        {{-- Filter Periode Laporan --}}
        <section aria-label="Filter Periode Laporan" class="warung-panel p-3 p-sm-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <h2 class="h5 fw-bold mb-0" style="font-weight: 800;">Pilih Periode Laporan</h2>
                <span class="fw-bold" style="color: var(--warung-muted);">
                    Menampilkan: {{ $filter->label }}
                </span>
            </div>

            <div class="row g-2 g-sm-3">
                <div class="col-4">
                    <a href="{{ route('laporan.rekap', ['periode' => 'hari_ini']) }}"
                       class="btn-periode-tab w-100 {{ $filter->periode === 'hari_ini' ? 'is-active' : '' }}">
                        Hari Ini
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('laporan.rekap', ['periode' => 'semua']) }}"
                       class="btn-periode-tab w-100 {{ $filter->periode === 'semua' ? 'is-active' : '' }}">
                        Semua Waktu
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('laporan.rekap', ['periode' => 'pilih_tanggal', 'tanggal' => $filter->inputTanggal()]) }}"
                       class="btn-periode-tab w-100 {{ $filter->periode === 'pilih_tanggal' ? 'is-active' : '' }}">
                        Pilih Tanggal
                    </a>
                </div>
            </div>

            @if ($filter->periode === 'pilih_tanggal' || $filter->periode === 'kustom')
                <form method="GET"
                      action="{{ route('laporan.rekap') }}"
                      class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center gap-3"
                      style="border-color: var(--warung-border-soft) !important;">
                    <input type="hidden" name="periode" value="pilih_tanggal">
                    <label for="filter-tanggal-laporan" class="d-inline-flex align-items-center gap-2 fw-bold mb-0">
                        <i class="bi bi-calendar3" style="color: var(--warung-green);" aria-hidden="true"></i>
                        <span>Tanggal Dipilih:</span>
                    </label>
                    <input type="date"
                           id="filter-tanggal-laporan"
                           name="tanggal"
                           value="{{ $filter->inputTanggal() }}"
                           onchange="this.form.submit()"
                           class="form-control warung-input-lg fw-bold w-auto"
                           style="min-height: 52px; background-color: var(--warung-cream);">
                    <button type="submit" class="btn fw-bold text-white px-4" style="min-height: 52px; border-radius: 0.75rem; background-color: var(--warung-green);">
                        Tampilkan
                    </button>
                </form>
            @endif
        </section>

        {{-- UC-04: Rekap Penjualan --}}
        <section aria-labelledby="judul-rekap-penjualan" class="warung-panel p-4">
            <div class="warung-panel-header">
                <h2 id="judul-rekap-penjualan" class="h4 fw-bold mb-0" style="font-weight: 800;">
                    Rekap Penjualan ({{ $filter->label }})
                </h2>
            </div>

            @if ($rekap['total_transaksi'] === 0)
                <div class="py-5 text-center">
                    <p class="h4 fw-bold mb-2" style="color: var(--warung-muted); font-weight: 800;">
                        Data belum tersedia
                    </p>
                    <p class="mb-0" style="color: var(--warung-subtle);">
                        Belum ada penjualan gultik tercatat untuk periode ini.
                    </p>
                </div>
            @else
                <div class="mt-4 d-flex flex-column gap-4">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="warung-cream-bordered p-3 p-sm-4 h-100">
                                <div class="fw-bold" style="color: var(--warung-muted);">
                                    Total Mangkok / Porsi Terjual
                                </div>
                                <div class="display-6 fw-bold font-mono-num mt-1" style="font-weight: 800;">
                                    {{ $rekap['total_porsi'] }} Porsi
                                </div>
                                <div class="fw-semibold mt-1" style="color: var(--warung-muted);">
                                    Dari {{ $rekap['total_transaksi'] }} kali transaksi
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="rekap-omzet-box p-3 p-sm-4 h-100">
                                <div class="fw-bold" style="color: var(--warung-green);">
                                    Total Pendapatan Penjualan
                                </div>
                                <div class="display-6 fw-bold font-mono-num mt-1" style="font-weight: 800; color: var(--warung-green);">
                                    {{ \App\Support\FormatUang::rupiah($rekap['total_omzet']) }}
                                </div>
                                <div class="fw-semibold mt-1" style="color: var(--warung-green-hover);">
                                    Otomatis terhitung dari penjualan
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rincian Tunai vs QRIS --}}
                    <div>
                        <h3 class="h5 fw-bold mb-3" style="font-weight: 800;">
                            Rincian Metode Pembayaran (Tunai vs QRIS)
                        </h3>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <div class="warung-cream-bordered p-3 p-sm-4 h-100">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="d-inline-flex align-items-center gap-2 fs-5 fw-bold" style="color: var(--warung-green); font-weight: 800;">
                                            <i class="bi bi-cash-stack" aria-hidden="true"></i>
                                            <span>TUNAI</span>
                                        </span>
                                        <span class="fw-bold font-mono-num" style="color: var(--warung-muted);">
                                            {{ $persenTunai }}%
                                        </span>
                                    </div>
                                    <div class="h3 fw-bold font-mono-num mt-2 mb-1" style="font-weight: 800;">
                                        {{ \App\Support\FormatUang::rupiah($rekap['tunai_omzet']) }}
                                    </div>
                                    <div class="fw-medium" style="color: var(--warung-muted);">
                                        {{ $rekap['tunai_porsi'] }} porsi &middot; {{ $rekap['tunai_transaksi'] }} transaksi
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="warung-cream-bordered p-3 p-sm-4 h-100">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="d-inline-flex align-items-center gap-2 fs-5 fw-bold" style="color: var(--warung-amber); font-weight: 800;">
                                            <i class="bi bi-qr-code" aria-hidden="true"></i>
                                            <span>QRIS</span>
                                        </span>
                                        <span class="fw-bold font-mono-num" style="color: var(--warung-muted);">
                                            {{ $persenQris }}%
                                        </span>
                                    </div>
                                    <div class="h3 fw-bold font-mono-num mt-2 mb-1" style="font-weight: 800;">
                                        {{ \App\Support\FormatUang::rupiah($rekap['qris_omzet']) }}
                                    </div>
                                    <div class="fw-medium" style="color: var(--warung-muted);">
                                        {{ $rekap['qris_porsi'] }} porsi &middot; {{ $rekap['qris_transaksi'] }} transaksi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- UC-05: Informasi Pemasukan & Pengeluaran --}}
        <section aria-labelledby="judul-ringkasan-keuangan" class="warung-panel p-4">
            <div class="warung-panel-header">
                <h2 id="judul-ringkasan-keuangan" class="h4 fw-bold mb-1" style="font-weight: 800;">
                    Informasi Pemasukan &amp; Pengeluaran
                </h2>
                <p class="mb-0" style="color: var(--warung-muted);">
                    Ringkasan gabungan penjualan, pemasukan tambahan, dan pengeluaran ({{ $filter->label }}).
                </p>
            </div>

            <div class="mt-3">
                <div class="py-3 border-bottom d-flex align-items-center justify-content-between gap-3" style="border-color: var(--warung-border-soft) !important;">
                    <div>
                        <div class="fs-5 fw-bold">Pendapatan Penjualan</div>
                        <div class="small" style="color: var(--warung-muted);">Otomatis dari transaksi jualan gultik</div>
                    </div>
                    <div class="fs-4 fw-bold font-mono-num" style="color: var(--warung-green); font-weight: 800;">
                        + {{ \App\Support\FormatUang::rupiah($totalPenjualan) }}
                    </div>
                </div>

                <div class="py-3 border-bottom d-flex align-items-center justify-content-between gap-3" style="border-color: var(--warung-border-soft) !important;">
                    <div>
                        <div class="fs-5 fw-bold">Pemasukan Lain (Non-Penjualan)</div>
                        <div class="small" style="color: var(--warung-muted);">{{ $pemasukanList->count() }} catatan pemasukan tambahan</div>
                    </div>
                    <div class="fs-4 fw-bold font-mono-num" style="color: var(--warung-green); font-weight: 800;">
                        + {{ \App\Support\FormatUang::rupiah($totalPemasukanLain) }}
                    </div>
                </div>

                <div class="py-3 d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <div class="fs-5 fw-bold">Total Pengeluaran Operasional</div>
                        <div class="small" style="color: var(--warung-muted);">{{ $pengeluaranList->count() }} catatan belanja &amp; biaya</div>
                    </div>
                    <div class="fs-4 fw-bold font-mono-num" style="color: var(--warung-red); font-weight: 800;">
                        - {{ \App\Support\FormatUang::rupiah($totalPengeluaran) }}
                    </div>
                </div>
            </div>

            {{-- Saldo Bersih Usaha --}}
            <div class="mt-3 p-4 d-flex flex-wrap align-items-center justify-content-between gap-3 {{ $saldoBersih >= 0 ? 'saldo-banner-untung' : 'saldo-banner-defisit' }}">
                <div>
                    <div class="fs-5 fw-bold opacity-90">Saldo Bersih Usaha</div>
                    <div class="small fw-medium opacity-75 mt-1">
                        {{ $saldoBersih >= 0 ? 'Pemasukan lebih besar dari pengeluaran (Untung)' : 'Pengeluaran melebihi pemasukan (Defisit)' }}
                    </div>
                </div>
                <div class="display-6 fw-bold font-mono-num mb-0" style="font-weight: 800;">
                    {{ \App\Support\FormatUang::rupiah($saldoBersih) }}
                </div>
            </div>

            {{-- Daftar Rincian Pengeluaran & Pemasukan Lain --}}
            @if ($pengeluaranList->isNotEmpty() || $pemasukanList->isNotEmpty())
                <div class="mt-4 pt-4 border-top d-flex flex-column gap-4" style="border-color: var(--warung-border-soft) !important;">
                    @if ($pengeluaranList->isNotEmpty())
                        <div>
                            <h3 class="h5 fw-bold d-flex align-items-center gap-2 mb-3" style="font-weight: 800;">
                                <i class="bi bi-graph-down-arrow" style="color: var(--warung-red);" aria-hidden="true"></i>
                                <span>Daftar Pengeluaran ({{ $pengeluaranList->count() }})</span>
                            </h3>
                            <ul class="list-unstyled mb-0">
                                @foreach ($pengeluaranList as $item)
                                    <li class="py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--warung-border-soft) !important;">
                                        <div>
                                            <div class="fs-5 fw-bold">{{ $item->keterangan ?: $item->kategori }}</div>
                                            <div class="small fw-medium" style="color: var(--warung-muted);">
                                                {{ $item->tanggal->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2" data-inline-confirm>
                                            <span class="fs-5 fw-bold font-mono-num" style="color: var(--warung-red); font-weight: 800;">
                                                - {{ \App\Support\FormatUang::rupiah($item->nominal) }}
                                            </span>
                                            <a href="{{ route('pengeluaran.edit', $item) }}"
                                               class="btn btn-sm btn-outline-secondary fw-bold px-3 py-2 rounded-3"
                                               aria-label="Ubah pengeluaran {{ $item->keterangan }}">
                                                Ubah
                                            </a>
                                            <button type="button"
                                                    class="btn-hapus-icon"
                                                    aria-label="Hapus pengeluaran {{ $item->keterangan }}"
                                                    data-confirm-trigger>
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                            <form method="POST" action="{{ route('pengeluaran.destroy', $item) }}" class="d-none d-inline-flex gap-1" data-confirm-box>
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="kembali_ke" value="rekap">
                                                <input type="hidden" name="periode" value="{{ $filter->periode }}">
                                                <input type="hidden" name="tanggal" value="{{ $filter->inputTanggal() }}">
                                                <button type="submit" class="btn-yakin-hapus">Yakin Hapus?</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary fw-bold rounded-3" data-confirm-cancel>Batal</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($pemasukanList->isNotEmpty())
                        <div>
                            <h3 class="h5 fw-bold d-flex align-items-center gap-2 mb-3" style="font-weight: 800;">
                                <i class="bi bi-graph-up-arrow" style="color: var(--warung-green);" aria-hidden="true"></i>
                                <span>Daftar Pemasukan Lain ({{ $pemasukanList->count() }})</span>
                            </h3>
                            <ul class="list-unstyled mb-0">
                                @foreach ($pemasukanList as $item)
                                    <li class="py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--warung-border-soft) !important;">
                                        <div>
                                            <div class="fs-5 fw-bold">{{ $item->keterangan ?: $item->kategori }}</div>
                                            <div class="small fw-medium" style="color: var(--warung-muted);">
                                                {{ $item->tanggal->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2" data-inline-confirm>
                                            <span class="fs-5 fw-bold font-mono-num" style="color: var(--warung-green); font-weight: 800;">
                                                + {{ \App\Support\FormatUang::rupiah($item->nominal) }}
                                            </span>
                                            <a href="{{ route('pemasukan.edit', $item) }}"
                                               class="btn btn-sm btn-outline-secondary fw-bold px-3 py-2 rounded-3"
                                               aria-label="Ubah pemasukan {{ $item->keterangan }}">
                                                Ubah
                                            </a>
                                            <button type="button"
                                                    class="btn-hapus-icon"
                                                    aria-label="Hapus pemasukan {{ $item->keterangan }}"
                                                    data-confirm-trigger>
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                            <form method="POST" action="{{ route('pemasukan.destroy', $item) }}" class="d-none d-inline-flex gap-1" data-confirm-box>
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="kembali_ke" value="rekap">
                                                <input type="hidden" name="periode" value="{{ $filter->periode }}">
                                                <input type="hidden" name="tanggal" value="{{ $filter->inputTanggal() }}">
                                                <button type="submit" class="btn-yakin-hapus">Yakin Hapus?</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary fw-bold rounded-3" data-confirm-cancel>Batal</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection
