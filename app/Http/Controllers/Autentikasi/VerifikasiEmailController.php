<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use App\Layanan\LayananVerifikasiEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** UJIKOM — Email verification. */
class VerifikasiEmailController extends Controller
{
    public function pemberitahuan(): View
    {
        return view('autentikasi.verifikasi-email');
    }

    public function verifikasi(EmailVerificationRequest $request, LayananVerifikasiEmail $verifikasi): RedirectResponse
    {
        $request->fulfill();
        $verifikasi->tandaiTerpakai($request->user());

        return redirect()->route('katalog')->with('status', 'Email sudah diverifikasi. Silakan belanja.');
    }

    public function kirimUlang(Request $request): RedirectResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Tautan verifikasi dikirim ulang. Admin dapat melihat tautan di Pemantauan.');
    }
}
