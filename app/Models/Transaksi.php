<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use App\Enums\StatusTransaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * REVISI — Transaksi (dulu pesanan).
 * kode_transaksi, status pending/diproses/selesai/dibatalkan,
 * metode_pembayaran, bukti_pembayaran, alamat_pengiriman, kode_unik (3 digit).
 */
class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'status',
        'sumber',
        'total',
        'kode_unik',
        'metode_pembayaran',
        'bukti_pembayaran',
        'alamat_pengiriman',
        'dibayar_pada',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusTransaksi::class,
            'total' => 'decimal:2',
            'kode_unik' => 'integer',
            'metode_pembayaran' => MetodePembayaran::class,
            'dibayar_pada' => 'datetime',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(): HasMany
    {
        return $this->hasMany(ItemTransaksi::class, 'transaksi_id');
    }

    public function resep(): HasOne
    {
        return $this->hasOne(Resep::class, 'transaksi_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'transaksi_id');
    }

    public function butuhResep(): bool
    {
        return $this->item()->whereHas('obat', fn ($q) => $q->where('butuh_resep', true))->exists();
    }

    public static function buatKode(): string
    {
        return 'TRX-'.now()->format('Ymd').'-'.str_pad((string) ((static::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);
    }

    /** Subtotal dari item (belum termasuk kode unik). */
    public function subtotalItem(): float
    {
        return (float) $this->item()->sum('subtotal');
    }

    /** Generate kode unik 3 digit (100–999) untuk membedakan nominal transfer. */
    public static function buatKodeUnik(): int
    {
        return random_int(100, 999);
    }
}
