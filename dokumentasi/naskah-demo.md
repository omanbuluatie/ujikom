# Naskah Demo Presentasi  
## Sistem E-Commerce Penjualan Obat — Klinik Makmur Jaya

**Durasi target:** 35–40 menit demo + 15–20 menit tanya jawab  
**Produk:** 1 aplikasi web Laravel 12 + paket dokumentasi  
**Tujuan:** Membuktikan seluruh butir uji kompetensi Web Developer (analisis → perancangan → pengembangan → pengujian → implementasi → monitoring → dokumentasi)

Gunakan naskah ini seperti skrip. Jangan improvisasi urutan pada bagian inti (FIFO, antrian, migrasi, keamanan).

---

## 0. Persiapan 10 menit sebelum mulai

### Checklist teknis

- [ ] MySQL `apotek_makmur_jaya` hidup  
- [ ] `php artisan migrate:fresh --seed` sudah dijalankan (atau data demo utuh)  
- [ ] `php artisan storage:link`  
- [ ] Terminal A: `php artisan serve --host=127.0.0.1 --port=8000`  
- [ ] Terminal B: `php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3`  
- [ ] Terminal C (opsional paralel): perintah yang sama  
- [ ] Browser: jendela 1 (pasien), jendela 2 (apoteker/admin) — atau pakai profil/incognito  
- [ ] Siapkan 1 foto resep palsu (JPG) di Desktop  
- [ ] File CSV migrasi: `dokumentasi/contoh-data/obat-lama.csv`  
- [ ] File CSV kedaluwarsa: `dokumentasi/contoh-data/obat-kedaluwarsa-demo.csv`  
- [ ] Buka `dokumentasi/panduan-pengguna.md` dan `RENCANA.md` di tab cadangan  
- [ ] Catat password demo: `Password1`

### Kalimat pembuka (30 detik)

> “Assalamu’alaikum / selamat pagi. Saya akan mendemonstrasikan sistem e-commerce penjualan obat Klinik Makmur Jaya. Klinik ini melayani 150–200 pasien per hari dengan lebih dari 2.000 jenis obat. Masalah utamanya: penjualan manual, belum ada beli online, stok tidak real-time, laporan terpisah, dan verifikasi resep manual. Sistem ini menyelesaikan kelima masalah itu dalam satu alur sederhana tetapi robust.”

---

## BAGIAN A — Analisis & Perancangan (4 menit)

### A1. Lingkup & produk (1 menit)

**Tampilkan:** `RENCANA.md` atau slide ringkas.

**Ucapkan:**

> “Lingkup in: katalog, keranjang, bayar, resep, FIFO, kasir, dasbor, migrasi CSV, laporan PDF, monitoring. Lingkup out: payment gateway sungguhan, Redis, aplikasi mobile. Keputusan ini menjaga 18 jam efektif tetap cukup, tetapi setiap butir kompetensi punya satu bukti.”

**Butir tertutup:** project integration, scope, quality management (arahkan ke dokumen charter/WBS bila ditanya).

### A2. Arsitektur perangkat keras & topologi (2 menit)

**Tampilkan:** diagram di papan / `dokumentasi/arsitektur.md` (jika ada) / gambar sederhana:

```
Pengguna → Web Server (Nginx/Apache)
        → Aplikasi Laravel 12
        → MySQL (obat, batch, pesanan)
        → Worker antrian (paralel)
```

**Ucapkan spek rancangan (bukan spek laptop demo):**

| Node | Spek rancangan |
| --- | --- |
| Web + App | 2 vCPU, RAM 4 GB, SSD 80 GB |
| Database | 2 vCPU, RAM 4 GB, SSD 100 GB |
| Bandwidth | ±20 Mbps (halaman ringan, gambar terkompresi) |

**Skalabilitas (wajib disebut):**

> “Untuk 150–200 pasien/hari: pagination katalog, indeks pada kode/nama/status, dan menambah worker antrian dulu. Jika transaksi naik, baru pisah server database. Tidak langsung microservices.”

**Butir tertutup:** arsitektur perangkat keras, analisis skalabilitas.

### A3. Analisis tools (1 menit)

