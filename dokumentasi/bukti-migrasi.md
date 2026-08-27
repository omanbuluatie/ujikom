# Bukti migrasi data (Excel/CSV → sistem)

## Tujuan
Memindahkan master obat dari spreadsheet lama ke Apotek Digital tanpa kehilangan baris valid.

## Mapping field

| Kolom file lama (CSV) | Field sistem |
| --- | --- |
| `kode_obat` | `obat.kode` |
| `nama_obat` | `obat.nama` |
| `kategori` | `kategori_obat.nama` (dibuat jika belum ada) |
| `pemasok` | `pemasok.nama` (opsional) |
| `harga` | `obat.harga` |
| `stok` | `batch_obat.sisa` / jumlah masuk |
| `stok_minimum` | `obat.stok_minimum` |
| `butuh_resep` | `obat.butuh_resep` (0/1) |
| `kedaluwarsa` | `batch_obat.kedaluwarsa` (YYYY-MM-DD) |

Sumber mapping di kode: `LayananMigrasi::mappingKolom()`.

## Validasi per baris
- Kode & nama wajib  
- Harga & stok ≥ 0  
- Tanggal kedaluwarsa format `YYYY-MM-DD`  
- Baris gagal dicatat di `log_migrasi`; baris valid tetap masuk  

## File contoh
| File | Fungsi |
| --- | --- |
| `contoh-data/obat-lama.csv` | Migrasi normal (kode `XL-xxx`) |
| `contoh-data/obat-lama-campur-error.csv` | Demo validasi gagal sebagian |
| `contoh-data/obat-kedaluwarsa-demo.csv` | Demo alert 30/60/90 (`KD-xxx`) |

## Langkah bukti
1. Admin → Migrasi → unggah CSV.  
2. Worker antrian `impor` memproses `Bus::batch`.  
3. Cek log migrasi (sukses/gagal).  
4. Verifikasi obat muncul di katalog.  
5. (Opsional) Rollback batch jika perlu.  

Checklist operasional: `rencana-cutover.md`.
