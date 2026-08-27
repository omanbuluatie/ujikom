<p>Halo {{ $pesanan->pelanggan->name }},</p>
<p>Status pesanan <strong>{{ $pesanan->nomor }}</strong> sekarang: <strong>{{ $pesanan->status->label() }}</strong>.</p>
<p>Total: Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
<p>Klinik Makmur Jaya</p>
