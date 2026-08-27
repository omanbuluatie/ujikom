@extends('layouts.meja')
@section('judul', 'Pemantauan')
@section('subjudul', 'Antrian job · error severity · audit · sesi')
@section('isi')
<div class="grid gap-4 sm:grid-cols-3">
    <div class="papan-antrian"><p class="text-[11px] uppercase opacity-70">Job menunggu</p><p class="angka">{{ $antrian }}</p></div>
    <div class="papan-antrian"><p class="text-[11px] uppercase opacity-70">Job gagal</p><p class="angka">{{ $gagal }}</p></div>
    <div class="papan-antrian"><p class="text-[11px] uppercase opacity-70">Sesi aktif</p><p class="angka">{{ $sesi }}</p></div>
</div>
<div class="mt-6 grid gap-4 lg:grid-cols-2">
    <div class="kartu overflow-hidden">
        <div class="strip-fifo">Log kesalahan</div>
        <ul class="max-h-80 overflow-auto text-sm">
            @foreach($kesalahan as $k)
                <li class="border-b px-4 py-2">
                    <span class="{{ $k->tingkat->kelasTiket() }}">{{ $k->tingkat->labelInggris() }}</span>
                    <span class="ml-2">{{ $k->pesan }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="kartu overflow-hidden">
        <div class="strip-fifo">Audit</div>
        <ul class="max-h-80 overflow-auto text-sm">
            @foreach($audit as $a)
                <li class="border-b px-4 py-2 font-mono text-xs">{{ $a->created_at->format('H:i') }} · {{ $a->pengguna?->name }} · {{ $a->aksi }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
