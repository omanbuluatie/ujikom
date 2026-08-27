<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * UJIKOM — Alur robust: stok tidak pernah negatif.
 * Dilempar dari LayananStok jika FIFO kehabisan batch; pemanggil wajib rollback transaksi.
 */
class StokTidakCukupException extends RuntimeException
{
    public static function untuk(string $namaObat, int $kekurangan): self
    {
        return new self("Stok {$namaObat} tidak mencukupi (kurang {$kekurangan} unit). Transaksi dibatalkan.");
    }
}
