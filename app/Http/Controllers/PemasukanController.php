<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemasukanRequest;
use App\Models\Pemasukan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PemasukanController extends BaseKasController
{
    public function store(StorePemasukanRequest $request): RedirectResponse
    {
        return $this->simpanData($request);
    }

    public function edit(Pemasukan $pemasukan): View
    {
        return $this->tampilkanFormEdit($pemasukan);
    }

    public function update(StorePemasukanRequest $request, Pemasukan $pemasukan): RedirectResponse
    {
        return $this->perbaruiData($request, $pemasukan);
    }

    public function destroy(Pemasukan $pemasukan): RedirectResponse
    {
        return $this->hapusData($pemasukan);
    }

    protected function modelClass(): string
    {
        return Pemasukan::class;
    }

    protected function routePrefix(): string
    {
        return 'pemasukan';
    }

    protected function indexView(): string
    {
        return 'kas.pemasukan';
    }

    protected function kategoriConfigKey(): string
    {
        return 'warung.kategori_pemasukan';
    }

    protected function keteranganPresetConfigKey(): string
    {
        return 'warung.preset_keterangan_pemasukan';
    }

    protected function jenisLabel(): string
    {
        return 'Pemasukan Tambahan';
    }
}
