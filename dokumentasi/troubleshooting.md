# Troubleshooting

Panduan perbaikan cepat saat demo atau UAT gagal.

---

## A. Antrian & pembayaran

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Status tetap **Menunggu bayar** | Belum klik **Bayar sekarang** | Buka halaman bayar → Bayar sekarang |
| Status tetap **Menunggu bayar** | Worker tidak mendengar antrian `pembayaran` | `php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3` |
| Flash “Pesanan ini tidak menunggu pembayaran” | Double-pay / halaman usang | Muat ulang daftar pesanan; status sudah berubah |
| Job menumpuk di `jobs` | Worker mati | Nyalakan ulang worker; cek `failed_jobs` |

## B. Auth & akses

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Tidak bisa checkout | Email belum diverifikasi | Buka tautan di `storage/logs/laravel.log` |
| 403 Forbidden | Salah peran | Login akun sesuai menu (admin/apoteker/kasir/pasien) |
| Sesi hilang mendadak | Timeout 30 menit | Masuk ulang (perilaku sengaja untuk keamanan) |
| Kata sandi ditolak saat daftar | Validasi min 8 + huruf + angka | Contoh demo: `Password1` |

## C. Stok & FIFO

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Stok katalog tidak berubah | Halaman lama / transaksi gagal | Hard refresh; cek status pesanan & `mutasi_stok` |
| Error stok tidak cukup | Batch kosong | Tambah batch di form obat / impor CSV |
| Stok negatif (tidak boleh) | Bug lama | Pastikan potong lewat `LayananStok` + `DB::transaction` |

## D. Multimedia & storage

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Gambar obat/resep 404 | Belum `storage:link` | `php artisan storage:link` |
| Unggah gagal | Ukuran / tipe salah | Resep JPG/PNG maks ~4 MB; gambar obat maks ~2 MB |

## E. Migrasi CSV

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Semua baris gagal | Header tidak sesuai mapping | Samakan dengan `contoh-data/obat-lama.csv` |
| Sebagian gagal | Validasi baris (harga/stok/tanggal) | Baca `log_migrasi`; baris valid tetap masuk |
| Rollback tidak menghapus | Batch ID salah | Pilih batch yang sama dari log migrasi |

## F. Laporan & PDF

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| PDF kosong / error | Rentang tanggal kosong / DomPDF | Ubah filter tanggal; cek log Laravel |
| Laporan antrian tidak selesai | Worker antrian `laporan` mati | Nyalakan worker dengan daftar antrian lengkap |

## G. Peringatan kedaluwarsa

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Tidak ada alert 30/60/90 | Scheduler belum jam 06:00 | `php artisan apotek:cek-kedaluwarsa --sync` |
| Data demo habis | Belum impor CSV kedaluwarsa | Unggah `obat-kedaluwarsa-demo.csv` lalu jalankan perintah di atas |

## H. Lingkungan lokal

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Halaman tanpa CSS | Asset belum build / Vite | `npm run build` atau `npm run dev` |
| Koneksi DB gagal | `.env` MySQL salah | Cek `DB_*`; pastikan service MySQL hidup |
| View usang setelah ubah Blade | Cache view | `php artisan view:clear` |

---

## Perintah darurat demo

```powershell
php artisan serve
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
php artisan apotek:cek-kedaluwarsa --sync
php artisan storage:link
php artisan view:clear
npm run build
```

Log aplikasi: `storage/logs/laravel.log`  
Log kesalahan UI: Admin → Pemantauan  
Audit: Admin → Pemantauan (bagian audit)
