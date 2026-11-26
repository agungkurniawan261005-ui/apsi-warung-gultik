<?php

namespace App\Support;

/**
 * Helper pemformatan angka ke format mata uang Rupiah yang seragam.
 */
class FormatUang
{
    public static function rupiah(int|float|string|null $nominal): string
    {
        $nilai = (int) round((float) ($nominal ?? 0));
        $prefix = $nilai < 0 ? '-Rp ' : 'Rp ';

        return $prefix . number_format(abs($nilai), 0, ',', '.');
    }

    /**
     * Mengubah format ribuan titik ala Indonesia (mis. "50.000") menjadi string digit.
     */
    public static function normalisasiInputAngka(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        if (preg_match('/^\d{1,3}(\.\d{3})+$/', $trimmed) === 1) {
            return str_replace('.', '', $trimmed);
        }

        return $trimmed;
    }
}
