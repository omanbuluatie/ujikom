@extends('layouts.loket')
@section('judul', 'Checkout')
@section('isi')
<h1 class="font-display mb-2 text-3xl">Konfirmasi transaksi</h1>
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
            <span class="font-mono">Rp {{ number_format($baris['subtotal'], 2, ',', '.') }}</span>
        </li>
    @endforeach
    <li class="flex justify-between px-4 py-4 font-display text-lg">
        <span>Total</span><span>Rp {{ number_format($total, 2, ',', '.') }}</span>
    </li>
</ul>
<p class="mt-3 text-sm text-[#3D4C58]">Saat checkout, sistem menambahkan <strong>kode unik 3 digit</strong> ke total transfer agar pembayaran mudah diverifikasi.</p>
<form method="POST" action="{{ route('transaksi.buat') }}" class="mt-6 space-y-4">
    @csrf
    <div>
        <label class="label-lapangan">Alamat pengiriman</label>
        <textarea name="alamat_pengiriman" required class="input-lapangan" rows="2" placeholder="Jl. ..., Kota, Kode pos">{{ old('alamat_pengiriman', auth()->user()->alamat) }}</textarea>
    </div>
    <div>
        <label class="label-lapangan">Catatan untuk apoteker</label>
        <textarea name="catatan" class="input-lapangan" rows="2" placeholder="Opsional">{{ old('catatan') }}</textarea>
    </div>
    <button class="btn btn-utama">Buat transaksi & bayar</button>
</form>
@endsection
