<?php

namespace App\Jobs;

use App\Layanan\LayananLaporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

/**
 * UJIKOM — Background job untuk laporan besar.
 * PDF ditulis ke storage; pengguna mengunduh setelah job selesai.
 */
class JobBuatLaporan implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $dari,
        public string $sampai,
        public string $namaBerkas,
    ) {
        $this->onQueue('laporan');
    }

    public function handle(LayananLaporan $laporan): void
    {
        $rekap = $laporan->rekapTransaksi($this->dari, $this->sampai);
        $terlaris = $laporan->obatTerlaris();
        $kedaluwarsa = $laporan->obatMendekatiKedaluwarsa();

        $pdf = Pdf::loadView('pdf.laporan-penjualan', [
            'dari' => $this->dari,
            'sampai' => $this->sampai,
            'rekap' => $rekap,
            'terlaris' => $terlaris,
            'kedaluwarsa' => $kedaluwarsa,
            'dibuatPada' => now(),
        ]);

        Storage::disk('public')->put('laporan/'.$this->namaBerkas, $pdf->output());
    }
}
