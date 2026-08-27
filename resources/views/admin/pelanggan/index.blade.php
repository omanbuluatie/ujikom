@extends('layouts.meja')
@section('judul', 'Pelanggan')
@section('subjudul', 'Akun dengan peran pasien')
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
<table class="tabel-meja">
    <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td class="font-mono text-xs">{{ $u->email }}</td>
            <td>{{ $u->telepon }}</td>
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
