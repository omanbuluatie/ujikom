@extends('layouts.meja')
@section('judul', 'Laci stok FIFO')
@section('subjudul', 'Setiap laci = satu obat, strip kuning = batch yang akan keluar berikutnya')
@section('isi')
<div class="space-y-3">
    @foreach($daftar as $obat)
        <article class="kartu overflow-hidden">
            @include('komponen.strip-fifo', ['obat' => $obat])
            <div class="px-4 py-3">
                <div class="flex flex-wrap items-baseline justify-between gap-2">
                    <h2 class="font-display text-lg">{{ $obat->nama }}</h2>
                    <span class="font-mono text-xs">{{ $obat->kode }}</span>
                </div>
                <div class="mt-2 flex flex-wrap gap-1">
                    @foreach($obat->batch->sortBy('tanggal_masuk') as $i => $b)
                        <span class="font-mono text-[11px] px-2 py-1 {{ $i === 0 && $b->sisa > 0 ? 'bg-[#FFD54A]' : 'bg-[#E6ECF0]' }}">
                            {{ $b->tanggal_masuk->format('d.m') }} · {{ $b->sisa }} · exp {{ $b->tanggal_kedaluwarsa->format('d.m.y') }}
                        </span>
                    @endforeach
                </div>
            </div>
        </article>
    @endforeach
</div>
<div class="mt-6">{{ $daftar->links() }}</div>
@endsection
