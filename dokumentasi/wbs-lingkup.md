# WBS & lingkup (Work Breakdown Structure)

## 1. Analisis & perancangan
1.1 Piagam, WBS, jadwal  
1.2 Arsitektur hardware & topologi  
1.3 Analisis tools  
1.4 Risiko keamanan  
1.5 Checklist mutu  

## 2. Fondasi aplikasi
2.1 Laravel 12 + Breeze + peran  
2.2 Migrasi tabel domain  
2.3 Seeder akun & obat demo  

## 3. Modul master
3.1 CRUD Obat (+ gambar, batch)  
3.2 Kategori, Pemasok, Pelanggan  
3.3 Daftar / batal transaksi  

## 4. Alur penjualan
4.1 Katalog fuzzy + filter + autocomplete  
4.2 Keranjang sesi → checkout  
4.3 Pembayaran simulasi (job)  
4.4 Unggah & verifikasi resep  
4.5 Kasir counter (FIFO sama)  

## 5. Stok, alert, paralel
5.1 `LayananStok` FIFO  
5.2 Job bayar / stok / impor / laporan / kedaluwarsa  
5.3 Peringatan stok min & 30/60/90 hari  
5.4 Dasbor polling 10 detik  

## 6. Migrasi & operasional
6.1 Impor CSV + mapping + validasi  
6.2 Rollback batch  
6.3 Checklist cutover  
6.4 Pemantauan antrian, error, audit  

## 7. Laporan
7.1 SQL penjualan / terlaris / kedaluwarsa / rekap  
7.2 Export PDF  

## 8. Dokumentasi & UAT
8.1 Panduan pengguna, FAQ, API, troubleshooting  
8.2 UAT + naskah demo  
8.3 Bukti migrasi, cutover, rollback, git  

## Batas lingkup
Lihat `piagam-proyek.md` — fitur di luar daftar di atas tidak dikerjakan.
