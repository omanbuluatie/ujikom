<?php

namespace App\Http\Controllers\Apoteker;

use App\Enums\StatusPesanan;
use App\Enums\StatusResep;
use App\Http\Controllers\Controller;
use App\Jobs\JobPotongStok;
use App\Models\LogAudit;
use App\Models\Resep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — Verifikasi resep (setuju/tolak) menggantikan proses manual. */
class ResepController extends Controller
{
    public function index(): View
    {
        return view('apoteker.resep', [
            'antrian' => Resep::query()->with('pesanan.item.obat', 'pesanan.pelanggan')->latest()->paginate(15),
        ]);
    }

    public function putuskan(Request $request, Resep $resep): RedirectResponse
    {
        $data = $request->validate([
            'keputusan' => ['required', 'in:disetujui,ditolak'],
            'catatan_apoteker' => ['nullable', 'string', 'max:500'],
        ]);

        $resep->update([
            'status' => $data['keputusan'] === 'disetujui' ? StatusResep::Disetujui : StatusResep::Ditolak,
            'diverifikasi_oleh' => $request->user()->id,
            'catatan_apoteker' => $data['catatan_apoteker'] ?? null,
            'diverifikasi_pada' => now(),
        ]);

        if ($data['keputusan'] === 'disetujui') {
            $resep->pesanan->update(['status' => StatusPesanan::Diproses]);
            JobPotongStok::dispatch($resep->pesanan_id);
        } else {
            $resep->pesanan->update([
                'status' => StatusPesanan::Dibatalkan,
                'catatan' => $data['catatan_apoteker'] ?? 'Resep ditolak',
            ]);
        }

        LogAudit::catat('resep.'.$data['keputusan'], $resep->pesanan);

        return back()->with('status', 'Resep '.$data['keputusan'].'.');
    }
}
