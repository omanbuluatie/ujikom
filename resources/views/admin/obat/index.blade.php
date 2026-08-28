@extends('layouts.meja')
@section('judul', 'Obat')
@section('subjudul', 'CRUD master + gambar + batch FIFO')
@section('isi')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ request('q') }}" class="input-lapangan" placeholder="Cari nama">
        <button class="btn btn-senyap">Cari</button>
    </form>
    <a href="{{ route('admin.obat.create') }}" class="btn btn-utama">Obat baru</a>
</div>
<div class="overflow-x-auto">
    <table class="tabel-meja">
        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Sisa</th><th></th></tr></thead>
        <tbody>
        @foreach($daftar as $obat)
            <tr>
                <td class="font-mono text-xs">{{ $obat->kode }}</td>
                <td>{{ $obat->nama }} @if($obat->butuh_resep)<span class="tiket tiket--tolak">resep</span>@endif</td>
                <td>{{ $obat->kategori->nama }}</td>
                <td class="font-mono">{{ number_format($obat->harga, 2, ',', '.') }}</td>
                <td class="font-mono {{ $obat->batch_sum_sisa <= $obat->stok_minimum ? 'text-[#C23B22]' : '' }}">{{ $obat->batch_sum_sisa }}</td>
                <td class="whitespace-nowrap">
                    <a href="{{ route('admin.obat.edit', $obat) }}" class="text-sm underline">Ubah</a>
                    <form method="POST" action="{{ route('admin.obat.destroy', $obat) }}" class="inline" onsubmit="return confirm('Hapus obat ini?')">
                        @csrf @method('DELETE')
                        <button class="text-sm text-[#C23B22]">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $daftar->links() }}
@endsection
