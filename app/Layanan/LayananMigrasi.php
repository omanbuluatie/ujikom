<?php

namespace App\Layanan;

use App\Models\KategoriObat;
use App\Models\LogMigrasi;
use App\Models\Obat;
use App\Models\Pemasok;
use Illuminate\Support\Str;

/**
 * UJIKOM — Migrasi teknologi Excel/CSV → e-commerce.
 *
 * Prosedur:
 * 1. Mapping kolom file lama → field sistem (lihat mappingKolom()).
 * 2. Validasi: kode unik, harga angka, stok ≥ 0, tanggal kedaluwarsa.
 * 3. Baris valid disimpan; baris rusak dicatat di log_migrasi (tidak menggagalkan seluruh batch).
 * 4. Rollback: hapus obat + batch yang tercatat pada batch_migrasi_id yang sama.
 */
class LayananMigrasi
{
    /**
     * Mapping field Excel lama → kolom sistem (bukti migrasi).
     *
     * @return array<string, string>
     */
    public function mappingKolom(): array
    {
        return [
            'kode_obat' => 'obat.kode',
            'nama_obat' => 'obat.nama',
            'kategori' => 'kategori_obat.nama',
            'pemasok' => 'pemasok.nama',
            'harga' => 'obat.harga',
            'stok' => 'batch_obat.jumlah_masuk / sisa',
            'stok_minimum' => 'obat.stok_minimum',
            'butuh_resep' => 'obat.butuh_resep (1/0)',
            'kedaluwarsa' => 'batch_obat.tanggal_kedaluwarsa (YYYY-MM-DD)',
        ];
    }

    /**
     * @param  array<string, string>  $baris
     * @return array{ok: bool, pesan: string, obat?: Obat}
     */
    public function imporBaris(array $baris, int $nomorBaris, string $batchId): array
    {
        $kode = trim((string) ($baris['kode_obat'] ?? ''));
        $nama = trim((string) ($baris['nama_obat'] ?? ''));
        $harga = (int) ($baris['harga'] ?? 0);
        $stok = (int) ($baris['stok'] ?? 0);
        $kedaluwarsa = trim((string) ($baris['kedaluwarsa'] ?? ''));

        if ($kode === '' || $nama === '') {
            return $this->gagal($batchId, $nomorBaris, $kode, 'Kode dan nama wajib diisi.');
        }
        if ($harga < 0 || $stok < 0) {
            return $this->gagal($batchId, $nomorBaris, $kode, 'Harga dan stok tidak boleh negatif.');
        }
        if ($kedaluwarsa === '' || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $kedaluwarsa)) {
            return $this->gagal($batchId, $nomorBaris, $kode, 'Tanggal kedaluwarsa harus YYYY-MM-DD.');
        }

        $kategori = KategoriObat::query()->firstOrCreate(
            ['slug' => Str::slug($baris['kategori'] ?? 'umum') ?: 'umum'],
            ['nama' => $baris['kategori'] ?: 'Umum']
        );

        $pemasok = null;
        if (! empty($baris['pemasok'])) {
            $pemasok = Pemasok::query()->firstOrCreate(['nama' => $baris['pemasok']]);
        }

        $obat = Obat::query()->updateOrCreate(
            ['kode' => $kode],
            [
                'nama' => $nama,
                'kategori_obat_id' => $kategori->id,
                'pemasok_id' => $pemasok?->id,
                'harga' => $harga,
                'stok_minimum' => (int) ($baris['stok_minimum'] ?? 10),
                'butuh_resep' => in_array((string) ($baris['butuh_resep'] ?? '0'), ['1', 'ya', 'true'], true),
            ]
        );

        if ($stok > 0) {
            app(LayananStok::class)->masukkanBatch($obat, $stok, $kedaluwarsa, null, 'migrasi:'.$batchId);
        }

        LogMigrasi::query()->create([
            'batch_migrasi_id' => $batchId,
            'baris_ke' => $nomorBaris,
            'kode_obat' => $kode,
            'status' => 'sukses',
            'pesan' => 'Impor berhasil',
        ]);

        return ['ok' => true, 'pesan' => 'ok', 'obat' => $obat];
    }

    public function rollback(string $batchId): int
    {
        $kodeBerhasil = LogMigrasi::query()
            ->where('batch_migrasi_id', $batchId)
            ->where('status', 'sukses')
            ->pluck('kode_obat');

        $jumlah = Obat::query()->whereIn('kode', $kodeBerhasil)->delete();

        LogMigrasi::query()->where('batch_migrasi_id', $batchId)->update([
            'status' => 'rollback',
            'pesan' => 'Dibatalkan oleh operator',
        ]);

        return $jumlah;
    }

    /**
     * @return array{ok: bool, pesan: string}
     */
    private function gagal(string $batchId, int $nomorBaris, string $kode, string $pesan): array
    {
        LogMigrasi::query()->create([
            'batch_migrasi_id' => $batchId,
            'baris_ke' => $nomorBaris,
            'kode_obat' => $kode,
            'status' => 'gagal',
            'pesan' => $pesan,
        ]);

        return ['ok' => false, 'pesan' => $pesan];
    }
}
