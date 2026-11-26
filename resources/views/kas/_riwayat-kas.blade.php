<div class="card warung-card">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h2 class="h4 fw-bold mb-0">{{ $judulDaftar }}</h2>
            <span class="badge bg-light text-dark border fs-6 py-2 px-3">
                Hari Ini:
                <strong class="tabular-angka {{ $jenis === 'pengeluaran' ? 'text-danger' : 'text-success' }}">
                    {{ \App\Support\FormatUang::rupiah($totalHariIni) }}
                </strong>
            </span>
        </div>

        @if ($riwayat->isEmpty())
            <div class="empty-state-box py-4">
                <i class="bi bi-journal-x fs-1 text-muted d-block mb-2" aria-hidden="true"></i>
                <div class="fs-5 fw-bold text-muted">Data belum tersedia</div>
                <p class="mb-0 text-muted mt-1">Catatan yang Anda simpan akan tampil di daftar ini.</p>
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach ($riwayat as $item)
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                            <div>
                                <span class="badge {{ $jenis === 'pengeluaran' ? 'text-bg-danger' : 'text-bg-success' }} fs-6 mb-1">
                                    {{ $item->kategori }}
                                </span>
                                <div class="fw-bold fs-4 tabular-angka {{ $jenis === 'pengeluaran' ? 'text-danger' : 'text-success' }}">
                                    {{ \App\Support\FormatUang::rupiah($item->nominal) }}
                                </div>
                                <div class="text-dark mt-1">
                                    {{ $item->keterangan ?: 'Tanpa keterangan tambahan' }}
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>
                                    {{ $item->tanggal->translatedFormat('d M Y') }}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route($jenis . '.edit', $item) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    Ubah
                                </a>
                                <button type="button"
                                        class="btn btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKonfirmasiHapus"
                                        data-delete-action="{{ route($jenis . '.destroy', $item) }}"
                                        data-delete-detail="{{ $item->kategori }} - {{ \App\Support\FormatUang::rupiah($item->nominal) }}">
                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($riwayat->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $riwayat->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
