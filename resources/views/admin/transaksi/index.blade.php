@extends('layouts.meja')
@section('judul', 'Transaksi')
@section('isi')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <form method="GET" class="flex gap-2">
        <select name="status" class="input-lapangan max-w-xs" onchange="this.form.submit()">
            <option value="">Semua status</option>
            @foreach(\App\Enums\StatusTransaksi::cases() as $s)
                <option value="{{ $s->value }}" @selected(request('status')===$s->value)>{{ $s->label() }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('admin.transaksi.ekspor-csv', request()->query()) }}" class="btn btn-utama !py-2 text-sm">Ekspor CSV</a>
</div>
<table class="tabel-meja">
    <thead><tr><th>Kode</th><th>Pelanggan</th><th>Sumber</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $t)
        <tr>
            <td class="font-mono text-xs"><a class="underline" href="{{ route('admin.transaksi.show', $t) }}">{{ $t->kode_transaksi }}</a></td>
            <td>{{ $t->pelanggan->name }}</td>
            <td>{{ $t->sumber }}</td>
            <td class="font-mono">{{ number_format($t->total, 2, ',', '.') }}</td>
            <td>@include('komponen.tiket-status', ['status' => $t->status])</td>
            <td><a href="{{ route('admin.transaksi.show', $t) }}" class="text-sm underline">Detail</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $daftar->links() }}
@endsection
