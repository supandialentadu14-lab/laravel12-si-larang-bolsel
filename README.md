# SI-LARANG (Sistem Informasi Pengelolaan Persediaan Barang)

**SI-LARANG** adalah platform manajemen inventaris modern yang dirancang khusus untuk **Dinas Komunikasi dan Informatika Bolaang Mongondow Selatan**. Aplikasi ini mengintegrasikan pengelolaan stok, administrasi dokumen, dan komunikasi internal dalam satu ekosistem yang efisien, transparan, dan akuntabel.

---

## ✨ Fitur Unggulan

### 📊 1. Dashboard & Monitoring
* **Sistem Ringkasan Real-time**: Pantau total barang, kategori, dan transaksi secara instan.
* **Low Stock Alerts**: Notifikasi otomatis saat stok barang mencapai ambang batas minimum.
* **Visualisasi Data**: Grafik perkembangan stok dan aktivitas terbaru.

### 📦 2. Manajemen Master Data
* **Data Barang & Barcode**: Kelola stok dengan detail satuan, serta cetak barcode untuk setiap barang.
* **Kategori (Jenis Belanja)**: Pengelompokan barang berdasarkan pos anggaran atau fungsi.
* **Database Penyedia**: Manajemen rekanan/supplier yang terintegrasi dengan pembuatan berkas.

### 📄 3. Otomatisasi Berkas & Laporan (Standard F4)
Produksi dokumen administrasi secara otomatis dengan format standar (KOP Surat & Penandatangan):
* **Nota Pesanan**: Pembuatan surat pesanan barang ke penyedia.
* **Berita Acara (BA) Pemeriksaan**: Validasi fisik barang yang diterima.
* **Berita Acara (BA) Penerimaan (BASTB)**: Serah terima barang secara resmi.
* **Kwitansi Pembayaran**: Pembuatan bukti pembayaran otomatis.
* **Berita Acara (BA) Stock Opname**: Verifikasi stok fisik berkala.
* **Pinjam Pakai**: Administrasi peminjaman barang inventaris.

### 💬 4. Chat Internal
* **Komunikasi Admin-Staff**: Fitur pengiriman pesan langsung antar pengguna.
* **Notifikasi Melayang**: Popup pesan baru yang memungkinkan akses cepat ke percakapan.
* **Status Online**: Pantau ketersediaan rekan kerja secara real-time.

### 📱 5. Optimasi Mobile & PWA
* **Antarmuka Mobile Khusus**: Desain yang dioptimalkan 100% berbeda untuk smartphone (Mobile-First).
* **Progressive Web App (PWA)**: Aplikasi dapat diinstal langsung di layar utama smartphone.
* **Navigasi Mulus**: Pengalaman pengguna yang cepat dengan animasi transisi yang halus.

### 🔐 6. Keamanan & Audit Trail
* **Log Aktivitas**: Rekaman setiap perubahan data (siapa, kapan, dan apa yang diubah).
* **Role Based Access Control (RBAC)**: Pengaturan izin akses mendalam untuk setiap fungsi.
* **Auto Backup**: Fitur pencadangan data untuk keamanan jangka panjang.

---

## 🚀 Panduan Instalasi Lokal

### 1. Persiapan
* **PHP**: Versi 8.2 atau lebih baru.
* **Database**: MySQL atau MariaDB.
* **Web Server**: Apache/Nginx.

### 2. Langkah-Langkah
```bash
# Clone repository
git clone https://github.com/supandialentadu14-lab/laravel12-si-larang-bolsel.git
cd laravel12-si-larang-bolsel

# Instalasi dependency
composer install
npm install && npm run build

# Environment setup
cp .env.example .env
php artisan key:generate

# Database & Storage
php artisan migrate --seed
php artisan storage:link
```

---

## 🛠️ Stack Teknologi
* **Backend**: Laravel 12 (Modern Framework)
* **Frontend**: Tailwind CSS & Alpine.js (Lightweight & Reactive)
* **Database**: MySQL
* **UI/UX**: Glassmorphism Design System, Font Plus Jakarta Sans
* **Mobile Support**: PWA with Service Workers

---

## 🔒 Privasi Data
Aplikasi ini menerapkan **Tenant-based Data Isolation**, di mana data yang dikelola oleh satu unit/user terjaga kerahasiaannya dan tidak dapat diakses oleh pihak yang tidak memiliki otorisasi, didukung dengan audit trail lengkap untuk setiap interaksi data.

---

Developed with ❤️ by **Emon Alentadu** for **BOLSEL**
