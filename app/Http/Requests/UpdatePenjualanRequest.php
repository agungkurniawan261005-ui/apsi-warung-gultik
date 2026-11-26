<?php

namespace App\Http\Requests;

class UpdatePenjualanRequest extends StorePenjualanRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'waktu_transaksi' => ['nullable', 'date'],
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'waktu_transaksi.date' => 'Format waktu transaksi tidak valid',
        ]);
    }
}