| Pilihan | Alasan singkat |
| --- | --- |
| Laravel 12 | MVC jelas, CSRF/XSS/hash/queue/migrasi bawaan |
| MySQL/MariaDB | Relasi stok–batch–transaksi–laporan |
| Blade + Alpine | Cepat UAT, tanpa SPA berat |
| Database queue | Paralel tanpa Redis |
| DomPDF | Export laporan |
| Git | Update software & rollback kode |
| Polling 10 dtk | Real-time yang stabil di laptop demo |

**Butir tertutup:** analisis tools, library/framework.

---

## BAGIAN B — Keamanan & Autentikasi (4 menit)

### B1. Empat peran (1,5 menit)

Login singkat tunjukkan menu berbeda:

1. `pasien@makmurjaya.test` → Katalog, Keranjang, Pesanan  
2. `kasir@makmurjaya.test` → Kasir  
3. `apoteker@makmurjaya.test` → Resep + Stok FIFO  
4. `admin@makmurjaya.test` → Papan antrian + CRUD + Migrasi + Pemantauan  

Coba buka URL admin sebagai pasien → **403**.

**Ucapkan:** “Hak akses berbasis enum `peran` + middleware `PastikanPeran`.”

### B2. Email verification, password, session (1,5 menit)

- Tunjukkan form daftar: validasi min 8 + huruf + angka.  
- Sebut password di-hash bcrypt (buka sejenak model `User` cast `hashed` bila diminta).  
- Session lifetime 30 menit di `.env` (`SESSION_LIFETIME=30`).  
- Form punya `@csrf`.

### B3. Proteksi injeksi & audit (1 menit)

- Pencarian memakai Eloquent binding (bukan SQL string dari input).  
- Teks di Blade `{{ }}` → XSS escape.  
- Buka **Pemantauan → Audit** setelah aksi login (jejak `masuk`).

**Butir tertutup:** role, email verification, hashing, validasi password, SQL injection, XSS, CSRF, session timeout, audit log, risiko keamanan (arahkan ke dokumen risiko).

---

## BAGIAN C — Alur pasien: katalog sampai bayar (7 menit)

Tetap login sebagai **pasien**.

### C1. Fuzzy search & autocomplete (2 menit)

1. Di katalog ketik `paracet` (bukan ejaan sempurna).  
2. Tunjukkan dropdown autocomplete.  
3. Filter kategori / urut harga.  
4. Buka detail Paracetamol.

**Ucapkan:**

> “Fuzzy search di sini sederhana dan bisa dijelaskan: SQL LIKE + skor `similar_text`, diurutkan nama diawali kata, kode persis, lalu skor kemiripan. Cukup untuk 2.000 obat tanpa Elasticsearch.”

**Buka kode singkat (opsional 20 dtk):** `app/Layanan/LayananPencarian.php` method `cariFuzzy` / `autocomplete`.

### C2. Multimedia & informasi FIFO (1 menit)

Di detail obat:

- Tunjukkan strip kuning FIFO + daftar batch (batch lama di atas / disorot).  
- Jelaskan: stok tampilan = SUM sisa batch, bukan kolom terpisah.

### C3. Cart → checkout → pembayaran antrian (4 menit)

1. Masukkan **Paracetamol** (bebas resep) ke keranjang.  
2. Checkout → buat pesanan.  
3. Di halaman bayar, tunjukkan nomor tiket seperti papan antrian.  
4. Klik **Bayar sekarang**.  
5. Tunjukkan terminal `queue:work` memproses job.  
6. Kembali ke **Pesanan saya** — status berubah.  
7. Opsional: buka `storage/logs/laravel.log` → email status (driver log).

**Ucapkan:**

> “HTTP request hanya men-dispatch `JobProsesPembayaran`. Ini memenuhi job queue pembayaran tanpa Midtrans. Robust: status dan email dicatat setelah job sukses.”

**Butir tertutup:** algoritma pencarian, multimedia, cart, checkout, pembayaran, konfirmasi, job queue, email notifikasi status.

---

## BAGIAN D — Resep, FIFO, paralel (8 menit)

### D1. Pesanan obat keras (3 menit)

Sebagai pasien:

