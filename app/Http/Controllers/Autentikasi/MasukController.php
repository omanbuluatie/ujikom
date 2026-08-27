<?php

namespace App\Http\Controllers\Autentikasi;

use App\Enums\PeranPengguna;
use App\Http\Controllers\Controller;
use App\Models\LogAudit;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * UJIKOM — Autentikasi: hashing, validasi password, email verification, session, CSRF (@csrf di form).
 */
class MasukController extends Controller
{
    public function form(): View
    {
        return view('autentikasi.masuk');
    }

    public function proses(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($data, $request->boolean('ingat'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak cocok.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        LogAudit::catat('masuk', $request->user());

        return redirect()->intended($this->tujuan($request->user()));
    }

    public function keluar(Request $request): RedirectResponse
    {
        LogAudit::catat('keluar', $request->user());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }

    public function formDaftar(): View
    {
        return view('autentikasi.daftar');
    }

    public function daftar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:255'],
            // min 8, huruf + angka sesuai dokumen tugas
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $pengguna = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telepon' => $data['telepon'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'password' => $data['password'],
            'peran' => PeranPengguna::Pasien,
        ]);

        event(new Registered($pengguna));
        Auth::login($pengguna);
        LogAudit::catat('daftar', $pengguna);

        return redirect()->route('verification.notice');
    }

    private function tujuan(User $pengguna): string
    {
        return match ($pengguna->peran) {
            PeranPengguna::Admin => route('admin.dasbor'),
            PeranPengguna::Apoteker => route('apoteker.resep.index'),
            PeranPengguna::Kasir => route('kasir.transaksi'),
            PeranPengguna::Pasien => route('katalog'),
        };
    }
}
