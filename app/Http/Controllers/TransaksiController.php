<?php

namespace App\Http\Controllers;

use App\Enums\StatusResep;
use App\Enums\StatusTransaksi;
use App\Jobs\JobProsesPembayaran;
use App\Layanan\LayananKeranjang;
use App\Models\ItemTransaksi;
use App\Models\LogAudit;
use App\Models\Notifikasi;
use App\Models\Resep;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * REVISI — Alur transaksi pasien: checkout → upload bukti bayar → resep → notifikasi.
 */
class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        return view('transaksi.index', [
            'daftar' => Transaksi::query()
                ->with('item.obat', 'resep')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10),
            'notifikasi' => Notifikasi::query()
                ->where('user_id', $request->user()->id)
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function checkout(LayananKeranjang $keranjang): View|RedirectResponse
    {
        $rincian = $keranjang->rincian();
        if ($rincian->isEmpty()) {
            return redirect()->route('keranjang')->withErrors(['keranjang' => 'Keranjang masih kosong.']);
        }

        return view('transaksi.checkout', [
            'rincian' => $rincian,
            'total' => $keranjang->total(),
        ]);
    }

    public function buat(Request $request, LayananKeranjang $keranjang): RedirectResponse
    {
        $rincian = $keranjang->rincian();
        if ($rincian->isEmpty()) {
            return redirect()->route('keranjang');
        }

        $data = $request->validate([
            'alamat_pengiriman' => ['required', 'string', 'max:500'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $transaksi = DB::transaction(function () use ($request, $rincian, $keranjang, $data) {
            $transaksi = Transaksi::query()->create([
                'kode_transaksi' => Transaksi::buatKode(),
                'user_id' => $request->user()->id,
                'status' => StatusTransaksi::Pending,
                'sumber' => 'online',
                'total' => $keranjang->total(),
                'alamat_pengiriman' => $data['alamat_pengiriman'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            foreach ($rincian as $baris) {
                ItemTransaksi::query()->create([
                    'transaksi_id' => $transaksi->id,
                    'obat_id' => $baris['obat']->id,
                    'jumlah' => $baris['jumlah'],
                    'harga_satuan' => $baris['obat']->harga,
                    'subtotal' => $baris['subtotal'],
                ]);
            }

            LogAudit::catat('transaksi.buat', $transaksi, $transaksi->kode_transaksi);
            $keranjang->kosongkan();

            return $transaksi;
        });

        return redirect()->route('transaksi.bayar', $transaksi);
    }

    public function bayar(Transaksi $transaksi): View|RedirectResponse
    {
        $this->otorisasi($transaksi);

        if ($transaksi->status !== StatusTransaksi::Pending) {
            return redirect()
                ->route('transaksi.index')
                ->with('status', 'Transaksi '.$transaksi->kode_transaksi.' sudah diproses ('.$transaksi->status->label().').');
        }

        return view('transaksi.bayar', ['transaksi' => $transaksi->load('item.obat')]);
    }

    /**
     * Upload bukti pembayaran + metode → antrian verifikasi (job).
     */
    public function prosesBayar(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $this->otorisasi($transaksi);

        if ($transaksi->status !== StatusTransaksi::Pending) {
            return redirect()->route('transaksi.index')
                ->with('status', 'Pembayaran '.$transaksi->kode_transaksi.' sudah diproses.');
        }

        $data = $request->validate([
            'metode_pembayaran' => ['required', 'string', 'max:60'],
            'bukti_pembayaran' => ['required', 'image', 'max:4096'],
        ]);

        $transaksi->update([
            'metode_pembayaran' => $data['metode_pembayaran'],
            'bukti_pembayaran' => $request->file('bukti_pembayaran')->store('pembayaran', 'public'),
        ]);

        JobProsesPembayaran::dispatch($transaksi->id);
        LogAudit::catat('pembayaran.dispatch', $transaksi);

        return redirect()->route('transaksi.index')
            ->with('status', 'Bukti pembayaran terkirim. Status akan berubah setelah worker memproses antrian.');
    }

    public function unggahResep(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $this->otorisasi($transaksi);
        abort_unless($transaksi->butuhResep() && $transaksi->status === StatusTransaksi::Diproses, 422);

        $request->validate(['berkas' => ['required', 'image', 'max:4096']]);

        Resep::query()->updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'berkas_gambar' => $request->file('berkas')->store('resep', 'public'),
                'status' => StatusResep::Pending,
            ]
        );

        LogAudit::catat('resep.unggah', $transaksi);

        return back()->with('status', 'Resep terkirim, menunggu verifikasi apoteker.');
    }

    private function otorisasi(Transaksi $transaksi): void
    {
        abort_unless(
            $transaksi->user_id === auth()->id() || auth()->user()?->adalah(\App\Enums\PeranPengguna::Admin),
            403
        );
    }
}
