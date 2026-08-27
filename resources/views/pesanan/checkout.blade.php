@extends('layouts.loket')
@section('judul', 'Checkout')
@section('isi')
<h1 class="font-display mb-2 text-3xl">Konfirmasi pesanan</h1>
<ol class="langkah mb-6">
    <li class="done">Keranjang</li>
    <li class="now">Checkout</li>
    <li>Bayar</li>
    <li>Resep / FIFO</li>
</ol>
<ul class="kartu divide-y divide-[#e3eaee]">
    @foreach($rincian as $baris)
        <li class="flex justify-between px-4 py-3 text-sm">
            <span>{{ $baris['obat']->nama }} × {{ $baris['jumlah'] }}</span>
            <span class="font-mono">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</span>
        </li>
    @endforeach
    <li class="flex justify-between px-4 py-4 font-display text-lg">
        <span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span>
    </li>
</ul>
<form method="POST" action="{{ route('pesanan.buat') }}" class="mt-6">
    @csrf
    <label class="label-lapangan">Catatan untuk apoteker</label>
    <textarea name="catatan" class="input-lapangan mb-4" rows="2" placeholder="Opsional"></textarea>
    <button class="btn btn-utama">Buat tiket pesanan & bayar</button>
</form>
@endsection
