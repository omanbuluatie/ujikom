<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * UJIKOM — Algoritma FIFO.
 * Satu baris = satu kedatangan obat. Yang tanggal_masuk-nya lebih lama keluar lebih dulu.
 */
class BatchObat extends Model
{
    protected $table = 'batch_obat';

    protected $fillable = [
        'obat_id',
        'jumlah_masuk',
        'sisa',
        'tanggal_masuk',
        'tanggal_kedaluwarsa',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_masuk' => 'integer',
            'sisa' => 'integer',
            'tanggal_masuk' => 'date',
            'tanggal_kedaluwarsa' => 'date',
        ];
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    public function mutasi(): HasMany
    {
        return $this->hasMany(MutasiStok::class, 'batch_obat_id');
    }

    public function hariMenujuKedaluwarsa(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->tanggal_kedaluwarsa, false);
    }
}
