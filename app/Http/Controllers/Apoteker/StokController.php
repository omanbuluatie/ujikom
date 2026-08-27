<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\View\View;

class StokController extends Controller
{
    public function index(): View
    {
        return view('apoteker.stok', [
            'daftar' => Obat::query()
                ->with(['batch' => fn ($q) => $q->orderBy('tanggal_masuk')])
                ->withSum('batch as batch_sum_sisa', 'sisa')
                ->orderBy('nama')
                ->paginate(20),
        ]);
    }
}
