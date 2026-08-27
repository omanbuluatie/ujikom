<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\JobImporBarisObat;
use App\Layanan\LayananMigrasi;
use App\Models\LogAudit;
use App\Models\LogMigrasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * UJIKOM — Migrasi Excel/CSV, cutover, validasi, rollback, batch paralel.
 */
class MigrasiController extends Controller
{
    public function index(LayananMigrasi $migrasi): View
    {
        return view('admin.migrasi.index', [
            'mapping' => $migrasi->mappingKolom(),
            'log' => LogMigrasi::query()->latest()->limit(50)->get(),
            // MySQL 8: DISTINCT + ORDER BY kolom lain ditolak — pakai GROUP BY + MAX(id).
            'batch' => LogMigrasi::query()
                ->selectRaw('batch_migrasi_id, MAX(id) as latest_id')
                ->groupBy('batch_migrasi_id')
                ->orderByDesc('latest_id')
                ->limit(10)
                ->pluck('batch_migrasi_id'),
        ]);
    }

    public function impor(Request $request): RedirectResponse
    {
        $request->validate(['berkas' => ['required', 'file', 'mimes:csv,txt', 'max:2048']]);

        $batchId = 'MIG-'.now()->format('YmdHis').'-'.Str::lower(Str::random(4));
        $jalur = $request->file('berkas')->getRealPath();
        $handle = fopen($jalur, 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn ($h) => Str::of((string) $h)->trim()->lower()->toString(), $header ?: []);

        $pekerjaan = [];
        $nomor = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $nomor++;
            if (count($data) === 1 && trim($data[0]) === '') {
                continue;
            }
            $baris = [];
            foreach ($header as $i => $namaKolom) {
                $baris[$namaKolom] = $data[$i] ?? '';
            }
            $pekerjaan[] = new JobImporBarisObat($baris, $nomor, $batchId);
        }
        fclose($handle);

        if ($pekerjaan === []) {
            return back()->withErrors(['berkas' => 'File CSV kosong.']);
        }

        Bus::batch($pekerjaan)->name('impor-obat-'.$batchId)->dispatch();
        LogAudit::catat('migrasi.impor', null, $batchId);

        return back()->with('status', 'Impor '.$batchId.' masuk antrian ('.count($pekerjaan).' baris). Jalankan queue:work.');
    }

    public function rollback(Request $request, LayananMigrasi $migrasi): RedirectResponse
    {
        $id = $request->validate(['batch_migrasi_id' => ['required', 'string']])['batch_migrasi_id'];
        $jumlah = $migrasi->rollback($id);
        LogAudit::catat('migrasi.rollback', null, $id);

        return back()->with('status', "Rollback {$id}: {$jumlah} obat dihapus.");
    }
}
