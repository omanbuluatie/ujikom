# REVISI Domain — Apotek Digital Klinik Makmur Jaya

Dokumentasi lengkap selarasan revisi uji kompetensi (Agustus 2026).  
Setiap bagian mencantumkan **lokasi file** dan **rentang baris** utama.

---

## Ringkasan Perubahan

| Area | Sebelum | Sesudah |
|------|---------|---------|
| Pesanan | Tabel `pesanan`, kolom `nomor` | Tabel `transaksi`, kolom `kode_transaksi` |
| Status transaksi | 7 status (menunggu_bayar, dll.) | 4 enum: `pending`, `diproses`, `selesai`, `dibatalkan` (default: pending) |
| Harga obat | `unsignedInteger` | `DECIMAL(12,2)` — mendukung pajak (mis. 3300,50) |
| Kategori obat | nama, slug | + `slot`, `deskripsi`, `is_active`, `email` |
| Mutasi stok `jenis` | masuk/keluar | `masuk`, `keluar`, `expired`, `return` |
| Resep status | menunggu/disetujui/ditolak | `pending`, `verifikasi`, `ditolak` (default: pending) |
| Catatan resep | `catatan_apoteker` | `catatan_verifikasi` |
| Pembayaran | Simulasi klik | `metode_pembayaran` (string) + upload `bukti_pembayaran` |
| Pengiriman | — | `alamat_pengiriman` wajib saat checkout |
| Notifikasi pasien | — | Tabel `notifikasi` + in-app di halaman transaksi |
| Ekspor | — | Admin CSV transaksi |

---

## 1. Migrasi Database

**File:** `database/migrations/2026_08_29_100000_revisi_domain_transaksi.php`

| Baris | Isi |
|-------|-----|
| 22–27 | Tambah kolom kategori: `slot`, `deskripsi`, `is_active`, `email` |
| 29–35 | Ubah `obat.harga` dan `item_pesanan` harga/subtotal ke DECIMAL via `ALTER TABLE` |
| 38–39 | Rename `pesanan` → `transaksi`, `item_pesanan` → `item_transaksi` |
| 41–43 | Rename `nomor` → `kode_transaksi` |
| 45–50 | Kolom transaksi: `metode_pembayaran`, `bukti_pembayaran`, `alamat_pengiriman`, total DECIMAL |
| 52–67 | Rename FK `pesanan_id` → `transaksi_id` (item, resep, mutasi, peringatan) |
| 69–82 | Pemetaan status lama → baru (transaksi & resep) |
| 84–93 | Buat tabel `notifikasi` |

**Jalankan setelah pull:**
```bash
php artisan migrate
# atau fresh install:
php artisan migrate:fresh --seed
php artisan queue:work --queue=pembayaran,stok,impor,laporan,default --tries=3
```

---

## 2. Enum

| File | Baris | Enum |
|------|-------|------|
| `app/Enums/StatusTransaksi.php` | 9–34 | `Pending`, `Diproses`, `Selesai`, `Dibatalkan` + label & kelas CSS tiket |
| `app/Enums/StatusResep.php` | 6–19 | `Pending`, `Verifikasi`, `Ditolak` |
| `app/Enums/JenisMutasi.php` | 9–24 | `Masuk`, `Keluar`, `Expired`, `Return` |
| `app/Enums/JenisNotifikasi.php` | 6–27 | Pembayaran diterima/ditolak, diproses, dikemas, selesai, resep ditolak, batal |

**Dihapus:** `app/Enums/StatusPesanan.php`

---

## 3. Model

| File | Baris | Keterangan |
|------|-------|------------|
| `app/Models/Transaksi.php` | 16–70 | Model utama; `buatKode()`, relasi item/resep/notifikasi, `butuhResep()` |
| `app/Models/ItemTransaksi.php` | 9–37 | Item baris; cast harga desimal |
| `app/Models/Notifikasi.php` | 10–39 | Notifikasi in-app pasien |
| `app/Models/Resep.php` | 12–41 | `transaksi_id`, `catatan_verifikasi`, cast `StatusResep` |
| `app/Models/KategoriObat.php` | 12–36 | Field revisi kategori |
| `app/Models/Obat.php` | 29–35 | Cast `harga` → `decimal:2` |
| `app/Models/MutasiStok.php` | 14–28 | FK `transaksi_id`, cast `JenisMutasi` |
| `app/Models/Peringatan.php` | — | FK `transaksi_id` |
| `app/Models/User.php` | 52–60 | Relasi `transaksi()`, `notifikasi()` |

