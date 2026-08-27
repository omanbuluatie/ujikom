<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriObat;
use App\Models\LogAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/** UJIKOM — CRUD Kategori. */
class KategoriController extends Controller
{
    public function index(): View
    {
        return view('admin.kategori.index', [
            'daftar' => KategoriObat::query()->withCount('obat')->orderBy('nama')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['nama' => ['required', 'string', 'max:80']]);
        $kategori = KategoriObat::query()->create([
            'nama' => $data['nama'],
            'slug' => Str::slug($data['nama']).'-'.Str::random(4),
        ]);
        LogAudit::catat('kategori.buat', $kategori);

        return back()->with('status', 'Kategori ditambah.');
    }

    public function destroy(KategoriObat $kategori): RedirectResponse
    {
        $kategori->delete();
        LogAudit::catat('kategori.hapus', $kategori, $kategori->nama);

        return back()->with('status', 'Kategori dihapus.');
    }
}
