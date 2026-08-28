<?php

namespace App\Enums;

/** Metode pembayaran yang tersedia untuk transaksi online. */
enum MetodePembayaran: string
{
    case TransferBca = 'transfer_bca';
    case TransferBri = 'transfer_bri';
    case Qris = 'qris';

    public function label(): string
    {
        return match ($this) {
            self::TransferBca => 'Transfer Bank BCA',
            self::TransferBri => 'Transfer Bank BRI',
            self::Qris => 'QRIS',
        };
    }
}
