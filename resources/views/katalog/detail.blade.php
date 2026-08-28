@extends('layouts.loket')
@section('judul', $obat->nama)
@section('isi')
<a href="{{ route('katalog') }}" class="mb-6 inline-block text-sm text-[#3D4C58] hover:underline">← Kembali ke katalog</a>
<div class="grid gap-8 md:grid-cols-2">
    <div class="kartu overflow-hidden">
        @include('komponen.strip-fifo', ['obat' => $obat])
        @if($obat->gambar)
            <img src="{{ asset('storage/'.$obat->gambar) }}" alt="{{ $obat->nama }}" class="w-full">
        @else
            <div class="flex aspect-square items-center justify-center bg-[#E6ECF0] font-mono text-sm">{{ $obat->kode }}</div>
        @endif
    </div>
    <div>
        <p class="font-mono text-xs uppercase tracking-wider text-[#0A5C44]">{{ $obat->kode }} · {{ $obat->kategori->nama }}</p>
        <h1 class="font-display mt-1 text-3xl">{{ $obat->nama }}</h1>
        @if($obat->butuh_resep)
            <p class="mt-2"><span class="tiket tiket--tolak">Wajib resep</span> unggah foto resep setelah bayar.</p>
        @endif
        <p class="mt-4 font-display text-3xl">Rp {{ number_format($obat->harga, 2, ',', '.') }}</p>
        <p class="mt-4 text-sm leading-relaxed text-[#3D4C58]">{{ $obat->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}</p>

        <h2 class="mt-8 font-mono text-[11px] uppercase tracking-widest text-[#3D4C58]">Urutan keluar batch</h2>
        <ol class="mt-2 space-y-1">
            @forelse($obat->batch->sortBy('tanggal_masuk') as $i => $b)
                <li class="flex items-center justify-between bg-white px-3 py-2 text-sm {{ $i === 0 && $b->sisa > 0 ? 'outline outline-2 outline-[#FFD54A]' : '' }}">
                    <span class="font-mono">{{ $b->tanggal_masuk->format('d.m.Y') }} → exp {{ $b->tanggal_kedaluwarsa->format('d.m.Y') }}</span>
                    <span>{{ $b->sisa }} unit</span>
                </li>
            @empty
                <li class="text-sm text-[#C23B22]">Belum ada batch. Stok kosong.</li>
            @endforelse
        </ol>

        @auth
            <form method="POST" action="{{ route('keranjang.tambah', $obat) }}" class="mt-8 flex gap-2">
                @csrf
                <input type="number" name="jumlah" value="1" min="1" class="input-lapangan w-24" aria-label="Jumlah">
                <button class="btn btn-utama">Masukkan keranjang</button>
            </form>
        @else
            <a href="{{ route('masuk') }}" class="btn btn-utama mt-8">Masuk untuk membeli</a>
        @endauth
    </div>
</div>
@endsection
