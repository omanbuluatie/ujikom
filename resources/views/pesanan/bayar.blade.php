@extends('layouts.loket')
@section('judul', 'Bayar')
@section('isi')
<ol class="langkah mb-6">
    <li class="done">Keranjang</li>
    <li class="done">Checkout</li>
    <li class="now">Bayar</li>
    <li>Resep / FIFO</li>
</ol>
<div class="kartu max-w-lg overflow-hidden">
    <div class="papan-antrian">
        <p class="text-[11px] uppercase opacity-70">Nomor tiket</p>
        <p class="angka">{{ $pesanan->nomor }}</p>
        <p class="mt-2 text-sm">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
    </div>
    <div class="p-5">
        <p class="text-sm text-[#3D4C58]">Pembayaran disimulasikan lewat antrian pekerjaan. Tidak ada gateway. Setelah Anda menekan tombol, worker memproses konfirmasi.</p>
        <form method="POST" action="{{ route('pesanan.proses-bayar', $pesanan) }}" class="mt-5">
            @csrf
            <button class="btn btn-tiket w-full">Bayar sekarang</button>
        </form>
    </div>
</div>
@endsection
