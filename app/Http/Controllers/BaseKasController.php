<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseKasRequest;
use App\Support\FormatUang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller dasar untuk pencatatan kas non-penjualan (pemasukan tambahan & pengeluaran).
 */
abstract class BaseKasController extends Controller
{
    public function index(Request $request): View
    {
        return self::renderKasPage($this->routePrefix());
    }

    public static function renderKasPage(string $jenisAktif = 'pengeluaran'): View
    {
        $jenisAktif = $jenisAktif === 'pemasukan' ? 'pemasukan' : 'pengeluaran';

        return view('kas.index', [
            'jenisAktif' => $jenisAktif,
            'presetNominal' => config('warung.preset_nominal'),
            'presetKeteranganPengeluaran' => config('warung.preset_keterangan_pengeluaran'),
            'presetKeteranganPemasukan' => config('warung.preset_keterangan_pemasukan'),
            'kategoriPengeluaran' => config('warung.kategori_pengeluaran'),
            'kategoriPemasukan' => config('warung.kategori_pemasukan'),
        ]);
    }

    protected function simpanData(BaseKasRequest $request): RedirectResponse
    {
        $modelClass = $this->modelClass();
        $record = $modelClass::create($request->validated());
        $label = $this->routePrefix() === 'pengeluaran' ? 'Pengeluaran' : 'Pemasukan tambahan';

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('form_feedback', true)
            ->with(
                'success',
                "{$label} sebesar " . FormatUang::rupiah($record->nominal) . ' berhasil disimpan.'
            );
    }

    protected function tampilkanFormEdit(Model $record): View
    {
        return view('kas.edit', [
            'record' => $record,
            'jenis' => $this->routePrefix(),
            'jenisLabel' => $this->jenisLabel(),
            'routeUpdate' => route($this->routePrefix() . '.update', $record),
            'routeBatal' => route('laporan.rekap'),
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

    protected function hapusData(Request $request, Model $record): RedirectResponse
    {
        $record->delete();
        $pesan = 'Catatan ' . strtolower($this->jenisLabel()) . ' berhasil dihapus.';

        if ($request->input('kembali_ke') === 'rekap') {
            return redirect()
                ->route('laporan.rekap', array_filter([
                    'periode' => $request->input('periode'),
                    'tanggal' => $request->input('tanggal'),
                ]))
                ->with('success', $pesan);
        }

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $pesan);
    }

    /**
     * @return class-string<Model>
     */
    abstract protected function modelClass(): string;

    abstract protected function routePrefix(): string;

    abstract protected function kategoriConfigKey(): string;

    abstract protected function keteranganPresetConfigKey(): string;

    abstract protected function jenisLabel(): string;
}
