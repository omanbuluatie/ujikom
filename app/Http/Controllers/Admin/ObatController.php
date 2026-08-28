<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Layanan\LayananStok;
use App\Models\KategoriObat;
use App\Models\LogAudit;
use App\Models\Obat;
use App\Models\Pemasok;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — CRUD Obat + unggah/pratinjau gambar. */
class ObatController extends Controller
{
    public function index(Request $request): View
    {
        $daftar = Obat::query()
            ->with(['kategori', 'pemasok'])
            ->withSum('batch as batch_sum_sisa', 'sisa')
            ->when($request->q, fn ($q, $kata) => $q->where('nama', 'like', '%'.$kata.'%'))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.obat.index', ['daftar' => $daftar]);
    }

    public function create(): View
    {
        return view('admin.obat.form', [
            'obat' => new Obat,
            'kategori' => KategoriObat::query()->orderBy('nama')->get(),
            'pemasok' => Pemasok::query()->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request, LayananStok $stok): RedirectResponse
    {
        $obat = Obat::query()->create($this->dataTervalidasi($request));
        if ($request->integer('stok_awal') > 0) {
            $stok->masukkanBatch(
                $obat,
                $request->integer('stok_awal'),
                $request->string('tanggal_kedaluwarsa', now()->addYear()->toDateString())->toString(),
            );
        }
        LogAudit::catat('obat.buat', $obat);

        return redirect()->route('admin.obat.index')->with('status', 'Obat disimpan.');
    }

    public function edit(Obat $obat): View
    {
        return view('admin.obat.form', [
            'obat' => $obat,
            'kategori' => KategoriObat::query()->orderBy('nama')->get(),
            'pemasok' => Pemasok::query()->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $obat->update($this->dataTervalidasi($request, $obat->id));
        LogAudit::catat('obat.ubah', $obat);

        return redirect()->route('admin.obat.index')->with('status', 'Obat diperbarui.');
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        $obat->delete();
        LogAudit::catat('obat.hapus', $obat, $obat->kode);

        return back()->with('status', 'Obat dihapus.');
    }

    /** @return array<string, mixed> */
    private function dataTervalidasi(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
        'kode' => [
            'required',
            'string',
            'max:40',
            \Illuminate\Validation\Rule::unique('obat', 'kode')->ignore($id),
        ],
            'nama' => ['required', 'string', 'max:150'],
            'kategori_obat_id' => ['required', 'exists:kategori_obat,id'],
            'pemasok_id' => ['nullable', 'exists:pemasok,id'],
            'harga' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'butuh_resep' => ['sometimes', 'boolean'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['butuh_resep'] = $request->boolean('butuh_resep');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('obat', 'public');
        }

        return $data;
    }
}
