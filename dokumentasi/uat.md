# UAT — User Acceptance Test

Lingkungan: lokal `http://127.0.0.1:8000`  
Akun: lihat `panduan-pengguna.md` (sandi `Password1`)

| ID | Skenario | Langkah singkat | Hasil diharapkan | Lulus? |
| --- | --- | --- | --- | --- |
| U01 | Login peran | Masuk 4 akun bergantian | Menu sesuai peran | [x] |
| U02 | Verifikasi email | Daftar pasien baru | Tidak checkout sebelum verified; tautan di log | [x] |
| U03 | Fuzzy search | Cari `paracet` | Paracetamol muncul | [x] |
| U04 | Autocomplete | Ketik di kotak cari | JSON/saran ≤10 item | [x] |
| U05 | Beli obat bebas | Keranjang → bayar sekarang | Status lanjut; stok FIFO turun | [x] |
| U06 | Job antrian | Pantau worker saat bayar | Job `pembayaran` lalu `stok` diproses | [x] |
| U07 | Obat resep | Beli + unggah gambar | Apoteker setuju → stok potong | [x] |
| U08 | Tolak resep | Apoteker tolak | Pesanan tidak potong stok | [x] |
| U09 | Kasir sinkron | Jual 1 item di kasir | Stok katalog ikut turun | [x] |
| U10 | Dasbor polling | Buka dasbor 10+ dtk | Badge/data realtime berubah | [x] |
| U11 | Alert stok | Turunkan stok di bawah minimum | Peringatan stok kritis | [x] |
| U12 | Kedaluwarsa | Impor CSV KD + `apotek:cek-kedaluwarsa --sync` | Alert 30/60/90 | [x] |
| U13 | Laporan PDF | Admin → Laporan → PDF | File PDF terunduh | [x] |
| U14 | Migrasi CSV | Unggah `obat-lama.csv` | Baris valid masuk; log tercatat | [x] |
| U15 | Validasi impor | Unggah CSV campur error | Baris rusak gagal; valid tetap masuk | [x] |
| U16 | Rollback migrasi | Rollback batch | Obat batch hilang dari katalog | [x] |
| U17 | Batalkan transaksi | Admin batalkan pesanan | Status dibatalkan | [x] |
| U18 | Keamanan peran | Pasien buka `/admin/dasbor` | 403 | [x] |
| U19 | Session timeout | Idle > 30 menit | Harus login ulang | [x] |
| U20 | Audit & error | Cek Pemantauan | Ada audit; severity terlihat | [x] |

## Catatan hasil
UAT fungsional lulus untuk demo ujikom. Item yang bergantung lingkungan (SMTP sungguhan, Redis) di luar lingkup — diganti mail log & database queue.

## Tanda tangan (demo)
| Peran | Nama | Tanggal |
| --- | --- | --- |
| Penguji | (isi saat presentasi) | |
| Admin klinik | (isi saat presentasi) | |
