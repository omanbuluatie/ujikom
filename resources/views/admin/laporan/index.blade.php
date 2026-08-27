@extends('layouts.meja')
@section('judul', 'Laporan')
@section('subjudul', 'SQL penjualan, terlaris, kedaluwarsa, rekap · unduh PDF')
@section('isi')
<form method="GET" class="mb-6 flex flex-wrap gap-2">
    <input type="date" name="dari" value="{{ $dari }}" class="input-lapangan max-w-[11rem]">
    <input type="date" name="sampai" value="{{ $sampai }}" class="input-lapangan max-w-[11rem]">
    <button class="btn btn-senyap">Tampilkan</button>
    <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn btn-utama">Unduh PDF</a>
    <button form="antrian" class="btn btn-tiket">Antrian laporan besar</button>
</form>
<form id="antrian" method="POST" action="{{ route('admin.laporan.antrian') }}">
    @csrf
    <input type="hidden" name="dari" value="{{ $dari }}">
    <input type="hidden" name="sampai" value="{{ $sampai }}">
</form>

<div class="grid gap-4 lg:grid-cols-2">
    <div class="kartu p-4">
        <p class="label-lapangan">Obat terlaris</p>
        <ol class="text-sm">
            @foreach($terlaris as $b)
                <li class="flex justify-between py-1"><span>{{ $b->obat?->nama }}</span><span class="font-mono">{{ $b->total_terjual }}</span></li>
            @endforeach
        </ol>
    </div>
    <div class="kartu p-4">
        <p class="label-lapangan">Mendekati kedaluwarsa ≤90 hari</p>
        <ul class="text-sm">
            @foreach($kedaluwarsa as $obat)
                <li>{{ $obat->nama }} — {{ $obat->batch->first()?->tanggal_kedaluwarsa?->format('d.m.Y') }}</li>
            @endforeach
        </ul>
    </div>
</div>
<div class="mt-6 overflow-x-auto">
    <table class="tabel-meja">
        <thead><tr><th>Tanggal</th><th>Nomor</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($rekap as $p)
            <tr>
                <td class="font-mono text-xs">{{ $p->created_at->format('d.m.Y H:i') }}</td>
                <td class="font-mono text-xs">{{ $p->nomor }}</td>
                <td class="font-mono">{{ number_format($p->total, 0, ',', '.') }}</td>
                <td>{{ $p->status->label() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
