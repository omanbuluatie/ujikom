<?php

namespace App\Layanan;

use App\Enums\StatusPesanan;
use App\Models\ItemPesanan;
use App\Models\Obat;
use App\Models\Pesanan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * UJIKOM — SQL laporan: penjualan, obat terlaris, kedaluwarsa, rekap transaksi.
 * Query memakai Query Builder / Eloquent (binding), bukan string SQL dari input.
 */
class LayananLaporan
{
    public function penjualanPeriode(string $dari, string $sampai): Collection
    {
        return Pesanan::query()
            ->selectRaw('DATE(dibayar_pada) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total) as pendapatan')
            ->whereIn('status', [
                StatusPesanan::Dikonfirmasi->value,
                StatusPesanan::MenungguResep->value,
                StatusPesanan::MenungguVerifikasi->value,
                StatusPesanan::Diproses->value,
                StatusPesanan::Selesai->value,
            ])
            ->whereNotNull('dibayar_pada')
            ->whereBetween('dibayar_pada', [$dari.' 00:00:00', $sampai.' 23:59:59'])
            ->groupBy(DB::raw('DATE(dibayar_pada)'))
            ->orderBy('tanggal')
            ->get();
    }

    public function ringkasanDasbor(): array
    {
        $dasar = Pesanan::query()->whereNotNull('dibayar_pada')->where('status', '!=', StatusPesanan::Dibatalkan);

        return [
            'harian' => (clone $dasar)->whereDate('dibayar_pada', today())->sum('total'),
            'mingguan' => (clone $dasar)->where('dibayar_pada', '>=', now()->startOfWeek())->sum('total'),
            'bulanan' => (clone $dasar)->where('dibayar_pada', '>=', now()->startOfMonth())->sum('total'),
            'transaksi_hari_ini' => (clone $dasar)->whereDate('dibayar_pada', today())->count(),
        ];
    }

    /** Obat terlaris berdasarkan SUM jumlah item pesanan yang sudah dibayar. */
    public function obatTerlaris(int $batas = 10): Collection
    {
        return ItemPesanan::query()
            ->select('obat_id', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as omzet'))
            ->whereHas('pesanan', function ($q) {
                $q->whereNotNull('dibayar_pada')
                    ->where('status', '!=', StatusPesanan::Dibatalkan);
            })
            ->groupBy('obat_id')
            ->orderByDesc('total_terjual')
            ->with('obat')
            ->limit($batas)
            ->get();
    }

    public function obatMendekatiKedaluwarsa(): Collection
    {
        return Obat::query()
            ->whereHas('batch', function ($q) {
                $q->where('sisa', '>', 0)
                    ->whereDate('tanggal_kedaluwarsa', '<=', now()->addDays(90));
            })
            ->with(['batch' => function ($q) {
                $q->where('sisa', '>', 0)
                    ->whereDate('tanggal_kedaluwarsa', '<=', now()->addDays(90))
                    ->orderBy('tanggal_kedaluwarsa');
            }])
            ->get();
    }

    public function rekapTransaksi(string $dari, string $sampai)
    {
        return Pesanan::query()
            ->with(['pelanggan', 'item.obat'])
            ->whereBetween('created_at', [$dari.' 00:00:00', $sampai.' 23:59:59'])
            ->orderByDesc('id')
            ->get();
    }
}
