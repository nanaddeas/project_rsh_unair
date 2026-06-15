# 🐾 Sistem Booking Rumah Sakit Hewan — Laravel 12

Aplikasi web booking rumah sakit hewan berbasis **Laravel 12**, **PHP 8.2+**, **MySQL**, dan **Bootstrap 5**. Pelanggan dapat booking tanpa akun, sementara admin mengelola seluruh data melalui dashboard.

---

## 📋 Fitur Utama

### 👥 Guest (Tanpa Login)
- ✅ Halaman beranda informatif dengan hero section
- ✅ Halaman daftar layanan
- ✅ Multi-step booking form (4 langkah)
- ✅ Slot booking visual grid dengan status (Tersedia / Dipilih / Penuh)
- ✅ Konfirmasi booking dengan ringkasan lengkap
- ✅ Halaman sukses dengan kode booking & opsi cetak

### 🔐 Admin (Login Required)
- ✅ Dashboard statistik real-time
- ✅ Manajemen booking (lihat, detail, ubah status, hapus)
- ✅ Search & filter booking
- ✅ Manajemen layanan (CRUD)
- ✅ SweetAlert2 untuk konfirmasi aksi
- ✅ Sidebar navigasi modern

---

## 🗂️ Struktur Folder Project

```
rshewan/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── ServiceController.php
│   │   │   └── Guest/
│   │   │       ├── BookingController.php
│   │   │       └── HomeController.php
│   ├── Models/
│   │   ├── Booking.php
│   │   ├── Pet.php
│   │   ├── PetOwner.php
│   │   ├── Service.php
│   │   └── User.php
│   └── Services/
│       └── BookingService.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_pet_owners_table.php
│   │   ├── 2024_01_01_000003_create_pets_table.php
│   │   ├── 2024_01_01_000004_create_services_table.php
│   │   └── 2024_01_01_000005_create_bookings_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── SampleBookingSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── guest.blade.php
│   │   └── admin.blade.php
│   ├── guest/
│   │   ├── booking/
│   │   │   ├── step1.blade.php
│   │   │   ├── step2.blade.php
│   │   │   ├── step3.blade.php
│   │   │   ├── confirm.blade.php
│   │   │   └── success.blade.php
│   │   └── pages/
│   │       ├── home.blade.php
│   │       └── services.blade.php
│   └── admin/
│       ├── auth/login.blade.php
│       ├── dashboard/index.blade.php
│       ├── bookings/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── services/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
└── routes/
    └── web.php
```

---

## 🛠️ Instalasi & Konfigurasi

### Persyaratan Sistem
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (opsional, hanya jika ingin compile assets)

---

### Langkah 1: Buat Project Laravel 12

```bash
composer create-project laravel/laravel rshewan "^12.0"
cd rshewan
```

### Langkah 2: Salin File Proyek

Salin seluruh file dari repository ini ke dalam folder project Laravel Anda, mengikuti struktur folder yang sama:

```
app/Http/Controllers/Admin/    → Salin semua controller admin
app/Http/Controllers/Guest/    → Salin semua controller guest
app/Models/                    → Salin semua model
app/Services/                  → Salin BookingService.php
database/migrations/           → Salin semua migration
database/seeders/              → Salin semua seeder
resources/views/               → Salin semua view Blade
routes/web.php                 → Ganti isi routes
```

### Langkah 3: Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:

```env
APP_NAME="RS Hewan Online"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rshewan
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Langkah 4: Buat Database

Masuk ke MySQL dan buat database:

```sql
CREATE DATABASE rshewan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Langkah 5: Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```

Seeder akan membuat:
- **Admin user**: `admin@rshewan.com` / `admin123`
- **8 layanan** default
- **5 sample booking** untuk demo

### Langkah 6: Jalankan Server

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## 🌐 URL Penting

| URL | Keterangan |
|-----|-----------|
| `http://localhost:8000` | Halaman utama (guest) |
| `http://localhost:8000/layanan` | Daftar layanan |
| `http://localhost:8000/booking/langkah-1` | Mulai booking |
| `http://localhost:8000/admin/login` | Login admin |
| `http://localhost:8000/admin/dashboard` | Dashboard admin |
| `http://localhost:8000/admin/bookings` | Manajemen booking |
| `http://localhost:8000/admin/services` | Manajemen layanan |

---

## 👤 Akun Default Admin

| Field | Value |
|-------|-------|
| Email | `admin@rshewan.com` |
| Password | `admin123` |

---

## 🗄️ Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| name | varchar | Nama admin |
| email | varchar | Email unik |
| password | varchar | Password (hashed) |
| role | enum | admin/staff |
| timestamps | - | created_at, updated_at |

### Tabel `pet_owners`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| name | varchar | Nama pemilik |
| address | text | Alamat lengkap |
| whatsapp | varchar | Nomor WA |
| timestamps | - | |

### Tabel `pets`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| owner_id | bigint | FK → pet_owners |
| name | varchar | Nama hewan |
| species | enum | Kucing/Anjing/dll |
| custom_species | varchar | Jika "Lainnya" |
| age | varchar | Usia (opsional) |
| timestamps | - | |

### Tabel `services`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| name | varchar | Nama layanan |
| description | text | Deskripsi |
| estimated_price | varchar | Estimasi harga |
| is_active | boolean | Status aktif |
| sort_order | integer | Urutan tampil |
| timestamps | - | |

### Tabel `bookings`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| booking_code | varchar | Kode unik (RSH-XXXXX-YYMMDD) |
| owner_id | bigint | FK → pet_owners |
| pet_id | bigint | FK → pets |
| service_id | bigint | FK → services |
| booking_date | date | Tanggal booking |
| booking_time | time | Jam booking |
| status | enum | Status booking |
| notes | text | Catatan (opsional) |
| timestamps | - | |

---

## ⚙️ Konfigurasi Slot Booking

Diatur di `app/Services/BookingService.php`:

- **Jam operasional**: 08:00 – 15:00
- **Durasi per sesi**: 20 menit
- **Istirahat Senin–Kamis & Sabtu**: 12:00 – 13:00
- **Istirahat Jumat**: 11:30 – 12:30
- **Hari libur**: Minggu (tutup)

---

## 🔒 Alur Status Booking

```
Booking Dibuat → Menunggu Konfirmasi
              ↓ (Admin setujui)
         Dikonfirmasi
              ↓ (Selesai dilayani)
            Selesai

         Alternatif:
         Dibatalkan (oleh admin)
         Tidak Hadir (pasien tidak datang)
```

---

## 🎨 Teknologi yang Digunakan

| Teknologi | Versi | Kegunaan |
|-----------|-------|---------|
| Laravel | ^12.0 | Framework PHP utama |
| PHP | ^8.2 | Server-side language |
| MySQL | ^8.0 | Database |
| Bootstrap | 5.3.3 | UI framework (CDN) |
| Bootstrap Icons | 1.11.3 | Icon set (CDN) |
| Flatpickr | latest | Date picker (CDN) |
| SweetAlert2 | 11 | Alert & konfirmasi (CDN) |
| Eloquent ORM | - | Database abstraction |

---

## 🔧 Tips Pengembangan Lanjutan

1. **Notifikasi WhatsApp**: Integrasikan Fonnte atau WA Cloud API untuk kirim notifikasi otomatis ke pemilik
2. **Export PDF**: Gunakan `barryvdh/laravel-dompdf` untuk cetak bukti booking yang lebih rapi
3. **Multi Admin**: Tambahkan role `staff` dengan akses terbatas
4. **Kalender Admin**: Tampilkan booking dalam format kalender bulanan
5. **Reminder**: Buat scheduled job untuk reminder booking H-1

---

## 📝 Lisensi

MIT License — Bebas digunakan dan dikembangkan.
