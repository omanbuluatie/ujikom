<?php

namespace App\Models;

use App\Enums\StatusPesanan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * UJIKOM — CRUD Transaksi + alur e-commerce.
 * Sumber `online` atau `kasir` memakai stok yang sama (LayananStok).
 */
class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'nomor',
        'user_id',
        'status',
        'sumber',
        'total',
        'dibayar_pada',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusPesanan::class,
            'total' => 'integer',
            'dibayar_pada' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(): HasMany
    {
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }

    public function resep(): HasOne
    {
        return $this->hasOne(Resep::class, 'pesanan_id');
    }

    public function butuhResep(): bool
    {
        return $this->item()->whereHas('obat', fn ($q) => $q->where('butuh_resep', true))->exists();
    }

    public static function buatNomor(): string
    {
        return 'PSN-'.now()->format('Ymd').'-'.str_pad((string) (static::max('id') + 1), 4, '0', STR_PAD_LEFT);
    }
}
