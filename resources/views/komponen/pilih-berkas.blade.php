@props([
    'name' => 'berkas',
    'accept' => 'image/*',
    'required' => false,
    'label' => null,
    'labelTombol' => 'Pilih file',
    'bantuan' => null,
    'id' => null,
])
@php
    $idInput = $id ?? ('berkas-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6)));
@endphp
{{-- Komponen tunggal untuk semua input file (resep, gambar obat, CSV migrasi). --}}
<div class="zona-unggah">
    @if($label)
        <p class="label-lapangan !mb-1">{{ $label }}@if($required) <span class="text-[#C23B22]">*</span>@endif</p>
    @endif
    @if($bantuan)
        <p class="mb-2 text-xs text-[#3D4C58]">{{ $bantuan }}</p>
    @endif
    <div class="berkas-pilih" x-data="{ nama: '' }">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $idInput }}"
            accept="{{ $accept }}"
            @if($required) required @endif
            {{ $attributes }}
            @change="nama = $event.target.files[0]?.name || ''"
        >
        <label for="{{ $idInput }}" class="tombol-pilih">{{ $labelTombol }}</label>
        <span class="nama-berkas" :class="nama ? 'ada' : ''" x-text="nama || 'Belum ada file dipilih'"></span>
    </div>
</div>
