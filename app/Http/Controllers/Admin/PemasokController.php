<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAudit;
use App\Models\Pemasok;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — CRUD Supplier / pemasok. */
class PemasokController extends Controller
{
    public function index(): View
    {
        return view('admin.pemasok.index', [
            'daftar' => Pemasok::query()->orderBy('nama')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ]);
        $pemasok = Pemasok::query()->create($data);
        LogAudit::catat('pemasok.buat', $pemasok);

        return back()->with('status', 'Pemasok ditambah.');
    }

    public function destroy(Pemasok $pemasok): RedirectResponse
    {
        $pemasok->delete();
        LogAudit::catat('pemasok.hapus', $pemasok, $pemasok->nama);

        return back()->with('status', 'Pemasok dihapus.');
    }
}
