<?php

namespace Tests\Feature;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_catat_kas_dapat_dibuka(): void
    {
        $this->get(route('kas.index'))
            ->assertStatus(200)
            ->assertSee('Catat Pemasukan Lain & Pengeluaran')
            ->assertSee('Pengeluaran Operasional')
            ->assertSee('Pemasukan Tambahan (Non-Penjualan)');
    }

    public function test_uc03_simpan_ubah_dan_hapus_pengeluaran(): void
    {
        $response = $this->post(route('pengeluaran.store'), [
            'nominal' => '150.000',
            'keterangan' => 'Gas elpiji 3 kg',
        ]);

        $response->assertRedirect(route('pengeluaran.index'));
        $this->assertDatabaseHas('pengeluarans', [
            'kategori' => 'Operasional/Gas',
            'nominal' => 150000,
            'keterangan' => 'Gas elpiji 3 kg',
        ]);

        $pengeluaran = Pengeluaran::firstOrFail();

        $this->put(route('pengeluaran.update', $pengeluaran), [
            'kategori' => 'Belanja Bahan',
            'nominal' => 25000,
            'keterangan' => 'Beras & santan',
            'tanggal' => now()->toDateString(),
        ])->assertRedirect(route('pengeluaran.index'));

        $this->assertDatabaseHas('pengeluarans', [
            'id' => $pengeluaran->id,
            'kategori' => 'Belanja Bahan',
            'nominal' => 25000,
        ]);

        $this->delete(route('pengeluaran.destroy', $pengeluaran))
            ->assertRedirect(route('pengeluaran.index'));

        $this->assertDatabaseMissing('pengeluarans', ['id' => $pengeluaran->id]);
    }

    public function test_uc03_simpan_ubah_dan_hapus_pemasukan_non_penjualan(): void
    {
        $response = $this->post(route('pemasukan.store'), [
            'nominal' => 500000,
            'keterangan' => 'Uang kembalian / modal',
        ]);

        $response->assertRedirect(route('pemasukan.index'));
        $this->assertDatabaseHas('pemasukans', [
            'kategori' => 'Tambahan Modal',
            'nominal' => 500000,
        ]);

        $pemasukan = Pemasukan::firstOrFail();

        $this->put(route('pemasukan.update', $pemasukan), [
            'kategori' => 'Pendapatan Lain-lain',
            'nominal' => 100000,
            'keterangan' => 'Titipan minuman',
            'tanggal' => now()->toDateString(),
        ])->assertRedirect(route('pemasukan.index'));

        $this->assertDatabaseHas('pemasukans', [
            'id' => $pemasukan->id,
            'kategori' => 'Pendapatan Lain-lain',
            'nominal' => 100000,
        ]);

        $this->delete(route('pemasukan.destroy', $pemasukan), ['kembali_ke' => 'rekap'])
            ->assertRedirect(route('laporan.rekap'));

        $this->assertDatabaseMissing('pemasukans', ['id' => $pemasukan->id]);
    }

    public function test_validasi_wajib_nominal_tidak_valid_pada_pemasukan_dan_pengeluaran(): void
    {
        $this->from(route('kas.index'))->post(route('pengeluaran.store'), [
            'nominal' => '',
        ])->assertSessionHasErrors(['nominal' => 'Nominal tidak valid']);

        $this->from(route('kas.index'))->post(route('pengeluaran.store'), [
            'nominal' => 0,
        ])->assertSessionHasErrors(['nominal' => 'Nominal tidak valid']);

        $this->from(route('kas.index'))->post(route('pemasukan.store'), [
            'nominal' => -10000,
        ])->assertSessionHasErrors(['nominal' => 'Nominal tidak valid']);
    }
}
