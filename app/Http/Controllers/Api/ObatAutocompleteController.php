<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Layanan\LayananPencarian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * UJIKOM — Autocomplete + dokumentasi API.
 * GET /api/obat/autocomplete?q=  (JSON, limit 10, debounce di Alpine).
 */
class ObatAutocompleteController extends Controller
{
    public function __invoke(Request $request, LayananPencarian $pencarian): JsonResponse
    {
        $kata = $request->string('q')->toString();

        return response()->json($pencarian->autocomplete($kata));
    }
}
