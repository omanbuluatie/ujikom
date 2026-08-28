@extends('layouts.meja')
@section('judul', 'Pemantauan')
@section('subjudul', 'Antrian job · error severity · audit · tautan verifikasi email')
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
<div class="mt-6 kartu overflow-hidden">
    <div class="strip-fifo">Tautan aktivasi email (demo)</div>
    <p class="border-b border-[#e6ecf0] px-4 py-2 text-xs text-[#3D4C58]">
        Tautan tersimpan otomatis saat pasien daftar atau kirim ulang verifikasi. <strong>Penting:</strong> buka tautan saat login sebagai pelanggan yang bersangkutan (logout admin dulu, atau tab/incognito). Alternatif: verifikasi manual di menu Pelanggan.
    </p>
    <div class="max-h-96 overflow-auto">
        <table class="tabel-meja text-sm">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th>Tautan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tautanVerifikasi as $t)
                <tr>
                    <td class="whitespace-nowrap font-mono text-[11px]">{{ $t->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <span class="block font-medium">{{ $t->pengguna?->name ?? '—' }}</span>
                        <span class="font-mono text-[11px] text-[#5a6b76]">{{ $t->email }}</span>
                    </td>
                    <td>
                        @if($t->dipakai_pada)
                            <span class="tiket tiket--selesai">Terpakai</span>
                            <span class="mt-1 block font-mono text-[10px] text-[#5a6b76]">{{ $t->dipakai_pada->format('d.m.Y H:i') }}</span>
                        @elseif($t->masihBerlaku())
                            <span class="tiket tiket--tunggu">Aktif</span>
                        @else
                            <span class="tiket tiket--tolak">Kedaluwarsa</span>
                        @endif
                    </td>
                    <td class="max-w-md">
                        @if($t->masihBerlaku())
                            <a href="{{ $t->tautan }}" target="_blank" rel="noopener" class="btn btn-tiket !py-1 !px-2 text-xs">Buka tautan</a>
                        @else
                            <span class="text-xs text-[#8a9aa6]">Tidak berlaku</span>
                        @endif
                        <details class="mt-1">
                            <summary class="cursor-pointer text-[11px] text-[#0A5C44]">Salin URL</summary>
                            <input type="text" readonly value="{{ $t->tautan }}" class="input-lapangan mt-1 w-full !py-1 font-mono text-[10px]" onclick="this.select(); document.execCommand('copy');">
                        </details>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-[#5a6b76]">Belum ada tautan. Daftarkan pasien baru atau kirim ulang verifikasi.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
