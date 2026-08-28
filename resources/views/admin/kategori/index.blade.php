@extends('layouts.meja')
@section('judul', 'Kategori')
@section('isi')
<form method="POST" action="{{ route('admin.kategori.store') }}" class="kartu mb-6 max-w-2xl space-y-3 p-4">
    @csrf
    <div class="grid gap-3 md:grid-cols-2">
        <div>
            <label class="label-lapangan">Nama</label>
            <input name="nama" required class="input-lapangan" placeholder="Nama kategori">
        </div>
        <div>
            <label class="label-lapangan">Slot urutan</label>
            <input name="slot" type="number" min="0" class="input-lapangan" placeholder="0">
        </div>
    </div>
    <div>
        <label class="label-lapangan">Deskripsi</label>
        <textarea name="deskripsi" class="input-lapangan" rows="2" placeholder="Opsional"></textarea>
    </div>
    <div class="grid gap-3 md:grid-cols-2">
        <div>
            <label class="label-lapangan">Email kontak</label>
            <input name="email" type="email" class="input-lapangan" placeholder="kategori@apotek.test">
        </div>
        <div class="flex items-end gap-2 pb-1">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" checked> Aktif
            </label>
        </div>
    </div>
    <button class="btn btn-utama">Tambah</button>
</form>
<table class="tabel-meja">
    <thead><tr><th>Slot</th><th>Nama</th><th>Email</th><th>Aktif</th><th>Jumlah obat</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $k)
        <tr>
            <td class="font-mono">{{ $k->slot }}</td>
            <td>{{ $k->nama }}</td>
            <td class="text-sm">{{ $k->email ?? '—' }}</td>
            <td>{{ $k->is_active ? 'Ya' : 'Tidak' }}</td>
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
