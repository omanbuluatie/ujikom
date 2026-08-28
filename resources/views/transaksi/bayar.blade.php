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
        <dl class="mt-3 space-y-1 text-sm">
            <div class="flex justify-between opacity-80">
                <dt>Subtotal obat</dt>
                <dd class="font-mono">Rp {{ number_format($transaksi->subtotalItem(), 2, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between opacity-80">
                <dt>Kode unik</dt>
                <dd class="font-mono">+ {{ $transaksi->kode_unik }}</dd>
            </div>
            <div class="flex justify-between border-t border-white/20 pt-2 font-semibold">
                <dt>Total transfer</dt>
                <dd class="font-mono text-base">Rp {{ number_format($transaksi->total, 2, ',', '.') }}</dd>
            </div>
        </dl>
        <p class="mt-2 text-[11px] opacity-70">Transfer tepat sesuai nominal di atas (termasuk 3 digit kode unik).</p>
    </div>
    <div class="p-5">
        <p class="text-sm text-[#3D4C58]">Unggah bukti transfer/pembayaran. Setelah dikirim, worker memverifikasi dan status berubah otomatis (demo) atau admin meninjau bukti.</p>
        <form method="POST" action="{{ route('transaksi.proses-bayar', $transaksi) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="label-lapangan" for="metode_pembayaran">Metode pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" required class="input-lapangan">
                    <option value="" disabled @selected(! old('metode_pembayaran'))>— Pilih metode —</option>
                    @foreach(\App\Enums\MetodePembayaran::cases() as $metode)
                        <option value="{{ $metode->value }}" @selected(old('metode_pembayaran') === $metode->value)>{{ $metode->label() }}</option>
                    @endforeach
                </select>
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
