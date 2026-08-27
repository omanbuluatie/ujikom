# Pengelolaan risiko keamanan informasi

## Matriks risiko

| Risiko | Dampak | Kemungkinan | Mitigasi di sistem |
| --- | --- | --- | --- |
| Akses tanpa hak (horizontal/vertical) | Tinggi | Sedang | Middleware `peran` + `auth` + `verified` |
| Pencurian kata sandi | Tinggi | Sedang | bcrypt; validasi min 8 + huruf + angka |
| Akun palsu / spam | Sedang | Sedang | Verifikasi email wajib sebelum transaksi |
| SQL Injection | Tinggi | Rendah | Eloquent / query binding; tanpa SQL mentah dari input |
| XSS | Tinggi | Rendah | Escape Blade `{{ }}` |
| CSRF | Tinggi | Rendah | `@csrf` / middleware CSRF Laravel |
| Session hijacking / idle | Sedang | Sedang | `SESSION_LIFETIME=30` |
| Manipulasi stok | Tinggi | Sedang | `DB::transaction` + `lockForUpdate` FIFO; audit log |
| Unggah file berbahaya | Sedang | Sedang | Validasi MIME/ukuran gambar resep & obat |
| Kebocoran data API | Sedang | Rendah | Realtime hanya admin; autocomplete hanya data katalog |

## Kontrol tambahan
- Audit (`LogAudit`) pada CRUD, login, transaksi, migrasi, verifikasi resep  
- Error severity (`log_kesalahan`: kritis / peringatan / info) di Pemantauan  
- Role gate di menu navigasi (UI) + middleware (server)

## Sisa risiko yang diterima (demo)
- Email tidak lewat SMTP sungguhan (driver `log`)  
- Pembayaran tanpa gateway — disengaja untuk lingkup ujikom  

Dokumen terkait: `checklist-mutu.md`, bagian keamanan di `panduan-pengguna.md`.
