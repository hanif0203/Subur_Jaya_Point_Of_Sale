<div align="center">

# 🏪 Subur Jaya — Point of Sale

**Sistem kasir berbasis web untuk toko bangunan, dibangun dengan Laravel 11 & Filament v3**

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v3-FDAE4B?style=for-the-badge&logo=filament&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

[Demo](#) · [Laporan Bug](https://github.com/hanif0203/Subur_Jaya_Point_Of_Sale/issues) · [Request Fitur](https://github.com/hanif0203/Subur_Jaya_Point_Of_Sale/issues)

</div>

---

## 📋 Tentang Proyek

**Subur Jaya POS** adalah sistem Point of Sale (POS) berbasis web yang dirancang khusus untuk kebutuhan toko bangunan. Dibangun menggunakan **Laravel 11** dan **Filament Admin Panel v3**, aplikasi ini menyediakan antarmuka yang intuitif untuk mengelola penjualan, inventaris, dan laporan keuangan secara real-time.

> 💡 Cocok untuk toko bangunan skala kecil hingga menengah yang membutuhkan sistem kasir yang lengkap dan mudah digunakan.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 🖥️ **Dashboard** | Ringkasan pendapatan, penjualan, dan diskon harian secara real-time |
| 🛒 **POS (Point of Sale)** | Modul kasir untuk transaksi penjualan yang cepat dan mudah |
| 📦 **Manajemen Produk** | Kelola produk, kategori, dan stok dengan mudah |
| 🏭 **Pemasok** | Manajemen data supplier dan pembelian barang |
| 👥 **Member** | Program keanggotaan pelanggan dengan tracking riwayat |
| 💳 **Metode Pembayaran** | Dukungan berbagai metode pembayaran (tunai, transfer, dll) |
| 📊 **Stok Opname** | Pengecekan dan penyesuaian stok gudang secara berkala |
| ⚠️ **Notifikasi Kadaluarsa** | Peringatan otomatis produk yang mendekati expired |
| 📜 **Riwayat Penjualan** | Laporan transaksi lengkap dengan filter dan export |
| 💰 **Piutang** | Pencatatan dan tracking hutang pelanggan |
| 🔐 **Manajemen Pengguna** | Role, jabatan, dan hak akses berbasis permission |
| 🎟️ **Voucher** | Manajemen voucher dan diskon untuk pelanggan |

---

## 🛠️ Tech Stack

| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| PHP | 8.2+ | Backend language |
| Laravel | 11.x | PHP Framework |
| Filament | 3.x | Admin Panel UI |
| MySQL | 8.0+ | Database |
| Tailwind CSS | 3.x | Styling |
| Livewire | 3.x | Reactive UI |

---

## 🚀 Instalasi

### Prasyarat
Pastikan sudah menginstall:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL 8.0+

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/hanif0203/Subur_Jaya_Point_Of_Sale.git
cd Subur_Jaya_Point_Of_Sale

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install && npm run build

# 4. Salin file environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_DATABASE=subur_jaya_pos
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 6. Jalankan migrasi & seeder
php artisan migrate --seed

# 7. Jalankan server
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

---

## 🔑 Akun Default

Setelah menjalankan seeder, gunakan akun berikut untuk login:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@admin.com | password |
| Kasir | kasir@kasir.com | password |

> ⚠️ Segera ganti password setelah login pertama kali!

---

## 📁 Struktur Proyek

```
app/
├── Console/          # Artisan commands
├── Enums/            # Enum classes
├── Events/           # Event classes
├── Exceptions/       # Custom exceptions
├── Features/         # Feature flags
├── Filament/
│   └── Tenant/
│       ├── Pages/    # Custom pages
│       ├── Resources/# CRUD resources
│       └── Widgets/  # Dashboard widgets
├── Filters/          # Query filters
├── Forms/Components/ # Reusable form components
└── Http/             # Controllers & Middleware
```

---

## 🔒 Keamanan

Aplikasi ini menerapkan beberapa lapisan keamanan:

- ✅ **CSRF Protection** — Perlindungan dari Cross-Site Request Forgery
- ✅ **Role & Permission** — Hak akses berbasis role menggunakan Spatie
- ✅ **Content Security Policy** — Header keamanan untuk mencegah XSS
- ✅ **SQL Injection Prevention** — Eloquent ORM & Query Builder
- ✅ **Authentication** — Laravel built-in authentication

---

## 🤝 Kontribusi

Kontribusi sangat disambut! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin feature/fitur-baru`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).

---

<div align="center">

Dibuat dengan ❤️ oleh **Hanif Bahy Hasyid**

⭐ Jangan lupa beri star jika project ini bermanfaat!

</div>
