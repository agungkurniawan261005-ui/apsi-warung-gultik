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
     * Halaman utama: Catat Penjualan & Metode Pembayaran (UC-01 & UC-02).
     */
    public function create(): View
    {
        $today = Carbon::today();
        $ringkasanHariIni = Penjualan::hitungRekap($today, $today);
        $penjualanTerbaru = Penjualan::query()
            ->dalamRentangTanggal($today, $today)
            ->orderByDesc('waktu_transaksi')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('penjualan.create', [
            'hargaDefault' => config('warung.harga_porsi_default'),
            'presetPorsi' => config('warung.preset_porsi'),
            'metodePembayaran' => config('warung.metode_pembayaran'),
            'ringkasanHariIni' => $ringkasanHariIni,
            'penjualanTerbaru' => $penjualanTerbaru,
        ]);
    }

    /**
     * Simpan transaksi penjualan baru dan hitung total harga di server.
     */
    public function store(StorePenjualanRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $jumlahPorsi = (int) $validated['jumlah_porsi'];
        $hargaSatuan = (int) ($validated['harga_satuan'] ?? config('warung.harga_porsi_default'));
        $totalHarga = $jumlahPorsi * $hargaSatuan;

        $penjualan = Penjualan::create([
            'jumlah_porsi' => $jumlahPorsi,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'waktu_transaksi' => now(),
        ]);

        return redirect()
            ->route('penjualan.create')
            ->with(
                'success',
                "Penjualan {$penjualan->jumlah_porsi} porsi (" . FormatUang::rupiah($penjualan->total_harga) . " - {$penjualan->metode_pembayaran}) berhasil dicatat!"
            );
    }

    /**
     * Riwayat Penjualan beserta filter periode & metode pembayaran (UC-06).
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
