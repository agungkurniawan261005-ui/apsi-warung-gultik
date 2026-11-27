<?php

namespace App\Http\Requests;

class StorePemasukanRequest extends BaseKasRequest
{
    protected function allowedCategories(): array
    {
        return config('warung.kategori_pemasukan');
    }

    protected function defaultKeterangan(): string
    {
        return 'Pemasukan tambahan';
    }

    protected function inferKategori(string $keterangan): string
    {
        $teks = mb_strtolower($keterangan);
        if (str_contains($teks, 'modal') || str_contains($teks, 'kembalian')) {
            return 'Tambahan Modal';
        }

        return 'Pendapatan Lain-lain';
    }

    protected function categoryRequiredMessage(): string
    {
        return 'Pilih kategori pemasukan';
    }

    protected function categoryInvalidMessage(): string
    {
        return 'Kategori pemasukan tidak valid';
    }
}
