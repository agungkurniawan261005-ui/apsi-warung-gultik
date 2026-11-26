<?php

namespace App\Models;

use App\Models\Concerns\FilterableByTanggal;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent untuk Data Store D2 (Data Pemasukan Non-Penjualan).
 */
class Pemasukan extends Model
{
    use FilterableByTanggal;

    protected $table = 'pemasukans';

    protected $fillable = [
        'kategori',
        'nominal',
        'keterangan',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'integer',
            'tanggal' => 'date',
        ];
    }
}
