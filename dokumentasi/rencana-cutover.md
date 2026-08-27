# Rencana cutover

Peralihan dari Excel/manual ke sistem web untuk demo / go-live terbatas.

## Sebelum cutover
- [ ] Backup database  
- [ ] Pastikan `.env` produksi/demo benar (DB, `APP_KEY`, `SESSION_LIFETIME`)  
- [ ] `php artisan migrate --force`  
- [ ] `php artisan storage:link`  
- [ ] `npm run build`  
- [ ] Worker antrian aktif dengan daftar queue lengkap  
- [ ] CSV master obat sudah dicek header (mapping di menu Migrasi)  
- [ ] Akun admin/apoteker/kasir siap  
- [ ] Checklist mutu lulus (`checklist-mutu.md`)  

## Saat cutover
1. Bekukan input Excel baru (stop edit spreadsheet).  
2. Impor CSV terakhir lewat Admin → Migrasi.  
3. Verifikasi jumlah obat & sample batch FIFO.  
4. Uji 1 transaksi kasir + 1 pesanan online.  
5. Nyalakan akses pengguna.  

## Sesudah cutover
- [ ] Pantau Pemantauan (failed jobs, log kesalahan) 30–60 menit  
- [ ] Bandingkan stok sample vs catatan lama  
- [ ] Pastikan alert stok/kedaluwarsa muncul jika data mendukung  
- [ ] Catat hasil di `log-perubahan.md`  

## Kriteria sukses cutover
Impor selesai, transaksi uji sukses, stok sinkron, tidak ada error kritis di Pemantauan.

Jika gagal → `rencana-rollback.md`.
