# Checklist mutu

Centang sebelum menyerahkan ke asesor / demo resmi.

## Fungsional
- [ ] 4 peran login; menu sesuai hak  
- [ ] CRUD obat, kategori, pemasok, pelanggan; transaksi lihat/batal; resep verifikasi  
- [ ] Cart → checkout → bayar → konfirmasi  
- [ ] Resep: unggah, setuju, tolak  
- [ ] FIFO online & kasir  
- [ ] Fuzzy + autocomplete + pagination + sort + filter  
- [ ] Dasbor harian/mingguan/bulanan + grafik + polling  
- [ ] Alert stok min, kedaluwarsa 30/60/90, pesanan baru, error ber-severity  
- [ ] Job: bayar, stok, impor batch, laporan  
- [ ] PDF laporan  
- [ ] Migrasi CSV: mapping, validasi, rollback  

## Non-fungsional / keamanan
- [ ] Email verification aktif  
- [ ] Password di-hash; validasi aturan sandi  
- [ ] CSRF pada form; XSS escape  
- [ ] Session 30 menit  
- [ ] Audit log terlihat di Pemantauan  

## Dokumentasi
- [ ] `panduan-pengguna.md`, `faq.md`, `api.md`, `troubleshooting.md`  
- [ ] Arsitektur, tools, risiko, piagam, WBS, jadwal  
- [ ] Bukti migrasi, cutover, rollback, git, UAT, naskah demo  

## Demo
- [ ] Worker antrian jalan dengan daftar queue lengkap  
- [ ] `storage:link` sudah dibuat  
- [ ] Akun demo & CSV contoh siap  
- [ ] Latihan naskah 30 menit tanpa improvisasi besar  
