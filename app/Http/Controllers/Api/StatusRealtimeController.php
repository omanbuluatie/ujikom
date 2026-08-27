<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peringatan;
use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * UJIKOM — Pemrograman real-time (polling 10 detik).
 * Dasbor memanggil endpoint ini untuk stok kritis & pesanan baru tanpa WebSocket.
 */
class StatusRealtimeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'peringatan_baru' => Peringatan::query()->whereNull('dibaca_pada')->latest()->limit(8)->get(),
            'pesanan_baru' => Pesanan::query()->latest()->limit(5)->get(['id', 'nomor', 'status', 'total', 'created_at']),
            'waktu_server' => now()->toDateTimeString(),
        ]);
    }
}
