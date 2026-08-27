<?php

namespace App\Layanan;

use App\Enums\JenisMutasi;
use App\Exceptions\StokTidakCukupException;
use App\Models\BatchObat;
use App\Models\MutasiStok;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;

/**
 * UJIKOM — Algoritma FIFO + sinkronisasi stok penjualan counter dengan online.
 *
 * Prosedur pemotongan:
 * 1. Kunci baris batch (lockForUpdate) supaya dua pesanan paralel tidak mengambil sisa yang sama.
 * 2. Urutkan batch dari tanggal_masuk paling lama (First In First Out).
 * 3. Kurangi sisa batch satu per satu sampai jumlahDiminta terpenuhi.
 * 4. Catat setiap potongan di mutasi_stok.
 * 5. Jika batch habis sebelum kebutuhan terpenuhi: lempar exception → transaksi DB di-rollback.
 *    Stok tidak pernah negatif.
 *
 * Kasir dan pesanan online memanggil fungsi yang sama. Itulah bukti sinkronisasi:
 * satu sumber kebenaran, bukan dua kolom stok terpisah.
 */
class LayananStok
{
    public function __construct(private LayananPeringatan $peringatan)
    {
    }

    /**
     * Menambah stok sebagai batch baru (pembelian / migrasi CSV).
     */
    public function masukkanBatch(
        Obat $obat,
        int $jumlah,
        string $tanggalKedaluwarsa,
        ?string $tanggalMasuk = null,
        string $sumber = 'manual',
    ): BatchObat {
        return DB::transaction(function () use ($obat, $jumlah, $tanggalKedaluwarsa, $tanggalMasuk, $sumber) {
            $batch = BatchObat::query()->create([
                'obat_id' => $obat->id,
                'jumlah_masuk' => $jumlah,
                'sisa' => $jumlah,
                'tanggal_masuk' => $tanggalMasuk ?? now()->toDateString(),
                'tanggal_kedaluwarsa' => $tanggalKedaluwarsa,
            ]);

            MutasiStok::query()->create([
                'obat_id' => $obat->id,
                'batch_obat_id' => $batch->id,
                'jenis' => JenisMutasi::Masuk,
                'jumlah' => $jumlah,
                'sumber' => $sumber,
            ]);

            return $batch;
        });
    }

    /**
     * Memotong stok FIFO. Dipakai job antrian pesanan online DAN kasir.
     *
     * @throws StokTidakCukupException
     */
    public function potongStokFifo(Obat $obat, int $jumlahDiminta, string $sumber, ?int $pesananId = null): void
    {
        if ($jumlahDiminta <= 0) {
            throw new \InvalidArgumentException('Jumlah pemotongan harus lebih dari nol.');
        }

        DB::transaction(function () use ($obat, $jumlahDiminta, $sumber, $pesananId) {
            $sisaKebutuhan = $jumlahDiminta;

            // Kunci baris: dua worker paralel tidak bisa memotong batch yang sama bersamaan.
            $daftarBatch = BatchObat::query()
                ->where('obat_id', $obat->id)
                ->where('sisa', '>', 0)
                ->orderBy('tanggal_masuk') // FIFO: yang masuk lebih dulu keluar lebih dulu
                ->lockForUpdate()
                ->get();

            foreach ($daftarBatch as $batch) {
                $ambil = min((int) $batch->sisa, $sisaKebutuhan);
                $batch->sisa -= $ambil;
                $batch->save();

                MutasiStok::query()->create([
                    'obat_id' => $obat->id,
                    'batch_obat_id' => $batch->id,
                    'jenis' => JenisMutasi::Keluar,
                    'jumlah' => $ambil,
                    'sumber' => $sumber,
                    'pesanan_id' => $pesananId,
                ]);

                $sisaKebutuhan -= $ambil;

                if ($sisaKebutuhan === 0) {
                    break;
                }
            }

            if ($sisaKebutuhan > 0) {
                throw StokTidakCukupException::untuk($obat->nama, $sisaKebutuhan);
            }
        });

        $this->peringatan->cekStokKritis($obat->fresh());
    }
}
