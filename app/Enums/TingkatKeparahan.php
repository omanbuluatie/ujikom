<?php

namespace App\Enums;

/**
 * UJIKOM — Dashboard error berdasarkan severity: Critical / Warning / Info.
 * Nama nilai disimpan Indonesia agar UI dan laporan seragam.
 */
enum TingkatKeparahan: string
{
    case Kritis = 'kritis';
    case Peringatan = 'peringatan';
    case Info = 'info';

    public function labelInggris(): string
    {
        return match ($this) {
            self::Kritis => 'Critical',
            self::Peringatan => 'Warning',
            self::Info => 'Info',
        };
    }

    public function kelasTiket(): string
    {
        return match ($this) {
            self::Kritis => 'tiket tiket--tolak',
            self::Peringatan => 'tiket tiket--tunggu',
            self::Info => 'tiket tiket--proses',
        };
    }
}
