@extends('layouts.meja')
@section('judul', 'Papan antrian')
@section('subjudul', 'Polling 10 detik · penjualan · stok kritis · peringatan')
@section('isi')
<div class="grid gap-4 md:grid-cols-3">
    <div class="papan-antrian md:col-span-2">
        <p class="text-[11px] uppercase opacity-70">Pendapatan hari ini</p>
        <p class="angka">Rp {{ number_format($ringkas['harian'], 0, ',', '.') }}</p>
        <p class="mt-3 text-xs opacity-80">{{ $ringkas['transaksi_hari_ini'] }} tiket terbayar · minggu Rp {{ number_format($ringkas['mingguan'], 0, ',', '.') }} · bulan Rp {{ number_format($ringkas['bulanan'], 0, ',', '.') }}</p>
    </div>
    <div class="kartu p-4">
        <p class="label-lapangan">Obat terlaris</p>
        <ol class="space-y-2 text-sm">
            @forelse($terlaris as $baris)
                <li class="flex justify-between"><span>{{ $baris->obat?->nama }}</span><span class="font-mono">{{ $baris->total_terjual }}</span></li>
            @empty
                <li class="text-[#3D4C58]">Belum ada penjualan.</li>
            @endforelse
        </ol>
    </div>
</div>

<div class="mt-6 kartu p-4">
    <p class="label-lapangan">14 hari terakhir</p>
    @php $maks = max(1, $grafik->max('pendapatan') ?? 1); @endphp
    <div class="flex h-40 items-end gap-1">
        @forelse($grafik as $hari)
            <div class="flex flex-1 flex-col items-center gap-1">
                <div class="w-full bg-[#FFD54A]" style="height: {{ max(6, ($hari->pendapatan / $maks) * 100) }}%"></div>
                <span class="font-mono text-[9px] text-[#3D4C58]">{{ \Illuminate\Support\Carbon::parse($hari->tanggal)->format('d') }}</span>
            </div>
        @empty
            <p class="text-sm text-[#3D4C58]">Belum ada data grafik.</p>
        @endforelse
    </div>
</div>

<div class="mt-6 grid gap-4 lg:grid-cols-2">
    <div class="kartu overflow-hidden">
        <div class="strip-fifo">Stok ≤ minimum</div>
        <ul class="divide-y divide-[#e3eaee] text-sm">
            @forelse($stokKritis as $obat)
                <li class="flex justify-between px-4 py-2"><span>{{ $obat->nama }}</span><span class="font-mono text-[#C23B22]">{{ $obat->batch_sum_sisa }}</span></li>
            @empty
                <li class="px-4 py-3 text-[#3D4C58]">Tidak ada stok kritis.</li>
            @endforelse
        </ul>
    </div>
    <div class="kartu overflow-hidden">
        <div class="strip-fifo">Peringatan (live)</div>
        <ul class="divide-y divide-[#e3eaee] text-sm">
            @forelse($peringatan as $p)
                <li class="px-4 py-2">
                    <span class="{{ $p->tingkat->kelasTiket() }}">{{ $p->tingkat->labelInggris() }}</span>
                    <span class="ml-2">{{ $p->judul }}</span>
                </li>
            @empty
                <li class="px-4 py-3 text-[#3D4C58]">Belum ada peringatan.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
