@extends('layouts.meja')
@section('judul', $obat->exists ? 'Ubah obat' : 'Obat baru')
@section('subjudul', 'Gambar diunggah ke storage publik')
@section('isi')
<form method="POST" action="{{ $obat->exists ? route('admin.obat.update', $obat) : route('admin.obat.store') }}" enctype="multipart/form-data" class="kartu max-w-xl space-y-4 p-6">
    @csrf
    @if($obat->exists) @method('PUT') @endif
    <div><label class="label-lapangan">Kode</label><input name="kode" value="{{ old('kode', $obat->kode) }}" required class="input-lapangan"></div>
    <div><label class="label-lapangan">Nama</label><input name="nama" value="{{ old('nama', $obat->nama) }}" required class="input-lapangan"></div>
    <div>
        <label class="label-lapangan">Kategori</label>
        <select name="kategori_obat_id" class="input-lapangan" required>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_obat_id', $obat->kategori_obat_id)==$k->id)>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label-lapangan">Pemasok</label>
        <select name="pemasok_id" class="input-lapangan">
            <option value="">—</option>
            @foreach($pemasok as $p)
                <option value="{{ $p->id }}" @selected(old('pemasok_id', $obat->pemasok_id)==$p->id)>{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div><label class="label-lapangan">Harga</label><input type="number" name="harga" value="{{ old('harga', $obat->harga) }}" required class="input-lapangan"></div>
        <div><label class="label-lapangan">Stok minimum</label><input type="number" name="stok_minimum" value="{{ old('stok_minimum', $obat->stok_minimum ?? 10) }}" required class="input-lapangan"></div>
    </div>
    @unless($obat->exists)
        <div class="grid grid-cols-2 gap-3">
            <div><label class="label-lapangan">Stok awal</label><input type="number" name="stok_awal" value="{{ old('stok_awal', 0) }}" class="input-lapangan"></div>
            <div><label class="label-lapangan">Kedaluwarsa batch</label><input type="date" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa', now()->addYear()->toDateString()) }}" class="input-lapangan"></div>
        </div>
    @endunless
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="butuh_resep" value="1" @checked(old('butuh_resep', $obat->butuh_resep))> Wajib resep</label>
    <div><label class="label-lapangan">Deskripsi</label><textarea name="deskripsi" class="input-lapangan" rows="3">{{ old('deskripsi', $obat->deskripsi) }}</textarea></div>
    <div>
        @include('komponen.pilih-berkas', [
            'name' => 'gambar',
            'accept' => 'image/*',
            'required' => false,
            'label' => 'Gambar obat',
            'labelTombol' => 'Pilih gambar obat',
            'bantuan' => 'Opsional. Format JPG/PNG. Maksimal 2 MB.',
            'id' => 'gambar-obat',
        ])
        @if($obat->gambar)
            <p class="mt-2 text-xs text-[#3D4C58]">Gambar saat ini:</p>
            <img src="{{ asset('storage/'.$obat->gambar) }}" alt="{{ $obat->nama }}" class="mt-1 h-24 object-cover">
        @endif
    </div>
    <button class="btn btn-utama">Simpan</button>
</form>
@endsection
