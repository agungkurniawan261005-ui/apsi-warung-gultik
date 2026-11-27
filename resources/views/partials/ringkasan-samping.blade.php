<aside aria-label="Ringkasan Hari Ini dan Pengelolaan Data" class="d-flex flex-column gap-3">
    {{-- Kartu Ringkasan Hari Ini --}}
    <div class="warung-panel p-4">
        <div class="d-flex align-items-center justify-content-between gap-2 pb-3 border-bottom" style="border-color: var(--warung-border-soft) !important;">
            <h2 class="h5 fw-bold mb-0" style="font-weight: 800;">Ringkasan Hari Ini</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-decoration-none fw-bold px-2 py-1 rounded-3" style="color: var(--warung-green);">
                Lihat Detail
            </a>
        </div>

        <div class="mt-3 d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="fw-semibold" style="color: var(--warung-muted);">Porsi Terjual</span>
                <span class="fs-4 fw-bold font-mono-num">{{ $rekapHariIni['total_porsi'] }} Porsi</span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="fw-semibold" style="color: var(--warung-muted);">Pendapatan Jualan</span>
                <span class="fs-5 fw-bold font-mono-num" style="color: var(--warung-green);">
                    {{ \App\Support\FormatUang::rupiah($rekapHariIni['total_omzet']) }}
                </span>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-2" style="font-size: 0.95rem;">
                <span class="fw-medium" style="color: var(--warung-muted);">Tunai / QRIS</span>
                <span class="fw-bold font-mono-num">
                    {{ \App\Support\FormatUang::rupiah($rekapHariIni['tunai_omzet']) }} /
                    {{ \App\Support\FormatUang::rupiah($rekapHariIni['qris_omzet']) }}
                </span>
            </div>

            <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2" style="border-color: var(--warung-border-soft) !important;">
                <span class="fw-bold">Saldo Bersih Hari Ini</span>
                <span class="fs-4 fw-bold font-mono-num" style="color: {{ $saldoBersihHariIni >= 0 ? 'var(--warung-green)' : 'var(--warung-red)' }};">
                    {{ \App\Support\FormatUang::rupiah($saldoBersihHariIni) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Kartu Penyimpanan Data (3 Tabel MySQL) --}}
    <div class="warung-panel p-4">
        <div class="d-flex align-items-center gap-2 pb-3 border-bottom" style="border-color: var(--warung-border-soft) !important;">
            <i class="bi bi-database fs-5" aria-hidden="true"></i>
            <h2 class="h5 fw-bold mb-0" style="font-weight: 800;">Penyimpanan Data</h2>
        </div>

        <div class="mt-3 d-flex flex-column gap-1" style="color: var(--warung-muted);">
            <div class="d-flex justify-content-between">
                <span>Data Penjualan</span>
                <span class="fw-bold font-mono-num text-dark">{{ $jumlahDataPenjualan }} catatan</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Data Pemasukan</span>
                <span class="fw-bold font-mono-num text-dark">{{ $jumlahDataPemasukan }} catatan</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Data Pengeluaran</span>
                <span class="fw-bold font-mono-num text-dark">{{ $jumlahDataPengeluaran }} catatan</span>
            </div>
        </div>

        <div class="mt-4 d-grid gap-2">
            <form method="POST" action="{{ route('data.contoh') }}">
                @csrf
                <button type="submit" class="btn btn-demo-isi w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-file-earmark-plus" aria-hidden="true"></i>
                    <span>Isi Data Contoh</span>
                </button>
            </form>

            <div data-inline-confirm>
                <button type="button" class="btn btn-demo-reset w-100 d-flex align-items-center justify-content-center gap-2" data-confirm-trigger>
                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                    <span>Reset Data</span>
                </button>

                <div class="p-3 rounded-3 d-none" style="background-color: var(--warung-red-soft); border: 2px solid var(--warung-red);" data-confirm-box>
                    <div class="fw-bold text-center mb-2" style="color: var(--warung-red-text);">
                        Hapus semua catatan penjualan, pemasukan, dan pengeluaran?
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn bg-white border border-2 fw-bold w-100" style="min-height: 48px;" data-confirm-cancel>
                                Batal
                            </button>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="{{ route('data.reset') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn w-100 fw-bold text-white" style="min-height: 48px; background-color: var(--warung-red);">
                                    Ya, Hapus Semua
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
