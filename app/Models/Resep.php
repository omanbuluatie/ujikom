<?php

namespace App\Models;

use App\Enums\StatusResep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UJIKOM — Multimedia (unggah gambar resep) + verifikasi apoteker.
 * Satu pesanan : satu berkas resep, cukup untuk uji kompetensi.
 */
class Resep extends Model
{
    protected $table = 'resep';

    protected $fillable = [
        'pesanan_id',
        'berkas_gambar',
        'status',
        'diverifikasi_oleh',
        'catatan_apoteker',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusResep::class,
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function apoteker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
