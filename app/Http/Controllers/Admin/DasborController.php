<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Layanan\LayananLaporan;
use App\Models\Obat;
use App\Models\Peringatan;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * UJIKOM — Dasbor: penjualan harian/mingguan/bulanan, stok, pendapatan, grafik, alert, polling.
 */
class DasborController extends Controller
{
    public function index(LayananLaporan $laporan): View
    {
        $ringkas = $laporan->ringkasanDasbor();
        $grafik = $laporan->penjualanPeriode(now()->subDays(13)->toDateString(), now()->toDateString());
        $stokKritis = Obat::query()
            ->withSum('batch as batch_sum_sisa', 'sisa')
            ->get()
            ->filter(fn (Obat $o) => $o->batch_sum_sisa <= $o->stok_minimum)
            ->take(8);

        return view('admin.dasbor', [
            'ringkas' => $ringkas,
            'grafik' => $grafik,
            'stokKritis' => $stokKritis,
            'peringatan' => Peringatan::query()->latest()->limit(10)->get(),
            'terlaris' => $laporan->obatTerlaris(5),
        ]);
    }

    public function polling(): JsonResponse
    {
        return response()->json([
            'belum_dibaca' => Peringatan::query()->whereNull('dibaca_pada')->count(),
            'terbaru' => Peringatan::query()->latest()->limit(5)->get(['id', 'judul', 'tingkat', 'created_at']),
        ]);
    }
}
