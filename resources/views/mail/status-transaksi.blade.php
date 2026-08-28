<p>Halo {{ $transaksi->pelanggan->name }},</p>
<p>Status transaksi <strong>{{ $transaksi->kode_transaksi }}</strong> sekarang: <strong>{{ $transaksi->status->label() }}</strong>.</p>
<p>Total: Rp {{ number_format($transaksi->total, 2, ',', '.') }}</p>
@if($transaksi->metode_pembayaran)
    <p>Metode: {{ $transaksi->metode_pembayaran }}</p>
@endif
