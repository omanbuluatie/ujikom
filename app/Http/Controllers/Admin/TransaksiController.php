<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPesanan;
use App\Http\Controllers\Controller;
use App\Models\LogAudit;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — CRUD Transaksi. */
class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $daftar = Pesanan::query()
            ->with('pelanggan', 'item')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.transaksi.index', ['daftar' => $daftar]);
    }

    public function show(Pesanan $transaksi): View
    {
        return view('admin.transaksi.show', [
            'pesanan' => $transaksi->load('pelanggan', 'item.obat', 'resep'),
        ]);
    }

    public function batalkan(Pesanan $transaksi): RedirectResponse
    {
        $transaksi->update(['status' => StatusPesanan::Dibatalkan]);
        LogAudit::catat('transaksi.batal', $transaksi);

        return back()->with('status', 'Transaksi dibatalkan.');
    }
}
