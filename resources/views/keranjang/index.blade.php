@extends('layouts.loket')
@section('judul', 'Keranjang')
@section('isi')
<h1 class="font-display mb-6 text-3xl">Keranjang</h1>
@if($rincian->isEmpty())
    @include('komponen.kosong', ['judul' => 'Keranjang masih kosong', 'teks' => 'Pilih obat dari katalog, lalu kembali ke sini untuk checkout.', 'aksi' => 'Buka katalog', 'tautan' => route('katalog')])
@else
    <div class="overflow-x-auto">
        <table class="tabel-meja">
            <thead><tr><th>Obat</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
            <tbody>
            @foreach($rincian as $baris)
                <tr>
                    <td>
                        <p class="font-medium">{{ $baris['obat']->nama }}</p>
                        <p class="font-mono text-[11px] text-[#3D4C58]">{{ $baris['obat']->kode }} @if($baris['obat']->butuh_resep) · resep @endif</p>
                    </td>
                    <td class="font-mono">Rp {{ number_format($baris['obat']->harga, 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('keranjang.ubah', $baris['obat']) }}">
                            @csrf @method('PATCH')
                            <input type="number" name="jumlah" value="{{ $baris['jumlah'] }}" min="0" class="input-lapangan w-20" onchange="this.form.submit()" aria-label="Ubah jumlah">
                        </form>
                    </td>
                    <td class="font-mono">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <p class="font-display text-2xl">Total Rp {{ number_format($total, 0, ',', '.') }}</p>
        <a href="{{ route('pesanan.checkout') }}" class="btn btn-tiket">Lanjut checkout</a>
    </div>
@endif
@endsection
