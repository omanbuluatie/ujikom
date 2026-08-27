@props(['obat'])
@php
    $batch = $obat->batchTertua();
    $stok = $obat->stok_total;
    $kritis = $stok <= $obat->stok_minimum;
@endphp
<div class="strip-fifo">
    <span>FIFO {{ $batch?->tanggal_masuk?->format('d.m.Y') ?? '—' }}</span>
    <span class="{{ $kritis ? 'font-bold' : '' }}">sisa {{ $stok }}@if($kritis) · min {{ $obat->stok_minimum }}@endif</span>
</div>
