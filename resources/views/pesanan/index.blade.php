@extends('layouts.loket')
@section('judul', 'Pesanan saya')
@section('isi')
<h1 class="font-display mb-6 text-3xl">Tiket pesanan</h1>
@if($daftar->isEmpty())
    @include('komponen.kosong', ['judul' => 'Belum ada pesanan', 'teks' => 'Setelah checkout, nomor tiket dan status muncul di sini.', 'aksi' => 'Mulai dari katalog', 'tautan' => route('katalog')])
@else
    <div class="space-y-4">
        @foreach($daftar as $p)
            <article class="kartu overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#e3eaee] px-4 py-3">
                    <span class="font-mono text-sm">{{ $p->nomor }}</span>
                    @include('komponen.tiket-status', ['status' => $p->status])
                </div>
                <ul class="px-4 py-3 text-sm">
                    @foreach($p->item as $item)
                        <li class="flex justify-between py-1"><span>{{ $item->obat->nama }} × {{ $item->jumlah }}</span><span class="font-mono">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span></li>
                    @endforeach
                </ul>
                <div class="px-4 pb-4">
                    @if($p->status === \App\Enums\StatusPesanan::MenungguBayar)
                        <a href="{{ route('pesanan.bayar', $p) }}" class="btn btn-tiket !py-2 text-sm">Lanjut bayar</a>
                    @endif
                    @if($p->status === \App\Enums\StatusPesanan::MenungguResep)
                        <form method="POST" action="{{ route('pesanan.resep', $p) }}" enctype="multipart/form-data" class="mt-1 space-y-3">
                            @csrf
                            @include('komponen.pilih-berkas', [
                                'name' => 'berkas',
                                'accept' => 'image/*',
                                'required' => true,
                                'label' => 'Foto resep',
                                'labelTombol' => 'Pilih foto resep',
                                'bantuan' => 'Format JPG/PNG. Maksimal 4 MB.',
                                'id' => 'resep-'.$p->id,
                            ])
                            <button type="submit" class="btn btn-utama !py-2 text-sm">Unggah resep</button>
                        </form>
                    @endif
                    @if($p->resep)
                        <p class="mt-2 text-xs text-[#3D4C58]">Resep: {{ $p->resep->status->label() }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-6">{{ $daftar->links() }}</div>
@endif
@endsection