1. Beli **Amoxicillin** (tiket Resep).  
2. Bayar (pastikan worker hidup).  
3. Status **Unggah resep** → unggah foto.  
4. Status **Menunggu verifikasi**.

### D2. Verifikasi apoteker + potong stok FIFO (3 menit)

Ganti akun / jendela 2: `apoteker@makmurjaya.test`

1. Buka **Antrian resep**.  
2. Tunjukkan foto + daftar item.  
3. **Setujui & potong stok**.  
4. Worker menjalankan `JobPotongStok`.  
5. Buka **Laci stok FIFO** — batch tertua berkurang.  
6. Kembali ke katalog pasien — sisa ikut berubah.

**Buka kode (wajib jika ada waktu):** `LayananStok::potongStokFifo`

**Ucapkan prosedur:**

1. Kunci baris batch (`lockForUpdate`) — aman untuk paralel.  
2. Urut `tanggal_masuk` naik (FIFO).  
3. Kurangi sisa, catat `mutasi_stok`.  
4. Jika kurang → exception + rollback — stok tidak negatif.

### D3. Bukti paralel (2 menit)

- Tunjukkan dua worker, atau  
- Di admin, impor CSV sambil pesanan lain dibayar — job di antrian berbeda (`pembayaran`, `stok`, `impor`).  
- Sebut `Bus::batch` untuk impor baris.

**Butir tertutup:** verifikasi resep, FIFO, job update stok, pemrograman paralel, background processing.

---

## BAGIAN E — Kasir & sinkronisasi (3 menit)

Login `kasir@makmurjaya.test`

1. Catat sisa **CTM** atau **Ibuprofen** di layar kasir.  
2. Centang 1 obat, jumlah 1 → simpan.  
3. Tunjukkan struk sementara (total).  
4. Buka katalog / laci stok — angka turun.

**Ucapkan:** “Tidak ada sinkronisasi file. Satu fungsi stok = satu sumber kebenaran. Itu solusi sinkronisasi penjualan offline/counter dengan online.”

**Butir tertutup:** transaksi kasir, sinkronisasi counter ↔ online.

---

## BAGIAN F — Dasbor real-time, alert, monitoring (5 menit)

Login `admin@makmurjaya.test`

### F1. Papan antrian (2 menit)

Tunjukkan:

- Pendapatan harian/mingguan/bulanan  
- Grafik batang 14 hari  
- Obat terlaris  
- Stok kritis (CTM di seeder memang di bawah minimum)  
- Peringatan kedaluwarsa (Vitamin C mendekati 30 hari di seeder)  
- **Lebih kuat:** jika sudah impor `obat-kedaluwarsa-demo.csv` + `php artisan schedule:run`, tunjukkan alert `KD-xxx` (sudah expired / 30 / 60 / 90 hari)

Biarkan halaman terbuka ±10 detik — sebut badge polling.

> “Real-time memakai polling 10 detik, bukan WebSocket, agar demo stabil dan mudah dijelaskan.”

### F2. Severity Critical / Warning / Info (1 menit)

Tunjuk tiket warna di daftar peringatan / pemantauan.  
Sebut mapping: kritis→Critical, peringatan→Warning, info→Info.

### F3. Pemantauan resource (2 menit)

Buka **Pemantauan**:

- Job menunggu / gagal  
- Sesi aktif  
- Log kesalahan  
- Audit log  

Opsional: jalankan `php artisan apotek:cek-kedaluwarsa --sync` untuk `JobCekKedaluwarsa` (jangan pakai `schedule:run` kecuali jam 06:00).

**Butir tertutup:** dashboard, real-time, alert stok, kedaluwarsa 30/60/90, monitoring resource, error notification, debugging (log kesalahan).

---

## BAGIAN G — SQL laporan & PDF (3 menit)

1. Buka **Laporan**.  
2. Filter tanggal.  
3. Tunjukkan terlaris, kedaluwarsa, rekap.  
4. **Unduh PDF**.  
5. Sebut query ada di `LayananLaporan` (binding, bukan SQL mentah).  
6. Opsional: **Antrian laporan besar** → file di `storage/app/public/laporan`.

**Butir tertutup:** SQL laporan penjualan, obat terlaris, kedaluwarsa, rekap, export PDF, job laporan.

