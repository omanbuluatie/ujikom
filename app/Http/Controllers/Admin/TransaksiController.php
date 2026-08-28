<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JenisNotifikasi;
use App\Enums\StatusTransaksi;
use App\Http\Controllers\Controller;
use App\Jobs\JobPotongStok;
use App\Layanan\LayananNotifikasi;
use App\Models\LogAudit;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * REVISI — CRUD Transaksi admin: verifikasi/tolak bayar, batal, ekspor CSV.
 */
class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.transaksi.index', [
            'daftar' => Transaksi::query()
                ->with('pelanggan', 'item')
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function show(Transaksi $transaksi): View
    {
        return view('admin.transaksi.show', [
            'transaksi' => $transaksi->load('pelanggan', 'item.obat', 'resep'),
        ]);
    }

    public function setujuiPembayaran(Transaksi $transaksi, LayananNotifikasi $notifikasi): RedirectResponse
    {
        abort_unless($transaksi->status === StatusTransaksi::Pending && $transaksi->bukti_pembayaran, 422);

        $transaksi->update([
            'status' => StatusTransaksi::Diproses,
            'dibayar_pada' => now(),
        ]);

        $notifikasi->untukTransaksi(
            $transaksi,
            JenisNotifikasi::PembayaranDiterima,
            'Pembayaran transaksi '.$transaksi->kode_transaksi.' diterima. Obat akan segera diproses.'
        );
        $notifikasi->untukTransaksi(
            $transaksi->fresh(),
            JenisNotifikasi::SedangDikemas,
            'Transaksi '.$transaksi->kode_transaksi.' sedang dikemas.'
        );

        LogAudit::catat('transaksi.setujui-bayar', $transaksi);

        if (! $transaksi->butuhResep()) {
            JobPotongStok::dispatch($transaksi->id);
        }

        return back()->with('status', 'Pembayaran disetujui → diproses.');
    }

    public function tolakPembayaran(Request $request, Transaksi $transaksi, LayananNotifikasi $notifikasi): RedirectResponse
    {
        $data = $request->validate(['alasan' => ['required', 'string', 'max:500']]);

        $transaksi->update(['status' => StatusTransaksi::Dibatalkan, 'catatan' => $data['alasan']]);

        $notifikasi->untukTransaksi(
            $transaksi,
            JenisNotifikasi::PembayaranDitolak,
            'Pembayaran transaksi '.$transaksi->kode_transaksi.' ditolak: '.$data['alasan']
        );

        LogAudit::catat('transaksi.tolak-bayar', $transaksi);

        return back()->with('status', 'Pembayaran ditolak, transaksi dibatalkan.');
    }

    public function batalkan(Transaksi $transaksi, LayananNotifikasi $notifikasi): RedirectResponse
    {
        $transaksi->update(['status' => StatusTransaksi::Dibatalkan]);
        $notifikasi->untukTransaksi(
            $transaksi,
            JenisNotifikasi::TransaksiDibatalkan,
            'Transaksi '.$transaksi->kode_transaksi.' dibatalkan oleh admin.'
        );
        LogAudit::catat('transaksi.batal', $transaksi);

        return back()->with('status', 'Transaksi dibatalkan.');
    }

    public function eksporCsv(Request $request): StreamedResponse
    {
        $nama = 'transaksi-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['kode_transaksi', 'pelanggan', 'email', 'status', 'total', 'kode_unik', 'metode', 'sumber', 'dibayar_pada', 'created_at']);

            Transaksi::query()
                ->with('pelanggan')
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->latest()
                ->chunk(100, function ($chunk) use ($handle) {
                    foreach ($chunk as $t) {
                        fputcsv($handle, [
                            $t->kode_transaksi,
                            $t->pelanggan?->name,
                            $t->pelanggan?->email,
                            $t->status->value,
                            $t->total,
                            $t->kode_unik,
                            $t->metode_pembayaran?->value,
                            $t->sumber,
                            $t->dibayar_pada?->toDateTimeString(),
                            $t->created_at?->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $nama, ['Content-Type' => 'text/csv']);
    }
}
