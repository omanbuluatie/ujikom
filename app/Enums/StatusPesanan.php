<?php

namespace App\Enums;

/**
 * UJIKOM — Alur e-commerce: checkout → pembayaran → konfirmasi → resep → selesai.
 * Status dipersempit agar demo mudah dijelaskan, tanpa cabang tersembunyi.
 */
enum StatusPesanan: string
{
    case MenungguBayar = 'menunggu_bayar';
    case Dikonfirmasi = 'dikonfirmasi';
    case MenungguResep = 'menunggu_resep';
    case MenungguVerifikasi = 'menunggu_verifikasi';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MenungguBayar => 'Menunggu bayar',
            self::Dikonfirmasi => 'Dikonfirmasi',
            self::MenungguResep => 'Unggah resep',
            self::MenungguVerifikasi => 'Menunggu verifikasi',
            self::Diproses => 'Diproses FIFO',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    /** Warna tiket status — sama seperti kertas antrian di loket. */
    public function kelasTiket(): string
    {
        return match ($this) {
            self::Selesai, self::Dikonfirmasi => 'tiket tiket--selesai',
            self::Dibatalkan => 'tiket tiket--tolak',
            self::Diproses => 'tiket tiket--proses',
            default => 'tiket tiket--tunggu',
        };
    }
}
