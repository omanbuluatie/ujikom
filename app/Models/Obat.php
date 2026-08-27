<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * UJIKOM — CRUD Obat + multimedia gambar.
 * Stok tampilan dihitung dari batch FIFO (SUM sisa), bukan kolom yang bisa drift.
 */
class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_obat_id',
        'pemasok_id',
        'harga',
        'stok_minimum',
        'butuh_resep',
        'gambar',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'stok_minimum' => 'integer',
            'butuh_resep' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriObat::class, 'kategori_obat_id');
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function batch(): HasMany
    {
        return $this->hasMany(BatchObat::class, 'obat_id');
    }

    /** Stok nyata = sisa semua batch. Dipakai katalog, kasir, dan dasbor. */
    public function getStokTotalAttribute(): int
    {
        if (array_key_exists('batch_sum_sisa', $this->attributes)) {
            return (int) $this->attributes['batch_sum_sisa'];
        }

        return (int) $this->batch()->sum('sisa');
    }

    public function batchTertua(): ?BatchObat
    {
        return $this->batch()
            ->where('sisa', '>', 0)
            ->orderBy('tanggal_masuk')
            ->first();
    }
}
