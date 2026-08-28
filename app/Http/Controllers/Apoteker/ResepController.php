<?php

namespace App\Http\Controllers\Apoteker;

use App\Enums\JenisNotifikasi;
use App\Enums\StatusResep;
use App\Enums\StatusTransaksi;
use App\Http\Controllers\Controller;
use App\Jobs\JobPotongStok;
use App\Layanan\LayananNotifikasi;
use App\Models\LogAudit;
use App\Models\Resep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** REVISI — Verifikasi resep (verifikasi/tolak) sebelum stok FIFO dipotong. */
class ResepController extends Controller
{
    public function index(): View
    {
        return view('apoteker.resep', [
            'antrian' => Resep::query()->with('transaksi.item.obat', 'transaksi.pelanggan')->latest()->paginate(15),
        ]);
    }

    public function putuskan(Request $request, Resep $resep, LayananNotifikasi $notifikasi): RedirectResponse
    {
        $data = $request->validate([
            'keputusan' => ['required', 'in:verifikasi,ditolak'],
            'catatan_verifikasi' => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['keputusan'] === 'ditolak' && empty(trim($data['catatan_verifikasi'] ?? ''))) {
            return back()->withErrors(['catatan_verifikasi' => 'Catatan wajib jika menolak resep.']);
        }

        $resep->update([
            'status' => $data['keputusan'] === 'verifikasi' ? StatusResep::Verifikasi : StatusResep::Ditolak,
            'diverifikasi_oleh' => $request->user()->id,
            'catatan_verifikasi' => $data['catatan_verifikasi'] ?? null,
            'diverifikasi_pada' => now(),
        ]);

        $transaksi = $resep->transaksi;

        if ($data['keputusan'] === 'verifikasi') {
            JobPotongStok::dispatch($transaksi->id);
            $notifikasi->untukTransaksi(
                $transaksi,
                JenisNotifikasi::SedangDikemas,
                'Resep terverifikasi. Obat transaksi '.$transaksi->kode_transaksi.' sedang dikemas.'
            );
        } else {
            $transaksi->update([
                'status' => StatusTransaksi::Dibatalkan,
                'catatan' => $data['catatan_verifikasi'] ?? 'Resep ditolak',
            ]);
            $notifikasi->untukTransaksi(
                $transaksi,
                JenisNotifikasi::ResepDitolak,
                'Resep transaksi '.$transaksi->kode_transaksi.' ditolak: '.($data['catatan_verifikasi'] ?? '')
            );
        }

        LogAudit::catat('resep.'.$data['keputusan'], $transaksi);

        return back()->with('status', 'Resep '.$data['keputusan'].'.');
    }
}
