<?php

return [
    'nama_warung' => 'Warung Gultik Nusantara',
    'harga_porsi_default' => (int) env('WARUNG_HARGA_PORSI', 15000),
    'metode_pembayaran' => [
        'Tunai',
        'QRIS',
    ],
    'kategori_pemasukan' => [
        'Tambahan Modal',
        'Pendapatan Lain-lain',
    ],
    'kategori_pengeluaran' => [
        'Belanja Bahan',
        'Operasional/Gas',
        'Lainnya',
    ],
    'preset_porsi' => [1, 2, 3, 4, 5],
    'preset_nominal' => [10000, 20000, 50000, 100000, 200000, 500000],
    'preset_keterangan_pengeluaran' => [
        'Daging Sapi',
        'Bumbu & Santan',
        'Beras & Kerupuk',
        'Gas LPG 3kg',
        'Listrik & Air',
        'Plastik & Mangkok',
    ],
    'preset_keterangan_pemasukan' => [
        'Modal Harian Kasir',
        'Tambahan Modal Belanja',
        'Penjualan Kemasan/Lainnya',
    ],
];
