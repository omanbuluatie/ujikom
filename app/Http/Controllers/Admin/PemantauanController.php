<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAudit;
use App\Models\LogKesalahan;
use App\Models\Peringatan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * UJIKOM — Monitoring resource: antrian job, error severity, audit, stok.
 */
class PemantauanController extends Controller
{
    public function index(): View
    {
        return view('admin.pemantauan.index', [
            'antrian' => DB::table('jobs')->count(),
            'gagal' => DB::table('failed_jobs')->count(),
            'batch' => DB::table('job_batches')->orderByDesc('created_at')->limit(8)->get(),
            'kesalahan' => LogKesalahan::query()->latest()->limit(20)->get(),
            'audit' => LogAudit::query()->with('pengguna')->latest()->limit(20)->get(),
            'peringatan' => Peringatan::query()->latest()->limit(15)->get(),
            'sesi' => DB::table('sessions')->count(),
        ]);
    }
}
