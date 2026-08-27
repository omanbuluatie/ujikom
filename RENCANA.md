# Rencana selaras dokumen tugas

Sumber tugas: `readme.md` (uji kompetensi Web Developer + studi kasus Klinik Makmur Jaya).

Aplikasi: **Laravel sederhana, alur robust, setiap butir assessment punya satu bukti demo.** Bukan sistem produksi.

---

## 0. Status keselarasan

Rencana sebelumnya **sudah mengarah benar**, tetapi belum 1:1 dengan dokumen tugas.

| Status | Butir tugas | Masalah di rencana lama | Perbaikan di dokumen ini |
| --- | --- | --- | --- |
| Kurang eksplisit | CRUD **Pelanggan** | Hanya “pengguna”, tidak ada menu Pelanggan | Menu Admin → Pelanggan (`peran = pasien`) |
| Kurang eksplisit | CRUD **Transaksi** | Hanya alur pesanan | Daftar/ubah status/batalkan transaksi |
| Kurang eksplisit | **Fuzzy search** | Diganti “peringkat LIKE” | Fungsi `cariFuzzy()` tetap sederhana (`LIKE` + `similar_text`) |
| Kurang eksplisit | **Pembayaran** & **konfirmasi pesanan** | Hanya job di belakang | Halaman bayar simulasi + status `dikonfirmasi` |
| Kurang | **Email** (ada di diagram tugas) | Dilewati | 1 mail Laravel (`log` driver), cukup untuk demo |
| Kurang | **Dokumentasi API** | Disebut “pendek” | `dokumentasi/api.md` untuk 2 endpoint JSON |
| Kurang | Spek **150–200 pasien/hari**, **2.000 obat** | Skalabilitas umum | Angka studi kasus masuk arsitektur |
| Lebih dari tugas | 14 layar + cutover UI terpisah | Membengkak | 11 layar; cutover menumpang menu Migrasi |
| Lebih dari tugas | Laravel Excel, `item_resep`, keranjang DB | Berat untuk 18 jam | CSV native, 1 resep per pesanan, keranjang sesi |

Setelah penyelarasan: **semua butir `readme.md` tertutup**, tanpa fitur di luar tugas.

---

## 1. Produk yang diminta asesor

Tiga artefak, tidak kurang:

1. **Aplikasi** e-commerce penjualan obat Klinik Makmur Jaya
2. **Paket dokumentasi** proyek + pelanggan
3. **Demo 30–45 menit** + siap tanya jawab 15–20 menit

Lima masalah studi kasus yang harus kelihatan teratasi:

| Masalah klinik | Bukti di aplikasi |
| --- | --- |
| Penjualan masih manual | Kasir input transaksi di web |
| Belum ada beli obat online | Katalog → keranjang → checkout → bayar |
| Stok belum real-time | Dasbor polling 10 detik + potong FIFO |
| Laporan tidak terintegrasi | SQL laporan + unduh PDF |
| Verifikasi resep manual | Unggah gambar resep → apoteker setuju/tolak |

---

## 2. Prinsip bangun (supaya sederhana tapi lulus)

1. **Satu jalur bisnis.** Semua kompetensi numpang di: pesan → bayar → resep (jika perlu) → FIFO → notifikasi.
2. **Satu bukti per butir tugas.** Jangan dua cara untuk satu kompetensi.
3. **Gagal dengan aman.** Transaksi DB, stok tidak negatif, baris impor rusak tidak menggagalkan baris valid.
4. **Nama Indonesia** di kelas domain, variabel, komentar, UI. Tabel auth tetap `users` (bawaan Laravel).
5. **Bisa dijelaskan.** Komentar menulis *mengapa*, karena Q&A asesor menanyakan alasan pilihan.

Tidak dibuat: SPA, Redis, Midtrans, Elasticsearch, Docker cluster, mobile, multi-gudang.

---

## 3. Matriks telusur (dokumen tugas → bukti)

Ini acuan bangun dan acuan naskah demo. Jika suatu baris tidak bisa ditunjukkan, item itu belum selesai.

### A. Analisis & perancangan

| Butir tugas | Bukti |
| --- | --- |
| Arsitektur perangkat keras | `dokumentasi/arsitektur.md` + diagram User → Web Server → Aplikasi → Database |
| Analisis tools | Tabel Laravel vs PHP native, MySQL vs SQLite, Git vs copy folder |
| Skalabilitas | Hitungan 150–200 pasien/hari, 2.000 obat; pagination, indeks, tambah worker |
| Risiko keamanan informasi | `dokumentasi/risiko-keamanan.md` + mitigasi di kode |
| Integrasi, scope, mutu | Piagam, WBS, checklist mutu |

