# Kasirku - Aplikasi Point of Sale (POS) Modern

Kasirku adalah aplikasi web kasir Point of Sale (POS) yang responsif, modern, dan dirancang dengan antarmuka yang bersih serta skema warna biru cerah.

## Fitur Utama
1. **Dashboard Analitik**: Dilengkapi widget KPI (Total Penjualan, Transaksi, Jumlah Produk) dan grafik tren pendapatan bulanan berbasis Chart.js.
2. **Manajemen Produk (CRUD)**: Kelola data barcode, nama, kategori, stok, dan harga produk. Mendukung pemindaian langsung dari alat scanner barcode ke dalam kolom input form tambah/edit produk.
3. **Terminal POS Kasir**:
   - Pencarian produk manual berbasis autocomplete (live search) berdasarkan nama produk atau barcode.
   - Perekam scanner barcode fisik secara otomatis pada background (HID keyboard emulation).
   - Kalkulator kembalian instan dengan pilihan uang pas cepat.
   - Pencetakan nota thermal 58mm/80mm instan menggunakan printer default sistem operasi.
4. **Laporan Penjualan**: Laporan harian (rincian transaksi/invoice) dan bulanan (tabel akumulasi harian) yang dioptimalkan untuk cetak kertas/PDF.
5. **Manajemen Profil**: Pengaturan nama lengkap, email, dan penggantian password admin secara aman.

## Tech Stack
* **Backend**: Laravel 11 / 13
* **Frontend**: HTML5, Javascript, Tailwind CSS v4, Alpine.js, Lucide Icons
* **Database**: SQLite (default) / MySQL
* **Grafik**: Chart.js

## Kredensial Login Default (Seeder)
* **Email**: `admin@gmail.com`
* **Password**: `password123`

## Cara Instalasi Lokal

1. Kloning repository ini ke lokal.
2. Buka terminal di direktori proyek dan jalankan:
   ```bash
   composer install
   npm install
   ```
3. Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasinya jika diperlukan.
4. Jalankan migrasi database dan pengisian data demo awal (seed):
   ```bash
   php artisan migrate --seed
   ```
5. Kompilasi aset frontend:
   ```bash
   npm run build
   ```
6. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
   Akses aplikasi web di `http://127.0.0.1:8000/login`.
