<?php

namespace App\Enums;

/**
 * REVISI — Jenis mutasi stok (4 kategori).
 * masuk | keluar | expired | return
 */
enum JenisMutasi: string
{
    case Masuk = 'masuk';
    case Keluar = 'keluar';
    case Expired = 'expired';
    case Return = 'return';

    public function label(): string
    {
        return match ($this) {
            self::Masuk => 'Masuk',
            self::Keluar => 'Keluar',
            self::Expired => 'Kedaluwarsa',
            self::Return => 'Retur',
        };
    }
}
