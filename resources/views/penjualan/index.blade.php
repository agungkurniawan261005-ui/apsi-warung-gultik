@extends('layouts.app')

@section('title', 'Riwayat Penjualan - Warung Gultik Nusantara')

@section('content')
    <section aria-labelledby="judul-riwayat-penjualan" class="warung-panel p-4">
        <div class="warung-panel-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 id="judul-riwayat-penjualan" class="h3 fw-bold mb-1" style="font-weight: 800;">
                    Riwayat Penjualan
                </h2>
                <p class="mb-0" style="color: var(--warung-muted);">
                    Daftar transaksi tersimpan ({{ $filter->label }}).
                </p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('penjualan.index', array_filter(['periode' => 'hari_ini', 'metode' => $metodeAktif])) }}"
                   class="btn-periode-tab px-3 {{ $filter->periode === 'hari_ini' ? 'is-active' : '' }}"
                   style="min-height: 46px;">
                    Hari Ini
                </a>
                <a href="{{ route('penjualan.index', array_filter(['periode' => 'semua', 'metode' => $metodeAktif])) }}"
                   class="btn-periode-tab px-3 {{ $filter->periode === 'semua' ? 'is-active' : '' }}"
                   style="min-height: 46px;">
                    Semua ({{ $totalSemuaTransaksi }})
                </a>
            </div>
        </div>

        @if ($penjualans->isEmpty())
            <div class="py-5 text-center">
                <p class="h4 fw-bold mb-2" style="color: var(--warung-muted); font-weight: 800;">
                    Tidak ada riwayat penjualan
                </p>
                <p class="mb-0" style="color: var(--warung-subtle);">
                    @if ($totalSemuaTransaksi > 0)
                        Belum ada transaksi pada periode ini. Ketuk tombol Semua untuk melihat hari lain.
                    @else
                        Transaksi penjualan yang disimpan akan muncul di daftar ini.
                    @endif
                </p>
            </div>
        @else
            <ul class="list-unstyled mt-2 mb-0" data-testid="daftar-riwayat-penjualan">
                @foreach ($penjualans as $item)
                    <li class="py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--warung-border-soft) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-center py-2 px-3 rounded-3"
                                 style="min-width: 78px; background-color: var(--warung-cream); border: 1px solid var(--warung-border);">
                                <div class="fs-5 fw-bold font-mono-num" style="font-weight: 800;">
                                    {{ $item->waktu_transaksi->format('H:i') }}
                                </div>
                                <div class="fw-semibold" style="font-size: 0.75rem; color: var(--warung-muted);">
                                    {{ $item->waktu_transaksi->translatedFormat('d M Y') }}
                                </div>
                            </div>

                            <div>
                                <div class="fs-5 fw-bold" style="font-weight: 800;">
                                    {{ $item->jumlah_porsi }} Porsi
                                </div>
                                <div class="small fw-medium" style="color: var(--warung-muted);">
                                    &commat;{{ \App\Support\FormatUang::rupiah($item->harga_satuan) }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 ms-auto" data-inline-confirm>
                            <div class="text-end me-1">
                                <div class="fs-5 fw-bold font-mono-num" style="font-weight: 800;">
                                    {{ \App\Support\FormatUang::rupiah($item->total_harga) }}
                                </div>
                                <div class="mt-1 d-flex justify-content-end">
                                    @if ($item->metode_pembayaran === 'Tunai')
                                        <span class="badge-tunai">
                                            <i class="bi bi-cash-stack" aria-hidden="true"></i>
                                            <span>TUNAI</span>
                                        </span>
                                    @else
                                        <span class="badge-qris">
                                            <i class="bi bi-qr-code" aria-hidden="true"></i>
                                            <span>QRIS</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('penjualan.edit', $item) }}"
                               class="btn btn-sm btn-outline-secondary fw-bold px-3 py-2 rounded-3"
                               aria-label="Ubah transaksi {{ $item->jumlah_porsi }} porsi">
                                Ubah
                            </a>

                            <button type="button"
                                    class="btn-hapus-icon"
                                    aria-label="Hapus transaksi {{ $item->jumlah_porsi }} porsi jam {{ $item->waktu_transaksi->format('H:i') }}"
                                    data-confirm-trigger>
                                <i class="bi bi-trash" aria-hidden="true"></i>
                            </button>

                            <form method="POST" action="{{ route('penjualan.destroy', $item) }}" class="d-none d-inline-flex gap-1" data-confirm-box>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-yakin-hapus">Yakin Hapus?</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary fw-bold rounded-3" data-confirm-cancel>Batal</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if ($penjualans->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $penjualans->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection
