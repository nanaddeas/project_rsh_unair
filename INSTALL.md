# PANDUAN INSTALASI LENGKAP

## ⚠️ PENTING — Baca Sebelum Mulai

File dalam ZIP ini **BUKAN** project Laravel lengkap.
File ini adalah **source code tambahan** yang harus disalin ke dalam project Laravel 12 yang sudah dibuat.

---

## LANGKAH INSTALASI

### Step 1 — Buat Project Laravel 12 Baru

Buka terminal / command prompt, jalankan:

```
composer create-project laravel/laravel rshewan "^12.0"
cd rshewan
```

### Step 2 — Salin File dari ZIP ke Project

Extract ZIP ini, lalu salin file-file berikut ke dalam folder project Laravel:

```
DARI ZIP                                    → TUJUAN DI PROJECT LARAVEL
─────────────────────────────────────────────────────────────────────
rshewan/routes/web.php                      → routes/web.php              ← TIMPA FILE LAMA
rshewan/composer.json                       → composer.json                (opsional)
rshewan/.env.example                        → .env.example

rshewan/app/Models/                         → app/Models/
rshewan/app/Services/                       → app/Services/               ← Buat folder baru
rshewan/app/Http/Controllers/Admin/         → app/Http/Controllers/Admin/ ← Buat folder baru
rshewan/app/Http/Controllers/Guest/         → app/Http/Controllers/Guest/ ← Buat folder baru

rshewan/database/migrations/               → database/migrations/
rshewan/database/seeders/                  → database/seeders/

rshewan/resources/views/layouts/           → resources/views/layouts/     ← Buat folder baru
rshewan/resources/views/guest/             → resources/views/guest/       ← Buat folder baru
rshewan/resources/views/admin/             → resources/views/admin/       ← Buat folder baru
```

> **WAJIB:** File `routes/web.php` dari ZIP harus MENIMPA file bawaan Laravel!
> File bawaan Laravel berisi `return view('welcome')` yang menyebabkan error.

### Step 3 — Setup Environment

```
cp .env.example .env
php artisan key:generate
```

Edit file `.env`, ubah bagian database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rshewan
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4 — Buat Database MySQL

Buka phpMyAdmin atau MySQL CLI, buat database:

```sql
CREATE DATABASE rshewan;
```

### Step 5 — Jalankan Migration & Seeder

```
php artisan migrate --seed
```

Output yang diharapkan:
- Membuat tabel: users, pet_owners, pets, services, bookings
- Membuat admin: admin@rshewan.com / admin123
- Membuat 8 layanan default
- Membuat 5 data booking contoh

### Step 6 — Jalankan Server

```
php artisan serve
```

Buka browser: http://localhost:8000

---

## AKUN ADMIN

- URL Login  : http://localhost:8000/admin/login
- Email      : admin@rshewan.com
- Password   : admin123

---

## JIKA MASIH ERROR

### Error: View [welcome] not found
→ File routes/web.php belum diganti. Timpa dengan file dari ZIP.

### Error: Class not found (Controller)
→ Folder Admin/ atau Guest/ belum disalin ke app/Http/Controllers/

### Error: Table not found
→ Belum menjalankan: php artisan migrate --seed

### Error: No application encryption key
→ Belum menjalankan: php artisan key:generate

