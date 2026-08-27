<?php

namespace App\Console\Commands;

use App\Jobs\JobCekKedaluwarsa;
use App\Layanan\LayananPeringatan;
use Illuminate\Console\Command;

/**
 * UJIKOM — Alert kedaluwarsa 30/60/90 hari.
 * Scheduler harian jam 06:00; perintah ini untuk demo kapan saja.
 */
class CekKedaluwarsaCommand extends Command
{
    protected $signature = 'apotek:cek-kedaluwarsa {--sync : Jalankan langsung tanpa antrian}';

    protected $description = 'Cek batch obat kedaluwarsa / mendekati 30-60-90 hari dan buat peringatan';

    public function handle(LayananPeringatan $peringatan): int
    {
        if ($this->option('sync')) {
            $jumlah = $peringatan->cekKedaluwarsa();
            $this->info("Selesai. Peringatan baru dibuat: {$jumlah}");

            return self::SUCCESS;
        }

        JobCekKedaluwarsa::dispatch();
        $this->info('JobCekKedaluwarsa masuk antrian. Jalankan: php artisan queue:work');

        return self::SUCCESS;
    }
}
