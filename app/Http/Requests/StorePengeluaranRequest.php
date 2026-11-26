<?php

namespace App\Http\Requests;

class StorePengeluaranRequest extends BaseKasRequest
{
    protected function allowedCategories(): array
    {
        return config('warung.kategori_pengeluaran');
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
