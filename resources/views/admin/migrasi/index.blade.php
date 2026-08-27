@extends('layouts.meja')
@section('judul', 'Migrasi Excel → sistem')
@section('subjudul', 'Checklist cutover · impor CSV paralel · rollback per batch')
@section('isi')
<div class="grid gap-6 lg:grid-cols-2">
    <div class="kartu p-5">
        <p class="label-lapangan">Checklist sebelum go-live</p>
        <ul class="list-disc space-y-1 pl-4 text-sm">
            <li>Cadangkan Excel lama</li>
            <li>Mapping kolom sudah ditinjau</li>
            <li>Worker antrian menyala</li>
            <li>Akun admin & apoteker siap</li>
        </ul>
        <p class="label-lapangan mt-5">Mapping field</p>
        <table class="tabel-meja text-xs">
            @foreach($mapping as $asal => $tujuan)
                <tr><td class="font-mono">{{ $asal }}</td><td>{{ $tujuan }}</td></tr>
            @endforeach
        </table>
    </div>
    <div class="kartu p-5">
        <form method="POST" action="{{ route('admin.migrasi.impor') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @include('komponen.pilih-berkas', [
                'name' => 'berkas',
                'accept' => '.csv,text/csv',
                'required' => true,
                'label' => 'Berkas CSV (asal Excel)',
                'labelTombol' => 'Pilih file CSV',
                'bantuan' => 'Gunakan header sesuai mapping. Contoh: dokumentasi/contoh-data/obat-lama.csv',
                'id' => 'csv-migrasi',
            ])
            <button type="submit" class="btn btn-utama">Impor ke antrian</button>
        </form>
        <form method="POST" action="{{ route('admin.migrasi.rollback') }}" class="mt-6 space-y-2">
            @csrf
            <label class="label-lapangan">Rollback batch</label>
            <select name="batch_migrasi_id" class="input-lapangan" required>
                @foreach($batch as $id)
                    <option value="{{ $id }}">{{ $id }}</option>
                @endforeach
            </select>
            <button class="btn btn-bahaya" onclick="return confirm('Hapus data hasil batch ini?')">Rollback</button>
        </form>
    </div>
</div>
<div class="mt-6 overflow-x-auto">
    <table class="tabel-meja">
        <thead><tr><th>Batch</th><th>Baris</th><th>Kode</th><th>Status</th><th>Pesan</th></tr></thead>
        <tbody>
        @foreach($log as $l)
            <tr>
                <td class="font-mono text-[11px]">{{ $l->batch_migrasi_id }}</td>
                <td>{{ $l->baris_ke }}</td>
                <td class="font-mono">{{ $l->kode_obat }}</td>
                <td>{{ $l->status }}</td>
                <td>{{ $l->pesan }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
