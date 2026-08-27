@extends('layouts.meja')
@section('judul', 'Transaksi')
@section('isi')
<form method="GET" class="mb-4">
    <select name="status" class="input-lapangan max-w-xs" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach(\App\Enums\StatusPesanan::cases() as $s)
            <option value="{{ $s->value }}" @selected(request('status')===$s->value)>{{ $s->label() }}</option>
        @endforeach
    </select>
</form>
<table class="tabel-meja">
    <thead><tr><th>Nomor</th><th>Pelanggan</th><th>Sumber</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($daftar as $p)
        <tr>
            <td class="font-mono text-xs"><a class="underline" href="{{ route('admin.transaksi.show', $p) }}">{{ $p->nomor }}</a></td>
            <td>{{ $p->pelanggan->name }}</td>
            <td>{{ $p->sumber }}</td>
            <td class="font-mono">{{ number_format($p->total, 0, ',', '.') }}</td>
            <td>@include('komponen.tiket-status', ['status' => $p->status])</td>
            <td><a href="{{ route('admin.transaksi.show', $p) }}" class="text-sm underline">Detail</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $daftar->links() }}
@endsection
