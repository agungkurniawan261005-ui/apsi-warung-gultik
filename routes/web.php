<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;

// Halaman utama: Catat Penjualan & Metode Pembayaran (UC-01 & UC-02)
Route::get('/', [PenjualanController::class, 'create'])->name('penjualan.create');
Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

// Riwayat Penjualan + Koreksi/Hapus (UC-06)
Route::get('/riwayat-penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/penjualan/{penjualan}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update');
Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

// Catat Pengeluaran Operasional (UC-03 - D3)
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
Route::get('/pengeluaran/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
Route::put('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

// Catat Pemasukan Non-Penjualan (UC-03 - D2)
Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
Route::post('/pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store');
Route::get('/pemasukan/{pemasukan}/edit', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
Route::put('/pemasukan/{pemasukan}', [PemasukanController::class, 'update'])->name('pemasukan.update');
Route::delete('/pemasukan/{pemasukan}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');

// Rekap Penjualan (UC-04) & Laporan Keuangan Gabungan (UC-05)
Route::get('/rekap-penjualan', [LaporanController::class, 'rekapPenjualan'])->name('laporan.rekap-penjualan');
Route::get('/laporan-keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