**Dihapus:** `Pesanan.php`, `ItemPesanan.php`

---

## 4. Layanan (Services)

| File | Baris | Fungsi revisi |
|------|-------|---------------|
| `app/Layanan/LayananNotifikasi.php` | 13–40 | `kirim()`, `untukTransaksi()` — notifikasi pasien |
| `app/Layanan/LayananPeringatan.php` | 86–95 | `transaksiBaru()` (dulu `pesananBaru`) |
| `app/Layanan/LayananStok.php` | 68–96 | `potongStokFifo(..., ?int $transaksiId)` |
| `app/Layanan/LayananLaporan.php` | 17–86 | Query pakai `Transaksi` / `ItemTransaksi` / `StatusTransaksi` |
| `app/Layanan/LayananKeranjang.php` | 51–79 | Subtotal & total `float` (desimal) |

---

## 5. Jobs & Mail

| File | Baris | Alur |
|------|-------|------|
| `app/Jobs/JobProsesPembayaran.php` | 31–64 | Verifikasi bukti → status `diproses` → notifikasi + email |
| `app/Jobs/JobPotongStok.php` | 28–55 | FIFO → `selesai` atau `dibatalkan` + notifikasi |
| `app/Mail/NotifikasiStatusTransaksi.php` | 13–32 | Email log status (view: `mail/status-transaksi`) |

---

## 6. Controller

### Pasien — `app/Http/Controllers/TransaksiController.php`

| Method | Baris | Deskripsi |
|--------|-------|-----------|
| `index` | 24–37 | Daftar transaksi + 10 notifikasi terbaru |
| `checkout` | 40–50 | Konfirmasi keranjang |
| `buat` | 53–93 | Buat transaksi `pending` + `alamat_pengiriman` |
| `bayar` | 95–106 | Form upload bukti |
| `prosesBayar` | 111–135 | Simpan metode + bukti → dispatch job |
| `unggahResep` | 137–155 | Upload resep saat `diproses` + butuh resep |

### Admin — `app/Http/Controllers/Admin/TransaksiController.php`

| Method | Baris | Deskripsi |
|--------|-------|-----------|
| `index` | 23–32 | Filter status + paginate |
| `show` | 35–39 | Detail transaksi |
| `setujuiPembayaran` | 42–68 | Manual approve → `diproses` + notifikasi + job stok |
| `tolakPembayaran` | 71–86 | Tolak → `dibatalkan` + notifikasi |
| `batalkan` | 88–98 | Batalkan transaksi |
| `eksporCsv` | 101–131 | Stream CSV kolom transaksi |

### Apoteker — `app/Http/Controllers/Apoteker/ResepController.php`

| Method | Baris | Deskripsi |
|--------|-------|-----------|
| `index` | 18–22 | Antrian resep + relasi `transaksi` |
| `putuskan` | 24–68 | `verifikasi` → job stok; `ditolak` → batal + notifikasi |

### Lainnya

| File | Baris | Perubahan |
|------|-------|-----------|
| `app/Http/Controllers/Kasir/TransaksiController.php` | 58–86 | Pakai `Transaksi`, `ItemTransaksi`, `StatusTransaksi::Selesai` |
| `app/Http/Controllers/Admin/KategoriController.php` | 23–40 | Validasi slot, deskripsi, is_active, email |
| `app/Http/Controllers/Admin/ObatController.php` | 93 | Validasi harga `numeric` desimal |
| `app/Http/Controllers/Api/StatusRealtimeController.php` | 19–22 | Key `transaksi_baru` + `kode_transaksi` |

**Dihapus:** `app/Http/Controllers/PesananController.php`

---

## 7. Routes

**File:** `routes/web.php`

