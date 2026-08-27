<?php

namespace App\Jobs;

use App\Enums\StatusPesanan;
use App\Layanan\LayananPeringatan;
use App\Mail\NotifikasiStatusPesanan;
use App\Models\LogAudit;
use App\Models\Pesanan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

/**
 * UJIKOM — Job queue pembayaran + pemrograman paralel.
 * Request HTTP hanya men-dispatch job; worker memproses (bisa 2 worker bersamaan).
 * Pembayaran disimulasikan (tanpa gateway) — cukup sebagai bukti antrian pembayaran.
 */
class JobProsesPembayaran implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $pesananId)
    {
        $this->onQueue('pembayaran');
    }

    public function handle(LayananPeringatan $peringatan): void
    {
        $pesanan = Pesanan::query()->with('item.obat', 'pelanggan')->findOrFail($this->pesananId);

        if ($pesanan->status !== StatusPesanan::MenungguBayar) {
            return;
        }

        $pesanan->update([
            'status' => $pesanan->butuhResep()
                ? StatusPesanan::MenungguResep
                : StatusPesanan::Dikonfirmasi,
            'dibayar_pada' => now(),
        ]);

        $peringatan->pesananBaru($pesanan);
        LogAudit::catat('pembayaran.sukses', $pesanan, $pesanan->nomor);

        Mail::to($pesanan->pelanggan->email)->send(new NotifikasiStatusPesanan($pesanan->fresh()));

        if (! $pesanan->butuhResep()) {
            JobPotongStok::dispatch($pesanan->id);
        }
    }
}
