# Analisis tools

| Kebutuhan | Pilihan | Alternatif | Alasan memilih |
| --- | --- | --- | --- |
| Framework web | **Laravel 12** | PHP native | MVC, CSRF/XSS/hash/queue/migrasi bawaan; cepat untuk UAT |
| UI | **Blade + Alpine + Tailwind** | React/Vue SPA | Cepat dibangun, mudah dijelaskan asesor, tanpa kompleksitas SPA |
| Database | **MySQL 8** | SQLite | Relasi stok–transaksi–laporan; cocok spek klinik |
| Antrian paralel | **Database queue** | Redis | Tanpa infrastruktur ekstra; 2 worker cukup untuk demo |
| Real-time | **Polling 10 dtk** | WebSocket / Pusher | Cukup 150–200 pasien/hari; stabil lokal |
| Pencarian | **`cariFuzzy()` PHP** | Elasticsearch | Sederhana, bisa dijelaskan, cukup untuk katalog demo |
| PDF | **DomPDF** | Snappy | Mudah diintegrasikan Laravel |
| Impor Excel lama | **CSV native** | Laravel Excel | Ringan; Excel diekspor ke CSV dulu |
| Auth | **Breeze + peran** | Passport/Sanctum API | Email verification & session siap |
| Email | **Mail log** | SMTP | Bukti notifikasi tanpa setup SMTP |
| Versi | **Git** | Copy folder | Update software & rollback kode |
| Editor / kolaborasi | Cursor / VS Code | — | Produktivitas pengembang |

## Kesimpulan
Stack dipilih agar **setiap butir ujikom punya satu bukti**, bukan agar sistem produksi skala enterprise.
