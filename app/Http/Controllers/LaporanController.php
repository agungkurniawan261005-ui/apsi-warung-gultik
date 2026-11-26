<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Support\PeriodFilter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * UC-04: Rekap Penjualan (Total porsi, omzet, pemisahan Tunai dan QRIS).
     */
    public function rekapPenjualan(Request $request): View
    {
        $filter = PeriodFilter::fromRequest($request, 'hari_ini');
        $rekap = Penjualan::hitungRekap($filter->tanggalMulai, $filter->tanggalSelesai);

        return view('laporan.rekap-penjualan', [
            'filter' => $filter,
            'rekap' => $rekap,
        ]);
    }

    /**
     * UC-05: Informasi Pemasukan & Pengeluaran Gabungan tanpa double counting.
     */
    public function keuangan(Request $request): View
    {
        $filter = PeriodFilter::fromRequest($request, 'hari_ini');
        $rekapPenjualan = Penjualan::hitungRekap($filter->tanggalMulai, $filter->tanggalSelesai);

        $pemasukanList = Pemasukan::query()
            ->dalamPeriode($filter)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $pengeluaranList = Pengeluaran::query()
            ->dalamPeriode($filter)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $totalPenjualan = $rekapPenjualan['total_omzet'];
        $totalPemasukanLain = (int) $pemasukanList->sum('nominal');
        $totalPengeluaran = (int) $pengeluaranList->sum('nominal');
        $totalUangMasuk = $totalPenjualan + $totalPemasukanLain;
        $saldoBersih = $totalUangMasuk - $totalPengeluaran;

        $pemasukanPerKategori = $pemasukanList
            ->groupBy('kategori')
            ->map(fn ($items) => (int) $items->sum('nominal'));

        $pengeluaranPerKategori = $pengeluaranList
            ->groupBy('kategori')
            ->map(fn ($items) => (int) $items->sum('nominal'));

        $isKosong = $rekapPenjualan['total_transaksi'] === 0
            && $pemasukanList->isEmpty()
            && $pengeluaranList->isEmpty();

        return view('laporan.keuangan', [
            'filter' => $filter,
            'rekapPenjualan' => $rekapPenjualan,
            'pemasukanList' => $pemasukanList,
            'pengeluaranList' => $pengeluaranList,
            'pemasukanPerKategori' => $pemasukanPerKategori,
            'pengeluaranPerKategori' => $pengeluaranPerKategori,
            'totalPenjualan' => $totalPenjualan,
            'totalPemasukanLain' => $totalPemasukanLain,
            'totalUangMasuk' => $totalUangMasuk,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoBersih' => $saldoBersih,
            'isKosong' => $isKosong,
        ]);
    }
}
