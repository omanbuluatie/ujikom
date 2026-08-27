<?php

namespace App\Enums;

/**
 * UJIKOM — Alert notification: stok kritis, kedaluwarsa, pesanan baru, kesalahan.
 */
enum JenisPeringatan: string
{
    case StokKritis = 'stok_kritis';
    case Kedaluwarsa = 'kedaluwarsa';
    case PesananBaru = 'pesanan_baru';
    case Kesalahan = 'kesalahan';
}
