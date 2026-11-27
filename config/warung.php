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
    'preset_harga' => [12000, 15000, 18000, 20000],
    'preset_nominal' => [10000, 20000, 50000, 100000],
    'preset_keterangan_pengeluaran' => [
        'Beli daging & jeroan',
        'Gas elpiji 3 kg',
        'Beras & santan',
        'Bumbu & kerupuk',
        'Plastik & kemasan',
    ],
    'preset_keterangan_pemasukan' => [
        'Titipan minuman',
        'Pesanan tambahan',
        'Uang kembalian / modal',
    ],
];
