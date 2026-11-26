<?php

namespace Tests\Feature;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_uc04_dan_uc05_menampilkan_data_belum_tersedia_saat_kosong(): void
    {
        $this->get(route('laporan.rekap-penjualan'))
            ->assertStatus(200)
            ->assertSee('Data belum tersedia');

        $this->get(route('laporan.keuangan'))
            ->assertStatus(200)
            ->assertSee('Data belum tersedia');
    }

    public function test_uc04_rekap_penjualan_memisahkan_tunai_dan_qris(): void
    {
        Penjualan::create([
            'jumlah_porsi' => 3,
            'harga_satuan' => 15000,
            'total_harga' => 45000,
            'metode_pembayaran' => 'Tunai',
            'waktu_transaksi' => Carbon::now(),
        ]);

        Penjualan::create([
            'jumlah_porsi' => 2,
            'harga_satuan' => 15000,
            'total_harga' => 30000,
            'metode_pembayaran' => 'QRIS',
            'waktu_transaksi' => Carbon::now(),
        ]);

        // Data bulan lalu tidak ikut hitungan filter default Hari Ini
        Penjualan::create([
            'jumlah_porsi' => 10,
            'harga_satuan' => 15000,
            'total_harga' => 150000,
            'metode_pembayaran' => 'Tunai',
            'waktu_transaksi' => Carbon::now()->subDays(40),
        ]);

        $responseHariIni = $this->get(route('laporan.rekap-penjualan'));
        $responseHariIni->assertStatus(200);
        $responseHariIni->assertSee('5 Porsi');
        $responseHariIni->assertSee('Rp 75.000');
        $responseHariIni->assertSee('Rp 45.000');
        $responseHariIni->assertSee('Rp 30.000');

        $responseSemua = $this->get(route('laporan.rekap-penjualan', ['periode' => 'semua']));
        $responseSemua->assertStatus(200);
        $responseSemua->assertSee('15 Porsi');
        $responseSemua->assertSee('Rp 225.000');
    }

    public function test_uc05_laporan_keuangan_gabungan_otomatis_tanpa_double_counting_dan_filter_tanggal(): void
    {
        $hariIni = Carbon::today();
        $kemarinLusa = Carbon::today()->subDays(10);

        Penjualan::create([
            'jumlah_porsi' => 4,
            'harga_satuan' => 15000,
            'total_harga' => 60000,
            'metode_pembayaran' => 'Tunai',
            'waktu_transaksi' => $hariIni->copy()->setHour(12),
        ]);

        Pemasukan::create([
            'kategori' => 'Tambahan Modal',
            'nominal' => 200000,
            'keterangan' => 'Modal kasir',
            'tanggal' => $hariIni->toDateString(),
        ]);

        Pengeluaran::create([
            'kategori' => 'Belanja Bahan',
            'nominal' => 100000,
            'keterangan' => 'Daging dan bumbu',
            'tanggal' => $hariIni->toDateString(),
        ]);

        // Data di luar rentang Hari Ini
        Penjualan::create([
            'jumlah_porsi' => 5,
            'harga_satuan' => 15000,
            'total_harga' => 75000,
            'metode_pembayaran' => 'QRIS',
            'waktu_transaksi' => $kemarinLusa->copy()->setHour(19),
        ]);

        $response = $this->get(route('laporan.keuangan'));
        $response->assertStatus(200);
        // 60.000 (penjualan) + 200.000 (pemasukan non-penjualan) - 100.000 (pengeluaran) = 160.000
        $response->assertSee('Rp 60.000');
        $response->assertSee('Rp 200.000');
        $response->assertSee('Rp 100.000');
        $response->assertSee('Rp 160.000');

        // Cek filter rentang tanggal kustom untuk hari kemarin lusa saja
        $responseKustom = $this->get(route('laporan.keuangan', [
            'periode' => 'kustom',
            'tanggal_mulai' => $kemarinLusa->toDateString(),
            'tanggal_selesai' => $kemarinLusa->toDateString(),
        ]));
        $responseKustom->assertStatus(200);
        $responseKustom->assertSee('Rp 75.000');
        $responseKustom->assertDontSee('Modal kasir');
    }
}
