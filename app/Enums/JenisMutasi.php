<?php

namespace App\Enums;

/**
 * UJIKOM — Jejak FIFO: setiap masuk/keluar stok tercatat, bukan hanya angka stok akhir.
 */
enum JenisMutasi: string
{
    case Masuk = 'masuk';
    case Keluar = 'keluar';
}
