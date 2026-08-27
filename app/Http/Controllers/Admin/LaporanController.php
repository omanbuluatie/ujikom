<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\JobBuatLaporan;
use App\Layanan\LayananLaporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

/**
 * UJIKOM — SQL laporan + export PDF + job laporan besar.
 */
class LaporanController extends Controller
{
    public function index(Request $request, LayananLaporan $laporan): View
    {
        $dari = $request->string('dari', now()->startOfMonth()->toDateString())->toString();
        $sampai = $request->string('sampai', now()->toDateString())->toString();

        return view('admin.laporan.index', [
            'dari' => $dari,
            'sampai' => $sampai,
            'harian' => $laporan->penjualanPeriode($dari, $sampai),
            'terlaris' => $laporan->obatTerlaris(),
            'kedaluwarsa' => $laporan->obatMendekatiKedaluwarsa(),
            'rekap' => $laporan->rekapTransaksi($dari, $sampai),
        ]);
    }

    public function pdf(Request $request, LayananLaporan $laporan): Response
    {
        $dari = $request->string('dari', now()->startOfMonth()->toDateString())->toString();
        $sampai = $request->string('sampai', now()->toDateString())->toString();

        $pdf = Pdf::loadView('pdf.laporan-penjualan', [
            'dari' => $dari,
            'sampai' => $sampai,
            'rekap' => $laporan->rekapTransaksi($dari, $sampai),
            'terlaris' => $laporan->obatTerlaris(),
            'kedaluwarsa' => $laporan->obatMendekatiKedaluwarsa(),
            'dibuatPada' => now(),
        ]);

        return $pdf->download('laporan-penjualan-'.$dari.'-'.$sampai.'.pdf');
    }

    public function antrian(Request $request): RedirectResponse
    {
        $dari = $request->string('dari', now()->startOfMonth()->toDateString())->toString();
        $sampai = $request->string('sampai', now()->toDateString())->toString();
        $nama = 'laporan-'.now()->format('YmdHis').'.pdf';

        JobBuatLaporan::dispatch($dari, $sampai, $nama);

        return back()->with('status', 'Laporan masuk antrian sebagai '.$nama.'. Cek storage/app/public/laporan setelah worker jalan.');
    }
}
