<?php

use App\Enums\TingkatKeparahan;
use App\Http\Middleware\PastikanPeran;
use App\Models\LogKesalahan;
use App\Models\Peringatan;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * UJIKOM — CSRF aktif otomatis pada grup web.
 * Alias middleware `peran` untuk RBAC.
 * Exception ditulis ke log_kesalahan + peringatan admin (severity).
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'peran' => PastikanPeran::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('masuk'));
        $middleware->redirectUsersTo(fn () => route('katalog'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (Throwable $e): void {
            try {
                $tingkat = $e instanceof \App\Exceptions\StokTidakCukupException
                    ? TingkatKeparahan::Peringatan
                    : TingkatKeparahan::Kritis;

                LogKesalahan::query()->create([
                    'tingkat' => $tingkat,
                    'pesan' => mb_substr($e->getMessage(), 0, 500),
                    'jejak' => mb_substr($e->getTraceAsString(), 0, 4000),
                    'url' => request()->fullUrl(),
                ]);

                Peringatan::query()->create([
                    'jenis' => \App\Enums\JenisPeringatan::Kesalahan,
                    'tingkat' => $tingkat,
                    'judul' => 'Kesalahan sistem',
                    'pesan' => mb_substr($e->getMessage(), 0, 500),
                ]);
            } catch (Throwable) {
                // Jangan sampai pencatatan error membuat migrasi/seeder gagal.
            }
        });
    })
    ->create();
