<?php

namespace App\Http\Controllers;

use App\Layanan\LayananPencarian;
use App\Models\KategoriObat;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — Katalog, search, filter, detail, pagination, sorting. */
class KatalogController extends Controller
{
    public function index(Request $request, LayananPencarian $pencarian): View
    {
        $daftarObat = $pencarian->cariFuzzy(
            $request->string('q')->toString(),
            $request->integer('kategori') ?: null,
            $request->string('urut', 'nama')->toString(),
            $request->string('arah', 'asc')->toString(),
        );

        return view('katalog.index', [
            'daftarObat' => $daftarObat,
            'daftarKategori' => KategoriObat::query()->orderBy('nama')->get(),
            'kata' => $request->string('q')->toString(),
            'kategoriId' => $request->integer('kategori') ?: null,
        ]);
    }

    public function detail(Obat $obat): View
    {
        $obat->load(['kategori', 'pemasok', 'batch']);

        return view('katalog.detail', ['obat' => $obat]);
    }
}
