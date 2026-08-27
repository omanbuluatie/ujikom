@extends('layouts.meja')
@section('judul', 'Kategori')
@section('isi')
<form method="POST" action="{{ route('admin.kategori.store') }}" class="kartu mb-6 flex max-w-lg gap-2 p-4">
    @csrf
    <input name="nama" required class="input-lapangan" placeholder="Nama kategori">
    <button class="btn btn-utama">Tambah</button>
</form>
<table class="tabel-meja">
    <thead><tr><th>Nama</th><th>Jumlah obat</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $k)
        <tr>
            <td>{{ $k->nama }}</td>
            <td class="font-mono">{{ $k->obat_count }}</td>
            <td>
                <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}" onsubmit="return confirm('Hapus kategori?')">
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
