# Panduan Penggunaan  
## Apotek Digital Klinik Makmur Jaya

**Versi:** 1.0  
**Sistem:** E-commerce penjualan obat berbasis web (Laravel 12)  
**Audiens:** Pasien/pelanggan, kasir, apoteker, admin klinik, asesor  

Dokumen ini adalah **panduan teknis pelanggan + paket dokumentasi proyek** untuk uji kompetensi Web Developer. Semua penjelasan (penggunaan, API, arsitektur, migrasi, UAT, dsb.) digabung di sini supaya satu berkas cukup untuk demo dan tanya jawab.

---

## Daftar isi

1. [Ringkasan sistem](#1-ringkasan-sistem)  
2. [Persiapan sebelum memakai](#2-persiapan-sebelum-memakai)  
3. [Panduan per peran](#3-panduan-per-peran)  
4. [Keamanan yang harus diketahui pengguna](#4-keamanan-yang-harus-diketahui-pengguna)  
5. [Arti status & peringatan](#5-arti-status--peringatan)  
6. [Troubleshooting](#6-troubleshooting)  
7. [FAQ](#7-faq)  
8. [Dokumentasi API JSON](#8-dokumentasi-api-json)  
9. [Matriks fitur → butir uji + jejak kode (file/baris)](#9-matriks-fitur--butir-uji-kompetensi--jejak-kode)  
10. [Piagam proyek](#10-piagam-proyek)  
11. [WBS & lingkup](#11-wbs--lingkup)  
12. [Jadwal 18 jam](#12-jadwal-18-jam)  
13. [Arsitektur perangkat keras & topologi](#13-arsitektur-perangkat-keras--topologi)  
14. [Analisis tools](#14-analisis-tools)  
15. [Risiko keamanan informasi](#15-risiko-keamanan-informasi)  
16. [Checklist mutu](#16-checklist-mutu)  
17. [Log perubahan](#17-log-perubahan)  
18. [Analisis dampak modul stok](#18-analisis-dampak-modul-stok)  
19. [Bukti integrasi](#19-bukti-integrasi)  
20. [Bukti migrasi data](#20-bukti-migrasi-data)  
21. [Rencana cutover](#21-rencana-cutover)  
22. [Rencana rollback](#22-rencana-rollback)  
23. [Pembaruan perangkat lunak via Git](#23-pembaruan-perangkat-lunak-via-git)  
24. [UAT](#24-uat)  
25. [Kontak & dukungan demo](#25-kontak--dukungan-demo)

File pendukung di folder yang sama: `naskah-demo.md`, `panduan-deploy.md` (deploy/pull produksi), `contoh-data/*.csv`.

---

## 1. Ringkasan sistem

Sistem menggantikan penjualan manual dan Excel dengan alur:

1. Pasien mencari obat di katalog (online).  
2. Memasukkan keranjang → checkout → bayar (simulasi antrian).  
3. Jika obat wajib resep: unggah foto resep → apoteker setuju/tolak.  
4. Stok dipotong **FIFO** (batch yang masuk lebih dulu keluar lebih dulu).  
5. Kasir di loket memakai stok yang sama (sinkron dengan online).  
6. Admin memantau penjualan, stok kritis, kedaluwarsa, antrian pekerjaan, dan migrasi data.

### Alamat & lingkungan demo

| Item | Nilai |
| --- | --- |
| URL lokal | `http://127.0.0.1:8000` |
| Basis data | MySQL / MariaDB `apotek_makmur_jaya` |
| Sesi | Habis otomatis setelah **30 menit** tidak aktif |
| Email | Driver `log` — isi terlihat di `storage/logs/laravel.log` |

### Akun demo (kata sandi semua: `Password1`)

| Peran | Email | Menu utama |
| --- | --- | --- |
| Admin | `admin@makmurjaya.test` | Papan antrian, CRUD, laporan, migrasi, pemantauan |
| Apoteker | `apoteker@makmurjaya.test` | Antrian resep, laci stok FIFO |
| Kasir | `kasir@makmurjaya.test` | Kasir counter |
| Pasien | `pasien@makmurjaya.test` | Katalog, keranjang, pesanan |

---

## 2. Persiapan sebelum memakai

Jalankan di terminal (dari folder proyek):

```bash
# 1) Migrasi + data demo (jika belum)
php artisan migrate:fresh --seed

# 2) Tautan folder gambar/unggahan
php artisan storage:link

# 3) Server web
php artisan serve --host=127.0.0.1 --port=8000

# 4) Worker antrian (WAJIB — dengar semua nama antrian job)
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

Untuk membuktikan **pemrosesan paralel**, buka **dua** terminal worker dengan perintah yang sama.

> **Penting:** `php artisan queue:work` tanpa `--queue=...` hanya memproses antrian `default`.  
> Job bayar ada di antrian `pembayaran`, stok di `stok`, impor di `impor`. Tanpa flag itu, status pesanan bisa tetap **Menunggu bayar** meski worker sudah jalan.

Cek kedaluwarsa 30/60/90 hari:

- Jadwal otomatis: tiap hari jam **06:00**. Di luar jam itu `schedule:run` biasanya menulis *No scheduled commands are ready to run* — itu normal.
- Untuk **demo kapan saja**:
  ```bash
  php artisan apotek:cek-kedaluwarsa --sync
  ```

---

## 3. Panduan per peran

### 3.1 Pasien / pelanggan

#### A. Daftar akun baru

1. Buka `/daftar`.  
2. Isi nama, email, telepon, alamat.  
3. Kata sandi minimal **8 karakter, ada huruf dan angka**.  
4. Setelah daftar, sistem meminta **verifikasi email**.  
5. Untuk demo: buka `storage/logs/laravel.log`, cari tautan verifikasi, buka di browser.  
6. Setelah terverifikasi, Anda bisa belanja.

**Butir ujikom:** email verification, validasi password, password hashing.

#### B. Masuk

1. Buka `/masuk`.  
2. Isi email dan kata sandi.  
3. Opsional: centang “Ingat saya”.  
4. Sistem membuat ulang sesi (anti session fixation).

#### C. Mencari obat (katalog)

1. Buka `/katalog`.  
2. Ketik di kotak cari, contoh: `paracet` (ejaan tidak harus sempurna).  
3. Saran otomatis muncul dalam ±0,3 detik (**autocomplete**).  
4. Filter kategori, urut nama/harga/kode, arah A–Z atau Z–A.  
5. Klik kartu obat untuk detail, gambar, dan daftar batch FIFO.

**Baca strip kuning di kartu:** tanggal batch yang akan keluar berikutnya + sisa stok.  
Jika ada tiket merah **Resep**, obat itu wajib foto resep setelah bayar.

**Butir ujikom:** fuzzy search, autocomplete, pagination, sorting, filtering, multimedia, FIFO (informasi).

#### D. Keranjang → checkout → bayar

1. Di katalog, klik **Masukkan keranjang**.  
2. Buka `/keranjang` — ubah jumlah atau isi `0` untuk menghapus.  
3. Klik **Lanjut checkout**.  
4. Tambah catatan opsional → **Buat tiket pesanan & bayar**.  
5. Di halaman bayar, klik **Bayar sekarang** (bukan hanya “Lanjut bayar”).  
   - “Lanjut bayar” hanya membuka halaman tiket; status masih **Menunggu bayar**.  
   - **Bayar sekarang** mengirim `JobProsesPembayaran` ke antrian `pembayaran`.  
6. Status di `/pesanan` berubah (misalnya menjadi “Unggah resep” atau menuju selesai).  
7. Opsional: buka `storage/logs/laravel.log` → email status (driver log).

##### Jika status tetap **Menunggu bayar**

| Langkah | Tombol / perintah | Hasil |
| --- | --- | --- |
| 1 | **Buat tiket pesanan & bayar** | Status = **Menunggu bayar** (belum ada job) |
| 2 | **Lanjut bayar** | Hanya buka halaman pembayaran; status **belum berubah** |
| 3 | **Bayar sekarang** | Job `JobProsesPembayaran` masuk antrian `pembayaran` |
| 4 | Worker antrian | Status berubah |

```powershell
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

Di terminal worker yang benar, setelah **Bayar sekarang** harus muncul:

```text
App\Jobs\JobProsesPembayaran .......... DONE
```

Jika muncul *“Pesanan ini tidak menunggu pembayaran”*: biasanya pembayaran **sudah sukses**. Buka `/pesanan`, jangan bayar ulang dari tab lama.

**Butir ujikom:** cart, checkout, pembayaran, konfirmasi, job queue pembayaran, email notifikasi status.

#### E. Unggah resep

1. Jika status **Unggah resep**, di daftar pesanan muncul form unggah.  
2. Pilih foto resep (JPG/PNG, maks. 4 MB).  
3. Klik **Unggah resep**.  
4. Status menjadi **Menunggu verifikasi**.  
5. Setelah apoteker setuju, stok FIFO dipotong dan pesanan selesai.

#### F. Melacak status

| Warna tiket | Arti singkat |
| --- | --- |
| Kuning | Menunggu aksi (bayar / resep) |
| Abu | Sedang diproses |
| Hijau | Selesai / dikonfirmasi |
| Merah | Dibatalkan / ditolak |

---

### 3.2 Kasir

1. Masuk sebagai `kasir@makmurjaya.test`.  
2. Buka **Kasir counter** (`/kasir/transaksi`).  
3. Cari obat di kotak “Cari obat di laci”.  
4. Centang obat, isi jumlah.  
5. Lihat total di panel **Struk sementara**.  
6. Klik **Simpan & potong stok FIFO**.

Kasir memanggil `LayananStok::potongStokFifo` yang sama dengan pesanan online. Setelah jual, stok di katalog ikut berkurang — bukti **sinkronisasi counter ↔ online**. Jika stok kurang, transaksi di-rollback; stok tidak pernah negatif.

---

### 3.3 Apoteker

#### A. Verifikasi resep

1. Masuk sebagai `apoteker@makmurjaya.test`.  
2. Buka **Antrian resep**.  
3. Lihat foto resep; cek daftar obat.  
4. **Setujui & potong stok** → `JobPotongStok`, atau **Tolak** → pesanan dibatalkan.

#### B. Laci stok FIFO

Strip kuning = batch yang akan keluar berikutnya. Chip batch diurut dari tanggal masuk lama → baru. Batch hampir kedaluwarsa terlihat dari tanggal `exp`.

---

### 3.4 Admin

#### A. Papan antrian (dasbor)

- Pendapatan harian / mingguan / bulanan  
- Jumlah tiket terbayar hari ini  
- Grafik 14 hari, obat terlaris, stok ≤ minimum  
- Peringatan dengan tingkat **Critical / Warning / Info**  
- Badge sidebar diperbarui tiap **10 detik** (polling)

#### B. CRUD master data

| Menu | Fungsi |
| --- | --- |
| Obat | Tambah/ubah/hapus, unggah gambar, stok awal → batch FIFO, flag wajib resep |
| Kategori | Tambah / hapus kategori |
| Pemasok | CRUD supplier |
| Pelanggan | CRUD akun peran pasien |
| Transaksi | Daftar, detail, batalkan |

#### C. Laporan & PDF

1. Buka **Laporan** → pilih rentang tanggal.  
2. Lihat terlaris, kedaluwarsa ≤90 hari, rekap.  
3. **Unduh PDF** atau **Antrian laporan besar** (`JobBuatLaporan` → `storage/app/public/laporan/`).

#### D. Migrasi Excel/CSV

1. Buka **Migrasi CSV**; baca mapping field.  
2. Unggah contoh:
   - `dokumentasi/contoh-data/obat-lama.csv` — kode `XL-xxx`  
   - `dokumentasi/contoh-data/obat-kedaluwarsa-demo.csv` — kode `KD-xxx` (demo alert)  
   - `dokumentasi/contoh-data/obat-lama-campur-error.csv` — demo validasi gagal sebagian  
3. Impor lewat `Bus::batch` + worker antrian `impor`.  
4. Jika salah: **Rollback** batch.

#### E. Demo alert kedaluwarsa 30 / 60 / 90 hari

| Grup kode | Kondisi |
| --- | --- |
| `KD-101` … `KD-105` | Sudah kedaluwarsa |
| `KD-201` … `KD-205` | Mendekati ≤30 hari |
| `KD-301` … `KD-305` | Mendekati ≤60 hari |
| `KD-401` … `KD-405` | Mendekati ≤90 hari |

Setelah impor: `php artisan apotek:cek-kedaluwarsa --sync`, lalu cek Papan antrian / Laporan / Laci stok.

#### F. Pemantauan

Jumlah job menunggu/gagal, sesi aktif, log kesalahan ber-severity, jejak audit, ringkasan peringatan.

---

## 4. Keamanan yang harus diketahui pengguna

| Fitur | Yang dirasakan pengguna |
| --- | --- |
| Hak akses peran | Menu berbeda; membuka URL orang lain → 403 |
| CSRF | Form selalu membawa token |
| XSS | Teks ditampilkan aman (Blade `{{ }}`) |
| SQL Injection | Pencarian memakai binding, bukan SQL mentah |
| Hash password | Kata sandi tidak disimpan teks biasa |
| Session timeout | Setelah 30 menit idle, harus masuk ulang |
| Verifikasi email | Belum verifikasi → tidak bisa checkout |
| Audit | Admin dapat menelusuri aksi penting |

Detail matriks risiko: [bagian 15](#15-risiko-keamanan-informasi).

---

## 5. Arti status & peringatan

### Status pesanan

| Status | Arti untuk pelanggan |
| --- | --- |
| Menunggu bayar | Segera bayar di halaman tiket |
| Dikonfirmasi | Bayar diterima; obat bebas lanjut proses |
| Unggah resep | Unggah foto resep |
| Menunggu verifikasi | Menunggu apoteker |
| Diproses FIFO | Stok sedang dipotong |
| Selesai | Siap diambil / selesai |
| Dibatalkan | Ditolak / stok kurang / dibatalkan admin |

### Tingkat peringatan

| Tingkat | Contoh |
| --- | --- |
| Critical | Stok 0, kedaluwarsa ≤30 hari, error sistem |
| Warning | Stok di bawah minimum, kedaluwarsa ≤60/90 hari |
| Info | Pesanan baru |

---

## 6. Troubleshooting

### A. Antrian & pembayaran

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Status tetap **Menunggu bayar** | Belum klik **Bayar sekarang** | Buka halaman bayar → Bayar sekarang |
| Status tetap **Menunggu bayar** | Worker tidak mendengar antrian `pembayaran` | Jalankan worker dengan daftar queue lengkap |
| Flash “Pesanan ini tidak menunggu pembayaran” | Double-pay / halaman usang | Muat ulang daftar pesanan |
| Job menumpuk di `jobs` | Worker mati | Nyalakan ulang worker; cek `failed_jobs` |

### B. Auth & akses

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Tidak bisa checkout | Email belum diverifikasi | Buka tautan di `storage/logs/laravel.log` |
| 403 Forbidden | Salah peran | Login akun sesuai menu |
| Sesi hilang mendadak | Timeout 30 menit | Masuk ulang |
| Kata sandi ditolak saat daftar | Validasi min 8 + huruf + angka | Contoh: `Password1` |

### C. Stok & FIFO

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Stok katalog tidak berubah | Halaman lama / transaksi gagal | Hard refresh; cek status pesanan & `mutasi_stok` |
| Error stok tidak cukup | Batch kosong | Tambah batch / impor CSV |
| Stok negatif (tidak boleh) | Update sisa di luar layanan | Pastikan potong lewat `LayananStok` + transaksi DB |

### D. Multimedia & storage

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Gambar 404 | Belum `storage:link` | `php artisan storage:link` |
| Unggah gagal | Tipe/ukuran salah | Resep JPG/PNG maks ~4 MB; gambar obat maks ~2 MB |

### E. Migrasi CSV

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Semua baris gagal | Header tidak sesuai mapping | Samakan dengan `obat-lama.csv` |
| Sebagian gagal | Validasi baris | Baca log migrasi; baris valid tetap masuk |
| Rollback tidak menghapus | Batch ID salah | Pilih batch yang sama dari log |

### F. Laporan, kedaluwarsa, lingkungan

| Gejala | Perbaikan |
| --- | --- |
| PDF kosong | Ubah filter tanggal; cek log |
| Tidak ada alert kedaluwarsa | Impor `obat-kedaluwarsa-demo.csv` + `apotek:cek-kedaluwarsa --sync` |
| Halaman tanpa CSS | `npm run build` atau `npm run dev` |
| View usang | `php artisan view:clear` |

### Perintah darurat demo

```powershell
php artisan serve
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
php artisan apotek:cek-kedaluwarsa --sync
php artisan storage:link
php artisan view:clear
npm run build
```

---

## 7. FAQ

1. **Apakah pembayaran memakai Midtrans/transfer bank?**  
   Tidak. Pembayaran disimulasikan lewat antrian (`JobProsesPembayaran`) agar ada bukti *job queue pembayaran*.

2. **Mengapa tiket masih MENUNGGU BAYAR padahal queue:work sudah jalan?**  
   Belum **Bayar sekarang**, atau worker tanpa `--queue=pembayaran,stok,impor,laporan,default`.

3. **Mengapa stok katalog dan kasir selalu sama?**  
   Keduanya memakai `LayananStok` FIFO yang sama — satu sumber kebenaran.

4. **Apa arti strip kuning “FIFO”?**  
   Batch tertua yang akan keluar berikutnya beserta sisa unit.

5. **Bagaimana jika ejaan obat salah sedikit?**  
   Fuzzy search + autocomplete (`GET /api/obat/autocomplete?q=`).

6. **Apakah semua obat butuh resep?**  
   Tidak. Hanya yang bertanda **Resep**.

7. **Di mana email verifikasi dan status pesanan?**  
   `storage/logs/laravel.log` (`MAIL_MAILER=log`).

8. **Bagaimana membatalkan transaksi?**  
   Admin → Transaksi → Detail → Batalkan.

9. **Bagaimana memindahkan data Excel lama?**  
   Export CSV sesuai mapping Migrasi, impor, verifikasi log; rollback per batch tersedia.

10. **Apa yang terjadi jika stok tidak cukup?**  
    Rollback; stok tidak negatif; admin mendapat peringatan.

11. **Bagaimana melihat error Critical/Warning/Info?**  
    Admin → Pemantauan / Papan antrian.

12. **Berapa lama sesi login?**  
    30 menit tidak aktif.

13. **Bagaimana update aplikasi setelah perbaikan?**  
    Lihat [bagian 23](#23-pembaruan-perangkat-lunak-via-git).

14. **Bagaimana demo alert kedaluwarsa tanpa menunggu jam 06:00?**  
    `php artisan apotek:cek-kedaluwarsa --sync`.

15. **Siapa yang boleh mengakses dasbor admin?**  
    Hanya peran `admin` (middleware `peran`).

---

## 8. Dokumentasi API JSON

Aplikasi menyediakan **dua endpoint JSON utama** (bukan API publik besar). Basis URL: `http://127.0.0.1:8000`.

### 8.1 Autocomplete obat

| Item | Nilai |
| --- | --- |
| Metode | `GET` |
| Path | `/api/obat/autocomplete` |
| Auth | Tidak wajib (katalog publik) |
| Query | `q` — kata pencarian |
| Limit | Maksimal 10 hasil |
| Sumber | `LayananPencarian::autocomplete()` |

**Contoh permintaan**

```http
GET /api/obat/autocomplete?q=paracet HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json
```

**Contoh respons `200`**

```json
[
  {
    "id": 1,
    "kode": "OBT-001",
    "nama": "Paracetamol 500mg",
    "harga": 5000,
    "stok": 120,
    "butuh_resep": false,
    "skor": 85
  }
]
```

| Field | Tipe | Keterangan |
| --- | --- | --- |
| `id` | number | ID obat |
| `kode` | string | Kode unik |
| `nama` | string | Nama tampilan |
| `harga` | number | Harga satuan (Rp) |
| `stok` | number | Sisa stok (`SUM` batch) |
| `butuh_resep` | boolean | Perlu unggah resep |
| `skor` | number | Skor fuzzy (internal peringkat) |

Jika `q` kosong → `[]`. UI memakai **debounce ±300 ms**.

### 8.2 Status real-time dasbor

| Item | Nilai |
| --- | --- |
| Metode | `GET` |
| Path | `/admin/api/realtime` |
| Auth | Login + peran `admin` |
| Refresh | Tiap **10 detik** |
| Tujuan | Real-time tanpa WebSocket |

**Contoh respons `200`**

```json
{
  "peringatan_baru": [
    {
      "id": 12,
      "jenis": "stok_kritis",
      "judul": "Stok Paracetamol di bawah minimum",
      "tingkat": "peringatan",
      "dibaca_pada": null,
      "created_at": "2026-08-27T10:00:00.000000Z"
    }
  ],
  "pesanan_baru": [
    {
      "id": 5,
      "nomor": "PSN-20260827-0005",
      "status": "menunggu_bayar",
      "total": 25000,
      "created_at": "2026-08-27T10:01:00.000000Z"
    }
  ],
  "waktu_server": "2026-08-27 17:00:00"
}
```

| Field | Keterangan |
| --- | --- |
| `peringatan_baru` | Hingga 8 peringatan belum dibaca |
| `pesanan_baru` | 5 pesanan terbaru |
| `waktu_server` | Timestamp server |

`401`/redirect = belum login; `403` = bukan admin.

### 8.3 Endpoint pendukung

| Path | Fungsi |
| --- | --- |
| `GET /admin/dasbor/polling` | Badge: `belum_dibaca`, `terbaru` |

### 8.4 Keamanan API

- Autocomplete hanya data katalog (bukan data pasien).  
- Realtime dilindungi `auth` + `verified` + `peran:admin`.  
- Form HTML tetap CSRF; endpoint GET di atas idempotent.

---

## 9. Matriks fitur → butir uji kompetensi (+ jejak kode)

Nomor baris mengacu ke versi kode saat dokumen ini ditulis. Jika sedikit bergeser setelah edit, cari **nama method/kelas** di kolom file — itulah titik prosesnya.

### 9.1 Ringkas: fitur → butir

| Fitur di panduan ini | Butir penilaian |
| --- | --- |
| 4 peran + menu terpisah | Autentikasi & keamanan (role) |
| Daftar + verifikasi email | Email verification |
| Kata sandi min 8 huruf+angka | Validasi password + hashing |
| Katalog fuzzy + autocomplete | Algoritma pencarian |
| Strip FIFO + potong stok | Algoritma FIFO |
| Keranjang–checkout–bayar | E-commerce |
| Unggah/pratinjau gambar | Multimedia |
| Kasir = stok online | Sinkronisasi counter/online |
| Antrian bayar/stok/impor/laporan | Paralel & background job |
| Dasbor polling 10 dtk | Real-time |
| Peringatan severity | Alert notification |
| Laporan + PDF | SQL + export |
| Migrasi CSV + rollback | Migrasi & cutover |
| Pemantauan job/error/audit | Monitoring resource + audit log |
| Session 30 menit | Session timeout |
| Bagian 10–24 di dokumen ini | Analisis, arsitektur, tools, risiko, UAT, API, dokumentasi |

### 9.2 Jejak kode per butir (controller / layanan / job / rute)

| Butir ujikom | Proses | File & baris (inti) | Rute / UI terkait |
| --- | --- | --- | --- |
| **Role-based access** (admin, apoteker, kasir, pasien) | Enum peran + middleware gate | `app/Enums/PeranPengguna.php`; `app/Http/Middleware/PastikanPeran.php` ~L16; alias di `bootstrap/app.php` ~L22–25; pengelompokan rute `routes/web.php` ~L66–101 | Prefix `/admin`, `/apoteker`, `/kasir` |
| **Email verification** | `MustVerifyEmail` + halaman verifikasi | `app/Models/User.php` ~L17 (`implements MustVerifyEmail`); `app/Http/Controllers/Autentikasi/VerifikasiEmailController.php` ~L14–26; rute `routes/web.php` ~L45–49; middleware `verified` di ~L53 | `/email/verifikasi` |
| **Password hashing** | Cast `hashed` saat simpan | `app/Models/User.php` ~L40–41; create di `MasukController::daftar` ~L69–74 | Form `/daftar` |
| **Validasi password** | min 8 + huruf + angka | `MasukController::daftar` ~L60–67 (`Password::min(8)->letters()->numbers()`) | Form daftar |
| **CSRF** | Token form web | Otomatis Laravel; disebut di `bootstrap/app.php` ~L12; view pakai `@csrf` (mis. `resources/views/autentikasi/masuk.blade.php`) | Semua form POST |
| **XSS** | Escape output Blade | Semua view: `{{ }}` (bukan `{!! !!}` untuk input user) | — |
| **SQL Injection** | Binding / Eloquent | `LayananPencarian::dasarKueri` ~L85–95; query laporan di `LayananLaporan.php` | Katalog & laporan |
| **Session timeout 30 menit** | Konfigurasi sesi | `.env` / `.env.example` `SESSION_LIFETIME=30`; `config/session.php` | Habis idle → login ulang |
| **Audit log** | Catat aksi penting | `app/Models/LogAudit.php` ~L31 `catat()`; dipanggil mis. `PesananController` ~L112, `MasukController` ~L80, `MigrasiController` ~L60; tampil `PemantauanController` ~L17 | Admin → Pemantauan |
| **Error severity** (critical/warning/info) | Exception → `log_kesalahan` + peringatan | `bootstrap/app.php` ~L29–48; enum `TingkatKeparahan`; tampil Pemantauan | Admin → Pemantauan |
| **Fuzzy search** | `cariFuzzy()` + `similar_text` | `app/Layanan/LayananPencarian.php` `cariFuzzy` ~L25–37, skor ~L56–75, `dasarKueri` ~L85; dipanggil `KatalogController::index` ~L14 | `/katalog` |
| **Autocomplete** | API JSON limit 10 | `ObatAutocompleteController` ~L16–20; `LayananPencarian::autocomplete` ~L45–79; rute `web.php` ~L34; UI debounce `resources/views/katalog/index.blade.php` ~L19–20 | `GET /api/obat/autocomplete?q=` |
| **Pagination / sort / filter** | Query katalog | `LayananPencarian::cariFuzzy` ~L25–37 (`paginate(12)`, `orderBy`); `KatalogController::index` ~L14 | `/katalog` |
| **Algoritma FIFO** | Potong batch tertua dulu | `app/Layanan/LayananStok.php` `potongStokFifo` **~L68–112** (`orderBy('tanggal_masuk')`, `lockForUpdate`) | Dipakai online + kasir |
| **Sinkron counter ↔ online** | Satu fungsi stok | Online: `JobPotongStok::handle` ~L29 → `potongStokFifo`; Kasir: `Kasir/TransaksiController::simpan` **~L75** | `/kasir/transaksi` vs katalog |
| **Cart / keranjang** | Sesi | `KeranjangController` ~L18–34; `app/Layanan/LayananKeranjang.php`; rute `web.php` ~L54–56 | `/keranjang` |
| **Checkout** | Buat pesanan | `PesananController::checkout` ~L34, `buat` ~L47–54; rute ~L59–60 | `/pesanan/checkout` |
| **Pembayaran + job queue** | Tombol Bayar sekarang → antrian | Dispatch: `PesananController::prosesBayar` **~L101–112**; proses: `app/Jobs/JobProsesPembayaran.php` ~L19–53 (`onQueue('pembayaran')` ~L27) | `/pesanan/{id}/bayar` |
| **Konfirmasi pesanan / email status** | Update status + mail log | `JobProsesPembayaran::handle` ~L38–48; `app/Mail/NotifikasiStatusPesanan.php`; view `resources/views/mail/status-pesanan.blade.php` | Log: `storage/logs/laravel.log` |
| **Verifikasi resep** | Unggah → setuju/tolak | Unggah: `PesananController::unggahResep` ~L118–127; putuskan: `Apoteker/ResepController::putuskan` ~L25–41 → `JobPotongStok::dispatch` ~L41 | `/pesanan`, `/apoteker/resep` |
| **Multimedia** (gambar obat & resep) | Store ke disk public | Obat: `Admin/ObatController` ~L97–103; resep: `PesananController` ~L127; pratinjau view apoteker/katalog; `php artisan storage:link` | Form obat, unggah resep |
| **CRUD Obat** | Resource admin | `Admin/ObatController.php` (store ~L40, update, destroy); rute `web.php` ~L82 | `/admin/obat` |
| **CRUD Kategori / Pemasok / Pelanggan** | Controller admin | `Admin/KategoriController.php`, `PemasokController.php`, `PelangganController.php`; rute ~L83–91 | Menu Admin |
| **CRUD Transaksi** | Daftar / detail / batal | `Admin/TransaksiController.php` index ~L16, show ~L28, batalkan ~L35; rute ~L92–94 | `/admin/transaksi` |
| **Dasbor penjualan** | Angka harian/mingguan/bulanan + grafik | `Admin/DasborController::index` ~L17–33; data SQL `LayananLaporan::ringkasanDasbor` ~L36, `penjualanPeriode` ~L18 | `/admin/dasbor` |
| **Real-time (polling 10 dtk)** | Fetch berkala | Badge: `layouts/meja.blade.php` ~L14–15 (`setInterval(..., 10000)`); API `DasborController::polling` ~L36; ringkas `StatusRealtimeController` ~L17; rute ~L79–80 | `/admin/dasbor/polling`, `/admin/api/realtime` |
| **Alert stok kritis** | Setelah FIFO | `LayananPeringatan::cekStokKritis` ~L18; dipanggil akhir `LayananStok::potongStokFifo` ~L111 | Dasbor / Pemantauan |
| **Alert kedaluwarsa 30/60/90** | Scheduler + perintah demo | Logika: `LayananPeringatan::cekKedaluwarsa` ~L48; job `JobCekKedaluwarsa.php` ~L17; jadwal `routes/console.php` ~L10; perintah `app/Console/Commands/CekKedaluwarsaCommand.php` ~L15–28 | `apotek:cek-kedaluwarsa --sync` |
| **Alert pesanan baru** | Setelah bayar | `LayananPeringatan::pesananBaru` ~L86; dipanggil `JobProsesPembayaran` ~L45 | Badge dasbor |
| **Paralel / queue jobs** | Antrian bernama | Bayar: `JobProsesPembayaran` queue `pembayaran`; stok: `JobPotongStok` queue `stok`; impor: `JobImporBarisObat` + `Bus::batch` di `MigrasiController::impor` **~L59**; laporan: `JobBuatLaporan` dari `LaporanController::antrian` ~L52 | `queue:work --queue=pembayaran,stok,impor,laporan,default` |
| **SQL laporan** | 4 jenis query | `app/Layanan/LayananLaporan.php`: penjualan ~L18, ringkasan ~L36, terlaris ~L49, kedaluwarsa ~L64, rekap ~L79 | `/admin/laporan` |
| **Export PDF** | DomPDF | `Admin/LaporanController::pdf` ~L35; view `resources/views/pdf/laporan-penjualan.blade.php` | `/admin/laporan/pdf` |
| **Migrasi teknologi (CSV)** | Mapping + validasi + impor | Mapping/validasi: `LayananMigrasi.php` `mappingKolom` ~L27, `imporBaris` ~L46; UI/dispatch: `MigrasiController::impor` ~L30–62; job baris `JobImporBarisObat.php` ~L29 | `/admin/migrasi` |
| **Rollback migrasi** | Hapus obat per batch | `LayananMigrasi::rollback` ~L101; `MigrasiController::rollback` ~L65–71 | Tombol Rollback di Migrasi |
| **Cutover checklist** | UI + dokumen | View `resources/views/admin/migrasi/index.blade.php`; prosedur di bagian 21 dokumen ini | Menu Migrasi |
| **Monitoring resource** | Antrian, error, sesi, audit | `Admin/PemantauanController::index` ~L17; view `resources/views/admin/pemantauan/index.blade.php` | `/admin/pemantauan` |
| **Library / framework** | Laravel 12, DomPDF, Alpine, Tailwind | `composer.json`, `package.json`, Vite `vite.config.js` | — |
| **Update software (Git)** | Pull + migrate + build | Prosedur bagian 23 + `dokumentasi/panduan-deploy.md` | Repo GitHub |
| **Dokumentasi API** | 2 endpoint JSON | Autocomplete: di atas; realtime: `StatusRealtimeController.php` ~L17; penjelasan lengkap bagian 8 | `/api/obat/autocomplete`, `/admin/api/realtime` |

### 9.3 Alur bisnis → file (urutan baca untuk demo)

```
Pasien daftar/masuk
  → MasukController.php (~L26, ~L58)
  → User.php (MustVerifyEmail, hashed)

Katalog fuzzy + autocomplete
  → KatalogController.php (~L14) + LayananPencarian.php
  → ObatAutocompleteController.php + katalog/index.blade.php (~L19)

Keranjang → checkout → bayar
  → KeranjangController.php + LayananKeranjang.php
  → PesananController.php buat(~L47) → prosesBayar(~L101)
  → JobProsesPembayaran.php (~L30) → Mail + JobPotongStok (jika bebas)

Resep
  → PesananController::unggahResep (~L118)
  → Apoteker/ResepController::putuskan (~L25) → JobPotongStok

FIFO
  → LayananStok::potongStokFifo (~L68)  ← juga Kasir/TransaksiController (~L75)

Admin dasbor / laporan / migrasi / pantau
  → DasborController.php, LaporanController.php,
    MigrasiController.php + LayananMigrasi.php,
    PemantauanController.php
```

### 9.4 Enum & migrasi tabel (referensi cepat)

| Domain | File |
| --- | --- |
| Status pesanan / resep | `app/Enums/StatusPesanan.php`, `StatusResep.php` |
| Tingkat keparahan | `app/Enums/TingkatKeparahan.php` |
| Skema DB | `database/migrations/2026_08_27_100000_*.php`, `100100_*.php`, `100200_*.php` |
| Data demo | `database/seeders/DatabaseSeeder.php` |

---

## 10. Piagam proyek

**Nama:** Apotek Digital Klinik Makmur Jaya  

**Tujuan:** Membangun aplikasi e-commerce penjualan obat berbasis web yang menggantikan penjualan manual dan Excel, dengan bukti demonstrasi untuk setiap butir uji kompetensi Web Developer.

**Lingkup:** katalog & fuzzy, keranjang–checkout–bayar (job), verifikasi resep, FIFO online+kasir, dasbor/laporan PDF, migrasi CSV, pemantauan, keamanan 4 peran, paket dokumentasi + naskah demo.

**Di luar lingkup:** Midtrans/gateway sungguhan, Redis/WebSocket, Elasticsearch, SPA, mobile, multi-gudang, Docker cluster.

**Pemangku kepentingan:** manajemen klinik (studi kasus), pasien/kasir/apoteker/admin, pengembang, asesor.

**Kriteria sukses:** setiap butir matriks bisa didemo; stok tidak negatif; FIFO terbukti; dokumen lengkap; demo 30–45 menit.

**Batasan waktu:** ±18 jam / 3 hari. **Asumsi demo:** satu mesin, email `log`, antrian database.

---

## 11. WBS & lingkup

1. **Analisis & perancangan** — piagam, WBS, jadwal, arsitektur, tools, risiko, mutu  
2. **Fondasi** — Laravel 12, Breeze, peran, migrasi, seeder  
3. **Master** — obat, kategori, pemasok, pelanggan, transaksi  
4. **Penjualan** — katalog, keranjang, bayar, resep, kasir  
5. **Stok & paralel** — FIFO, job, alert, dasbor polling  
6. **Migrasi & ops** — CSV, rollback, cutover, pemantauan  
7. **Laporan** — SQL 4 jenis + PDF  
8. **Dokumentasi & UAT** — panduan ini + naskah demo  

---

## 12. Jadwal 18 jam

| Hari | Jam | Fokus | Hasil |
| --- | --- | --- | --- |
| 1 | 6 | Analisis, desain, scope, risiko | Dokumen + scaffold + peran + seeder |
| 2 | 6 | Development, migrasi, monitoring | Alur bisnis, FIFO, job, dasbor, kasir |
| 3 | 6 | Debugging, UAT, dokumentasi | UAT, polesan, latihan naskah |

**Tonggak:** M1 auth → M2 alur pasien → M3 stok robust → M4 operasional → M5 siap uji.

---

## 13. Arsitektur perangkat keras & topologi

```
Pengguna (browser)
        │
        ▼
Web Server (Nginx/Apache) ──► Aplikasi Laravel (PHP 8.2+)
                                    │
                    ┌───────────────┼───────────────┐
                    ▼               ▼               ▼
                 MySQL 8      Worker antrian    Log email + peringatan
```

Lokal demo = satu mesin; dokumen tetap memisahkan peran logis web/app/DB.

### Spek rancangan (150–200 pasien/hari, >2.000 obat)

| Node | Spek |
| --- | --- |
| Web + aplikasi | 2 vCPU, RAM 4 GB, SSD 80 GB |
| Database | 2 vCPU, RAM 4 GB, SSD 100 GB |
| Bandwidth | 20 Mbps |

**Skala naik:** tambah worker → indeks SQL → pisah DB → cache bila perlu.  
**Real-time:** polling 10 detik (bukan WebSocket).

| Lapisan | Teknologi |
| --- | --- |
| Framework | Laravel 12 |
| UI | Blade + Alpine.js + Tailwind CSS 4 |
| DB | MySQL / MariaDB |
| Antrian | Database queue |
| PDF | DomPDF |
| Auth | Breeze + enum `peran` |

---

## 14. Analisis tools

| Kebutuhan | Pilihan | Alternatif | Alasan |
| --- | --- | --- | --- |
| Framework | Laravel 12 | PHP native | MVC, CSRF/XSS/hash/queue bawaan |
| UI | Blade + Alpine + Tailwind | SPA | Cepat UAT, mudah dijelaskan |
| Database | MySQL 8 | SQLite | Relasi stok–transaksi–laporan |
| Antrian | Database queue | Redis | Tanpa infrastruktur ekstra |
| Real-time | Polling 10 dtk | WebSocket | Cukup volume klinik |
| Pencarian | `cariFuzzy()` | Elasticsearch | Sederhana, cukup demo |
| PDF | DomPDF | Snappy | Integrasi mudah |
| Impor | CSV native | Laravel Excel | Ringan |
| Auth | Breeze + peran | Sanctum API | Email verify & session siap |
| Email | Mail log | SMTP | Bukti tanpa SMTP |
| Versi | Git | Copy folder | Update & rollback kode |

---

## 15. Risiko keamanan informasi

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Akses tanpa hak | Tinggi | Middleware `peran` + `auth` + `verified` |
| Pencurian kata sandi | Tinggi | bcrypt; min 8 + huruf + angka |
| Akun palsu | Sedang | Verifikasi email wajib |
| SQL Injection | Tinggi | Eloquent / binding |
| XSS | Tinggi | Escape Blade `{{ }}` |
| CSRF | Tinggi | `@csrf` / middleware CSRF |
| Session idle | Sedang | `SESSION_LIFETIME=30` |
| Manipulasi stok | Tinggi | Transaksi DB + `lockForUpdate` + audit |
| Unggah berbahaya | Sedang | Validasi MIME/ukuran |
| Kebocoran API | Sedang | Realtime hanya admin; autocomplete katalog saja |

**Risiko diterima (demo):** email tidak SMTP; pembayaran tanpa gateway — disengaja untuk lingkup ujikom.

---

## 16. Checklist mutu

**Fungsional:** 4 peran; CRUD master + transaksi/resep; cart–bayar–resep; FIFO online+kasir; fuzzy+autocomplete+filter; dasbor+polling; alert; job; PDF; migrasi+rollback.

**Keamanan:** email verify; hash+aturan sandi; CSRF; XSS escape; session 30 menit; audit terlihat.

**Demo:** worker queue lengkap; `storage:link`; akun & CSV siap; naskah 30 menit.

---

## 17. Log perubahan

| Versi | Tanggal | Ringkasan |
| --- | --- | --- |
| 1.0 | 2026-08 | Rilis demo ujikom: alur lengkap, FIFO, antrian, migrasi, PDF, dokumentasi |
| 1.0.1 | 2026-08 | Perbaikan UX bayar; komponen unggah seragam; `apotek:cek-kedaluwarsa --sync` |

Detail teknis: `git log`.

---

## 18. Analisis dampak modul stok

**Objek:** `LayananStok::potongStokFifo()`, `batch_obat`, `mutasi_stok`.

**Mengapa sensitif:** satu sumber kebenaran untuk katalog, kasir, laporan, alert. Salah ubah → stok negatif / FIFO salah / laporan menyimpang.

| Komponen | Uji regresi |
| --- | --- |
| Pesanan online | Beli obat bebas → cek sisa batch |
| Kasir | Jual 1 item → katalog turun |
| Dasbor / alert | Peringatan setelah potong |
| Laporan | Bandingkan PDF vs `mutasi_stok` |
| Migrasi | Impor lalu potong FIFO |
| Audit | Ada jejak `stok.fifo` |

**Aturan aman:** semua potong lewat `LayananStok`; selalu transaksi + lock; jangan kolom stok cache terpisah dari `SUM(batch.sisa)`.

---

## 19. Bukti integrasi

| Dari | Ke | Mekanisme |
| --- | --- | --- |
| Katalog / autocomplete | Keranjang | ID obat + sesi |
| Checkout | Pembayaran | `menunggu_bayar` → `JobProsesPembayaran` |
| Bayar sukses | Email + peringatan | Mail log + tabel `peringatan` |
| Obat resep | Apoteker | Unggah gambar |
| Resep disetujui | Stok | `JobPotongStok` → FIFO |
| Kasir | Stok | `LayananStok` yang sama |
| Impor CSV | Master + batch | `Bus::batch` |
| Scheduler/perintah | Alert kedaluwarsa | `JobCekKedaluwarsa` |
| Dasbor | Realtime | Polling JSON |
| Laporan | PDF | `LayananLaporan` + DomPDF |

**Bukti demo:** beli online → stok turun; kasir jual → turun lagi; badge dasbor berubah ≤10 detik.

---

## 20. Bukti migrasi data

### Mapping field

| Kolom CSV lama | Field sistem |
| --- | --- |
| `kode_obat` | `obat.kode` |
| `nama_obat` | `obat.nama` |
| `kategori` | `kategori_obat.nama` |
| `pemasok` | `pemasok.nama` |
| `harga` | `obat.harga` |
| `stok` | `batch_obat` (jumlah/sisa) |
| `stok_minimum` | `obat.stok_minimum` |
| `butuh_resep` | `obat.butuh_resep` (0/1) |
| `kedaluwarsa` | `batch_obat.kedaluwarsa` (`YYYY-MM-DD`) |

Kode: `LayananMigrasi::mappingKolom()`.

**Validasi:** kode & nama wajib; harga & stok ≥ 0; tanggal valid. Baris gagal dicatat di `log_migrasi`; baris valid tetap masuk.

**File contoh:** `obat-lama.csv`, `obat-lama-campur-error.csv`, `obat-kedaluwarsa-demo.csv`.

**Langkah bukti:** unggah → worker `impor` → cek log → verifikasi katalog → (opsional) rollback.

---

## 21. Rencana cutover

**Sebelum:** backup DB; `.env` benar; `migrate`; `storage:link`; `npm run build`; worker aktif; CSV dicek header; akun staf siap; checklist mutu lulus.

**Saat:** bekukan Excel → impor CSV terakhir → verifikasi sample stok → uji 1 kasir + 1 online → buka akses.

**Sesudah:** pantau Pemantauan 30–60 menit; bandingkan sample stok; pastikan alert muncul jika data mendukung; catat di log perubahan.

**Sukses:** impor selesai, transaksi uji OK, stok sinkron, tidak ada error kritis. Jika gagal → bagian 22.

---

## 22. Rencana rollback

**A. Data migrasi:** Admin → Migrasi → Rollback batch ID → verifikasi katalog → perbaiki CSV → impor ulang.

**B. Transaksi:** Admin batalkan pesanan yang belum selesai. Jangan edit `batch_obat.sisa` manual.

**C. Kode:**

```powershell
git log --oneline -10
git checkout <commit-stabil>
composer install
php artisan migrate
npm run build
```

**D. DB darurat:** hentikan worker & web → restore dump pra-cutover → nyalakan ulang → catat kejadian.

---

## 23. Pembaruan perangkat lunak via Git

```powershell
git status
git pull origin main
composer install --no-interaction
npm ci
npm run build
php artisan migrate --force
php artisan config:clear
php artisan view:clear
php artisan storage:link
php artisan queue:restart
```

Nyalakan lagi worker dengan daftar antrian lengkap. Bukti ke asesor: `git log` singkat + bagian log perubahan.

---

## 24. UAT

Lingkungan: `http://127.0.0.1:8000` — akun demo bagian 1.

| ID | Skenario | Hasil diharapkan | Lulus? |
| --- | --- | --- | --- |
| U01 | Login 4 peran | Menu sesuai hak | [x] |
| U02 | Verifikasi email | Tidak checkout sebelum verified | [x] |
| U03 | Fuzzy `paracet` | Paracetamol muncul | [x] |
| U04 | Autocomplete | ≤10 saran | [x] |
| U05 | Beli obat bebas + Bayar sekarang | Status lanjut; stok turun | [x] |
| U06 | Worker saat bayar | Job pembayaran/stok DONE | [x] |
| U07 | Obat resep setuju | Stok potong setelah setuju | [x] |
| U08 | Tolak resep | Stok tidak potong | [x] |
| U09 | Kasir jual | Katalog ikut turun | [x] |
| U10 | Dasbor polling | Badge/data berubah ≤10 dtk | [x] |
| U11 | Stok di bawah minimum | Alert stok kritis | [x] |
| U12 | CSV KD + cek kedaluwarsa | Alert 30/60/90 | [x] |
| U13 | Unduh PDF | File PDF ada | [x] |
| U14 | Impor `obat-lama.csv` | Baris valid masuk | [x] |
| U15 | CSV campur error | Rusak gagal; valid masuk | [x] |
| U16 | Rollback migrasi | Obat batch hilang | [x] |
| U17 | Batalkan transaksi | Status dibatalkan | [x] |
| U18 | Pasien buka `/admin/dasbor` | 403 | [x] |
| U19 | Idle > 30 menit | Harus login ulang | [x] |
| U20 | Pemantauan | Audit + severity terlihat | [x] |

SMTP/Redis di luar lingkup — diganti mail log & database queue.

| Peran | Nama | Tanggal |
| --- | --- | --- |
| Penguji | (isi saat presentasi) | |
| Admin klinik | (isi saat presentasi) | |

---

## 25. Kontak & dukungan demo

Untuk presentasi uji kompetensi, gunakan akun demo di bagian 1.  
Urutan presentasi: `naskah-demo.md`.  

Jika halaman kosong setelah `migrate:fresh --seed`, pastikan seeder selesai tanpa error dan worker antrian aktif sebelum demo pembayaran.
