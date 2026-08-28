<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriObat;
use App\Models\LogAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/** REVISI — CRUD Kategori: nama, slot, deskripsi, is_active, email. */
class KategoriController extends Controller
{
    public function index(): View
    {
        return view('admin.kategori.index', [
            'daftar' => KategoriObat::query()->withCount('obat')->orderBy('slot')->orderBy('nama')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:80'],
            'slot' => ['nullable', 'integer', 'min:0', 'max:999'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'email' => ['nullable', 'email', 'max:120'],
        ]);

        $kategori = KategoriObat::query()->create([
            'nama' => $data['nama'],
            'slug' => Str::slug($data['nama']).'-'.Str::random(4),
            'slot' => $data['slot'] ?? 0,
            'deskripsi' => $data['deskripsi'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'email' => $data['email'] ?? null,
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
