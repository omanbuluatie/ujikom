<?php

namespace App\Http\Middleware;

use App\Enums\PeranPengguna;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * UJIKOM — Role-based access control.
 * Prosedur: cek login → cocokkan enum peran → 403 jika tidak berhak (bukti isolasi kasir/admin/pasien).
 */
class PastikanPeran
{
    public function handle(Request $request, Closure $next, string ...$daftarPeran): Response
    {
        $pengguna = $request->user();
        if (! $pengguna) {
            return redirect()->route('masuk');
        }

        $diizinkan = array_map(fn (string $p) => PeranPengguna::from($p), $daftarPeran);

        if (! $pengguna->adalah(...$diizinkan)) {
            abort(403, 'Akun ini tidak berhak membuka halaman tersebut.');
        }

        return $next($request);
    }
}