### B. Pengembangan & implementasi

| Butir tugas | Bukti di kode (satu saja) |
| --- | --- |
| Algoritma | FIFO, `cariFuzzy()`, autocomplete |
| SQL | `LayananLaporan`: penjualan, terlaris, kedaluwarsa, rekap |
| Library/framework | Laravel 12, DomPDF, Alpine.js |
| Migrasi teknologi | Excel/CSV lama → impor obat |
| Update software | Git + `dokumentasi/pembaruan-git.md` |
| Real-time | Dasbor muat ulang stok & pesanan tiap 10 detik |
| Paralel | 2 worker + `Bus::batch()` impor; job beberapa pesanan |
| Multimedia | Unggah/pratinjau gambar obat dan resep |
| Cutover | Menu Migrasi: checklist, jalankan, verifikasi, rollback |
| Monitoring resource | Halaman Pemantauan: antrian, error, stok |
| Alert | Stok min, kedaluwarsa 30/60/90, pesanan, error |
| Dampak perubahan | `dokumentasi/analisis-dampak.md` (ubah modul stok) |

### C. Pengujian

| Butir tugas | Bukti |
| --- | --- |
| Debugging | `log_kesalahan` + 1 contoh bug stok yang sudah diperbaiki |
| UAT | `dokumentasi/uat.md` (skenario + hasil) |

### D. Keamanan (wajib di aplikasi)

| Butir tugas | Implementasi |
| --- | --- |
| Role admin, apoteker, kasir, pasien | Enum `peran` + middleware |
| Email verification | Breeze, middleware `verified` |
| Password hashing | bcrypt Laravel |
| Validasi password | min 8, ada huruf dan angka |
| SQL Injection | Eloquent / binding, tanpa SQL mentah dari input |
| XSS | `{{ }}` Blade |
| CSRF | `@csrf` |
| Session timeout | `SESSION_LIFETIME=30` |
| Audit log | `LogAudit` pada CRUD dan transaksi |
| Analisis risiko | dokumen + role gate |

### E. Fitur bisnis dari studi kasus

| Butir tugas | Implementasi sederhana |
| --- | --- |
| Dasbor penjualan harian/mingguan/bulanan | 3 angka + 1 grafik |
| Stok obat + pendapatan + grafik | Kartu dasbor |
| Katalog, search, filter, detail | Halaman katalog |
| Upload/preview gambar | Obat + resep |
| Notifikasi stok kritis & pesanan baru | Tabel `peringatan` + badge dasbor |
| Export PDF | Laporan penjualan |
| CRUD Obat, Kategori, Supplier, Pelanggan, Transaksi, Resep | 6 menu admin/staf |
| Autocomplete | `GET /api/obat/autocomplete` |
| Fuzzy search | `LayananPencarian::cariFuzzy()` |
| FIFO, pagination, sorting, filtering | `LayananStok` + `FilterDaftar` |
| Cart, checkout, pembayaran, konfirmasi, verifikasi resep | Alur pasien + apoteker |
| Alert kedaluwarsa 30/60/90 hari | Scheduler harian |
| Notifikasi status pesanan | Daftar pesanan + email log |
| Error severity critical / warning / info | `log_kesalahan.tingkat` |
| Paralel pesanan, batch impor, job laporan, job bayar, job stok | Laravel Queue |
| Sinkron counter ↔ online | Kasir dan pasien memanggil `LayananStok` yang sama |
| Migrasi field mapping, validasi, rollback, checklist, verifikasi | Menu Migrasi + CSV contoh |
| User guide, FAQ ≥ 10, API, troubleshooting | folder `dokumentasi/` |

---

## 4. Stack dan arsitektur (angka studi kasus)

| Pilihan | Keputusan | Alasan ke asesor |
| --- | --- | --- |
| Framework | Laravel 12, PHP 8.2+ | MVC, CSRF/XSS/hash/queue/migrasi bawaan |
| UI | Blade + Alpine.js + Tailwind | Cepat, mudah UAT, tanpa SPA |
| Database | MySQL 8 | Relasi stok–transaksi–laporan |
| Antrian | Database queue, 2 worker | Paralel tanpa Redis |
| Real-time | Polling 10 detik | Cukup untuk 150–200 pasien/hari; stabil di laptop demo |
| PDF | DomPDF | Export laporan |
| Impor | CSV native (asal file Excel) | Migrasi Excel → sistem, tanpa paket berat |
| Auth | Breeze Blade + `peran` | Verifikasi email, session, hash |
| Email | Laravel Mail, driver `log` | Bukti notifikasi email tanpa SMTP |
| Versi | Git | Update software & rollback kode |

