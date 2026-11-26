<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseKasRequest;
use App\Support\FormatUang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller dasar untuk pencatatan kas non-penjualan (pemasukan tambahan & pengeluaran).
 */
abstract class BaseKasController extends Controller
{
    public function index(): View
    {
        $modelClass = $this->modelClass();
        $riwayat = $modelClass::query()
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10);

        $totalHariIni = (int) $modelClass::query()
            ->whereDate('tanggal', now()->toDateString())
            ->sum('nominal');

        return view($this->indexView(), [
            'riwayat' => $riwayat,
            'totalHariIni' => $totalHariIni,
            'kategoriList' => config($this->kategoriConfigKey()),
            'presetNominal' => config('warung.preset_nominal'),
            'presetKeterangan' => config($this->keteranganPresetConfigKey()),
            'tabAktif' => $this->routePrefix(),
        ]);
    }

    protected function simpanData(BaseKasRequest $request): RedirectResponse
    {
        $modelClass = $this->modelClass();
        $record = $modelClass::create($request->validated());

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with(
                'success',
                $this->jenisLabel() . ' (' . $record->kategori . ') sebesar ' . FormatUang::rupiah($record->nominal) . ' berhasil dicatat!'
            );
    }

    protected function tampilkanFormEdit(Model $record): View
    {
        return view('kas.edit', [
            'record' => $record,
            'jenis' => $this->routePrefix(),
            'jenisLabel' => $this->jenisLabel(),
            'routeUpdate' => route($this->routePrefix() . '.update', $record),
            'routeBatal' => route($this->routePrefix() . '.index'),
            'kategoriList' => config($this->kategoriConfigKey()),
            'presetNominal' => config('warung.preset_nominal'),
            'presetKeterangan' => config($this->keteranganPresetConfigKey()),
        ]);
    }

    protected function perbaruiData(BaseKasRequest $request, Model $record): RedirectResponse
    {
        $record->update($request->validated());

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', 'Catatan ' . strtolower($this->jenisLabel()) . ' berhasil diperbarui.');
    }

    protected function hapusData(Model $record): RedirectResponse
    {
        $record->delete();

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', 'Catatan ' . strtolower($this->jenisLabel()) . ' berhasil dihapus.');
    }

    /**
     * @return class-string<Model>
     */
    abstract protected function modelClass(): string;

    abstract protected function routePrefix(): string;

    abstract protected function indexView(): string;

    abstract protected function kategoriConfigKey(): string;

    abstract protected function keteranganPresetConfigKey(): string;

    abstract protected function jenisLabel(): string;
}
