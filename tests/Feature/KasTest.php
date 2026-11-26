<?php

namespace Tests\Feature;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasTest extends TestCase
{
    use RefreshDatabase;

    public function test_uc03_simpan_ubah_dan_hapus_pengeluaran(): void
    {
        $response = $this->post(route('pengeluaran.store'), [
            'kategori' => 'Belanja Bahan',
            'nominal' => '150.000',
            'keterangan' => 'Daging Sapi 1 kg',
            'tanggal' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('pengeluaran.index'));
        $this->assertDatabaseHas('pengeluarans', [
            'kategori' => 'Belanja Bahan',
            'nominal' => 150000,
            'keterangan' => 'Daging Sapi 1 kg',
        ]);

        $pengeluaran = Pengeluaran::firstOrFail();

        $this->put(route('pengeluaran.update', $pengeluaran), [
            'kategori' => 'Operasional/Gas',
            'nominal' => 25000,
            'keterangan' => 'Gas LPG 3kg',
            'tanggal' => now()->toDateString(),
        ])->assertRedirect(route('pengeluaran.index'));

        $this->assertDatabaseHas('pengeluarans', [
            'id' => $pengeluaran->id,
            'kategori' => 'Operasional/Gas',
            'nominal' => 25000,
        ]);

        $this->delete(route('pengeluaran.destroy', $pengeluaran))
            ->assertRedirect(route('pengeluaran.index'));

        $this->assertDatabaseMissing('pengeluarans', ['id' => $pengeluaran->id]);
    }

    public function test_uc03_simpan_ubah_dan_hapus_pemasukan_non_penjualan(): void
    {
        $response = $this->post(route('pemasukan.store'), [
            'kategori' => 'Tambahan Modal',
            'nominal' => 500000,
            'keterangan' => 'Modal awal warung',
            'tanggal' => now()->toDateString(),
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
            'keterangan' => 'Penjualan kerupuk tambahan',
            'tanggal' => now()->toDateString(),
        ])->assertRedirect(route('pemasukan.index'));

        $this->assertDatabaseHas('pemasukans', [
            'id' => $pemasukan->id,
            'kategori' => 'Pendapatan Lain-lain',
            'nominal' => 100000,
        ]);

        $this->delete(route('pemasukan.destroy', $pemasukan))
            ->assertRedirect(route('pemasukan.index'));

        $this->assertDatabaseMissing('pemasukans', ['id' => $pemasukan->id]);
    }

    public function test_validasi_wajib_nominal_tidak_valid_pada_pemasukan_dan_pengeluaran(): void
    {
        $this->from(route('pengeluaran.index'))->post(route('pengeluaran.store'), [
            'kategori' => 'Belanja Bahan',
            'nominal' => '',
        ])->assertSessionHasErrors(['nominal' => 'Nominal tidak valid']);

        $this->from(route('pengeluaran.index'))->post(route('pengeluaran.store'), [
            'kategori' => 'Belanja Bahan',
            'nominal' => 0,
        ])->assertSessionHasErrors(['nominal' => 'Nominal tidak valid']);

        $this->from(route('pemasukan.index'))->post(route('pemasukan.store'), [
            'kategori' => 'Tambahan Modal',
            'nominal' => -10000,
        ])->assertSessionHasErrors(['nominal' => 'Nominal tidak valid']);
    }
}
