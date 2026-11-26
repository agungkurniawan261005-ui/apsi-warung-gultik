<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6">
        <a href="{{ route('pengeluaran.index') }}"
           class="kas-tab-btn {{ $tabAktif === 'pengeluaran' ? 'is-pengeluaran-active shadow-sm' : '' }}">
            <i class="bi bi-arrow-up-right-circle-fill fs-3" aria-hidden="true"></i>
            <div class="text-start">
                <div>Catat Pengeluaran</div>
                <div class="small fw-normal {{ $tabAktif === 'pengeluaran' ? 'text-white-50' : 'text-muted' }}">
                    Belanja Bahan, Gas, Operasional
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6">
        <a href="{{ route('pemasukan.index') }}"
           class="kas-tab-btn {{ $tabAktif === 'pemasukan' ? 'is-pemasukan-active shadow-sm' : '' }}">
            <i class="bi bi-arrow-down-left-circle-fill fs-3" aria-hidden="true"></i>
            <div class="text-start">
                <div>Catat Pemasukan Tambahan</div>
                <div class="small fw-normal {{ $tabAktif === 'pemasukan' ? 'text-white-50' : 'text-muted' }}">
                    Tambahan Modal / Non-Penjualan
                </div>
            </div>
        </a>
    </div>
</div>
