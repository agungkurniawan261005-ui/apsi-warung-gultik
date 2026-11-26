<?php

namespace App\Models;

use App\Models\Concerns\FilterableByTanggal;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent untuk Data Store D1 (Data Penjualan).
 */
class Penjualan extends Model
{
    use FilterableByTanggal;

    protected $table = 'penjualans';

    protected $fillable = [
        'jumlah_porsi',
        'harga_satuan',
        'total_harga',
        'metode_pembayaran',
        'waktu_transaksi',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_porsi' => 'integer',
            'harga_satuan' => 'integer',
            'total_harga' => 'integer',
            'waktu_transaksi' => 'datetime',
        ];
    }

    protected static function dateFilterColumn(): string
    {
        return 'waktu_transaksi';
    }

    protected static function dateFilterIsDateTime(): bool
    {
        return true;
    }

    /**
     * Menghitung rekap penjualan (total porsi, omzet, serta pemisahan Tunai dan QRIS).
     *
     * @return array<string, int>
     */
    public static function hitungRekap(?\Carbon\Carbon $mulai = null, ?\Carbon\Carbon $selesai = null): array
    {
        $row = static::query()
            ->dalamRentangTanggal($mulai, $selesai)
            ->selectRaw("
                COUNT(*) as total_transaksi,
                COALESCE(SUM(jumlah_porsi), 0) as total_porsi,
                COALESCE(SUM(total_harga), 0) as total_omzet,
                COALESCE(SUM(CASE WHEN metode_pembayaran = 'Tunai' THEN 1 ELSE 0 END), 0) as tunai_transaksi,
                COALESCE(SUM(CASE WHEN metode_pembayaran = 'Tunai' THEN jumlah_porsi ELSE 0 END), 0) as tunai_porsi,
                COALESCE(SUM(CASE WHEN metode_pembayaran = 'Tunai' THEN total_harga ELSE 0 END), 0) as tunai_omzet,
                COALESCE(SUM(CASE WHEN metode_pembayaran = 'QRIS' THEN 1 ELSE 0 END), 0) as qris_transaksi,
                COALESCE(SUM(CASE WHEN metode_pembayaran = 'QRIS' THEN jumlah_porsi ELSE 0 END), 0) as qris_porsi,
                COALESCE(SUM(CASE WHEN metode_pembayaran = 'QRIS' THEN total_harga ELSE 0 END), 0) as qris_omzet
            ")
            ->first();

        return [
            'total_transaksi' => (int) ($row->total_transaksi ?? 0),
            'total_porsi' => (int) ($row->total_porsi ?? 0),
            'total_omzet' => (int) ($row->total_omzet ?? 0),
            'tunai_transaksi' => (int) ($row->tunai_transaksi ?? 0),
            'tunai_porsi' => (int) ($row->tunai_porsi ?? 0),
            'tunai_omzet' => (int) ($row->tunai_omzet ?? 0),
            'qris_transaksi' => (int) ($row->qris_transaksi ?? 0),
            'qris_porsi' => (int) ($row->qris_porsi ?? 0),
            'qris_omzet' => (int) ($row->qris_omzet ?? 0),
        ];
    }
}
