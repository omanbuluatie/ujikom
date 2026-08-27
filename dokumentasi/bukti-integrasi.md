# Bukti integrasi

Sistem terintegrasi pada **satu alur bisnis**, bukan modul terpisah tanpa hubungan.

## Titik integrasi

| Dari | Ke | Mekanisme |
| --- | --- | --- |
| Katalog / autocomplete | Keranjang | ID obat + sesi |
| Checkout | Pembayaran | Status `menunggu_bayar` → `JobProsesPembayaran` |
| Pembayaran sukses | Email + peringatan | `NotifikasiStatusPesanan` (log) + tabel `peringatan` |
| Obat butuh resep | Apoteker | Unggah gambar → status menunggu verifikasi |
| Resep disetujui | Stok | `JobPotongStok` → `LayananStok` FIFO |
| Kasir | Stok | Controller kasir memanggil `LayananStok` yang sama |
| Impor CSV | Master obat + batch | `Bus::batch` + `LayananMigrasi` |
| Scheduler / perintah | Alert kedaluwarsa | `JobCekKedaluwarsa` / `apotek:cek-kedaluwarsa` |
| Dasbor | Realtime | Polling JSON `/admin/api/realtime` |
| Laporan | PDF | `LayananLaporan` + DomPDF / job laporan |

## Bukti demo singkat
1. Beli online → stok turun.  
2. Kasir jual obat sama → stok turun lagi.  
3. Badge dasbor bertambah setelah peringatan baru (≤10 detik).  

Arsitektur: `arsitektur.md`. API: `api.md`.
