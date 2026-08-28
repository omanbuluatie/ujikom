<?php

namespace App\Enums;

/** REVISI — Resep: pending (default) | verifikasi | ditolak */
enum StatusResep: string
{
    case Pending = 'pending';
    case Verifikasi = 'verifikasi';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Verifikasi => 'Terverifikasi',
            self::Ditolak => 'Ditolak',
        };
    }
}
