<?php

namespace App\Jobs;

use App\Layanan\LayananMigrasi;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * UJIKOM — Batch import obat CSV/Excel + pemrograman paralel (Bus::batch).
 * Satu job = satu baris CSV, dijalankan worker secara paralel.
 */
class JobImporBarisObat implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * @param  array<string, string>  $baris
     */
    public function __construct(
        public array $baris,
        public int $nomorBaris,
        public string $batchMigrasiId,
    ) {
        $this->onQueue('impor');
    }

    public function handle(LayananMigrasi $migrasi): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $migrasi->imporBaris($this->baris, $this->nomorBaris, $this->batchMigrasiId);
    }
}
