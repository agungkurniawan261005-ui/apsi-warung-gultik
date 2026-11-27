<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengeluaranRequest;
use App\Models\Pengeluaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengeluaranController extends BaseKasController
{
    public function store(StorePengeluaranRequest $request): RedirectResponse
    {
        return $this->simpanData($request);
    }

    public function edit(Pengeluaran $pengeluaran): View
    {
        return $this->tampilkanFormEdit($pengeluaran);
    }

    public function update(StorePengeluaranRequest $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        return $this->perbaruiData($request, $pengeluaran);
    }

    public function destroy(Request $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        return $this->hapusData($request, $pengeluaran);
    }

    protected function modelClass(): string
    {
        return Pengeluaran::class;
    }

    protected function routePrefix(): string
    {
        return 'pengeluaran';
    }

    protected function kategoriConfigKey(): string
    {
        return 'warung.kategori_pengeluaran';
    }

    protected function keteranganPresetConfigKey(): string
    {
        return 'warung.preset_keterangan_pengeluaran';
    }

    protected function jenisLabel(): string
    {
        return 'Pengeluaran';
    }
}
