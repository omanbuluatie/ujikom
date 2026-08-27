Berikut **ringkasan utama dan poin-poin utama** dari dua dokumen yang Anda unggah.

## 1. Inti Dokumen Uji Kompetensi Web Developer

Dokumen pertama adalah pedoman proyek untuk **uji kompetensi skema Web Developer**. Proyek harus menunjukkan kemampuan dari tahap **analisis → perancangan → pengembangan → pengujian → implementasi → monitoring → dokumentasi**. 

### Poin utama kompetensi yang harus ditunjukkan

**A. Analisis & Perancangan**

* Arsitektur perangkat keras.
* Analisis tools.
* Analisis skalabilitas perangkat lunak.
* Pengelolaan risiko keamanan informasi.
* Project integration, scope, dan quality management. 

**B. Pengembangan & Implementasi**

* Algoritma pemrograman.
* SQL.
* Library/framework.
* Migrasi teknologi.
* Update software.
* Pemrograman real-time.
* Pemrograman paralel.
* Multimedia.
* Cutover aplikasi.
* Monitoring resource.
* Alert notification.
* Analisis dampak perubahan. 

**C. Pengujian**

* Debugging.
* User Acceptance Test (UAT). 

**D. Dokumentasi & Presentasi**
Harus tersedia antara lain:

* Project charter.
* WBS/scope.
* Jadwal proyek.
* Quality checklist.
* Change log.
* Panduan teknis pelanggan.
* Bukti integrasi.
* Skenario dan hasil UAT.
* Bukti migrasi.
* Cutover plan.
* Rollback plan. 

### Waktu pelaksanaan

Total **3 hari / 18 jam efektif**:

| Hari       | Fokus                                            |
| ---------- | ------------------------------------------------ |
| **Hari 1** | Analisis, desain, scope, risiko, kualitas        |
| **Hari 2** | Development, migrasi, monitoring, alert, cutover |
| **Hari 3** | Debugging, UAT, dokumentasi, presentasi          |

Presentasi dan demo **30–45 menit**, dilanjutkan tanya jawab **15–20 menit**.  

---

# 2. Inti Studi Kasus: E-Commerce Apotek

Studi kasus yang harus dibuat adalah:

> **Sistem E-Commerce Penjualan Obat Berbasis Web pada Klinik Makmur Jaya.**



Klinik melayani **150–200 pasien per hari** dan mempunyai **lebih dari 2.000 jenis obat**. 

### Masalah yang harus diselesaikan

1. Penjualan masih manual.
2. Belum ada pembelian obat secara online.
3. Stok belum dapat dipantau secara real-time.
4. Tidak ada laporan penjualan terintegrasi.
5. Verifikasi resep masih manual. 

---

# 3. Fitur Utama Sistem yang Harus Ada

### 1. Autentikasi & Keamanan

Minimal ada role:

* Admin
* Apoteker
* Kasir
* Pasien/Pelanggan

Keamanan mencakup:

* Email verification.
* Password hashing.
* Validasi password.
* SQL Injection protection.
* XSS protection.
* CSRF protection.
* Session timeout.
* Audit log.
* Analisis risiko keamanan. 

### 2. Dashboard & Real-Time

Dashboard harus menampilkan:

* Penjualan harian/mingguan/bulanan.
* Stok obat.
* Pendapatan.
* Grafik/chart.

Selain itu:

* Katalog obat.
* Search & filter.
* Detail obat.
* Upload/preview gambar.
* Notifikasi stok kritis.
* Notifikasi pesanan baru.
* Export laporan PDF. 

### 3. Data & Transaksi

CRUD:

* Obat.
* Kategori.
* Supplier.
* Pelanggan.
* Transaksi.
* Resep.

SQL untuk:

* Laporan penjualan.
* Obat terlaris.
* Obat mendekati kedaluwarsa.
* Rekap transaksi.

Algoritma:

* Autocomplete.
* Fuzzy search.
* FIFO.
* Pagination.
* Sorting.
* Filtering.

E-commerce:

* Cart.
* Checkout.
* Pembayaran.
* Konfirmasi pesanan.
* Verifikasi resep. 

---

# 4. Notifikasi & Alert

Sistem harus mampu memberikan:

* Alert stok di bawah minimum.
* Notifikasi obat mendekati kedaluwarsa **30/60/90 hari**.
* Notifikasi status pesanan.
* Notifikasi error kepada admin.
* Dashboard error berdasarkan severity:

  * Critical
  * Warning
  * Info. 

---

# 5. Pemrograman Paralel & Background Processing

Ini salah satu bagian penting untuk memenuhi kompetensi.

Harus ada:

* Pemrosesan beberapa pesanan secara paralel.
* Batch import obat CSV/Excel.
* Background job untuk laporan besar.
* Job queue untuk pembayaran.
* Job queue untuk update stok.
* Sinkronisasi stok penjualan offline/counter dengan online. 

---

# 6. Arsitektur & Infrastruktur

Wajib dibuat rancangan:

**User → Web Server → Application → Database**

Beserta:

* Topologi jaringan.
* Web server.
* Database server.
* Spesifikasi CPU.
* RAM.
* Storage.
* Bandwidth.
* Strategi menangani peningkatan user/transaksi. 

---

# 7. Migrasi & Deployment

Harus disimulasikan migrasi:

**Sistem Manual/Excel → Sistem E-Commerce**

Yang perlu dibuat:

1. Mapping field.
2. Migrasi data obat.
3. Validasi data.
4. Rollback plan.
5. Cutover plan.
6. Checklist sebelum cutover.
7. Verifikasi setelah cutover.
8. Update aplikasi menggunakan Git.
9. Impact analysis ketika suatu modul berubah. 

---

# 8. Dokumentasi Pelanggan

Minimal tersedia:

* **User Guide**
* **FAQ minimal 10 pertanyaan**
* Dokumentasi API jika ada.
* Troubleshooting Guide. 

---

## Gambaran Besarnya

Kalau disederhanakan, **asesor sebenarnya ingin melihat satu aplikasi web yang lengkap**, bukan hanya CRUD.

```text
                    SISTEM E-COMMERCE APOTEK
                              │
          ┌───────────────────┼───────────────────┐
          │                   │                   │
      FRONTEND            BACKEND             DATABASE
          │                   │                   │
   ┌──────┼──────┐      ┌─────┼─────┐       ┌────┼─────┐
   │      │      │      │     │     │       │    │     │
 Pasien Kasir Apoteker  Auth  API   Queue   Obat Transaksi
                                  │
                         ┌────────┼────────┐
                         │        │        │
                      Alert    Realtime  Background
                         │        │        │
                      Email    Dashboard   Report
```

**Kunci untuk lulus bukan sekadar aplikasi berjalan**, tetapi Anda harus bisa menjelaskan **mengapa arsitektur, framework, database, algoritma, keamanan, skalabilitas, migrasi, testing, monitoring, dan deployment tersebut dipilih**, sekaligus menunjukkan buktinya melalui demo.

Jadi, dari kedua dokumen tersebut, **produk akhirnya adalah:**

> **1 aplikasi E-Commerce Apotek + 1 paket dokumentasi proyek + 1 presentasi/demo yang membuktikan seluruh kompetensi Web Developer.**
