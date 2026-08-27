<?php

namespace App\Http\Controllers;

use App\Enums\StatusPesanan;
use App\Enums\StatusResep;
use App\Jobs\JobProsesPembayaran;
use App\Layanan\LayananKeranjang;
use App\Models\ItemPesanan;
use App\Models\LogAudit;
use App\Models\Pesanan;
use App\Models\Resep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * UJIKOM — Checkout, pembayaran, konfirmasi, unggah resep, notifikasi status.
 */
class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $daftar = Pesanan::query()
            ->with('item.obat', 'resep')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pesanan.index', ['daftar' => $daftar]);
    }

    public function checkout(LayananKeranjang $keranjang): View|RedirectResponse
    {
        $rincian = $keranjang->rincian();
        if ($rincian->isEmpty()) {
            return redirect()->route('keranjang')->withErrors(['keranjang' => 'Keranjang masih kosong.']);
        }

        return view('pesanan.checkout', [
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

        $pesanan = DB::transaction(function () use ($request, $rincian, $keranjang) {
            $pesanan = Pesanan::query()->create([
                'nomor' => Pesanan::buatNomor(),
                'user_id' => $request->user()->id,
                'status' => StatusPesanan::MenungguBayar,
                'sumber' => 'online',
                'total' => $keranjang->total(),
                'catatan' => $request->string('catatan')->toString() ?: null,
            ]);

            foreach ($rincian as $baris) {
                ItemPesanan::query()->create([
                    'pesanan_id' => $pesanan->id,
                    'obat_id' => $baris['obat']->id,
                    'jumlah' => $baris['jumlah'],
                    'harga_satuan' => $baris['obat']->harga,
                    'subtotal' => $baris['subtotal'],
                ]);
            }

            LogAudit::catat('pesanan.buat', $pesanan, $pesanan->nomor);
            $keranjang->kosongkan();

            return $pesanan;
        });

        return redirect()->route('pesanan.bayar', $pesanan);
    }

    public function bayar(Pesanan $pesanan): View|RedirectResponse
    {
        $this->otorisasi($pesanan);

        // Halaman bayar hanya untuk status menunggu_bayar.
        // Jika job sudah selesai (mis. menunggu_resep), jangan tampilkan tombol bayar lagi.
        if ($pesanan->status !== StatusPesanan::MenungguBayar) {
            return redirect()
                ->route('pesanan.index')
                ->with('status', 'Tiket '.$pesanan->nomor.' sudah diproses. Status sekarang: '.$pesanan->status->label().'. Tidak perlu bayar ulang.');
        }

        return view('pesanan.bayar', ['pesanan' => $pesanan->load('item.obat')]);
    }

    /**
     * Tombol "Bayar sekarang" hanya men-dispatch job — bukti antrian pembayaran.
     */
    public function prosesBayar(Pesanan $pesanan): RedirectResponse
    {
        $this->otorisasi($pesanan);

        if ($pesanan->status !== StatusPesanan::MenungguBayar) {
            return redirect()
                ->route('pesanan.index')
                ->with('status', 'Pembayaran '.$pesanan->nomor.' sudah pernah diproses ('.$pesanan->status->label().'). Buka daftar pesanan untuk langkah berikutnya (unggah resep jika diminta).');
        }

        JobProsesPembayaran::dispatch($pesanan->id);
        LogAudit::catat('pembayaran.dispatch', $pesanan);

        return redirect()->route('pesanan.index')
            ->with('status', 'Pembayaran masuk antrian `pembayaran`. Muat ulang daftar pesanan setelah worker selesai. Worker: php artisan queue:work --queue=pembayaran,stok,impor,laporan,default');
    }

    public function unggahResep(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->otorisasi($pesanan);
        abort_unless($pesanan->status === StatusPesanan::MenungguResep, 422);

        $data = $request->validate([
            'berkas' => ['required', 'image', 'max:4096'],
        ]);

        $jalur = $request->file('berkas')->store('resep', 'public');

        Resep::query()->updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'berkas_gambar' => $jalur,
                'status' => StatusResep::Menunggu,
            ]
        );

        $pesanan->update(['status' => StatusPesanan::MenungguVerifikasi]);
        LogAudit::catat('resep.unggah', $pesanan);

        return back()->with('status', 'Resep terkirim, menunggu apoteker.');
    }

    private function otorisasi(Pesanan $pesanan): void
    {
        abort_unless($pesanan->user_id === auth()->id() || auth()->user()?->adalah(\App\Enums\PeranPengguna::Admin), 403);
    }
}
