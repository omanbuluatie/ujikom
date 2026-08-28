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
        <p class="text-[11px] uppercase opacity-70">Kode transaksi</p>
        <p class="angka">{{ $transaksi->kode_transaksi }}</p>
        <p class="mt-2 text-sm">Rp {{ number_format($transaksi->total, 2, ',', '.') }}</p>
    </div>
    <div class="p-5">
        <p class="text-sm text-[#3D4C58]">Unggah bukti transfer/pembayaran. Setelah dikirim, worker memverifikasi dan status berubah otomatis (demo) atau admin meninjau bukti.</p>
        <form method="POST" action="{{ route('transaksi.proses-bayar', $transaksi) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="label-lapangan">Metode pembayaran</label>
                <input name="metode_pembayaran" required class="input-lapangan" placeholder="Transfer BCA / QRIS / dll" value="{{ old('metode_pembayaran') }}">
            </div>
            @include('komponen.pilih-berkas', [
                'name' => 'bukti_pembayaran',
                'accept' => 'image/*',
                'required' => true,
                'label' => 'Bukti pembayaran',
                'labelTombol' => 'Pilih bukti transfer',
                'bantuan' => 'Foto/screenshot bukti. Maks 4 MB.',
                'id' => 'bukti-'.$transaksi->id,
            ])
            <button class="btn btn-tiket w-full">Kirim bukti & proses</button>
        </form>
    </div>
</div>
@endsection
