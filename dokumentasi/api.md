# Dokumentasi API (JSON)

Aplikasi Apotek Digital Klinik Makmur Jaya menyediakan **dua endpoint JSON utama** untuk autocomplete dan pemantauan real-time (polling). Endpoint ini bukan API publik besar — cukup untuk memenuhi butir dokumentasi API uji kompetensi.

Basis URL demo lokal: `http://127.0.0.1:8000`

---

## 1. Autocomplete obat

| Item | Nilai |
| --- | --- |
| Metode | `GET` |
| Path | `/api/obat/autocomplete` |
| Auth | Tidak wajib (katalog publik) |
| Query | `q` — kata pencarian (string) |
| Limit | Maksimal 10 hasil |
| Sumber | `LayananPencarian::autocomplete()` (fuzzy / LIKE + skor) |

### Contoh permintaan

```http
GET /api/obat/autocomplete?q=paracet HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json
```

### Contoh respons sukses (`200`)

```json
[
  {
    "id": 1,
    "kode": "OBT-001",
    "nama": "Paracetamol 500mg",
    "harga": 5000,
    "stok": 120,
    "butuh_resep": false
  }
]
```

| Field | Tipe | Keterangan |
| --- | --- | --- |
| `id` | number | ID obat |
| `kode` | string | Kode unik |
| `nama` | string | Nama tampilan |
| `harga` | number | Harga satuan (Rp) |
| `stok` | number | Sisa stok (SUM batch) |
| `butuh_resep` | boolean | Perlu unggah resep |

Jika `q` kosong atau terlalu pendek, respons biasanya array kosong `[]`.

### Pemakaian di UI

Kotak cari katalog memanggil endpoint ini dengan **debounce ±300 ms** (Alpine.js) agar tidak membanjiri server.

---

## 2. Status real-time dasbor

| Item | Nilai |
| --- | --- |
| Metode | `GET` |
| Path | `/admin/api/realtime` |
| Auth | Wajib login + peran `admin` |
| Refresh | Dipanggil tiap **10 detik** dari dasbor |
| Tujuan | Bukti pemrograman real-time tanpa WebSocket |

### Contoh permintaan

```http
GET /admin/api/realtime HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json
Cookie: laravel_session=...
X-CSRF-TOKEN: ...
```

### Contoh respons sukses (`200`)

```json
{
  "peringatan_baru": [
    {
      "id": 12,
      "jenis": "stok_kritis",
      "judul": "Stok Paracetamol di bawah minimum",
      "tingkat": "peringatan",
      "dibaca_pada": null,
      "created_at": "2026-08-27T10:00:00.000000Z"
    }
  ],
  "pesanan_baru": [
    {
      "id": 5,
      "nomor": "PSN-20260827-0005",
      "status": "menunggu_bayar",
      "total": 25000,
      "created_at": "2026-08-27T10:01:00.000000Z"
    }
  ],
  "waktu_server": "2026-08-27 17:00:00"
}
```

| Field | Keterangan |
| --- | --- |
| `peringatan_baru` | Hingga 8 peringatan belum dibaca |
| `pesanan_baru` | 5 pesanan terbaru (ringkas) |
| `waktu_server` | Timestamp server untuk sinkron UI |

### Kode HTTP lain

| Kode | Arti |
| --- | --- |
| `401` / redirect login | Belum autentikasi |
| `403` | Bukan admin |

---

## Endpoint pendukung (bukan API publik)

| Path | Fungsi |
| --- | --- |
| `GET /admin/dasbor/polling` | Badge jumlah peringatan belum dibaca (`belum_dibaca`, `terbaru`) |

Digunakan badge navigasi staf; dokumentasi utama tetap dua endpoint di atas.

---

## Catatan keamanan

- Autocomplete hanya mengembalikan data katalog (bukan data pasien).
- Realtime dilindungi middleware `auth` + `verified` + `peran:admin`.
- Semua form HTML tetap memakai CSRF; endpoint GET di atas idempotent.
- Tidak ada API key / OAuth: cukup untuk lingkup demo uji kompetensi.
