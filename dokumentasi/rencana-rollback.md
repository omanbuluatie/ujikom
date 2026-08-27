# Rencana rollback

## A. Rollback data migrasi (batch impor)
1. Admin → Migrasi → pilih **batch ID** impor.  
2. Jalankan **Rollback** (menghapus obat yang berhasil masuk dari batch itu).  
3. Verifikasi katalog tidak lagi menampilkan kode batch tersebut.  
4. Perbaiki CSV → impor ulang.

Mekanisme: `LayananMigrasi` + `log_migrasi` per batch.

## B. Rollback transaksi bisnis
- Pesanan belum selesai: Admin → Transaksi → **Batalkan**.  
- Stok yang sudah dipotong FIFO hanya di-rollback jika transaksi DB gagal di tengah (exception). Jangan mengedit `batch_obat.sisa` manual.

## C. Rollback kode aplikasi
```powershell
git log --oneline -10
git checkout <commit-stabil>
composer install
php artisan migrate
npm run build
```
Detail: `pembaruan-git.md`.

## D. Rollback database penuh (darurat)
1. Hentikan `queue:work` dan akses web.  
2. Restore dump MySQL dari backup pra-cutover.  
3. Nyalakan ulang aplikasi + worker.  
4. Catat kejadian di `log-perubahan.md`.

## E. Kapan rollback wajib
- Impor massal merusak master obat  
- Bug stok menyebabkan inkonsistensi luas  
- Cutover gagal kriteria sukses di `rencana-cutover.md`
