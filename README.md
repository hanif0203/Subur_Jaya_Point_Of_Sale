# 🏗️ Subur Jaya — Point of Sale

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square)
![Filament](https://img.shields.io/badge/Filament-v3-orange?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

Sistem Point of Sale (POS) berbasis web yang dirancang khusus untuk kebutuhan 
toko bangunan. Dibangun menggunakan **Laravel 11** dan **Filament Admin Panel v3**, 
aplikasi ini menyediakan antarmuka yang intuitif untuk mengelola penjualan, 
inventaris, dan laporan keuangan secara real-time.

## ✨ Fitur Utama

- 🖥️ **Dashboard** — Ringkasan pendapatan, penjualan, dan diskon harian
- 🛒 **POS (Point of Sale)** — Modul kasir untuk transaksi penjualan
- 📦 **Manajemen Produk** — Kelola produk, kategori, dan stok
- 🏭 **Pemasok** — Manajemen data supplier dan pembelian
- 👥 **Member** — Program keanggotaan pelanggan
- 💳 **Metode Pembayaran** — Dukungan berbagai metode pembayaran
- 📊 **Stok Opnam** — Pengecekan dan penyesuaian stok gudang
- ⚠️ **Notifikasi Kadaluarsa** — Peringatan otomatis produk expired
- 📜 **Riwayat Penjualan** — Laporan transaksi lengkap
- 💰 **Piutang** — Pencatatan dan tracking hutang pelanggan
- 🔐 **Manajemen Pengguna** — Role, jabatan, dan hak akses

## 🛠️ Tech Stack

| Teknologi | Versi |
|-----------|-------|
| PHP | 8.2+ |
| Laravel | 11.x |
| Filament | 3.x |
| MySQL | 8.0+ |
| Tailwind CSS | 3.x |

## ⚙️ Instalasi

```bash
git clone https://github.com/username/subur-jaya-pos.git
cd subur-jaya-pos

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu:
php artisan migrate --seed
php artisan serve
```

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).
