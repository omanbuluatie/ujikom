<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PeranPengguna;
use App\Http\Controllers\Controller;
use App\Layanan\LayananVerifikasiEmail;
use App\Models\LogAudit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** UJIKOM — CRUD Pelanggan (users dengan peran pasien). */
class PelangganController extends Controller
{
    public function index(): View
    {
        return view('admin.pelanggan.index', [
            'daftar' => User::query()->where('peran', PeranPengguna::Pasien)->latest()->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ]);

        $pelanggan = User::query()->create([
            ...$data,
            'peran' => PeranPengguna::Pasien,
            'email_verified_at' => now(),
        ]);
        LogAudit::catat('pelanggan.buat', $pelanggan);

        return back()->with('status', 'Pelanggan ditambah.');
    }

    public function destroy(User $pelanggan): RedirectResponse
    {
        abort_unless($pelanggan->peran === PeranPengguna::Pasien, 403);
        $pelanggan->delete();
        LogAudit::catat('pelanggan.hapus', $pelanggan, $pelanggan->email);

        return back()->with('status', 'Pelanggan dihapus.');
    }

    public function verifikasiEmail(User $pelanggan, LayananVerifikasiEmail $verifikasi): RedirectResponse
    {
        abort_unless($pelanggan->peran === PeranPengguna::Pasien, 403);

        if ($pelanggan->email_verified_at !== null) {
            return back()->with('status', 'Email '.$pelanggan->email.' sudah terverifikasi.');
        }

        $pelanggan->update(['email_verified_at' => now()]);
        $verifikasi->tandaiTerpakai($pelanggan);
        LogAudit::catat('pelanggan.verifikasi-email', $pelanggan, $pelanggan->email);

        return back()->with('status', 'Email '.$pelanggan->email.' berhasil diverifikasi. Pelanggan bisa checkout.');
    }
}
