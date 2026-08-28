<?php

namespace App\Models;

use App\Enums\JenisMutasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** UJIKOM — Jejak FIFO / audit stok. */
class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';

    protected $fillable = [
        'obat_id',
        'batch_obat_id',
        'jenis',
        'jumlah',
        'sumber',
        'transaksi_id',
    ];

    protected function casts(): array
    {
        return [
            'jenis' => JenisMutasi::class,
            'jumlah' => 'integer',
        ];
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(BatchObat::class, 'batch_obat_id');
    }
}
