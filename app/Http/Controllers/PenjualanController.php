<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Models\Penjualan;
use App\Support\FormatUang;
use App\Support\PeriodFilter;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    /**
     * Halaman utama (Menu 1: Jualan): Catat Penjualan & Metode Pembayaran (UC-01 & UC-02).
     */
    public function create(): View
    {
        return view('penjualan.create', [
            'hargaDefault' => (int) session('harga_porsi_aktif', config('warung.harga_porsi_default')),
            'presetPorsi' => config('warung.preset_porsi'),
            'presetHarga' => config('warung.preset_harga'),
            'metodePembayaran' => config('warung.metode_pembayaran'),
        ]);
    }

    /**
     * Simpan transaksi penjualan baru dan hitung total harga di server.
     */
    public function store(StorePenjualanRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $jumlahPorsi = (int) $validated['jumlah_porsi'];
        $hargaSatuan = (int) ($validated['harga_satuan'] ?? session('harga_porsi_aktif', config('warung.harga_porsi_default')));
        $totalHarga = $jumlahPorsi * $hargaSatuan;

        if (! empty($validated['harga_satuan'])) {
            session(['harga_porsi_aktif' => $hargaSatuan]);
        }

        $penjualan = Penjualan::create([
            'jumlah_porsi' => $jumlahPorsi,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'waktu_transaksi' => now(),
        ]);

        $metodeLabel = $penjualan->metode_pembayaran === 'Tunai' ? 'TUNAI' : 'QRIS';

        return redirect()
            ->route('penjualan.create')
            ->with('form_feedback', true)
            ->with(
                'success',
                "Transaksi berhasil — {$penjualan->jumlah_porsi} porsi (" . FormatUang::rupiah($penjualan->total_harga) . " · {$metodeLabel}) sudah dicatat."
            );
    }

    /**
     * Menu 4: Riwayat Penjualan beserta filter Hari Ini / Semua / Metode (UC-06).
     */
    public function index(Request $request): View
    {
        $filter = PeriodFilter::fromRequest($request, 'semua');
        $metode = (string) $request->query('metode', '');
        $metodeList = config('warung.metode_pembayaran');

        $query = Penjualan::query()->dalamPeriode($filter);

        if (in_array($metode, $metodeList, true)) {
            $query->where('metode_pembayaran', $metode);
        } else {
            $metode = '';
        }

        $penjualans = $query
            ->orderByDesc('waktu_transaksi')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('penjualan.index', [
            'penjualans' => $penjualans,
            'totalSemuaTransaksi' => Penjualan::query()->count(),
            'filter' => $filter,
            'metodeAktif' => $metode,
            'metodeList' => $metodeList,
        ]);
    }

    public function edit(Penjualan $penjualan): View
    {
        return view('penjualan.edit', [
            'penjualan' => $penjualan,
            'presetPorsi' => config('warung.preset_porsi'),
            'metodePembayaran' => config('warung.metode_pembayaran'),
        ]);
    }

    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan): RedirectResponse
    {
        $validated = $request->validated();
        $jumlahPorsi = (int) $validated['jumlah_porsi'];
        $hargaSatuan = (int) ($validated['harga_satuan'] ?? $penjualan->harga_satuan);
        $waktuTransaksi = ! empty($validated['waktu_transaksi'])
            ? Carbon::parse($validated['waktu_transaksi'])
            : $penjualan->waktu_transaksi;

        $penjualan->update([
            'jumlah_porsi' => $jumlahPorsi,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $jumlahPorsi * $hargaSatuan,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'waktu_transaksi' => $waktuTransaksi,
        ]);

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Catatan penjualan berhasil diperbarui.');
    }

    public function destroy(Penjualan $penjualan): RedirectResponse
    {
        $penjualan->delete();

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Catatan penjualan berhasil dihapus.');
    }
}
