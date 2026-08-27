# Piagam proyek (Project Charter)

## Nama proyek
Apotek Digital Klinik Makmur Jaya

## Tujuan
Membangun aplikasi e-commerce penjualan obat berbasis web yang menggantikan penjualan manual dan Excel, dengan bukti demonstrasi untuk setiap butir uji kompetensi Web Developer.

## Lingkup
- Katalog obat online, keranjang, checkout, pembayaran simulasi (job antrian)
- Verifikasi resep (unggah gambar → apoteker)
- Potong stok FIFO (online & kasir memakai layanan yang sama)
- Dasbor, laporan PDF, migrasi CSV, pemantauan antrian/error/audit
- Keamanan: 4 peran, verifikasi email, hash password, CSRF, XSS escape, session 30 menit
- Paket dokumentasi + naskah demo 30–45 menit

## Di luar lingkup
Payment gateway sungguhan, Redis/WebSocket, Elasticsearch, SPA, mobile app, multi-gudang, Docker cluster.

## Pemangku kepentingan
| Peran | Keterwakilan |
| --- | --- |
| Sponsor / klinik | Manajemen Klinik Makmur Jaya (studi kasus) |
| Pengguna | Pasien, kasir, apoteker, admin |
| Pengembang | Tim Web Developer (uji kompetensi) |
| Asesor | Penilai kompetensi |

## Kriteria sukses
1. Semua butir matriks di `RENCANA.md` bisa didemo.
2. Stok tidak negatif; FIFO terbukti.
3. Dokumentasi lengkap di folder `dokumentasi/`.
4. Demo 30–45 menit mengikuti `naskah-demo.md`.

## Batasan waktu
3 hari kerja / ±18 jam (lihat `jadwal.md`).

## Asumsi
Lokal demo satu mesin; email lewat driver `log`; antrian database (bukan Redis).
