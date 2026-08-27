@extends('layouts.meja')
@section('judul', $pesanan->nomor)
@section('isi')
<div class="kartu max-w-2xl p-6">
    <div class="flex justify-between">
        @include('komponen.tiket-status', ['status' => $pesanan->status])
        <span class="text-sm">{{ $pesanan->sumber }} · {{ $pesanan->pelanggan->name }}</span>
    </div>
    <ul class="mt-4 text-sm">
        @foreach($pesanan->item as $item)
            <li class="flex justify-between border-b py-2"><span>{{ $item->obat->nama }} × {{ $item->jumlah }}</span><span class="font-mono">{{ number_format($item->subtotal, 0, ',', '.') }}</span></li>
        @endforeach
    </ul>
    <p class="mt-4 font-display text-xl">Total Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
    @if($pesanan->resep)
        <p class="mt-3 text-sm">Resep: {{ $pesanan->resep->status->label() }}</p>
        <img src="{{ asset('storage/'.$pesanan->resep->berkas_gambar) }}" alt="" class="mt-2 max-h-48">
    @endif
    @if(!in_array($pesanan->status, [\App\Enums\StatusPesanan::Selesai, \App\Enums\StatusPesanan::Dibatalkan]))
        <form method="POST" action="{{ route('admin.transaksi.batal', $pesanan) }}" class="mt-4">
            @csrf
            <button class="btn btn-bahaya">Batalkan transaksi</button>
        </form>
    @endif
</div>
@endsection