Diagram wajib (sama dengan tugas):

```
Pengguna → Web Server (Nginx/Apache) → Aplikasi Laravel → MySQL
                                         → Worker antrian (paralel)
                                         → Log email + peringatan
```

Spek rancangan (klinik 150–200 pasien/hari, >2.000 obat) — untuk dokumen, demo tetap localhost:

| Node | Spek |
| --- | --- |
| Web + aplikasi | 2 vCPU, RAM 4 GB, SSD 80 GB |
| Database | 2 vCPU, RAM 4 GB, SSD 100 GB |
| Bandwidth | 20 Mbps (halaman ringan, gambar terkompresi) |
| Skala naik | Tambah worker antrian → indeks SQL → pisah server DB → cache nanti jika perlu |

Lokal demo = satu mesin. Dokumen tetap menggambar 2 server, sesuai tugas.

---

## 5. Alur bisnis tunggal (robust)

```
PASIEN                         APOTEKER              KASIR            SISTEM
  daftar + verifikasi email
  cari fuzzy / autocomplete
  keranjang (sesi) → checkout
       │                                                              pesanan: menunggu_bayar
       ├─ halaman bayar (simulasi) ─────────────────────────────────── job ProsesPembayaran
       │                                                              status: dikonfirmasi
       │                                                              email log + peringatan pesanan baru
       ├─ jika ada obat butuh_resep: unggah gambar resep
       │                         setuju / tolak
       │                                                              job PotongStokFifo (DB::transaction)
       │                                                              gagal stok → rollback, tidak negatif
       │                                                              alert stok min / kedaluwarsa
       lihat status              pantau stok         jual counter     LayananStok yang sama
```

Aturan gagal:

1. Pesanan + stok selalu `DB::transaction()`.
2. FIFO berhenti dan rollback jika batch tidak cukup.
3. Obat `butuh_resep` tidak selesai sebelum resep `disetujui`.
4. Job gagal → `failed_jobs` + `log_kesalahan`.
5. Impor CSV: baris rusak dicatat, baris valid tetap masuk.

Pembayaran **tidak** pakai gateway. Tombol “Bayar sekarang” mengirim job (sukses/gagal simulasi). Itu memenuhi “job queue pembayaran”.

---

## 6. Algoritma (nama sesuai tugas)

### FIFO — `LayananStok::potongStokFifo()`

Masuk obat = 1 baris `batch_obat` (jumlah, sisa, tanggal_masuk, kedaluwarsa).  
Keluar = batch `tanggal_masuk` paling lama dulu.

```
sisaKebutuhan ← jumlahDiminta
untuk setiap batch (sisa > 0) urut tanggal_masuk naik:
    ambil ← min(batch.sisa, sisaKebutuhan)
    batch.sisa ← batch.sisa − ambil
    catat mutasi_stok
    jika sisaKebutuhan = 0: selesai
jika sisaKebutuhan > 0: gagal + rollback
stok tampilan ← SUM(batch.sisa)
jika stok ≤ stok_minimum: buat peringatan stok_kritis
```

Satu fungsi ini dipakai **pesanan online dan kasir** → sinkronisasi counter/online.

### Fuzzy search — `LayananPencarian::cariFuzzy($kata)`

Bukan Elasticsearch. Cukup untuk disebut fuzzy di demo:

1. `LIKE %kata%` pada nama, kode, kategori
2. Skor `similar_text(nama, kata)`
3. Urut: nama diawali kata → kode persis → skor similar tertinggi

Autocomplete: `GET /api/obat/autocomplete?q=`, limit 10, debounce 300 ms.

### Pagination, sorting, filtering

Satu helper `FilterDaftar` untuk katalog dan CRUD. Jangan diulang.

### Paralel

- Dua `queue:work` saat demo (dua pesanan bersamaan).
- Impor CSV: `Bus::batch()` per baris.
- Laporan PDF besar: `JobBuatLaporan`.
- Bayar: `JobProsesPembayaran`.
- Stok: `JobPotongStok`.

---

## 7. Data (istilah Indonesia)

