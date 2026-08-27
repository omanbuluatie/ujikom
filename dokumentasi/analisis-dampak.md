# Analisis dampak — perubahan modul stok

## Objek perubahan
Modul `LayananStok::potongStokFifo()` dan struktur `batch_obat` / `mutasi_stok`.

## Mengapa sensitif
Stok adalah sumber kebenaran tunggal untuk katalog online, kasir, laporan, dan alert. Salah ubah → stok negatif, FIFO salah, atau laporan menyimpang.

## Dampak per komponen

| Komponen | Dampak jika stok diubah | Uji regresi |
| --- | --- | --- |
| Pesanan online (`JobPotongStok`) | Gagal potong / status salah | Beli obat bebas → cek sisa batch |
| Kasir | Counter tidak sinkron | Jual 1 item → katalog turun |
| Dasbor / alert | False positive stok kritis | Cek peringatan setelah potong |
| Laporan penjualan | Angka tidak cocok mutasi | Bandingkan PDF vs `mutasi_stok` |
| Migrasi CSV | Batch baru dari impor | Impor lalu potong FIFO |
| Audit | Jejak tidak lengkap | Cek `LogAudit` stok.fifo |

## Aturan perubahan aman
1. Semua potong stok lewat `LayananStok` (jangan update `sisa` langsung dari controller).  
2. Selalu dalam `DB::transaction()` + `lockForUpdate`.  
3. Jangan menambah kolom “stok cache” yang bisa menyimpang dari `SUM(batch.sisa)`.  
4. Setelah ubah: jalankan skenario UAT stok di `uat.md`.

## Rollback
Kembalikan commit Git modul stok; jika data batch rusak, restore DB dari backup sebelum cutover (lihat `rencana-rollback.md`).
