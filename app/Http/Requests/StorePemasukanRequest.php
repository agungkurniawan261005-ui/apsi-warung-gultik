<?php

namespace App\Http\Requests;

class StorePemasukanRequest extends BaseKasRequest
{
    protected function allowedCategories(): array
    {
        return config('warung.kategori_pemasukan');
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