```
users                     peran: admin | apoteker | kasir | pasien
kategori_obat
pemasok                   # CRUD Supplier
obat                      kode, nama, harga, stok_minimum, butuh_resep, gambar
batch_obat                unit FIFO
mutasi_stok
pesanan + item_pesanan    # CRUD Transaksi
                          menunggu_bayar | dikonfirmasi | menunggu_resep
                          | menunggu_verifikasi | diproses | selesai | dibatalkan
resep                     1 pesanan : 1 gambar resep (bukan item_resep)
peringatan                stok_kritis | kedaluwarsa | pesanan_baru | kesalahan
                          tingkat: kritis | peringatan | info
log_audit
log_kesalahan             tingkat: kritis | peringatan | info
log_migrasi
```

Keranjang = **sesi**, bukan tabel. Cukup untuk cart/checkout, lebih sederhana.

Pelanggan = baris `users` dengan `peran = pasien` (CRUD Admin → Pelanggan). Tidak buat tabel kedua.

Stok di layar = `SUM(batch_obat.sisa)`, bukan kolom yang bisa menyimpang.

---

## 8. Layar (11, tidak lebih)

| # | Layar | Menutup butir |
| --- | --- | --- |
| 1 | Masuk / daftar / verifikasi email | Auth, email verification |
| 2 | Katalog + cari fuzzy + filter + detail + gambar | Katalog, algoritma, multimedia |
| 3 | Keranjang → checkout → bayar simulasi | Cart, checkout, pembayaran, konfirmasi |
| 4 | Pesanan saya + unggah resep + status | Resep, notifikasi status |
| 5 | Kasir: 1 halaman jual | Transaksi offline, sinkron stok |
| 6 | Apoteker: antrian resep + stok/kedaluwarsa | Verifikasi resep |
| 7 | Dasbor (polling) | Penjualan, stok, pendapatan, grafik, alert, error severity |
| 8 | CRUD: Obat, Kategori, Pemasok, Pelanggan | 4 master + gambar obat |
| 9 | Daftar transaksi + detail | CRUD Transaksi |
| 10 | Laporan + PDF | SQL + export |
| 11 | Migrasi CSV + Pemantauan antrian/error/audit | Migrasi, cutover, monitoring, paralel |

Resep tidak perlu CRUD penuh terpisah: buat dari pasien, verifikasi dari apoteker, admin bisa lihat di transaksi.

---

## 9. Struktur kode

```
app/
  Http/Controllers/
    KatalogController.php
    KeranjangController.php
    PesananController.php
    Api/ObatAutocompleteController.php
    Kasir/TransaksiController.php
    Apoteker/ResepController.php
    Admin/
      DasborController.php
      ObatController.php
      KategoriController.php
      PemasokController.php
      PelangganController.php
      TransaksiController.php
      LaporanController.php
      MigrasiController.php
      PemantauanController.php
  Layanan/
    LayananStok.php
    LayananPencarian.php
    LayananPeringatan.php
    LayananLaporan.php
    LayananMigrasi.php
  Jobs/
    JobProsesPembayaran.php
    JobPotongStok.php
    JobImporBarisObat.php
    JobBuatLaporan.php
    JobCekKedaluwarsa.php
  Mail/NotifikasiStatusPesanan.php
  Models/ ...
```

Contoh komentar wajib:

```php
/**
 * Memotong stok FIFO: batch tertua keluar lebih dulu
 * agar obat tidak menumpuk sampai kedaluwarsa.
 * Dipakai pesanan online dan kasir agar stok tetap satu sumber.
 */
public function potongStokFifo(Obat $obat, int $jumlahDiminta): void
```

Rute Indonesia: `/katalog`, `/keranjang`, `/pesanan`, `/kasir/transaksi`, `/admin/dasbor`, `/admin/pelanggan`, `/admin/migrasi`.

---

## 10. Dokumentasi wajib (nama mengikuti tugas)

Folder `dokumentasi/`:

| File | Artefak di dokumen tugas |
| --- | --- |
| `piagam-proyek.md` | Project charter |
| `wbs-lingkup.md` | WBS / scope |
| `jadwal.md` | Jadwal 3 hari / 18 jam |
| `arsitektur.md` | Hardware, topologi, CPU, RAM, storage, bandwidth |
| `analisis-tools.md` | Analisis tools |
| `risiko-keamanan.md` | Pengelolaan risiko |
| `checklist-mutu.md` | Quality checklist |
| `log-perubahan.md` | Change log |
| `analisis-dampak.md` | Impact analysis |
| `bukti-integrasi.md` | Bukti integrasi |
| `bukti-migrasi.md` | Bukti migrasi + mapping field |
| `rencana-cutover.md` | Cutover + checklist sebelum/sesudah |
| `rencana-rollback.md` | Rollback plan |
| `pembaruan-git.md` | Update software via Git |
| `panduan-pengguna.md` | User guide / panduan teknis pelanggan |
| `faq.md` | FAQ ≥ 10 |
| `api.md` | Dokumentasi 2 endpoint JSON |
| `troubleshooting.md` | Troubleshooting |
| `uat.md` | Skenario + hasil UAT |
| `naskah-demo.md` | Urutan 30–45 menit |
| `contoh-data/obat-lama.csv` | Data Excel lama untuk migrasi |

