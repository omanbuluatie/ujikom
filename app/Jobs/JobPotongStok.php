<?php

namespace App\Jobs;

use App\Enums\StatusPesanan;
use App\Exceptions\StokTidakCukupException;
use App\Layanan\LayananPeringatan;
use App\Layanan\LayananStok;
use App\Models\LogAudit;
use App\Models\Pesanan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * UJIKOM — Job queue update stok.
 * Memanggil LayananStok::potongStokFifo() — fungsi yang sama dengan kasir.
 */
class JobPotongStok implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(public int $pesananId)
    {
        $this->onQueue('stok');
    }

    public function handle(LayananStok $stok, LayananPeringatan $peringatan): void
    {
        $pesanan = Pesanan::query()->with('item.obat')->findOrFail($this->pesananId);

        try {
            foreach ($pesanan->item as $item) {
                $stok->potongStokFifo($item->obat, $item->jumlah, 'pesanan-online', $pesanan->id);
            }

            $pesanan->update(['status' => StatusPesanan::Selesai]);
            LogAudit::catat('stok.fifo', $pesanan, 'Stok dipotong FIFO untuk '.$pesanan->nomor);
        } catch (StokTidakCukupException $e) {
            $pesanan->update(['status' => StatusPesanan::Dibatalkan, 'catatan' => $e->getMessage()]);
            $peringatan->kesalahan('Stok tidak cukup', $e->getMessage());
            throw $e;
        }
    }
}
