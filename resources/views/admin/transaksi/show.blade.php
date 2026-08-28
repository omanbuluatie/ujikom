@extends('layouts.meja')
@section('judul', $transaksi->kode_transaksi)
@section('isi')
<div class="kartu max-w-2xl p-6">
    <div class="flex justify-between">
        @include('komponen.tiket-status', ['status' => $transaksi->status])
        <span class="text-sm">{{ $transaksi->sumber }} · {{ $transaksi->pelanggan->name }}</span>
    </div>
    @if($transaksi->alamat_pengiriman)
        <p class="mt-3 text-sm"><span class="font-semibold">Alamat:</span> {{ $transaksi->alamat_pengiriman }}</p>
    @endif
    @if($transaksi->metode_pembayaran)
        <p class="text-sm">Metode: {{ $transaksi->metode_pembayaran->label() }}</p>
    @endif
    <ul class="mt-4 text-sm">
        @foreach($transaksi->item as $item)
            <li class="flex justify-between border-b py-2"><span>{{ $item->obat->nama }} × {{ $item->jumlah }}</span><span class="font-mono">{{ number_format($item->subtotal, 2, ',', '.') }}</span></li>
        @endforeach
    </ul>
    @if($transaksi->kode_unik)
        <p class="mt-4 text-sm text-[#3D4C58]">Subtotal Rp {{ number_format($transaksi->subtotalItem(), 2, ',', '.') }} + kode unik {{ $transaksi->kode_unik }}</p>
    @endif
    <p class="mt-1 font-display text-xl">Total transfer Rp {{ number_format($transaksi->total, 2, ',', '.') }}</p>

    @if($transaksi->bukti_pembayaran)
        <div class="mt-4">
            <p class="label-lapangan">Bukti pembayaran</p>
            <a href="{{ asset('storage/'.$transaksi->bukti_pembayaran) }}" target="_blank">
                <img src="{{ asset('storage/'.$transaksi->bukti_pembayaran) }}" alt="Bukti" class="mt-2 max-h-48 rounded border">
            </a>
        </div>
    @endif

    @if($transaksi->resep)
        <p class="mt-3 text-sm">Resep: {{ $transaksi->resep->status->label() }}</p>
        @if($transaksi->resep->catatan_verifikasi)
            <p class="text-sm text-[#3D4C58]">Catatan verifikasi: {{ $transaksi->resep->catatan_verifikasi }}</p>
        @endif
        <img src="{{ asset('storage/'.$transaksi->resep->berkas_gambar) }}" alt="" class="mt-2 max-h-48">
    @endif

    @if($transaksi->status === \App\Enums\StatusTransaksi::Pending && $transaksi->bukti_pembayaran)
        <div class="mt-4 flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.transaksi.setujui-bayar', $transaksi) }}">
                @csrf
                <button class="btn btn-utama !py-2 text-sm">Setujui pembayaran</button>
            </form>
            <form method="POST" action="{{ route('admin.transaksi.tolak-bayar', $transaksi) }}" class="flex flex-1 flex-wrap gap-2">
                @csrf
                <input name="alasan" required class="input-lapangan min-w-[12rem] flex-1" placeholder="Alasan penolakan">
                <button class="btn btn-bahaya !py-2 text-sm">Tolak pembayaran</button>
            </form>
        </div>
    @endif

    @if(!in_array($transaksi->status, [\App\Enums\StatusTransaksi::Selesai, \App\Enums\StatusTransaksi::Dibatalkan]))
        <form method="POST" action="{{ route('admin.transaksi.batal', $transaksi) }}" class="mt-4">
            @csrf
            <button class="btn btn-bahaya">Batalkan transaksi</button>
        </form>
    @endif
</div>
@endsection
