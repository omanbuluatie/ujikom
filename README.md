# Apotek Digital Klinik Makmur Jaya

Aplikasi e-commerce penjualan obat berbasis **Laravel 12** untuk uji kompetensi Web Developer — studi kasus Klinik Makmur Jaya.

Repo: [omanbuluatie/ujikom](https://github.com/omanbuluatie/ujikom)

---

## Fitur utama

- Katalog obat (fuzzy search, autocomplete, filter, pagination)
- Keranjang → checkout → upload bukti pembayaran (job antrian)
- Notifikasi in-app untuk pasien (status transaksi, pembayaran, pengemasan)
- Verifikasi resep (unggah gambar → apoteker verifikasi/tolak)
- Potong stok **FIFO** (transaksi online & kasir memakai layanan yang sama)
- Harga obat desimal (mendukung pajak, mis. Rp 3.300,50)
- 4 peran: admin, apoteker, kasir, pasien
- Dasbor polling 10 detik, alert stok/kedaluwarsa, laporan PDF
- Admin: kelola transaksi, setujui/tolak pembayaran, ekspor CSV
- Migrasi CSV obat + rollback, pemantauan antrian/error/audit
- Keamanan: verifikasi email, hash password, CSRF, XSS escape, session 30 menit, audit log

---

## Stack

| Lapisan | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade, Alpine.js, Tailwind CSS 4 (Vite) |
| Database | MySQL 8 / MariaDB 10.4+ |
| Antrian | Database queue |
| PDF | DomPDF |

---

## Prasyarat

Pastikan sudah terpasang sebelum clone:

- PHP 8.2+ — ekstensi: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`
- Composer 2.x
- Node.js 20+ dan npm
- MySQL / MariaDB (service harus **sudah jalan**)
- Git

---

## Instalasi setelah clone

Ikuti urutan di bawah. Jangan loncat langkah — terutama worker antrian wajib di terminal terpisah.

### 1. Clone & masuk folder

```bash
git clone git@github.com:omanbuluatie/ujikom.git
cd ujikom
```

### 2. Dependensi PHP & frontend

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

Edit `.env` — sesuaikan koneksi database:

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

> `MAIL_MAILER=log` berarti email (verifikasi & notifikasi) ditulis ke `storage/logs/laravel.log`, cocok untuk demo lokal.

### 4. Basis data

Pilih **satu** opsi. Keduanya menghasilkan skema yang sama.

#### Opsi A — Impor dump (disarankan, paling cepat)

File: [`database/dumps/apotek_makmur_jaya.sql`](database/dumps/apotek_makmur_jaya.sql)

Dump sudah berisi `CREATE DATABASE`, semua tabel, dan data demo (akun, obat, batch, dll.).

**Linux / macOS:**

```bash
mysql -u root -p < database/dumps/apotek_makmur_jaya.sql
```

**Windows (sesuaikan path `mysql.exe`):**

```powershell
& "D:\website8\mysql\bin\mysql.exe" -h 127.0.0.1 -u root < database\dumps\apotek_makmur_jaya.sql
```

**GUI (phpMyAdmin / HeidiSQL / DBeaver):** Import file `.sql` di atas. Database `apotek_makmur_jaya` akan dibuat otomatis.

> Setelah impor dump, **tidak perlu** menjalankan `php artisan migrate`.

#### Opsi B — Migrasi + seeder

Gunakan jika tidak ingin impor dump atau ingin database benar-benar kosong lalu diisi seeder:

```bash
php artisan migrate:fresh --seed
```

### 5. Storage & symlink upload

Wajib agar upload bukti pembayaran, resep, dan gambar obat bisa diakses:

```bash
php artisan storage:link
php artisan config:clear
```

### 6. Jalankan aplikasi

Butuh **dua terminal** secara bersamaan:

**Terminal 1 — web server:**

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

**Terminal 2 — worker antrian (WAJIB):**

```bash
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

Buka browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

> Tanpa worker, transaksi bisa stuck di status **Pending** setelah upload bukti bayar, dan stok FIFO tidak dipotong.

### 7. (Opsional) Trigger peringatan kedaluwarsa

```bash
php artisan apotek:cek-kedaluwarsa --sync
```

---

## Akun demo

Kata sandi semua akun: **`Password1`**

| Peran | Email | Akses utama |
| --- | --- | --- |
| Admin | `admin@makmurjaya.test` | `/admin/dasbor`, obat, transaksi, laporan, migrasi |
| Apoteker | `apoteker@makmurjaya.test` | `/apoteker/resep`, `/apoteker/stok` |
| Kasir | `kasir@makmurjaya.test` | `/kasir/transaksi` |
| Pasien | `pasien@makmurjaya.test` | `/katalog`, `/keranjang`, `/transaksi` |

Akun demo sudah terverifikasi email. Pasien baru yang mendaftar sendiri harus klik tautan verifikasi (lihat log jika `MAIL_MAILER=log`).

---

## Cara menggunakan aplikasi

### Pasien — beli obat online

1. Masuk sebagai pasien → buka **Katalog** (`/katalog`)
2. Tambahkan obat ke **Keranjang** (`/keranjang`)
3. **Checkout** (`/transaksi/checkout`) — isi alamat pengiriman
4. **Upload bukti bayar** (`/transaksi/{kode}/bayar`) — pilih metode (transfer/QRIS) + foto bukti
5. Tunggu worker memproses → status berubah ke **Diproses**; notifikasi muncul di halaman **Transaksi** (`/transaksi`)
6. Jika ada obat keras (butuh resep): unggah foto resep dari halaman transaksi
7. Apoteker verifikasi resep → stok dipotong FIFO → status **Selesai**

### Apoteker — verifikasi resep

1. Masuk sebagai apoteker → **Antrian resep** (`/apoteker/resep`)
2. Periksa foto resep → **Verifikasi** (stok dipotong) atau **Tolak** (wajib isi catatan)
3. Lihat batch FIFO per obat di **Laci stok** (`/apoteker/stok`)

### Kasir — penjualan di counter

1. Masuk sebagai kasir → **Transaksi kasir** (`/kasir/transaksi`)
2. Pilih obat + jumlah → simpan
3. Stok langsung dipotong FIFO (sama dengan alur online)

### Admin

| Menu | URL | Fungsi |
| --- | --- | --- |
| Dasbor | `/admin/dasbor` | Penjualan, grafik, stok kritis (polling 10 detik) |
| Obat & Kategori | `/admin/obat`, `/admin/kategori` | CRUD obat, kategori (slot, email, aktif) |
| Transaksi | `/admin/transaksi` | Lihat, setujui/tolak bayar, batalkan, **ekspor CSV** |
| Laporan | `/admin/laporan` | Rekap penjualan + unduh PDF |
| Migrasi CSV | `/admin/migrasi` | Impor/rollback data obat via antrian |
| Pemantauan | `/admin/pemantauan` | Log audit, error, tautan verifikasi email |

---

## Status transaksi

| Status | Arti |
| --- | --- |
| `pending` | Transaksi dibuat, menunggu/meninjau pembayaran |
| `diproses` | Pembayaran diterima, obat sedang diproses/dikemas |
| `selesai` | Stok sudah dipotong, siap diambil/dikirim |
| `dibatalkan` | Dibatalkan (pembayaran ditolak, resep ditolak, stok habis, dll.) |

---

## Dump database di repo

| Item | Nilai |
| --- | --- |
| File | `database/dumps/apotek_makmur_jaya.sql` |
| Nama DB | `apotek_makmur_jaya` |
| Isi | Skema lengkap + data demo |

Tabel utama: `obat`, `batch_obat`, `mutasi_stok`, `transaksi`, `item_transaksi`, `resep`, `notifikasi`, `users`, `peringatan`, `log_audit`, dll.

### Membuat ulang dump (pengembang)

Setelah mengubah data demo di lokal:

```bash
# Linux / macOS
mysqldump -u root -p --single-transaction --routines --triggers --add-drop-table --databases apotek_makmur_jaya > database/dumps/apotek_makmur_jaya.sql

# Windows (contoh)
& "D:\website8\mysql\bin\mysqldump.exe" -h 127.0.0.1 -u root --single-transaction --routines --triggers --add-drop-table --databases apotek_makmur_jaya -r "database\dumps\apotek_makmur_jaya.sql"
```

Commit file SQL bersama perubahan yang ingin dibagikan ke tim.

---

## Struktur penting

```
app/Layanan/          # FIFO, fuzzy search, laporan, migrasi, notifikasi, peringatan
app/Jobs/             # pembayaran, stok, impor, laporan, kedaluwarsa
app/Http/Controllers/ # katalog, transaksi, kasir, apoteker, admin
app/Enums/            # StatusTransaksi, StatusResep, JenisMutasi, JenisNotifikasi
database/dumps/       # SQL dump siap impor
database/migrations/  # skema (alternatif jika tidak pakai dump)
```

---

## Troubleshooting

| Gejala | Penyebab & solusi |
| --- | --- |
| `Connection refused` saat migrate | MySQL belum jalan — start service MySQL/MariaDB |
| `could not find driver` | Aktifkan ekstensi `pdo_mysql` di `php.ini` yang dipakai CLI |
| Status transaksi tidak berubah setelah bayar | Worker antrian belum jalan — lihat langkah 6 |
| Gambar bukti/resep tidak tampil | Jalankan `php artisan storage:link` |
| Email verifikasi tidak masuk inbox | Normal di demo — cek `storage/logs/laravel.log` |
| Pagination teks aneh (`pagination.previous`) | Pastikan sudah pull versi terbaru; view `apotek.blade.php` + `lang/id/pagination.php` |

---

## Deploy produksi

Lihat [PANDUAN-PRODUKSI.md](PANDUAN-PRODUKSI.md) untuk langkah deploy server setelah `git pull`.

---

## Perintah sering dipakai

```bash
php artisan serve
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
php artisan migrate:fresh --seed
php artisan storage:link
php artisan apotek:cek-kedaluwarsa --sync
npm run build
npm run dev
```

---

## Lisensi

Proyek asesmen / uji kompetensi. Laravel sendiri berlisensi [MIT](https://opensource.org/licenses/MIT).
