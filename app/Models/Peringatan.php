<?php

namespace App\Models;

use App\Enums\JenisPeringatan;
use App\Enums\TingkatKeparahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UJIKOM — Alert + dasbor real-time (dipolling 10 detik).
 */
class Peringatan extends Model
{
    protected $table = 'peringatan';

    protected $fillable = [
        'jenis',
        'tingkat',
        'judul',
        'pesan',
        'obat_id',
        'transaksi_id',
        'dibaca_pada',
    ];

    protected function casts(): array
    {
        return [
            'jenis' => JenisPeringatan::class,
            'tingkat' => TingkatKeparahan::class,
            'dibaca_pada' => 'datetime',
        ];
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }
}
