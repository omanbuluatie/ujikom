<?php

namespace App\Models;

use App\Enums\JenisNotifikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** REVISI — Notifikasi in-app untuk pasien (pembayaran, pengemasan, dll.) */
class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'transaksi_id',
        'jenis',
        'judul',
        'pesan',
        'dibaca_pada',
    ];

    protected function casts(): array
    {
        return [
            'jenis' => JenisNotifikasi::class,
            'dibaca_pada' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
