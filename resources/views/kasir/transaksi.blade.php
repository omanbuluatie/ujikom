@extends('layouts.meja')
@section('judul', 'Kasir counter')
@section('subjudul', 'Stok dipotong FIFO di tempat — sama dengan pesanan online')
@section('isi')
<div class="grid gap-6 lg:grid-cols-[1fr_20rem]" x-data="{
    cari: '',
    total: 0,
    saring() {
        const q = this.cari.toLowerCase();
        document.querySelectorAll('[data-nama]').forEach(tr => {
            tr.style.display = tr.dataset.nama.includes(q) ? '' : 'none';
        });
    },
    hitung() {
        let t = 0;
        document.querySelectorAll('tbody tr').forEach(tr => {
            const c = tr.querySelector('input[type=checkbox]');
            if (!c || !c.checked) return;
            const harga = Number(tr.querySelector('[data-harga]')?.dataset.harga || 0);
            const jml = Number(tr.querySelector('input[type=number]')?.value || 0);
            t += harga * jml;
        });
        this.total = t;
    }
}">
    <div class="kartu p-4">
        <label class="label-lapangan" for="cari-kasir">Cari obat di laci</label>
        <input id="cari-kasir" class="input-lapangan" placeholder="Nama atau kode" x-model="cari" @input="saring()">
        <form method="POST" action="{{ route('kasir.transaksi.simpan') }}" class="mt-4">
            @csrf
            <div class="overflow-x-auto">
                <table class="tabel-meja">
                    <thead><tr><th></th><th>Obat</th><th>Sisa</th><th>Harga</th><th>Jml</th></tr></thead>
                    <tbody>
                        @foreach($daftarObat as $obat)
                            <tr data-nama="{{ Str::lower($obat->nama.' '.$obat->kode) }}">
                                <td><input type="checkbox" name="pilih[]" value="{{ $obat->id }}" @change="hitung()"></td>
                                <td>
                                    <p>{{ $obat->nama }}</p>
                                    <p class="font-mono text-[11px] text-[#3D4C58]">{{ $obat->kode }}</p>
                                </td>
                                <td class="font-mono {{ ($obat->batch_sum_sisa ?? 0) <= $obat->stok_minimum ? 'text-[#C23B22] font-semibold' : '' }}">{{ $obat->batch_sum_sisa ?? 0 }}</td>
                                <td class="font-mono" data-harga="{{ $obat->harga }}">{{ number_format($obat->harga, 0, ',', '.') }}</td>
                                <td><input type="number" name="jumlah[{{ $obat->id }}]" value="1" min="1" class="input-lapangan w-16" @input="hitung()"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-tiket mt-4">Simpan & potong stok FIFO</button>
        </form>
    </div>
    <aside class="kartu h-fit overflow-hidden">
        <div class="papan-antrian">
            <p class="text-[11px] uppercase opacity-70">Struk sementara</p>
            <p class="angka text-2xl" x-text="'Rp ' + total.toLocaleString('id-ID')">Rp 0</p>
        </div>
        <p class="p-4 text-xs leading-relaxed text-[#3D4C58]">Centang obat, isi jumlah. Setelah simpan, batch tertua berkurang — katalog online ikut berubah.</p>
    </aside>
</div>
@endsection
