@extends('layouts.loket')
@section('judul', 'Transaksi saya')
@section('isi')
<h1 class="font-display mb-6 text-3xl">Tiket transaksi</h1>

@if($notifikasi->isNotEmpty())
    <aside class="kartu mb-6 border-l-4 border-[#FFD54A] p-4">
        <p class="label-lapangan">Notifikasi terbaru</p>
        <ul class="mt-2 space-y-2 text-sm">
            @foreach($notifikasi as $n)
                <li>
                    <span class="font-semibold">{{ $n->judul }}</span>
                    <span class="text-[#3D4C58]"> — {{ $n->pesan }}</span>
                    <span class="block font-mono text-[10px] text-[#3D4C58]">{{ $n->created_at->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    </aside>
@endif

@if($daftar->isEmpty())
    @include('komponen.kosong', ['judul' => 'Belum ada transaksi', 'teks' => 'Setelah checkout, kode transaksi dan status muncul di sini.', 'aksi' => 'Mulai dari katalog', 'tautan' => route('katalog')])
@else
    <div class="space-y-4">
        @foreach($daftar as $t)
            <article class="kartu overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#e3eaee] px-4 py-3">
                    <span class="font-mono text-sm">{{ $t->kode_transaksi }}</span>
                    @include('komponen.tiket-status', ['status' => $t->status])
                </div>
                @if($t->alamat_pengiriman)
                    <p class="px-4 pt-3 text-xs text-[#3D4C58]">Kirim ke: {{ $t->alamat_pengiriman }}</p>
                @endif
                <ul class="px-4 py-3 text-sm">
                    @foreach($t->item as $item)
                        <li class="flex justify-between py-1"><span>{{ $item->obat->nama }} × {{ $item->jumlah }}</span><span class="font-mono">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</span></li>
                    @endforeach
                </ul>
                <div class="px-4 pb-4">
                    @if($t->status === \App\Enums\StatusTransaksi::Pending)
                        <a href="{{ route('transaksi.bayar', $t) }}" class="btn btn-tiket !py-2 text-sm">Upload bukti bayar</a>
                    @endif
                    @if($t->butuhResep() && $t->status === \App\Enums\StatusTransaksi::Diproses && (!$t->resep || $t->resep->status === \App\Enums\StatusResep::Ditolak))
                        <form method="POST" action="{{ route('transaksi.resep', $t) }}" enctype="multipart/form-data" class="mt-1 space-y-3">
                            @csrf
                            @include('komponen.pilih-berkas', [
                                'name' => 'berkas',
                                'accept' => 'image/*',
                                'required' => true,
                                'label' => 'Foto resep',
                                'labelTombol' => 'Pilih foto resep',
                                'bantuan' => 'Format JPG/PNG. Maksimal 4 MB.',
                                'id' => 'resep-'.$t->id,
                            ])
                            <button type="submit" class="btn btn-utama !py-2 text-sm">Unggah resep</button>
                        </form>
                    @endif
                    @if($t->resep)
                        <p class="mt-2 text-xs text-[#3D4C58]">Resep: {{ $t->resep->status->label() }}</p>
                    @endif
                    @if($t->kode_unik)
                        <p class="px-4 text-xs text-[#3D4C58]">
                            Total transfer: <span class="font-mono">Rp {{ number_format($t->total, 2, ',', '.') }}</span>
                            (kode unik +{{ $t->kode_unik }})
                        </p>
                    @endif
                    @if($t->metode_pembayaran)
                        <p class="mt-1 px-4 pb-1 text-xs text-[#3D4C58]">Bayar via {{ $t->metode_pembayaran->label() }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-6">{{ $daftar->links() }}</div>
@endif
@endsection
