# FAQ — Apotek Digital Klinik Makmur Jaya

Minimal 10 pertanyaan umum untuk pengguna dan asesor.

---

**1. Apakah pembayaran memakai Midtrans atau transfer bank?**  
Tidak. Untuk uji kompetensi, pembayaran disimulasikan lewat antrian pekerjaan (`JobProsesPembayaran`) agar tetap ada bukti *job queue pembayaran*.

**2. Mengapa tiket masih “Menunggu bayar” padahal `queue:work` sudah jalan?**  
Biasanya: (1) baru menekan **Lanjut bayar**, belum **Bayar sekarang**; atau (2) worker dijalankan tanpa antrian bernama. Jalankan:

```powershell
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

**3. Mengapa stok di katalog dan kasir selalu sama?**  
Keduanya memakai `LayananStok::potongStokFifo()`. Satu sumber kebenaran — bukti sinkronisasi counter ↔ online.

**4. Apa arti strip kuning “FIFO” di kartu obat?**  
Menunjukkan batch tertua yang akan keluar berikutnya beserta sisa unit.

**5. Bagaimana jika ejaan obat salah sedikit?**  
Pakai pencarian fuzzy (`cariFuzzy`): ketik sebagian nama (mis. `paracet`) — sistem tetap menyarankan Paracetamol. Autocomplete memakai `GET /api/obat/autocomplete?q=`.

**6. Apakah semua obat butuh resep?**  
Tidak. Hanya obat bertanda **Resep** (contoh demo: Amoxicillin / obat keras di seeder).

**7. Di mana email verifikasi dan notifikasi status pesanan?**  
Di lingkungan demo: `storage/logs/laravel.log` karena `MAIL_MAILER=log`.

**8. Bagaimana membatalkan transaksi?**  
Admin → Transaksi → Detail → **Batalkan** (jika belum selesai / dibatalkan).

**9. Bagaimana memindahkan data Excel lama?**  
Export ke CSV sesuai mapping di menu Migrasi, unggah, verifikasi log. Rollback tersedia per batch. Lihat `bukti-migrasi.md` dan file `contoh-data/obat-lama.csv`.

**10. Apa yang terjadi jika stok tidak cukup?**  
Transaksi di-rollback. Stok tidak menjadi negatif. Admin mendapat peringatan.

**11. Bagaimana melihat error Critical / Warning / Info?**  
Admin → Pemantauan (log kesalahan + tingkat keparahan).

**12. Berapa lama sesi login?**  
30 menit tidak aktif (`SESSION_LIFETIME=30`), lalu harus masuk lagi.

**13. Bagaimana update aplikasi setelah perbaikan bug?**  
Ikuti `pembaruan-git.md`: pull/tag Git → `composer install` → `migrate` → `npm run build`.

**14. Bagaimana demo alert kedaluwarsa tanpa menunggu jam 06:00?**  

```powershell
php artisan apotek:cek-kedaluwarsa --sync
```

**15. Siapa yang boleh mengakses dasbor admin?**  
Hanya peran `admin`. Apoteker, kasir, dan pasien mendapat menu sesuai hak (middleware `peran`).

---

Panduan langkah demi langkah: `panduan-pengguna.md`.  
Gangguan teknis: `troubleshooting.md`.
