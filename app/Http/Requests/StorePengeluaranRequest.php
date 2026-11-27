<?php

namespace App\Http\Requests;

class StorePengeluaranRequest extends BaseKasRequest
{
    protected function allowedCategories(): array
    {
        return config('warung.kategori_pengeluaran');
    }

    protected function defaultKeterangan(): string
    {
        return 'Pengeluaran operasional';
    }

    protected function inferKategori(string $keterangan): string
    {
        $teks = mb_strtolower($keterangan);
        if (
            str_contains($teks, 'gas')
            || str_contains($teks, 'elpiji')
            || str_contains($teks, 'plastik')
            || str_contains($teks, 'listrik')
        ) {
            return 'Operasional/Gas';
        }

        return 'Belanja Bahan';
    }

    protected function categoryRequiredMessage(): string
    {
        return 'Pilih kategori pengeluaran';
    }

    protected function categoryInvalidMessage(): string
    {
        return 'Kategori pengeluaran tidak valid';
    }
}
