@extends('layouts.loket')
@section('judul', 'Katalog obat')
@section('isi')
<section class="mb-8">
    <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-[#0A5C44]">Loket apotek · 2.000 jenis terdata</p>
    <h1 class="font-display mt-1 text-3xl md:text-4xl">Ambil nomor, pilih obat</h1>
    <p class="mt-2 max-w-xl text-[#3D4C58]">Cari dengan ejaan yang hampir benar. Strip kuning di kartu adalah batch FIFO — yang masuk lebih dulu keluar lebih dulu.</p>
</section>

<form method="GET" class="kartu mb-8 p-3 md:p-4"
      x-data="{ q: @js($kata), hasil: [], buka: false }"
      @click.outside="buka = false">
    <div class="flex flex-col gap-2 md:flex-row">
        <div class="relative min-w-0 flex-1">
            <label class="label-lapangan" for="q">Cari nama atau kode</label>
            <input id="q" name="q" x-model="q" value="{{ $kata }}" placeholder="Contoh: paracet"
                   autocomplete="off"
                   class="input-lapangan"
                   @input.debounce.300ms="
                        fetch('{{ route('api.obat.autocomplete') }}?q=' + encodeURIComponent(q))
                          .then(r => r.json()).then(d => { hasil = d; buka = d.length > 0 })
                   "
                   @focus="buka = hasil.length > 0">
            <ul x-cloak x-show="buka" class="absolute z-20 mt-1 w-full overflow-hidden bg-white shadow-lg">
                <template x-for="item in hasil" :key="item.id">
                    <li class="border-b border-[#eef2f5]">
                        <a :href="'/katalog/' + item.id" class="flex items-center justify-between px-3 py-2.5 text-sm hover:bg-[#FFD54A]/25">
                            <span><span class="font-mono text-[11px] text-[#0A5C44]" x-text="item.kode"></span> <span x-text="item.nama"></span></span>
                            <span class="font-mono text-xs" x-text="'sisa ' + item.stok"></span>
                        </a>
                    </li>
                </template>
            </ul>
        </div>
        <div class="md:w-40">
            <label class="label-lapangan">Kategori</label>
            <select name="kategori" class="input-lapangan">
                <option value="">Semua</option>
                @foreach($daftarKategori as $k)
                    <option value="{{ $k->id }}" @selected($kategoriId == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:w-32">
            <label class="label-lapangan">Urut</label>
            <select name="urut" class="input-lapangan">
                <option value="nama" @selected(request('urut')==='nama')>Nama</option>
                <option value="harga" @selected(request('urut')==='harga')>Harga</option>
                <option value="kode" @selected(request('urut')==='kode')>Kode</option>
            </select>
        </div>
        <div class="md:w-28">
            <label class="label-lapangan">Arah</label>
            <select name="arah" class="input-lapangan">
                <option value="asc">A–Z</option>
                <option value="desc" @selected(request('arah')==='desc')>Z–A</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-tiket w-full md:w-auto">Cari</button>
        </div>
    </div>
</form>

@if($daftarObat->isEmpty())
    @include('komponen.kosong', ['judul' => 'Tidak ada obat yang cocok', 'teks' => 'Ubah kata kunci atau kosongkan filter kategori.', 'aksi' => 'Reset pencarian', 'tautan' => route('katalog')])
@else
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($daftarObat as $obat)
            <article class="kartu flex flex-col overflow-hidden">
                @include('komponen.strip-fifo', ['obat' => $obat])
                <a href="{{ route('katalog.detail', $obat) }}" class="block aspect-[4/3] bg-[#E6ECF0]">
                    @if($obat->gambar)
                        <img src="{{ asset('storage/'.$obat->gambar) }}" alt="{{ $obat->nama }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center font-mono text-xs text-[#3D4C58]">{{ $obat->kode }}</div>
                    @endif
                </a>
                <div class="flex flex-1 flex-col p-4">
                    <p class="font-mono text-[10px] uppercase tracking-wide text-[#0A5C44]">
                        {{ $obat->kategori->nama }}
                        @if($obat->butuh_resep)<span class="tiket tiket--tolak ml-1">Resep</span>@endif
                    </p>
                    <h2 class="font-display mt-1 text-lg leading-snug"><a href="{{ route('katalog.detail', $obat) }}">{{ $obat->nama }}</a></h2>
                    <p class="mt-auto pt-3 font-mono text-sm">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>
                    @auth
                        <form method="POST" action="{{ route('keranjang.tambah', $obat) }}" class="mt-3">
                            @csrf
                            <button class="btn btn-utama w-full !py-2 text-sm">Masukkan keranjang</button>
                        </form>
                    @else
                        <a href="{{ route('masuk') }}" class="btn btn-senyap mt-3 w-full !py-2 text-sm">Masuk untuk membeli</a>
                    @endauth
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-8">{{ $daftarObat->links() }}</div>
@endif
@endsection
