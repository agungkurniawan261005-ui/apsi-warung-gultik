<?php

namespace App\Models;

use App\Models\Concerns\FilterableByTanggal;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent untuk Data Store D3 (Data Pengeluaran).
 */
class Pengeluaran extends Model
{
    use FilterableByTanggal;

    protected $table = 'pengeluarans';

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
