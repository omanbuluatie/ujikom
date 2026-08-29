@extends('layouts.loket')
@section('judul', 'Riwayat transaksi')
@section('isi')
<h1 class="font-display mb-2 text-3xl">Riwayat transaksi</h1>
<p class="mb-6 text-sm text-[#3D4C58]">Status tersimpan di database. Muat ulang halaman kapan saja untuk cek progres — tidak perlu menunggu worker sedang aktif.</p>

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
    @include('komponen.kosong', ['judul' => 'Belum ada transaksi', 'teks' => 'Setelah checkout, riwayat dan status muncul di sini.', 'aksi' => 'Mulai dari katalog', 'tautan' => route('katalog')])
@else
    {{-- Tabel riwayat: cepat scan semua transaksi --}}
    <div class="kartu mb-8 overflow-x-auto">
        <table class="tabel-meja">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Total transfer</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($daftar as $t)
                    <tr>
                        <td class="font-mono text-xs">
                            <a href="{{ route('transaksi.show', $t) }}" class="underline">{{ $t->kode_transaksi }}</a>
                        </td>
                        <td class="text-sm whitespace-nowrap">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        <td class="font-mono text-sm">
                            Rp {{ number_format($t->total, 2, ',', '.') }}
                            @if($t->kode_unik)
                                <span class="block text-[10px] text-[#3D4C58]">unik +{{ $t->kode_unik }}</span>
                            @endif
                        </td>
                        <td class="text-sm">{{ $t->metode_pembayaran?->label() ?? '—' }}</td>
                        <td>@include('komponen.tiket-status', ['status' => $t->status])</td>
                        <td class="text-sm">
                            <a href="{{ route('transaksi.show', $t) }}" class="underline">Detail</a>
                            @if($t->status === \App\Enums\StatusTransaksi::Pending)
                                · <a href="{{ route('transaksi.bayar', $t) }}" class="underline">Bayar</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Kartu aksi: upload bayar / resep untuk yang masih aktif --}}
    @php
        $aktif = $daftar->filter(fn ($t) => in_array($t->status, [
            \App\Enums\StatusTransaksi::Pending,
            \App\Enums\StatusTransaksi::Diproses,
        ], true));
    @endphp
    @if($aktif->isNotEmpty())
        <h2 class="font-display mb-3 text-xl">Perlu tindakan</h2>
        <div class="space-y-4">
            @foreach($aktif as $t)
                <article class="kartu overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#e3eaee] px-4 py-3">
                        <a href="{{ route('transaksi.show', $t) }}" class="font-mono text-sm underline">{{ $t->kode_transaksi }}</a>
                        @include('komponen.tiket-status', ['status' => $t->status])
                    </div>
                    <ul class="px-4 py-3 text-sm">
                        @foreach($t->item as $item)
                            <li class="flex justify-between py-1">
                                <span>{{ $item->obat->nama }} × {{ $item->jumlah }}</span>
                                <span class="font-mono">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                            </li>
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
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <div class="mt-6">{{ $daftar->links() }}</div>
@endif
@endsection
