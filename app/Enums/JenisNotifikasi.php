<?php

namespace App\Enums;

/** REVISI — Notifikasi untuk pasien (pembayaran, pengemasan, dll.) */
enum JenisNotifikasi: string
{
    case PembayaranDiterima = 'pembayaran_diterima';
    case PembayaranDitolak = 'pembayaran_ditolak';
    case SedangDiproses = 'sedang_diproses';
    case SedangDikemas = 'sedang_dikemas';
    case Selesai = 'selesai';
    case ResepDitolak = 'resep_ditolak';
    case TransaksiDibatalkan = 'transaksi_dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::PembayaranDiterima => 'Pembayaran diterima',
            self::PembayaranDitolak => 'Pembayaran ditolak',
            self::SedangDiproses => 'Transaksi diproses',
            self::SedangDikemas => 'Obat sedang dikemas',
            self::Selesai => 'Transaksi selesai',
            self::ResepDitolak => 'Resep ditolak',
            self::TransaksiDibatalkan => 'Transaksi dibatalkan',
        };
    }
}