---

## BAGIAN H — Migrasi, cutover, rollback, update Git (5 menit)

### H1. Migrasi Excel → sistem (3 menit)

1. Buka **Migrasi CSV**.  
2. Baca tabel **mapping field** keras di layar.  
3. Baca checklist cutover.  
4. Unggah `dokumentasi/contoh-data/obat-lama.csv` (obat umum `XL-xxx`).  
5. Worker memproses batch paralel.  
6. Tunjukkan log sukses/gagal per baris.  
7. Cari obat baru di katalog (mis. OBH Mix Anak / `XL-001`).  
8. **Opsional kuat untuk alert:** unggah juga `obat-kedaluwarsa-demo.csv` (`KD-xxx`), lalu `php artisan apotek:cek-kedaluwarsa --sync`, tunjukkan peringatan di dasbor.  
9. Demo **Rollback** batch (jelaskan dulu, lalu jalankan jika waktu cukup).

**Ucapkan:** “Ini simulasi migrasi teknologi dari sistem manual/Excel ke e-commerce: mapping, validasi, impor, verifikasi, rollback.”

### H2. Update software Git (1 menit)

Tampilkan `git log --oneline -5` (jika repo sudah diinisialisasi) atau jelaskan alur:

```
git pull → composer install → php artisan migrate → npm run build → queue:restart
```

### H3. Analisis dampak (1 menit)

> “Jika modul stok berubah, dampaknya ke kasir, pesanan online, laporan, peringatan, dan migrasi — karena semua lewat `LayananStok`. Itu sengaja: satu titik ubah, dampak terkendali. Detail ada di dokumen analisis dampak.”

**Butir tertutup:** migrasi teknologi, mapping, validasi, rollback, cutover, update software, impact analysis.

---

## BAGIAN I — CRUD, UAT, dokumentasi (3 menit)

### I1. CRUD cepat (1,5 menit)

Sebagai admin, tunjukkan daftar:

- Obat (edit singkat / sebut unggah gambar)  
- Kategori, Pemasok, Pelanggan  
- Transaksi (detail 1 pesanan)

### I2. UAT & dokumen (1,5 menit)

Tunjukkan folder `dokumentasi/`:

- `panduan-pengguna.md` (user guide + FAQ ≥10 + troubleshooting)  
- `naskah-demo.md` (dokumen ini)  
- contoh CSV migrasi  

Sebut artefak lain yang dilampirkan pada paket penilaian: piagam, WBS, jadwal, arsitektur, risiko, checklist mutu, change log, UAT, cutover, rollback.

**Butir tertutup:** CRUD lengkap, dokumentasi pelanggan, UAT (arahkan ke `dokumentasi/uat.md`), presentasi.

---

## BAGIAN J — Penutup (1 menit)

### Kalimat penutup

> “Saya telah menunjukkan satu aplikasi yang menyelesaikan lima masalah klinik, dengan bukti keamanan, FIFO, antrian paralel, real-time polling, migrasi Excel, laporan SQL/PDF, dan monitoring. Arsitekturnya sengaja sederhana agar robust dan bisa dijelaskan. Siap menerima pertanyaan.”

### Ringkasan bukti dalam 20 detik

| Masalah klinik | Bukti yang baru dilihat |
| --- | --- |
| Penjualan manual | Kasir counter |
| Belum online | Katalog → bayar |
| Stok tidak real-time | Strip FIFO + dasbor polling |
| Laporan tidak terintegrasi | Laporan + PDF |
| Resep manual | Unggah + verifikasi apoteker |

---

## Lampiran 1 — Matriks telusur demo → butir kompetensi

Centang saat latihan. Semua harus bisa ditunjukkan atau dirujuk dokumen.

### A. Analisis & perancangan

| Butir | Segmen naskah |
| --- | --- |
| Arsitektur perangkat keras | A2 |
| Analisis tools | A3 |
| Skalabilitas | A2 |
| Risiko keamanan | B + dokumen |
| Integrasi / scope / mutu | A1 + dokumen |

### B. Pengembangan & implementasi

