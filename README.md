# Apotek Digital Klinik Makmur Jaya

Aplikasi e-commerce penjualan obat berbasis **Laravel 12** untuk uji kompetensi Web Developer — studi kasus Klinik Makmur Jaya.

Repo: [omanbuluatie/ujikom](https://github.com/omanbuluatie/ujikom)

---

## Fitur utama

- Katalog obat (fuzzy search, autocomplete, filter, pagination)
- Keranjang → checkout → pembayaran simulasi (job antrian)
- Verifikasi resep (unggah gambar → apoteker setuju/tolak)
- Potong stok **FIFO** (pesanan online & kasir memakai layanan yang sama)
- 4 peran: admin, apoteker, kasir, pasien
- Dasbor polling 10 detik, alert stok/kedaluwarsa, laporan PDF
- Migrasi CSV + rollback, pemantauan antrian/error/audit
- Keamanan: verifikasi email, hash password, CSRF, XSS escape, session 30 menit, audit log



---

## Stack

| Lapisan | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade, Alpine.js, Tailwind CSS 4 (Vite) |
| Database | MySQL 8 / MariaDB |
| Antrian | Database queue |
| PDF | DomPDF |

---

## Prasyarat lokal

- PHP 8.2+ (ekstensi: mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath, fileinfo, gd)
- Composer 2.x
- Node.js 20+ dan npm
- MySQL / MariaDB
- Git

---

## Instalasi setelah clone (localhost)

### 1. Clone & masuk folder

```bash
git clone git@github.com:omanbuluatie/ujikom.git
cd ujikom
```

### 2. Dependensi

```bash
composer install
npm install
npm run build
```

### 3. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi DB di `.env` (contoh):

```env
APP_NAME="Apotek Makmur Jaya"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek_makmur_jaya
DB_USERNAME=root
DB_PASSWORD=

SESSION_LIFETIME=30
QUEUE_CONNECTION=database
MAIL_MAILER=log
```

### 4. Basis data — pilih salah satu

#### Opsi A (disarankan): impor dump siap pakai

File dump ada di repo: [`database/dumps/apotek_makmur_jaya.sql`](database/dumps/apotek_makmur_jaya.sql)

**MySQL CLI:**

```bash
# Buat DB kosong jika dump tanpa CREATE DATABASE (dump ini sudah include --databases)
mysql -u root -p < database/dumps/apotek_makmur_jaya.sql
```

**Windows (contoh path mysql client):**

```powershell
# Sesuaikan path mysql.exe Anda
& "D:\website8\mysql\bin\mysql.exe" -h 127.0.0.1 -u root apotek_makmur_jaya -e "SELECT 1"
# Jika DB belum ada, dump berisi CREATE DATABASE:
& "D:\website8\mysql\bin\mysql.exe" -h 127.0.0.1 -u root < database\dumps\apotek_makmur_jaya.sql
```

**phpMyAdmin / HeidiSQL / DBeaver:** buat database `apotek_makmur_jaya` (atau biarkan dump membuatnya), lalu Import file `.sql` di atas.

#### Opsi B: migrasi + seeder (tanpa dump)

```bash
php artisan migrate:fresh --seed
```

Ini membuat skema + data demo dari seeder (akun & obat contoh).

### 5. Storage & cache

```bash
php artisan storage:link
php artisan config:clear
```

### 6. Jalankan aplikasi

Buka **dua** terminal:

```bash
# Terminal 1 — web
php artisan serve --host=127.0.0.1 --port=8000
```

```bash
# Terminal 2 — worker antrian (WAJIB untuk bayar/stok/impor/laporan)
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

Buka: [http://127.0.0.1:8000](http://127.0.0.1:8000)

> Tanpa worker dengan daftar `--queue=...` di atas, status pesanan bisa tetap **Menunggu bayar**.

### 7. (Opsional) Cek kedaluwarsa untuk demo

```bash
php artisan apotek:cek-kedaluwarsa --sync
```

---

## Akun demo

Kata sandi semua: **`Password1`**

| Peran | Email |
| --- | --- |
| Admin | `admin@makmurjaya.test` |
| Apoteker | `apoteker@makmurjaya.test` |
| Kasir | `kasir@makmurjaya.test` |
| Pasien | `pasien@makmurjaya.test` |

Email verifikasi / notifikasi (demo): lihat `storage/logs/laravel.log` (`MAIL_MAILER=log`).

---

## Dump database di repo

| Item | Nilai |
| --- | --- |
| File | `database/dumps/apotek_makmur_jaya.sql` |
| Nama DB | `apotek_makmur_jaya` |
| Isi | Skema + data demo (obat, batch, akun, dsb.) |

### Membuat ulang dump (pengembang)

```bash
# Linux / macOS
mysqldump -u root -p --single-transaction --routines --triggers --add-drop-table --databases apotek_makmur_jaya > database/dumps/apotek_makmur_jaya.sql

# Windows (contoh)
& "D:\website8\mysql\bin\mysqldump.exe" -h 127.0.0.1 -u root --single-transaction --routines --triggers --add-drop-table --databases apotek_makmur_jaya -r "database\dumps\apotek_makmur_jaya.sql"
```

Commit file SQL tersebut bersama perubahan data demo yang ingin dibagikan.

---

## Struktur penting

```
app/Layanan/          # FIFO, fuzzy, laporan, migrasi, peringatan
app/Jobs/             # pembayaran, stok, impor, laporan, kedaluwarsa
app/Http/Controllers/ # katalog, pesanan, kasir, apoteker, admin
database/dumps/       # SQL dump siap impor
database/migrations/  # skema alternatif opsi B
```

Folder `dokumentasi/` (panduan, naskah demo) disimpan lokal dan **tidak ikut di-push**, kecuali CSV contoh di `dokumentasi/contoh-data/` (lihat `.gitignore`).

**Deploy produksi:** lihat [PANDUAN-PRODUKSI.md](PANDUAN-PRODUKSI.md). **Revisi domain transaksi:** lihat [REVISI.md](REVISI.md).

---

## Perintah sering dipakai

```bash
php artisan serve
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
php artisan apotek:cek-kedaluwarsa --sync
php artisan storage:link
npm run build
npm run dev
```

---

## Lisensi

Proyek asesmen / uji kompetensi. Laravel sendiri berlisensi [MIT](https://opensource.org/licenses/MIT).
