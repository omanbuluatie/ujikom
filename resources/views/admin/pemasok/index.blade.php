@extends('layouts.meja')
@section('judul', 'Pemasok')
@section('isi')
<form method="POST" action="{{ route('admin.pemasok.store') }}" class="kartu mb-6 grid max-w-2xl gap-2 p-4 md:grid-cols-3">
    @csrf
    <input name="nama" required class="input-lapangan" placeholder="Nama">
    <input name="telepon" class="input-lapangan" placeholder="Telepon">
    <div class="flex gap-2">
        <input name="alamat" class="input-lapangan" placeholder="Alamat">
        <button class="btn btn-utama">Tambah</button>
    </div>
</form>
<table class="tabel-meja">
    <thead><tr><th>Nama</th><th>Telepon</th><th>Alamat</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $p)
        <tr>
            <td>{{ $p->nama }}</td>
            <td class="font-mono text-xs">{{ $p->telepon }}</td>
            <td>{{ $p->alamat }}</td>
            <td>
                <form method="POST" action="{{ route('admin.pemasok.destroy', $p) }}" onsubmit="return confirm('Hapus pemasok?')">
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
