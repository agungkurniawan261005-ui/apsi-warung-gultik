<?php

namespace App\Providers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        View::composer('partials.ringkasan-samping', function ($view): void {
            $today = Carbon::today();
            $rekapHariIni = Penjualan::hitungRekap($today, $today);
            $pemasukanHariIni = (int) Pemasukan::query()
                ->dalamRentangTanggal($today, $today)
                ->sum('nominal');
            $pengeluaranHariIni = (int) Pengeluaran::query()
                ->dalamRentangTanggal($today, $today)
                ->sum('nominal');

            $view->with([
                'rekapHariIni' => $rekapHariIni,
                'saldoBersihHariIni' => $rekapHariIni['total_omzet'] + $pemasukanHariIni - $pengeluaranHariIni,
                'jumlahDataPenjualan' => Penjualan::query()->count(),
                'jumlahDataPemasukan' => Pemasukan::query()->count(),
                'jumlahDataPengeluaran' => Pengeluaran::query()->count(),
            ]);
        });
    }
}
