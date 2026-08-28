# Panduan Deploy Server Produksi

Panduan men-deploy **Apotek Digital Klinik Makmur Jaya** (Laravel 12) ke server produksi, termasuk setelah `git pull` revisi domain transaksi.

Repo: [omanbuluatie/ujikom](https://github.com/omanbuluatie/ujikom)

---

## Prasyarat server

| Komponen | Versi minimum |
|----------|---------------|
| PHP | 8.2+ (mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath, fileinfo, gd) |
| Composer | 2.x |
| Node.js + npm | 20+ (hanya saat build asset) |
| MySQL / MariaDB | 8.x / 10.4+ |
| Web server | Nginx atau Apache + PHP-FPM |
| Process manager | Supervisor atau systemd (untuk queue worker) |

---

## Instalasi awal (server baru)

### 1. Clone & dependensi

```bash
cd /var/www
git clone git@github.com:omanbuluatie/ujikom.git apotek
cd apotek

composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

### 2. Environment produksi

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env` (jangan commit file ini):

```env
APP_NAME="Apotek Makmur Jaya"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://apotek.domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek_makmur_jaya
DB_USERNAME=apotek_user
DB_PASSWORD=********

SESSION_LIFETIME=30
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
# MAIL_HOST=...
# MAIL_PORT=...
# MAIL_USERNAME=...
# MAIL_PASSWORD=...
```

### 3. Basis data — pilih salah satu

**Opsi A — Impor dump (disarankan untuk demo/staging):**

```bash
mysql -u apotek_user -p < database/dumps/apotek_makmur_jaya.sql
```

Dump sudah berisi skema revisi (`transaksi`, `notifikasi`, harga desimal, dll.).

**Opsi B — Migrasi + seeder:**

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 4. Storage & permission

```bash
php artisan storage:link

# Linux — sesuaikan user web server (www-data / nginx)
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Document root web server harus mengarah ke folder **`public/`**, bukan root proyek.

### 5. Cache produksi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Queue worker (wajib)

Tanpa worker, pembayaran dan potong stok FIFO tidak jalan.

Lihat bagian [Supervisor](#supervisor-queue-worker) di bawah.

---

## Deploy setelah `git pull`

Gunakan urutan ini setiap kali ada update dari repo.

### Langkah 1 — Backup database

```bash
mysqldump -u apotek_user -p \
  --single-transaction --routines --triggers \
  apotek_makmur_jaya > ~/backup/apotek-$(date +%Y%m%d-%H%M).sql
```

### Langkah 2 — Pull kode

```bash
cd /var/www/apotek
git pull origin main
```

> Jangan menimpa `.env` produksi. File `.env` ada di `.gitignore`.

### Langkah 3 — Dependensi

```bash
composer install --no-dev --optimize-autoloader

# Hanya jika ada perubahan CSS/JS (package.json / resources/)
npm ci && npm run build
```

### Langkah 4 — Migrasi database

**Server yang sudah punya data lama** (jangan pakai `migrate:fresh` — akan menghapus semua data):

```bash
php artisan migrate --force
```

Migrasi revisi (`2026_08_29_100000_revisi_domain_transaksi`) akan:

- Rename `pesanan` → `transaksi`, `nomor` → `kode_transaksi`
- Tambah `metode_pembayaran`, `bukti_pembayaran`, `alamat_pengiriman`
- Buat tabel `notifikasi`
- Ubah harga ke desimal, perluas field kategori obat

**Server baru / boleh reset total:**

```bash
mysql -u apotek_user -p < database/dumps/apotek_makmur_jaya.sql
# atau
php artisan migrate:fresh --seed --force
```

### Langkah 5 — Storage link (jika belum ada)

```bash
php artisan storage:link
```

Upload bukti bayar dan resep disimpan di `storage/app/public/pembayaran/` dan `storage/app/public/resep/`.

### Langkah 6 — Refresh cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika ada error aneh setelah deploy:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
# lalu cache ulang langkah di atas
```

### Langkah 7 — Restart queue worker

```bash
php artisan queue:restart
```

Supervisor/systemd akan otomatis spawn worker baru.

### Langkah 8 — Verifikasi

| Cek | URL / perintah |
|-----|----------------|
| Halaman utama | `/katalog` |
| Transaksi pasien | `/transaksi` (bukan `/pesanan`) |
| Admin transaksi + CSV | `/admin/transaksi` |
| Worker aktif | `php artisan queue:monitor` atau log supervisor |
| Upload bukti | Buat transaksi → upload → status berubah |
| Log error | `storage/logs/laravel.log` |

---

## Supervisor (queue worker)

Buat file `/etc/supervisor/conf.d/apotek-worker.conf`:

```ini
[program:apotek-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/apotek/artisan queue:work --queue=pembayaran,stok,impor,laporan,default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/apotek/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start apotek-worker:*
```

Setelah setiap deploy: `php artisan queue:restart`

---

## Scheduler (opsional)

Jika memakai penjadwalan Laravel (cek kedaluwarsa, dll.), tambahkan crontab:

```cron
* * * * * cd /var/www/apotek && php artisan schedule:run >> /dev/null 2>&1
```

---

## Nginx — contoh singkat

```nginx
server {
    listen 80;
    server_name apotek.domainanda.com;
    root /var/www/apotek/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 8M;
}
```

`client_max_body_size` minimal **8M** agar upload bukti bayar/resep (max 4 MB) tidak ditolak.

---

## Ringkas — skrip deploy

Simpan sebagai `deploy.sh` di server (opsional):

```bash
#!/bin/bash
set -e
cd /var/www/apotek

echo "==> Pull"
git pull origin main

echo "==> Composer"
composer install --no-dev --optimize-autoloader

echo "==> Migrate"
php artisan migrate --force

echo "==> Cache"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Restart queue"
php artisan queue:restart

echo "Deploy selesai."
```

Jalankan: `bash deploy.sh`

Tambahkan `npm ci && npm run build` jika ada perubahan frontend.

---

## Rollback

Jika deploy gagal:

1. Restore database dari backup:
   ```bash
   mysql -u apotek_user -p apotek_makmur_jaya < ~/backup/apotek-YYYYMMDD-HHMM.sql
   ```
2. Checkout commit sebelumnya:
   ```bash
   git log --oneline -5
   git checkout <commit-hash>
   composer install --no-dev --optimize-autoloader
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   php artisan queue:restart
   ```

---

## Dokumen terkait

| File | Isi |
|------|-----|
| [README.md](README.md) | Instalasi localhost & akun demo |
| [REVISI.md](REVISI.md) | Detail perubahan domain transaksi + lokasi baris kode |
| [database/dumps/apotek_makmur_jaya.sql](database/dumps/apotek_makmur_jaya.sql) | Dump SQL siap impor |

---

*Terakhir diperbarui: 29 Agustus 2026*
