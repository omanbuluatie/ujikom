<?php

namespace App\Jobs;

use App\Enums\JenisNotifikasi;
use App\Enums\StatusTransaksi;
use App\Layanan\LayananNotifikasi;
use App\Layanan\LayananPeringatan;
use App\Mail\NotifikasiStatusTransaksi;
use App\Models\LogAudit;
use App\Models\Transaksi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

/**
 * REVISI — Job verifikasi pembayaran (bukti upload).
 * Simulasi demo: otomatis setujui → diproses. Produksi: admin setujui manual.
 */
class JobProsesPembayaran implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $transaksiId)
    {
        $this->onQueue('pembayaran');
    }

    public function handle(LayananPeringatan $peringatan, LayananNotifikasi $notifikasi): void
    {
        $transaksi = Transaksi::query()->with('item.obat', 'pelanggan')->findOrFail($this->transaksiId);

        if ($transaksi->status !== StatusTransaksi::Pending || ! $transaksi->bukti_pembayaran) {
            return;
        }

        // Demo: auto-approve setelah bukti terupload (robust: cek bukti ada).
        $transaksi->update([
            'status' => StatusTransaksi::Diproses,
            'dibayar_pada' => now(),
        ]);

        $peringatan->transaksiBaru($transaksi);
        LogAudit::catat('pembayaran.sukses', $transaksi, $transaksi->kode_transaksi);

        $notifikasi->untukTransaksi(
            $transaksi,
            JenisNotifikasi::PembayaranDiterima,
            'Pembayaran '.$transaksi->kode_transaksi.' diterima via '.$transaksi->metode_pembayaran.'.'
        );
        $notifikasi->untukTransaksi(
            $transaksi->fresh(),
            JenisNotifikasi::SedangDiproses,
            'Transaksi '.$transaksi->kode_transaksi.' sedang diproses.'
        );

        Mail::to($transaksi->pelanggan->email)->send(new NotifikasiStatusTransaksi($transaksi->fresh()));

        if (! $transaksi->butuhResep()) {
            JobPotongStok::dispatch($transaksi->id);
        }
    }
}
