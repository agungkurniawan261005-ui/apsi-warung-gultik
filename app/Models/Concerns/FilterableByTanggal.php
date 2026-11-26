<?php

namespace App\Models\Concerns;

use App\Support\PeriodFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Scope filter tanggal yang tetap ramah terhadap indeks MySQL.
 */
trait FilterableByTanggal
{
    public function scopeDalamPeriode(Builder $query, PeriodFilter $filter): Builder
    {
        return $this->scopeDalamRentangTanggal($query, $filter->tanggalMulai, $filter->tanggalSelesai);
    }

    public function scopeDalamRentangTanggal(Builder $query, ?Carbon $mulai, ?Carbon $selesai): Builder
    {
        $column = static::dateFilterColumn();
        $isDateTime = static::dateFilterIsDateTime();

        if ($mulai) {
            if ($isDateTime) {
                $query->where($column, '>=', $mulai->copy()->startOfDay());
            } else {
                $query->whereDate($column, '>=', $mulai->toDateString());
            }
        }

        if ($selesai) {
            if ($isDateTime) {
                $query->where($column, '<=', $selesai->copy()->endOfDay());
            } else {
                $query->whereDate($column, '<=', $selesai->toDateString());
            }
        }

        return $query;
    }

    protected static function dateFilterColumn(): string
    {
        return 'tanggal';
    }

    protected static function dateFilterIsDateTime(): bool
    {
        return false;
    }
}
