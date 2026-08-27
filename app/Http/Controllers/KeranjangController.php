<?php

namespace App\Http\Controllers;

use App\Layanan\LayananKeranjang;
use App\Models\Obat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — Cart. */
class KeranjangController extends Controller
{
    public function __construct(private LayananKeranjang $keranjang)
    {
    }

    public function index(): View
    {
        return view('keranjang.index', [
            'rincian' => $this->keranjang->rincian(),
            'total' => $this->keranjang->total(),
        ]);
    }

    public function tambah(Request $request, Obat $obat): RedirectResponse
    {
        $jumlah = max(1, $request->integer('jumlah', 1));
        $this->keranjang->tambah($obat->id, $jumlah);

        return back()->with('status', $obat->nama.' masuk keranjang.');
    }

    public function ubah(Request $request, Obat $obat): RedirectResponse
    {
        $this->keranjang->ubah($obat->id, $request->integer('jumlah'));

        return back();
    }
}