| Butir | Segmen naskah |
| --- | --- |
| Algoritma (FIFO, fuzzy, autocomplete) | C1, D2 |
| SQL | G |
| Library/framework | A3 |
| Migrasi teknologi | H1 |
| Update software | H2 |
| Real-time | F1 |
| Paralel | D3, H1 |
| Multimedia | C2, D1 |
| Cutover | H1 |
| Monitoring resource | F3 |
| Alert notification | F1–F2 |
| Analisis dampak | H3 |

### C. Pengujian

| Butir | Segmen naskah |
| --- | --- |
| Debugging | F3 (log kesalahan) |
| UAT | I2 |

### D. Keamanan & bisnis

| Butir | Segmen naskah |
| --- | --- |
| 4 role | B1 |
| Email verification | B2 |
| Hashing / validasi password | B2 |
| SQL Injection / XSS / CSRF | B3 |
| Session timeout | B2 |
| Audit log | B3, F3 |
| Dashboard angka + grafik | F1 |
| CRUD Obat/Kategori/Supplier/Pelanggan/Transaksi/Resep | I1, D |
| Cart/checkout/bayar/konfirmasi/resep | C3, D |
| Kasir sinkron | E |
| PDF | G |
| FAQ / user guide | I2 |

---

## Lampiran 2 — Timer ringkas (35 menit)

| Menit | Segmen |
| --- | --- |
| 0–4 | A Analisis & arsitektur |
| 4–8 | B Keamanan & peran |
| 8–15 | C Katalog → bayar |
| 15–23 | D Resep + FIFO + paralel |
| 23–26 | E Kasir sinkron |
| 26–31 | F Dasbor + monitoring |
| 31–34 | G Laporan PDF |
| 34–39 | H Migrasi + Git + dampak |
| 39–42 | I CRUD + dokumen + penutup |
| +15–20 | Tanya jawab |

Jika hanya 30 menit: potong penjelasan A3 dan H3 menjadi 20 detik; jangan potong D (FIFO) dan H1 (migrasi).

---

## Lampiran 3 — Pertanyaan asesor yang sering & jawaban singkat

**Q: Mengapa tidak pakai Redis / WebSocket?**  
A: Database queue + polling cukup untuk beban 150–200 pasien/hari di demo; lebih mudah dijelaskan dan di-debug.

**Q: Mengapa FIFO tidak memakai kolom stok tunggal?**  
A: Kolom tunggal mudah drift. Batch + SUM sisa menjaga jejak masuk/keluar dan kedaluwarsa.

**Q: Di mana bukti paralel?**  
A: Dua `queue:work`, job antrian terpisah, dan `Bus::batch` impor CSV.

**Q: Bagaimana rollback migrasi?**  
A: Hapus obat hasil `batch_migrasi_id` + tandai log rollback; tidak menghapus data operasional lain.

**Q: Bagaimana XSS dicegah?**  
A: Blade `{{ }}` melakukan escape; unggahan gambar disimpan di storage, bukan dieksekusi sebagai skrip.

**Q: Apa dampak ubah modul stok?**  
A: Kasir, pesanan online, alert, laporan mutasi — semua lewat `LayananStok`; lihat dokumen analisis dampak.

---

## Lampiran 4 — Data demo yang sengaja disiapkan

| Data | Fungsi di demo |
| --- | --- |
| Paracetamol 2 batch | FIFO terlihat (batch lama dulu) |
| Amoxicillin `butuh_resep` | Alur resep |
| CTM stok 8 / min 25 | Alert stok kritis |
| Vitamin C exp ±25 hari | Alert kedaluwarsa ≤30 |
| CSV `obat-lama.csv` | Migrasi 30 obat umum (`XL-xxx`) |
| CSV `obat-kedaluwarsa-demo.csv` | Alert expired + 30/60/90 (`KD-xxx`) |

Jika data hilang: `php artisan migrate:fresh --seed`.

---

**Dokumen terkait**

- `dokumentasi/panduan-pengguna.md` — panduan pelanggan + FAQ + troubleshooting  
- `RENCANA.md` — arsitektur & matriks kompetensi  
- `dokumentasi/contoh-data/obat-lama.csv` — bahan migrasi  

Latihan minimal 2 kali dengan stopwatch sebelum hari presentasi.
