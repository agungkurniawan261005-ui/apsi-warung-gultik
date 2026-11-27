<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DataDemoController extends Controller
{
    /**
     * Mengisi data contoh ke 3 tabel utama agar pemilik warung bisa langsung mencoba laporan.
     */
    public function isiContoh(): RedirectResponse
    {
        $sekarang = Carbon::now();
        $hariIni = $sekarang->toDateString();
        $kemarin = $sekarang->copy()->subDay();
        $tanggalKemarin = $kemarin->toDateString();

        DB::transaction(function () use ($sekarang, $hariIni, $kemarin, $tanggalKemarin): void {
            Penjualan::query()->delete();
            Pemasukan::query()->delete();
            Pengeluaran::query()->delete();

            Penjualan::insert([
                [
                    'jumlah_porsi' => 4,
                    'harga_satuan' => 15000,
                    'total_harga' => 60000,
                    'metode_pembayaran' => 'QRIS',
                    'waktu_transaksi' => $sekarang->copy()->subMinutes(25),
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'jumlah_porsi' => 1,
                    'harga_satuan' => 15000,
                    'total_harga' => 15000,
                    'metode_pembayaran' => 'Tunai',
                    'waktu_transaksi' => $sekarang->copy()->subMinutes(60),
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'jumlah_porsi' => 3,
                    'harga_satuan' => 15000,
                    'total_harga' => 45000,
                    'metode_pembayaran' => 'QRIS',
                    'waktu_transaksi' => $sekarang->copy()->subMinutes(120),
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'jumlah_porsi' => 2,
                    'harga_satuan' => 15000,
                    'total_harga' => 30000,
                    'metode_pembayaran' => 'Tunai',
                    'waktu_transaksi' => $sekarang->copy()->subMinutes(180),
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'jumlah_porsi' => 5,
                    'harga_satuan' => 15000,
                    'total_harga' => 75000,
                    'metode_pembayaran' => 'Tunai',
                    'waktu_transaksi' => $kemarin->copy()->setTime(20, 15),
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
            ]);

            Pemasukan::insert([
                [
                    'kategori' => 'Pendapatan Lain-lain',
                    'nominal' => 40000,
                    'keterangan' => 'Titipan es teh & kerupuk',
                    'tanggal' => $hariIni,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'kategori' => 'Pendapatan Lain-lain',
                    'nominal' => 50000,
                    'keterangan' => 'Pesanan tambahan sate telur puyuh',
                    'tanggal' => $tanggalKemarin,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
            ]);

            Pengeluaran::insert([
                [
                    'kategori' => 'Belanja Bahan',
                    'nominal' => 50000,
                    'keterangan' => 'Beli daging sapi & jeroan',
                    'tanggal' => $hariIni,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'kategori' => 'Operasional/Gas',
                    'nominal' => 22000,
                    'keterangan' => 'Gas elpiji 3 kg',
                    'tanggal' => $hariIni,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
                [
                    'kategori' => 'Belanja Bahan',
                    'nominal' => 30000,
                    'keterangan' => 'Beras, santan & bumbu gultik',
                    'tanggal' => $tanggalKemarin,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ],
            ]);
        });

        session(['harga_porsi_aktif' => config('warung.harga_porsi_default')]);

        return redirect()
            ->back()
            ->with('success', 'Data contoh berhasil diisi ke penyimpanan Penjualan, Pemasukan, dan Pengeluaran.');
    }

    /**
     * Menghapus seluruh catatan dari 3 tabel untuk reset data.
     */
    public function reset(): RedirectResponse
    {
        DB::transaction(function (): void {
            Penjualan::query()->delete();
            Pemasukan::query()->delete();
            Pengeluaran::query()->delete();
        });

        session()->forget('harga_porsi_aktif');

        return redirect()
            ->back()
            ->with('success', 'Semua data penjualan, pemasukan, dan pengeluaran telah direset.');
    }
}
