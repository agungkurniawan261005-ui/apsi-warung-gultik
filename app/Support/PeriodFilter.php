<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Menormalkan pilihan periode filter tanggal untuk halaman rekap dan laporan.
 */
class PeriodFilter
{
    public const PRESET_LABELS = [
        'hari_ini' => 'Hari Ini',
        'minggu_ini' => '7 Hari Terakhir',
        'bulan_ini' => 'Bulan Ini',
        'semua' => 'Semua Data',
        'kustom' => 'Rentang Tanggal',
    ];

    public function __construct(
        public readonly string $periode,
        public readonly ?Carbon $tanggalMulai,
        public readonly ?Carbon $tanggalSelesai,
        public readonly string $label,
    ) {
    }

    /**
     * Membaca parameter periode/tanggal dari HTTP request dengan default Hari Ini.
     */
    public static function fromRequest(Request $request, string $defaultPeriode = 'hari_ini'): self
    {
        $periode = (string) $request->query('periode', '');
        $rawMulai = $request->query('tanggal_mulai');
        $rawSelesai = $request->query('tanggal_selesai');

        if ($periode === '' && ($rawMulai || $rawSelesai)) {
            $periode = 'kustom';
        }

        if (! array_key_exists($periode, self::PRESET_LABELS)) {
            $periode = $defaultPeriode;
        }

        $today = Carbon::today();

        if ($periode === 'hari_ini') {
            return new self(
                periode: 'hari_ini',
                tanggalMulai: $today->copy(),
                tanggalSelesai: $today->copy(),
                label: 'Hari Ini (' . $today->translatedFormat('d M Y') . ')',
            );
        }

        if ($periode === 'minggu_ini') {
            $mulai = $today->copy()->subDays(6);

            return new self(
                periode: 'minggu_ini',
                tanggalMulai: $mulai,
                tanggalSelesai: $today->copy(),
                label: '7 Hari Terakhir (' . $mulai->translatedFormat('d M') . ' - ' . $today->translatedFormat('d M Y') . ')',
            );
        }

        if ($periode === 'bulan_ini') {
            $mulai = $today->copy()->startOfMonth();
            $selesai = $today->copy()->endOfMonth();

            return new self(
                periode: 'bulan_ini',
                tanggalMulai: $mulai,
                tanggalSelesai: $selesai,
                label: 'Bulan ' . $today->translatedFormat('F Y'),
            );
        }

        if ($periode === 'semua') {
            return new self(
                periode: 'semua',
                tanggalMulai: null,
                tanggalSelesai: null,
                label: 'Semua Data Tercatat',
            );
        }

        $mulai = self::parseDate($rawMulai);
        $selesai = self::parseDate($rawSelesai);

        if ($mulai && $selesai && $mulai->gt($selesai)) {
            [$mulai, $selesai] = [$selesai, $mulai];
        }

        if (! $mulai && ! $selesai) {
            return new self(
                periode: 'hari_ini',
                tanggalMulai: $today->copy(),
                tanggalSelesai: $today->copy(),
                label: 'Hari Ini (' . $today->translatedFormat('d M Y') . ')',
            );
        }

        $labelParts = [];
        if ($mulai) {
            $labelParts[] = 'Mulai ' . $mulai->translatedFormat('d M Y');
        }
        if ($selesai) {
            $labelParts[] = 'Sampai ' . $selesai->translatedFormat('d M Y');
        }

        return new self(
            periode: 'kustom',
            tanggalMulai: $mulai,
            tanggalSelesai: $selesai,
            label: implode(' ', $labelParts),
        );
    }

    public function inputMulai(): string
    {
        return $this->tanggalMulai?->toDateString() ?? '';
    }

    public function inputSelesai(): string
    {
        return $this->tanggalSelesai?->toDateString() ?? '';
    }

    private static function parseDate(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', trim($value))->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
