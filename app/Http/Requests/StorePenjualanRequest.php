<?php

namespace App\Http\Requests;

use App\Support\FormatUang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi transaksi penjualan (UC-01 & UC-02) dengan pesan error Bahasa Indonesia wajib.
 */
class StorePenjualanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $hargaSatuan = $this->input('harga_satuan');

        $this->merge([
            'jumlah_porsi' => FormatUang::normalisasiInputAngka($this->input('jumlah_porsi')),
            'harga_satuan' => ($hargaSatuan === null || trim((string) $hargaSatuan) === '')
                ? null
                : FormatUang::normalisasiInputAngka($hargaSatuan),
        ]);
    }

    public function rules(): array
    {
        return [
            'jumlah_porsi' => ['required', 'integer', 'min:1'],
            'harga_satuan' => ['nullable', 'integer', 'min:500'],
            'metode_pembayaran' => ['required', Rule::in(config('warung.metode_pembayaran'))],
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah_porsi.required' => 'Jumlah porsi harus diisi',
            'jumlah_porsi.integer' => 'Jumlah porsi harus diisi',
            'jumlah_porsi.min' => 'Jumlah porsi harus diisi',
            'harga_satuan.integer' => 'Harga per porsi tidak valid',
            'harga_satuan.min' => 'Harga per porsi minimal Rp 500',
            'metode_pembayaran.required' => 'Pilih metode pembayaran',
            'metode_pembayaran.in' => 'Pilih metode pembayaran',
        ];
    }
}
