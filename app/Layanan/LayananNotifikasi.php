<?php

namespace App\Layanan;

use App\Enums\JenisNotifikasi;
use App\Models\Notifikasi;
use App\Models\Transaksi;
use App\Models\User;

/**
 * REVISI — Notifikasi in-app pasien (pembayaran ditolak, sedang dikemas, selesai, dll.).
 */
class LayananNotifikasi
{
    public function kirim(
        User $pengguna,
        JenisNotifikasi $jenis,
        string $judul,
        string $pesan,
        ?Transaksi $transaksi = null,
    ): Notifikasi {
        return Notifikasi::query()->create([
            'user_id' => $pengguna->id,
            'transaksi_id' => $transaksi?->id,
            'jenis' => $jenis,
            'judul' => $judul,
            'pesan' => $pesan,
        ]);
    }

    public function untukTransaksi(Transaksi $transaksi, JenisNotifikasi $jenis, string $pesan): Notifikasi
    {
        return $this->kirim(
            $transaksi->pelanggan,
            $jenis,
            $jenis->label(),
            $pesan,
            $transaksi,
        );
    }
}
