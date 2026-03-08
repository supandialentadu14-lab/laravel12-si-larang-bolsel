# SI-LARANG (Sistem Informasi Pengelolaan Persediaan Barang)

**SI-LARANG** adalah aplikasi berbasis web yang digunakan oleh **Dinas Komunikasi dan Informatika Bolaang Mongondow Selatan** untuk mengelola inventaris dan persediaan barang secara efektif, transparan, dan akuntabel.

---

## ✨ Fitur Utama

- 📦 **Manajemen Barang**: Kelola data master barang, kategori (jenis belanja), dan supplier.
- 🔁 **Transaksi Stok**: Pencatatan barang masuk (in) dan keluar (out) dengan pembaruan stok otomatis.
- 📄 **Otomatisasi Laporan**: Pembuatan dokumen surat menyurat secara otomatis (PDF/Print):
  - Nota Pesanan
  - Berita Acara (BA) Pemeriksaan
  - Berita Acara (BA) Penerimaan
  - Kwitansi Pembayaran
  - Laporan Stock Opname
  - Kartu Persediaan Tahunan
- 🔐 **Multi-user & Keamanan**:
  - Isolasi data antar pengguna (Tenant-based).
  - Role-based Access Control (Admin & Staff).
  - Activity Log untuk memantau perubahan data.
- 📊 **Dashboard Interaktif**: Visualisasi stok dan ringkasan transaksi.
- 🌓 **Desain Premium**: Antarmuka modern dengan dukungan Dark Mode & Light Mode.

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal:

### 1. Persiapan Database
- Pastikan Anda sudah menginstal **MySQL** (atau MariaDB).
- Buat database baru dengan nama `si_larang`.

### 2. Clone Repository
```bash
git clone https://github.com/supandialentadu14-lab/laravel12-si-larang-bolsel.git
cd laravel12-si-larang-bolsel
```

### 3. Instalasi Dependency
Instal paket PHP dan JavaScript yang dibutuhkan:
```bash
composer install
npm install && npm run build
```

### 4. Konfigurasi Environment
Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda:
```bash
cp .env.example .env
```
Edit bagian database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=si_larang
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Key & Link Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 6. Migrasi Database
Jalankan migrasi untuk membuat tabel-tabel yang diperlukan:
```bash
php artisan migrate
```
*(Opsi)* Jika ingin menambahkan data dummy awal:
```bash
php artisan db:seed
```

---

## 🛠️ Cara Penggunaan

1. **Menjalankan Server**:
   ```bash
   php artisan serve
   ```
   Buka browser dan akses `http://127.0.0.1:8000`.

2. **Login Pertama**:
   - Jika Anda menjalankan seeder, gunakan email `test@example.com` (password sesuaikan dengan yang diatur di Factory/Seeder).
   - Atau daftar akun baru melalui menu **Registrasi**.

3. **Input Master Data**:
   - Masukkan **Jenis Belanja** terlebih dahulu.
   - Masukkan data **Supplier**.
   - Tambahkan data **Barang** (Daftar Barang).

4. **Transaksi**:
   - Gunakan menu **Manajemen Stok** untuk menambah barang masuk atau mencatat barang keluar.

5. **Pelaporan**:
   - Klik menu **Laporan** untuk mencetak dokumen sesuai kebutuhan. Pastikan data OPD dan Penandatangan sudah diatur di menu **Pengaturan**.

---

## 🔒 Keamanan & Pemeliharaan

- **Penyimpanan Password**: Password disimpan menggunakan hashing Bcrypt yang aman.
- **Log Aktivitas**: Semua aksi hapus dan edit terekam secara detail untuk audit.
- **Isolasi Data**: Pengguna hanya dapat melihat dan mengelola data yang mereka input sendiri (Tenantable Scope).

---

Developed with ❤️ by **Emon Alentadu**
