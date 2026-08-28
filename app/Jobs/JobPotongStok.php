<?php

namespace App\Jobs;

use App\Enums\JenisNotifikasi;
use App\Enums\StatusTransaksi;
use App\Exceptions\StokTidakCukupException;
use App\Layanan\LayananNotifikasi;
use App\Layanan\LayananPeringatan;
use App\Layanan\LayananStok;
use App\Models\LogAudit;
use App\Models\Transaksi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/** REVISI — Job potong stok FIFO untuk transaksi online. */
class JobPotongStok implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(public int $transaksiId)
    {
        $this->onQueue('stok');
    }

    public function handle(LayananStok $stok, LayananPeringatan $peringatan, LayananNotifikasi $notifikasi): void
    {
        $transaksi = Transaksi::query()->with('item.obat', 'pelanggan')->findOrFail($this->transaksiId);

        try {
            foreach ($transaksi->item as $item) {
                $stok->potongStokFifo($item->obat, $item->jumlah, 'transaksi-online', $transaksi->id);
            }

            $transaksi->update(['status' => StatusTransaksi::Selesai]);
            LogAudit::catat('stok.fifo', $transaksi, 'FIFO '.$transaksi->kode_transaksi);

            $notifikasi->untukTransaksi(
                $transaksi,
                JenisNotifikasi::Selesai,
                'Transaksi '.$transaksi->kode_transaksi.' selesai. Obat siap diambil/dikirim.'
            );
        } catch (StokTidakCukupException $e) {
            $transaksi->update(['status' => StatusTransaksi::Dibatalkan, 'catatan' => $e->getMessage()]);
            $peringatan->kesalahan('Stok tidak cukup', $e->getMessage());
            $notifikasi->untukTransaksi(
                $transaksi,
                JenisNotifikasi::TransaksiDibatalkan,
                'Transaksi '.$transaksi->kode_transaksi.' dibatalkan: '.$e->getMessage()
            );
            throw $e;
        }
    }
}
