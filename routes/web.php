<?php

use App\Http\Controllers\BaseKasController;
use App\Http\Controllers\DataDemoController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Menu 1: Jualan (Catat Penjualan & Metode Pembayaran - UC-01 & UC-02)
Route::get('/', [PenjualanController::class, 'create'])->name('penjualan.create');
Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

// Menu 4: Riwayat Penjualan (UC-06) + Koreksi & Hapus
Route::get('/riwayat-penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/penjualan/{penjualan}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update');
Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

// Menu 2: Catat Kas (Pengeluaran Operasional D3 & Pemasukan Tambahan D2 - UC-03)
Route::get('/catat-kas', function (Request $request) {
    $jenis = $request->query('jenis') === 'pemasukan' ? 'pemasukan' : 'pengeluaran';

    return BaseKasController::renderKasPage($jenis);
})->name('kas.index');

Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
Route::get('/pengeluaran/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
Route::put('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
Route::post('/pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store');
Route::get('/pemasukan/{pemasukan}/edit', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
Route::put('/pemasukan/{pemasukan}', [PemasukanController::class, 'update'])->name('pemasukan.update');
Route::delete('/pemasukan/{pemasukan}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');

// Menu 3: Rekap & Keuangan (UC-04 & UC-05)
Route::get('/rekap', [LaporanController::class, 'rekap'])->name('laporan.rekap');
Route::get('/rekap-penjualan', [LaporanController::class, 'rekapPenjualan'])->name('laporan.rekap-penjualan');
Route::get('/laporan-keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');

// Pengelolaan Data Contoh & Reset Data (Sidebar)
Route::post('/data-contoh', [DataDemoController::class, 'isiContoh'])->name('data.contoh');
Route::delete('/data-reset', [DataDemoController::class, 'reset'])->name('data.reset');
