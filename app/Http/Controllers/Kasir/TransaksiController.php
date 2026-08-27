<?php

namespace App\Http\Controllers\Kasir;

use App\Enums\StatusPesanan;
use App\Exceptions\StokTidakCukupException;
use App\Http\Controllers\Controller;
use App\Layanan\LayananStok;
use App\Models\ItemPesanan;
use App\Models\LogAudit;
use App\Models\Obat;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * UJIKOM — Penjualan counter + sinkronisasi stok dengan online.
 * Kasir memotong stok lewat LayananStok::potongStokFifo() yang sama dengan pesanan online.
 */
class TransaksiController extends Controller
{
    public function form(): View
    {
        return view('kasir.transaksi', [
            'daftarObat' => Obat::query()->withSum('batch as batch_sum_sisa', 'sisa')->orderBy('nama')->get(),
        ]);
    }

    public function simpan(Request $request, LayananStok $stok): RedirectResponse
    {
        $data = $request->validate([
            'pilih' => ['required', 'array', 'min:1'],
            'pilih.*' => ['exists:obat,id'],
            'jumlah' => ['required', 'array'],
            'jumlah.*' => ['integer', 'min:1'],
        ]);

        try {
            $pesanan = DB::transaction(function () use ($request, $data, $stok) {
                $baris = [];
                $total = 0;

                foreach ($data['pilih'] as $obatId) {
                    $jumlah = (int) ($data['jumlah'][$obatId] ?? 0);
                    if ($jumlah < 1) {
                        continue;
                    }
                    $obat = Obat::query()->findOrFail($obatId);
                    $subtotal = $obat->harga * $jumlah;
                    $baris[] = compact('obat', 'jumlah', 'subtotal');
                    $total += $subtotal;
                }

                abort_if($baris === [], 422, 'Pilih minimal satu obat.');

                $pesanan = Pesanan::query()->create([
                    'nomor' => Pesanan::buatNomor(),
                    'user_id' => $request->user()->id,
                    'status' => StatusPesanan::Selesai,
                    'sumber' => 'kasir',
                    'total' => $total,
                    'dibayar_pada' => now(),
                ]);

                foreach ($baris as $item) {
                    ItemPesanan::query()->create([
                        'pesanan_id' => $pesanan->id,
                        'obat_id' => $item['obat']->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['obat']->harga,
                        'subtotal' => $item['subtotal'],
                    ]);
                    $stok->potongStokFifo($item['obat'], $item['jumlah'], 'kasir', $pesanan->id);
                }

                LogAudit::catat('kasir.jual', $pesanan, $pesanan->nomor);

                return $pesanan;
            });
        } catch (StokTidakCukupException $e) {
            return back()->withErrors(['stok' => $e->getMessage()]);
        }

        return back()->with('status', 'Transaksi '.$pesanan->nomor.' tersimpan. Stok FIFO sudah dipotong.');
    }
}
