<?php

namespace App\Models;

use App\Enums\TingkatKeparahan;
use Illuminate\Database\Eloquent\Model;

/**
 * UJIKOM — Debugging + notifikasi error ke admin (severity Critical/Warning/Info).
 */
class LogKesalahan extends Model
{
    protected $table = 'log_kesalahan';

    protected $fillable = ['tingkat', 'pesan', 'jejak', 'url'];

    protected function casts(): array
    {
        return [
            'tingkat' => TingkatKeparahan::class,
        ];
    }
}
