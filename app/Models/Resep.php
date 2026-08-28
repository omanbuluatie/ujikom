<?php

namespace App\Models;

use App\Enums\StatusResep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * REVISI — Resep: status pending/verifikasi/ditolak, catatan_verifikasi.
 */
class Resep extends Model
{
    protected $table = 'resep';

    protected $fillable = [
        'transaksi_id',
        'berkas_gambar',
        'status',
        'diverifikasi_oleh',
        'catatan_verifikasi',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusResep::class,
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function apoteker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
