@extends('layouts.meja')
@section('judul', 'Antrian resep')
@section('subjudul', 'Setujui atau tolak foto resep sebelum stok FIFO dipotong')
@section('isi')
<div class="space-y-4">
    @forelse($antrian as $resep)
        <article class="kartu overflow-hidden md:grid md:grid-cols-[16rem_1fr]">
            <a href="{{ asset('storage/'.$resep->berkas_gambar) }}" target="_blank" class="block bg-[#E6ECF0]">
                <img src="{{ asset('storage/'.$resep->berkas_gambar) }}" alt="Resep {{ $resep->pesanan->nomor }}" class="h-48 w-full object-cover md:h-full">
            </a>
            <div class="p-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="font-mono text-sm">{{ $resep->pesanan->nomor }}</span>
                    @include('komponen.tiket-status', ['status' => $resep->pesanan->status])
                </div>
                <p class="mt-1 text-sm">{{ $resep->pesanan->pelanggan->name }} · {{ $resep->pesanan->pelanggan->email }}</p>
                <ul class="mt-3 text-sm">
                    @foreach($resep->pesanan->item as $item)
                        <li>{{ $item->obat->nama }} × {{ $item->jumlah }} @if($item->obat->butuh_resep)<span class="tiket tiket--tolak">resep</span>@endif</li>
                    @endforeach
                </ul>
                @if($resep->status === \App\Enums\StatusResep::Menunggu)
                    <form method="POST" action="{{ route('apoteker.resep.putuskan', $resep) }}" class="mt-4 space-y-2">
                        @csrf
                        <input name="catatan_apoteker" class="input-lapangan" placeholder="Catatan (wajib jika menolak)">
                        <div class="flex gap-2">
                            <button name="keputusan" value="disetujui" class="btn btn-utama !py-2 text-sm">Setujui & potong stok</button>
                            <button name="keputusan" value="ditolak" class="btn btn-bahaya !py-2 text-sm">Tolak</button>
                        </div>
                    </form>
                @else
                    <p class="mt-3 text-sm">Keputusan: {{ $resep->status->label() }} — {{ $resep->catatan_apoteker }}</p>
                @endif
            </div>
        </article>
    @empty
        @include('komponen.kosong', ['judul' => 'Tidak ada resep menunggu', 'teks' => 'Pesanan obat keras akan muncul di sini setelah pasien unggah foto resep.'])
    @endforelse
</div>
{{ $antrian->links() }}
@endsection
