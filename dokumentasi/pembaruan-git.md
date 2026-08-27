# Pembaruan perangkat lunak via Git

## Alur update (lingkungan demo / staging)

```powershell
# 1. Simpan pekerjaan lokal
git status

# 2. Ambil versi baru
git pull origin main

# 3. Dependensi PHP & JS
composer install --no-interaction
npm ci
npm run build

# 4. Basis data & cache
php artisan migrate --force
php artisan config:clear
php artisan view:clear
php artisan storage:link

# 5. Nyalakan ulang worker
php artisan queue:restart
```

Lalu jalankan lagi:

```powershell
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

## Rollback kode
```powershell
git log --oneline -15
git checkout <hash-versi-stabil>
composer install
npm run build
php artisan migrate
```

Jika migrasi DB tidak kompatibel mundur, restore backup DB (lihat `rencana-rollback.md`).

## Bukti untuk asesor
Tampilkan `git log` singkat sebagai bukti **update software** dan jejak perubahan, bersama `log-perubahan.md`.