---

## 11. Yang sengaja tidak dibuat (tetap lulus)

| Tidak dibuat | Pengganti yang masih memenuhi tugas |
| --- | --- |
| Midtrans / payment gateway | Halaman bayar + `JobProsesPembayaran` |
| Redis / WebSocket | Database queue + polling 10 detik |
| Elasticsearch | `cariFuzzy()` PHP |
| Laravel Excel | CSV dari Excel |
| Tabel keranjang & item_resep | Sesi + 1 gambar resep |
| API publik besar | 2 endpoint + `api.md` |
| Email SMTP sungguhan | Mail driver `log` (isi `storage/logs`) |

---

## 12. Jadwal 18 jam (mengikuti hari uji)

| Hari | Fokus tugas | Kerja konkret |
| --- | --- | --- |
| 1 (6 jam) | Analisis, desain, scope, risiko, mutu | Dokumen A + `laravel new` + Breeze + peran + migrasi + seeder 4 user + 20 obat |
| 2 (6 jam) | Development, migrasi, monitoring, alert, cutover | Alur bisnis, FIFO, job, dasbor, kasir, impor, pemantauan |
| 3 (6 jam) | Debugging, UAT, dokumentasi, presentasi | Perbaiki bug, isi UAT, FAQ, latihan naskah |

Seeder wajib: 1 akun per peran, obat bebas + obat `butuh_resep`, 1 batch hampir kedaluwarsa, 1 stok di bawah minimum — supaya demo tidak perlu data acak.

---

## 13. Naskah demo (menutup matriks)

1. Diagram arsitektur + spek 150–200 pasien (2 menit).
2. Daftar pasien → verifikasi email → katalog fuzzy + autocomplete + filter.
3. Beli obat bebas → bayar simulasi → lihat job antrian → stok FIFO berkurang.
4. Beli obat keras → unggah resep → apoteker setujui → stok potong.
5. Kasir jual 1 item → stok katalog ikut berkurang (sinkron).
6. Dasbor polling: grafik, stok kritis, kedaluwarsa 30/60/90, error critical/warning/info.
7. Migrasi CSV + rollback + checklist cutover.
8. PDF laporan; query terlaris di `LayananLaporan`.
9. Role ditolak, `@csrf`, audit log, `SESSION_LIFETIME`, email di log.
10. `git log` sebagai update software.

---

## 14. Definition of Done = checklist tugas

Aplikasi siap uji hanya jika semua ini bisa didemo:

- [ ] 4 peran login, menu sesuai hak
- [ ] CRUD: obat, kategori, pemasok, pelanggan, transaksi, resep (lihat/verifikasi)
- [ ] Cart, checkout, bayar, konfirmasi, verifikasi resep
- [ ] FIFO dipakai online dan kasir
- [ ] Fuzzy + autocomplete + pagination + sort + filter
- [ ] Dasbor angka harian/mingguan/bulanan, stok, pendapatan, grafik, polling
- [ ] Alert stok min, kedaluwarsa 30/60/90, pesanan baru, error ber-severity
- [ ] Job: bayar, stok, impor batch, laporan; 2 worker paralel
- [ ] PDF + SQL 4 laporan
- [ ] Migrasi CSV: mapping, validasi, rollback, checklist
- [ ] Audit, CSRF, XSS escape, hash, verifikasi email, session 30 menit
- [ ] Folder `dokumentasi/` sesuai tabel bagian 10
- [ ] Naskah demo 30 menit tanpa improvisasi

---

## 15. Urutan coding (setelah rencana ini)

1. Auth + `peran` + layout 4 menu
2. Master: kategori, pemasok, obat, batch, pelanggan
3. `LayananStok` FIFO (uji manual dulu)
4. Katalog fuzzy → keranjang sesi → checkout → bayar
5. Resep apoteker
6. Job antrian (bayar, stok, impor, laporan, kedaluwarsa)
7. Dasbor + peringatan + email log
8. Kasir
9. Laporan PDF + migrasi + pemantauan
10. Dokumentasi sisa + naskah demo
