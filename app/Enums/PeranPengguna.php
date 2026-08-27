<?php

namespace App\Enums;

/**
 * UJIKOM — Autentikasi & keamanan (minimal 4 role).
 * Enum dipakai di middleware dan menu agar hak akses tidak tersebar string mentah.
 */
enum PeranPengguna: string
{
    case Admin = 'admin';
    case Apoteker = 'apoteker';
    case Kasir = 'kasir';
    case Pasien = 'pasien';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Apoteker => 'Apoteker',
            self::Kasir => 'Kasir',
            self::Pasien => 'Pasien',
        };
    }
}
