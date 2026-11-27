<?php

namespace Tests\Feature;

use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_catat_penjualan_dapat_dibuka_dengan_navigasi_empat_menu(): void
    {
        $response = $this->get(route('penjualan.create'));

        $response->assertStatus(200);
        $response->assertSee('Warung Gultik Nusantara');
        $response->assertSee('Catat Penjualan');
        $response->assertSee('TUNAI');
        $response->assertSee('QRIS');
        $response->assertSee('Jualan');
        $response->assertSee('Catat Kas');
        $response->assertSee('Rekap');
        $response->assertSee('Riwayat');
    }

    public function test_uc01_dan_uc02_simpan_penjualan_menghitung_total_harga_otomatis(): void
    {
        $response = $this->post(route('penjualan.store'), [
            'jumlah_porsi' => 3,
            'metode_pembayaran' => 'Tunai',
        ]);

        $response->assertRedirect(route('penjualan.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('penjualans', [
            'jumlah_porsi' => 3,
            'harga_satuan' => 15000,
            'total_harga' => 45000,
            'metode_pembayaran' => 'Tunai',
        ]);
    }

    public function test_simpan_penjualan_dengan_ubah_harga_dan_qris(): void
    {
        $response = $this->post(route('penjualan.store'), [
            'jumlah_porsi' => 2,
            'harga_satuan' => '18.000',
            'metode_pembayaran' => 'QRIS',
        ]);

        $response->assertRedirect(route('penjualan.create'));
        $this->assertDatabaseHas('penjualans', [
            'jumlah_porsi' => 2,
            'harga_satuan' => 18000,
            'total_harga' => 36000,
            'metode_pembayaran' => 'QRIS',
        ]);
    }

    public function test_validasi_wajib_jumlah_porsi_kosong_atau_nol(): void
    {
        $responseKosong = $this->from(route('penjualan.create'))->post(route('penjualan.store'), [
            'jumlah_porsi' => '',
            'metode_pembayaran' => 'Tunai',
        ]);
        $responseKosong->assertRedirect(route('penjualan.create'));
        $responseKosong->assertSessionHasErrors(['jumlah_porsi' => 'Jumlah porsi harus diisi']);

        $responseNol = $this->from(route('penjualan.create'))->post(route('penjualan.store'), [
            'jumlah_porsi' => 0,
            'metode_pembayaran' => 'QRIS',
        ]);
        $responseNol->assertSessionHasErrors(['jumlah_porsi' => 'Jumlah porsi harus diisi']);

        $responseMinus = $this->from(route('penjualan.create'))->post(route('penjualan.store'), [
            'jumlah_porsi' => -2,
            'metode_pembayaran' => 'Tunai',
        ]);
        $responseMinus->assertSessionHasErrors(['jumlah_porsi' => 'Jumlah porsi harus diisi']);
    }

    public function test_validasi_wajib_metode_pembayaran_belum_dipilih(): void
    {
        $response = $this->from(route('penjualan.create'))->post(route('penjualan.store'), [
            'jumlah_porsi' => 2,
        ]);

        $response->assertRedirect(route('penjualan.create'));
        $response->assertSessionHasErrors(['metode_pembayaran' => 'Pilih metode pembayaran']);
    }

    public function test_uc06_riwayat_penjualan_kosong_menampilkan_pesan_ramah(): void
    {
        $response = $this->get(route('penjualan.index'));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada riwayat penjualan');
    }

    public function test_uc06_riwayat_penjualan_tampil_filter_ubah_dan_hapus(): void
    {
        $tunai = Penjualan::create([
            'jumlah_porsi' => 2,
            'harga_satuan' => 15000,
            'total_harga' => 30000,
            'metode_pembayaran' => 'Tunai',
            'waktu_transaksi' => Carbon::now(),
        ]);

        $qris = Penjualan::create([
            'jumlah_porsi' => 4,
            'harga_satuan' => 15000,
            'total_harga' => 60000,
            'metode_pembayaran' => 'QRIS',
            'waktu_transaksi' => Carbon::now(),
        ]);

        $this->get(route('penjualan.index', ['metode' => 'QRIS']))
            ->assertStatus(200)
            ->assertSee('4 Porsi')
            ->assertDontSee('2 Porsi')
            ->assertViewHas('penjualans', fn ($items) => $items->count() === 1 && $items->first()->id === $qris->id);

        $this->put(route('penjualan.update', $tunai), [
            'jumlah_porsi' => 5,
            'harga_satuan' => 15000,
            'metode_pembayaran' => 'Tunai',
        ])->assertRedirect(route('penjualan.index'));

        $this->assertDatabaseHas('penjualans', [
            'id' => $tunai->id,
            'jumlah_porsi' => 5,
            'total_harga' => 75000,
        ]);

        $this->delete(route('penjualan.destroy', $qris))
            ->assertRedirect(route('penjualan.index'));

        $this->assertDatabaseMissing('penjualans', ['id' => $qris->id]);
    }
}
