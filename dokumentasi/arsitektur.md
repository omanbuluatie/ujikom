# Arsitektur perangkat keras & topologi

## Diagram (sesuai studi kasus)

```
Pengguna (browser)
        │
        ▼
Web Server (Nginx/Apache) ──► Aplikasi Laravel (PHP 8.2+)
                                    │
                    ┌───────────────┼───────────────┐
                    ▼               ▼               ▼
                 MySQL 8      Worker antrian    Log email + peringatan
              (data bisnis)   (paralel job)     (MAIL_MAILER=log)
```

Lokal demo = **satu mesin**. Dokumen tetap menggambarkan pemisahan logis web/app/DB sesuai spek klinik.

## Spek rancangan (150–200 pasien/hari, >2.000 obat)

| Node | Spek |
| --- | --- |
| Web + aplikasi | 2 vCPU, RAM 4 GB, SSD 80 GB |
| Database | 2 vCPU, RAM 4 GB, SSD 100 GB |
| Bandwidth | 20 Mbps (halaman ringan, gambar terkompresi) |

## Skalabilitas naik (bertahap)

1. Tambah worker antrian (`queue:work`)  
2. Indeks SQL pada kolom cari / foreign key  
3. Pisah server database  
4. Cache (nanti) jika traffic naik  

## Pilihan real-time
Polling **10 detik** ke `/admin/api/realtime` dan `/admin/dasbor/polling` — cukup untuk volume klinik; stabil di laptop demo tanpa WebSocket/Redis.

## Stack perangkat lunak

| Lapisan | Teknologi |
| --- | --- |
| Framework | Laravel 12 |
| UI | Blade + Alpine.js + Tailwind CSS 4 |
| DB | MySQL / MariaDB |
| Antrian | Database queue |
| PDF | DomPDF |
| Auth | Breeze Blade + enum `peran` |
