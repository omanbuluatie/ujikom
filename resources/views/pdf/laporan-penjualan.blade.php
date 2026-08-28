<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #17202A; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #17202A; color: #fff; }
    </style>
</head>
<body>
    <h1>Laporan penjualan Klinik Makmur Jaya</h1>
    <p>{{ $dari }} s.d. {{ $sampai }} · dicetak {{ $dibuatPada->format('d-m-Y H:i') }}</p>
    <h2>Obat terlaris</h2>
    <table>
        <tr><th>Obat</th><th>Terjual</th><th>Omzet</th></tr>
        @foreach($terlaris as $b)
            <tr><td>{{ $b->obat?->nama }}</td><td>{{ $b->total_terjual }}</td><td>{{ number_format($b->omzet, 0, ',', '.') }}</td></tr>
        @endforeach
    </table>
    <h2>Rekap transaksi</h2>
    <table>
        <tr><th>Nomor</th><th>Total</th><th>Status</th></tr>
        @foreach($rekap as $p)
            <tr><td>{{ $p->kode_transaksi }}</td><td>{{ number_format($p->total, 2, ',', '.') }}</td><td>{{ $p->status->label() }}</td></tr>
        @endforeach
    </table>
</body>
</html>
