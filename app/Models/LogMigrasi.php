<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UJIKOM — Bukti migrasi Excel/CSV → sistem, termasuk jejak rollback.
 */
class LogMigrasi extends Model
{
    protected $table = 'log_migrasi';

    protected $fillable = [
        'batch_migrasi_id',
        'baris_ke',
        'kode_obat',
        'status',
        'pesan',
    ];
}
