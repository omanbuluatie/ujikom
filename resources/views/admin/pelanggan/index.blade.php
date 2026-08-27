@extends('layouts.meja')
@section('judul', 'Pelanggan')
@section('subjudul', 'Akun dengan peran pasien · admin dapat verifikasi email')
@section('isi')
<form method="POST" action="{{ route('admin.pelanggan.store') }}" class="kartu mb-6 grid max-w-3xl gap-2 p-4 md:grid-cols-2">
    @csrf
    <input name="name" required class="input-lapangan" placeholder="Nama">
    <input name="email" type="email" required class="input-lapangan" placeholder="Email">
    <input name="telepon" class="input-lapangan" placeholder="Telepon">
    <input name="password" type="password" required class="input-lapangan" placeholder="Kata sandi min 8 huruf+angka">
    <input name="alamat" class="input-lapangan md:col-span-2" placeholder="Alamat">
    <button class="btn btn-utama md:col-span-2">Tambah pelanggan</button>
</form>
<p class="mb-4 text-sm text-[#3D4C58]">Pelanggan baru dari admin otomatis terverifikasi. Yang daftar sendiri perlu klik tautan email — atau admin verifikasi manual di bawah.</p>
<table class="tabel-meja">
    <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Verifikasi email</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td class="font-mono text-xs">{{ $u->email }}</td>
            <td>{{ $u->telepon }}</td>
            <td>
                @if($u->email_verified_at)
                    <span class="tiket tiket--selesai">Terverifikasi</span>
                    <span class="mt-1 block font-mono text-[10px] text-[#5a6b76]">{{ $u->email_verified_at->format('d.m.Y H:i') }}</span>
                @else
                    <span class="tiket tiket--tunggu">Belum verifikasi</span>
                    <form method="POST" action="{{ route('admin.pelanggan.verifikasi-email', $u) }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-tiket !py-1 !px-2 text-xs" onclick="return confirm('Verifikasi email {{ $u->email }}?')">Verifikasi sekarang</button>
                    </form>
                @endif
            </td>
            <td>
                <form method="POST" action="{{ route('admin.pelanggan.destroy', $u) }}" onsubmit="return confirm('Hapus pelanggan?')">
                    @csrf @method('DELETE')
                    <button class="text-sm text-[#C23B22]">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $daftar->links() }}
@endsection
