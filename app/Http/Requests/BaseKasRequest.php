<?php

namespace App\Http\Requests;

use App\Support\FormatUang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi dasar bersama untuk pencatatan pemasukan non-penjualan (D2) dan pengeluaran (D3).
 */
abstract class BaseKasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $rawKeterangan = is_string($this->input('keterangan'))
            ? trim($this->input('keterangan'))
            : '';

        $keteranganFinal = $rawKeterangan !== '' ? $rawKeterangan : $this->defaultKeterangan();
        $kategoriInput = $this->input('kategori');

        $this->merge([
            'nominal' => FormatUang::normalisasiInputAngka($this->input('nominal')),
            'keterangan' => $keteranganFinal,
            'kategori' => ($kategoriInput === null || trim((string) $kategoriInput) === '')
                ? $this->inferKategori($rawKeterangan)
                : $kategoriInput,
            'tanggal' => $this->input('tanggal') ?: now()->toDateString(),
        ]);
    }

    public function rules(): array
    {
        return [
            'kategori' => ['required', Rule::in($this->allowedCategories())],
            'nominal' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori.required' => $this->categoryRequiredMessage(),
            'kategori.in' => $this->categoryInvalidMessage(),
            'nominal.required' => 'Nominal tidak valid',
            'nominal.integer' => 'Nominal tidak valid',
            'nominal.min' => 'Nominal tidak valid',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
        ];
    }

    /**
     * @return array<int, string>
     */
    abstract protected function allowedCategories(): array;

    abstract protected function defaultKeterangan(): string;

    abstract protected function inferKategori(string $keterangan): string;

    abstract protected function categoryRequiredMessage(): string;

    abstract protected function categoryInvalidMessage(): string;
}
