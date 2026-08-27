<?php

namespace App\Jobs;

use App\Layanan\LayananPeringatan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * UJIKOM — Alert kedaluwarsa 30/60/90 hari.
 * Dijadwalkan harian di routes/console.php.
 */
class JobCekKedaluwarsa implements ShouldQueue
{
    use Queueable;

    public function handle(LayananPeringatan $peringatan): void
    {
        $peringatan->cekKedaluwarsa();
    }
}
