@extends('layouts.loket')
@section('judul', $transaksi->kode_transaksi)
@section('isi')
<p class="mb-4"><a href="{{ route('transaksi.index') }}" class="text-sm underline">← Kembali ke riwayat</a></p>

<div class="kartu max-w-2xl overflow-hidden">
    <div class="papan-antrian">
        <p class="text-[11px] uppercase opacity-70">Kode transaksi</p>
        <p class="angka">{{ $transaksi->kode_transaksi }}</p>
        <p class="mt-2">@include('komponen.tiket-status', ['status' => $transaksi->status])</p>
        <p class="mt-2 text-sm opacity-80">{{ $transaksi->created_at->format('d M Y H:i') }}</p>
    </div>

    <div class="p-5 space-y-6">
        {{-- Timeline progres: dibaca dari status di DB --}}
        @php
            $urutan = [
                'pending' => 'Pending / menunggu bayar',
                'diproses' => 'Diproses / obat dikemas',
                'selesai' => 'Selesai',
            ];
            $nilai = $transaksi->status->value;
            $batal = $transaksi->status === \App\Enums\StatusTransaksi::Dibatalkan;
            $indeksSekarang = match ($nilai) {
                'pending' => 0,
                'diproses' => 1,
                'selesai' => 2,
                default => -1,
            };
        @endphp
        <div>
            <p class="label-lapangan">Progres</p>
            @if($batal)
                <p class="mt-2 text-sm text-[#C23B22]">Transaksi dibatalkan. @if($transaksi->catatan) Alasan: {{ $transaksi->catatan }} @endif</p>
            @else
                <ol class="langkah mt-2">
                    @foreach($urutan as $kunci => $label)
                        @php
                            $i = array_search($kunci, array_keys($urutan));
                            $kelas = $i < $indeksSekarang ? 'done' : ($i === $indeksSekarang ? 'now' : '');
                        @endphp
                        <li class="{{ $kelas }}">{{ $label }}</li>
                    @endforeach
                </ol>
            @endif
        </div>

        @if($transaksi->alamat_pengiriman)
            <div>
                <p class="label-lapangan">Alamat pengiriman</p>
                <p class="text-sm">{{ $transaksi->alamat_pengiriman }}</p>
            </div>
        @endif

        <div>
            <p class="label-lapangan">Item obat</p>
            <ul class="mt-1 divide-y divide-[#e3eaee] text-sm">
                @foreach($transaksi->item as $item)
                    <li class="flex justify-between py-2">
                        <span>{{ $item->obat->nama }} × {{ $item->jumlah }}</span>
                        <span class="font-mono">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            @if($transaksi->kode_unik)
                <p class="mt-2 flex justify-between text-sm text-[#3D4C58]">
                    <span>Kode unik</span>
                    <span class="font-mono">+{{ $transaksi->kode_unik }}</span>
                </p>
            @endif
            <p class="mt-2 flex justify-between font-display text-lg">
                <span>Total transfer</span>
                <span>Rp {{ number_format($transaksi->total, 2, ',', '.') }}</span>
            </p>
            @if($transaksi->metode_pembayaran)
                <p class="mt-1 text-xs text-[#3D4C58]">Metode: {{ $transaksi->metode_pembayaran->label() }}</p>
            @endif
            @if($transaksi->dibayar_pada)
                <p class="text-xs text-[#3D4C58]">Dibayar: {{ $transaksi->dibayar_pada->format('d/m/Y H:i') }}</p>
            @endif
        </div>

        @if($transaksi->resep)
            <div>
                <p class="label-lapangan">Resep</p>
                <p class="text-sm">Status: {{ $transaksi->resep->status->label() }}</p>
                @if($transaksi->resep->catatan_verifikasi)
                    <p class="text-sm text-[#3D4C58]">Catatan verifikasi: {{ $transaksi->resep->catatan_verifikasi }}</p>
                @endif
            </div>
        @endif

        @if($transaksi->notifikasi->isNotEmpty())
            <div>
                <p class="label-lapangan">Riwayat notifikasi transaksi ini</p>
                <ul class="mt-2 space-y-2 text-sm">
                    @foreach($transaksi->notifikasi->sortByDesc('created_at') as $n)
                        <li class="border-b border-[#e3eaee] pb-2">
                            <span class="font-semibold">{{ $n->judul }}</span>
                            <span class="text-[#3D4C58]"> — {{ $n->pesan }}</span>
                            <span class="block font-mono text-[10px] text-[#3D4C58]">{{ $n->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            @if($transaksi->status === \App\Enums\StatusTransaksi::Pending)
                <a href="{{ route('transaksi.bayar', $transaksi) }}" class="btn btn-tiket !py-2 text-sm">Upload bukti bayar</a>
            @endif
            <a href="{{ route('transaksi.index') }}" class="btn btn-utama !py-2 text-sm">Semua riwayat</a>
        </div>
    </div>
</div>
@endsection
