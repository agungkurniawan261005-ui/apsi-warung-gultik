# Sistem Pencatatan Penjualan dan Keuangan Warung Gultik Nusantara

Aplikasi web pencatatan transaksi penjualan, metode pembayaran (Tunai & QRIS), serta arus kas (pemasukan dan pengeluaran) yang dirancang khusus dengan antarmuka sederhana dan minim pengetikan untuk pemilik Warung Gultik Nusantara (Pak Yusda)[cite: 6].

**Mata Kuliah:** Analisis Perancangan Sistem Informasi (APSI) — Kelompok 1 (STT Terpadu Nurul Fikri)[cite: 8]  
**Anggota Kelompok:**
- Inayah Safitri (0110125028)
- Nazla Nabila Hidayat Lubis (0110125152)
- Agung Kurniawan (0110125049)
- Khalid Alghifari (0110125004)[cite: 8]

---

## Fitur Utama
1. **Catat Penjualan (UC-01):** Pencatatan transaksi penjualan berbasis jumlah porsi dengan kalkulasi total otomatis[cite: 12].
2. **Catat Metode Pembayaran (UC-02):** Pemisahan metode pembayaran antara **Tunai** dan **QRIS** untuk setiap transaksi[cite: 11, 12].
3. **Catat Pemasukan & Pengeluaran (UC-03):** Pencatatan pengeluaran operasional manual dan pemasukan tambahan di luar penjualan (pendapatan penjualan otomatis terakumulasi tanpa input ulang)[cite: 6, 9].
4. **Lihat Rekap Penjualan (UC-04):** Ringkasan total porsi terjual, omzet, serta rincian Tunai vs QRIS[cite: 6, 12].
5. **Lihat Informasi Pemasukan dan Pengeluaran (UC-05):** Laporan arus kas dan saldo bersih usaha dilengkapi filter periode tanggal[cite: 6, 11].
6. **Lihat Riwayat Penjualan (UC-06):** Daftar riwayat transaksi penjualan yang telah tersimpan[cite: 11].

---

## Prasyarat Software di Laptop
Sebelum menjalankan proyek ini, pastikan perangkat sudah terinstal:
- **XAMPP** (PHP >= 8.2 dan MariaDB/MySQL)
- **Composer**
- **Git**
- **MySQL Workbench** (atau phpMyAdmin)

---

## Panduan Menjalankan Proyek (Dari Awal Buka Laptop)

### 1. Nyalakan Server Lokal (XAMPP)
1. Buka aplikasi **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache** dan **MySQL** hingga keduanya berwarna hijau.

### 2. Buat Database Kosong
1. Buka **MySQL Workbench** (atau buka `http://localhost/phpmyadmin` di browser).
2. Buat *schema* / database baru dengan nama:
   ```sql
   db_gultik_nusantara

   3. Download Proyek dari GitHub (Clone)

    Buka Command Prompt (CMD) atau PowerShell.

    Masuk ke folder htdocs XAMPP:
    Bash

    cd C:\xampp\htdocs

    Unduh repositori proyek ini (ganti <URL-REPO-GITHUB> dengan link repositori kelompok):
    Bash

    git clone https://github.com/agungkurniawan261005-ui/apsi-warung-gultik

    Masuk ke folder proyek:
    Bash

    cd warung-gultik

4. Instal Dependensi & Konfigurasi Environment

Jalankan perintah berikut secara berurutan di terminal:

    Instal paket Laravel (vendor):
    Bash

    composer install

    Salin file contoh konfigurasi menjadi .env:
    Bash

    copy .env.example .env

    Generate kunci keamanan aplikasi:
    Bash

    php artisan key:generate

    Buka file .env menggunakan VS Code / Antigravity IDE / Notepad, lalu sesuaikan bagian database agar terhubung ke MySQL XAMPP:
    Cuplikan kode

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_gultik_nusantara
    DB_USERNAME=root
    DB_PASSWORD=

    (Simpan file .env dengan menekan Ctrl + S).

5. Buat Tabel Database (Migrasi)

Jalankan perintah migrasi agar tabel penjualans, pemasukans, dan pengeluarans otomatis dibuat di database:
Bash

php artisan migrate

(Jika tersedia data contoh/seeder, jalankan php artisan migrate --seed).
6. Jalankan Aplikasi di Browser

    Jalankan server pengembangan Laravel:
    Bash

    php artisan serve

    Buka browser (Chrome/Edge) dan akses alamat:
    Plaintext

    [http://127.0.0.1:8000](http://127.0.0.1:8000)

Alur Kolaborasi Git untuk Anggota Kelompok

Setiap kali akan mulai mengerjakan atau mengedit kode baru, biasakan menjalankan langkah berikut agar kode selalu sinkron:

    Ambil pembaruan terbaru dari GitHub sebelum ngoding:
    Bash

    git pull origin main

    Simpan & unggah perubahan setelah selesai mengedit:
    Bash

    git add .
    git commit -m "Deskripsi perubahan yang dikerjakan"
    git push origin main