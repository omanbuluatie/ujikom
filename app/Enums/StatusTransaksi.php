<?php

namespace App\Enums;

/**
 * REVISI — Status transaksi disederhanakan (default: pending).
 * pending → diproses → selesai | dibatalkan
 */
enum StatusTransaksi: string
{
    case Pending = 'pending';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Diproses => 'Diproses / dikemas',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    public function kelasTiket(): string
    {
        return match ($this) {
            self::Selesai => 'tiket tiket--selesai',
            self::Dibatalkan => 'tiket tiket--tolak',
            self::Diproses => 'tiket tiket--proses',
            default => 'tiket tiket--tunggu',
        };
    }
}
