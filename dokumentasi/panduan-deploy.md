# Panduan deploy & pull — server produksi

**Aplikasi:** Apotek Digital Klinik Makmur Jaya (Laravel 12)  
**Repo:** `git@github.com:omanbuluatie/ujikom.git`  
**Branch:** `main`

Dokumen ini untuk admin server. Panduan pemakaian aplikasi tetap di `panduan-pengguna.md`.

---

## 1. Prasyarat server

| Komponen | Minimum |
| --- | --- |
| OS | Linux (Ubuntu 22.04 / Debian 12 disarankan) |
| PHP | 8.2+ (ext: mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath, fileinfo, gd) |
| Composer | 2.x |
| Node.js | 20 LTS (untuk `npm run build`) |
| MySQL / MariaDB | 8.0+ / 10.6+ |
| Web server | Nginx atau Apache (document root → `public/`) |
| Git | dengan akses SSH ke GitHub |
| Proses supervisor | untuk `queue:work` (dan opsional `schedule:run`) |

---

## 2. Deploy pertama (clone)

```bash
# Contoh path — sesuaikan
sudo mkdir -p /var/www/ujikom
sudo chown -R $USER:www-data /var/www/ujikom
cd /var/www/ujikom

git clone git@github.com:omanbuluatie/ujikom.git .
```

### 2.1 Environment

```bash
cp .env.example .env
php artisan key:generate
nano .env   # atau editor lain
```

Isi wajib produksi:

```env
APP_NAME="Apotek Makmur Jaya"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://apotek.contoh.go.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek_makmur_jaya
DB_USERNAME=apotek_user
DB_PASSWORD=****ganti****

SESSION_DRIVER=database
SESSION_LIFETIME=30

QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local

# Produksi: ganti log dengan SMTP sungguhan jika tersedia
MAIL_MAILER=smtp
MAIL_HOST=smtp.contoh.go.id
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@contoh.go.id"
MAIL_FROM_NAME="${APP_NAME}"
```

Buat database & user MySQL terlebih dahulu, lalu:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
# Seeder hanya jika lingkungan baru & disetujui (akun demo Password1):
# php artisan db:seed --force

php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2.2 Izin folder

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 775 {} \;
sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
```

### 2.3 Web server (Nginx — cuplikan)

Document root **harus** `.../public`:

```nginx
server {
    listen 80;
    server_name apotek.contoh.go.id;
    root /var/www/ujikom/public;

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
}
```

Pasang TLS (Let's Encrypt) setelah DNS mengarah ke server.

### 2.4 Worker antrian (wajib)

Tanpa worker, pembayaran / potong stok / impor / laporan **tidak jalan**.

Contoh unit **Supervisor** (`/etc/supervisor/conf.d/apotek-worker.conf`):

```ini
[program:apotek-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ujikom/artisan queue:work --queue=pembayaran,stok,impor,laporan,default --sleep=1 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/ujikom/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start apotek-worker:*
```

`numprocs=2` = dua worker paralel (butir pemrosesan paralel).

### 2.5 Scheduler kedaluwarsa

Cron sebagai user yang menjalankan aplikasi:

```cron
* * * * * cd /var/www/ujikom && php artisan schedule:run >> /dev/null 2>&1
```

Job kedaluwarsa terjadwal jam **06:00**. Cek manual kapan saja:

```bash
php artisan apotek:cek-kedaluwarsa --sync
```

---

## 3. Pull update (deploy rutin)

Lakukan saat rilis baru sudah di `main` GitHub.

```bash
cd /var/www/ujikom

# 1) Mode maintenance (opsional tapi disarankan)
php artisan down --retry=60

# 2) Ambil kode terbaru
git fetch origin
git checkout main
git pull origin main

# 3) Dependensi
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# 4) Migrasi & cache
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5) Restart worker agar memuat kode baru
php artisan queue:restart
# atau:
# sudo supervisorctl restart apotek-worker:*

# 6) Hidupkan lagi
php artisan up
```

### Checklist setelah pull

- [ ] Halaman beranda / katalog terbuka (HTTPS)  
- [ ] Login admin OK  
- [ ] `supervisorctl status` — worker RUNNING  
- [ ] Uji satu **Bayar sekarang** → status berubah  
- [ ] Gambar obat/resep tidak 404 (`storage:link`)  
- [ ] Tidak ada error baru di `storage/logs/laravel.log`

---

## 4. Script ringkas (opsional)

Simpan sebagai `deploy.sh` di root proyek (jalankan di server, jangan commit secret):

```bash
#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

php artisan down --retry=60 || true
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan queue:restart
php artisan up
echo "Deploy selesai: $(git rev-parse --short HEAD)"
```

```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 5. Rollback cepat

### Kode

```bash
php artisan down
git log --oneline -15
git checkout <hash-stabil>
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force   # hati-hati jika migrasi tidak mundur
php artisan optimize
php artisan queue:restart
php artisan up
```

### Database

Restore dump MySQL dari backup **sebelum** deploy bermasalah. Hentikan worker dulu:

```bash
sudo supervisorctl stop apotek-worker:*
# restore dump ...
sudo supervisorctl start apotek-worker:*
```

---

## 6. Yang tidak boleh di server produksi

| Jangan | Alasan |
| --- | --- |
| `APP_DEBUG=true` | Bocor stack trace |
| Commit / upload `.env` | Berisi password DB & mail |
| Document root ke folder proyek (bukan `public/`) | File sensitif bisa terbaca |
| `php artisan serve` sebagai layanan utama | Hanya untuk lokal/demo |
| `queue:work` tanpa `--queue=pembayaran,stok,impor,laporan,default` | Bayar/stok/impor macet |
| `migrate:fresh` / `db:seed` di produksi hidup | Menghapus data |

---

## 7. Troubleshooting deploy

| Gejala | Perbaikan |
| --- | --- |
| 500 setelah pull | `storage/logs/laravel.log`; `php artisan config:clear` lalu `config:cache` lagi |
| CSS/JS lama | `npm run build`; hard refresh; pastikan `public/build` ter-generate |
| Permission denied `storage` | Perbaiki owner/chmod bagian 2.2 |
| Job tidak jalan | Cek Supervisor + daftar `--queue=...` |
| SSH git gagal | Pastikan deploy key / kunci SSH user server terdaftar di GitHub |
| Mixed content | `APP_URL` harus `https://...` |

---

## 8. Ringkasan alur

```
Laptop/dev ──git push──► GitHub (main)
                              │
                         git pull
                              ▼
                    Server produksi
                    composer + npm build
                    migrate + optimize
                    queue:restart
                              ▼
                    Nginx → public/index.php
                    Supervisor → queue:work
                    Cron → schedule:run
```