| Baris | Route | Nama |
|-------|-------|------|
| 58–63 | `/transaksi/*` | `transaksi.index`, `checkout`, `buat`, `bayar`, `proses-bayar`, `resep` |
| 93–98 | `/admin/transaksi/*` | `admin.transaksi.*` + `ekspor-csv`, `setujui-bayar`, `tolak-bayar`, `batal` |

---

## 8. Views

### Pasien (`resources/views/transaksi/`)

| File | Baris | Isi |
|------|-------|-----|
| `index.blade.php` | 1–68 | Daftar transaksi, panel notifikasi, upload resep |
| `checkout.blade.php` | 1–33 | Alamat pengiriman wajib + catatan |
| `bayar.blade.php` | 1–36 | Metode pembayaran + upload bukti |

### Admin

| File | Baris | Isi |
|------|-------|-----|
| `admin/transaksi/index.blade.php` | 1–28 | Filter + tombol ekspor CSV |
| `admin/transaksi/show.blade.php` | 1–55 | Bukti bayar, setujui/tolak, resep |
| `admin/kategori/index.blade.php` | 1–52 | Form field revisi kategori |

### Apoteker

| File | Baris | Isi |
|------|-------|-----|
| `apoteker/resep.blade.php` | 1–38 | `transaksi`, `catatan_verifikasi`, keputusan verifikasi/tolak |

### Layout & lainnya

| File | Perubahan |
|------|-----------|
| `layouts/loket.blade.php` | Nav → Transaksi |
| `layouts/aplikasi.blade.php` | Sidebar → Transaksi saya |
| `keranjang/index.blade.php` | Route checkout + harga 2 desimal |
| `katalog/*.blade.php` | Harga format `Rp x.xxx,xx` |
| `mail/status-transaksi.blade.php` | Template email baru |

**Dihapus:** `resources/views/pesanan/*`, `mail/status-pesanan.blade.php`

---

## 9. Seeder

**File:** `database/seeders/DatabaseSeeder.php`

| Baris | Isi |
|-------|-----|
| 42–52 | Kategori dengan slot, deskripsi, is_active, email |
| 56–61 | Harga obat desimal (contoh pajak: 3300.50, 4950.75, …) |

---

## 10. Alur Bisnis (End-to-End)

```
Keranjang → Checkout (alamat) → Transaksi pending
    → Upload bukti + metode bayar → JobProsesPembayaran
        → diproses + notifikasi "diterima" / "sedang diproses"
        → [jika obat keras] pasien upload resep (status pending)
            → apoteker verifikasi/tolak
                → verifikasi: JobPotongStok → selesai + notifikasi
                → ditolak: dibatalkan + notifikasi resep ditolak
        → [tanpa resep] JobPotongStok langsung → selesai
Admin: setujui/tolak manual, batalkan, ekspor CSV
```

---

## 11. Notifikasi Pasien

Jenis (`JenisNotifikasi`) dipicu di:

- `JobProsesPembayaran` — pembayaran diterima, sedang diproses
- `Admin/TransaksiController` — diterima, dikemas, ditolak, dibatalkan
- `JobPotongStok` — selesai / dibatalkan stok
- `Apoteker/ResepController` — dikemas / resep ditolak

Ditampilkan di `transaksi/index.blade.php` baris 6–18 (10 terbaru).

---

## 12. Checklist Deploy / Demo

1. `php artisan migrate` (wajib jika DB lama masih pakai `pesanan`)
2. `php artisan db:seed` (opsional, harga desimal demo)
3. `php artisan storage:link`
4. Worker antrian aktif
5. Login pasien: `pasien@makmurjaya.test` / `Password1`

---

## 13. File yang Dihapus (Obsolete)

- `app/Models/Pesanan.php`
- `app/Models/ItemPesanan.php`
- `app/Enums/StatusPesanan.php`
- `app/Http/Controllers/PesananController.php`
- `app/Mail/NotifikasiStatusPesanan.php`
- `resources/views/pesanan/*`
- `resources/views/mail/status-pesanan.blade.php`

---

*Terakhir diperbarui: 29 Agustus 2026 — revisi domain transaksi & notifikasi.*
