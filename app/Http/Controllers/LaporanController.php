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
     * Menu 3: Halaman gabungan Rekap Penjualan (UC-04) & Informasi Pemasukan/Pengeluaran (UC-05).
     */
    public function rekap(Request $request): View
    {
        $filter = PeriodFilter::fromRequest($request, 'hari_ini');
        $rekap = Penjualan::hitungRekap($filter->tanggalMulai, $filter->tanggalSelesai);

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

        $totalPenjualan = $rekap['total_omzet'];
        $totalPemasukanLain = (int) $pemasukanList->sum('nominal');
        $totalPengeluaran = (int) $pengeluaranList->sum('nominal');
        $saldoBersih = $totalPenjualan + $totalPemasukanLain - $totalPengeluaran;

        $persenTunai = $totalPenjualan > 0
            ? (int) round(($rekap['tunai_omzet'] / $totalPenjualan) * 100)
            : 0;
        $persenQris = $totalPenjualan > 0 ? 100 - $persenTunai : 0;

        $isKosong = $rekap['total_transaksi'] === 0
            && $pemasukanList->isEmpty()
            && $pengeluaranList->isEmpty();

        return view('laporan.rekap', [
            'filter' => $filter,
            'rekap' => $rekap,
            'persenTunai' => $persenTunai,
            'persenQris' => $persenQris,
            'pemasukanList' => $pemasukanList,
            'pengeluaranList' => $pengeluaranList,
            'totalPenjualan' => $totalPenjualan,
            'totalPemasukanLain' => $totalPemasukanLain,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoBersih' => $saldoBersih,
            'isKosong' => $isKosong,
        ]);
    }

    public function rekapPenjualan(Request $request): View
    {
        return $this->rekap($request);
    }

    public function keuangan(Request $request): View
    {
        return $this->rekap($request);
    }
}
