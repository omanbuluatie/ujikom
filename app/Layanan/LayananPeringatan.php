<?php

namespace App\Layanan;

use App\Enums\JenisPeringatan;
use App\Enums\TingkatKeparahan;
use App\Models\BatchObat;
use App\Models\Obat;
use App\Models\Peringatan;
use App\Models\Transaksi;

/**
 * UJIKOM — Alert notification:
 * stok di bawah minimum, kedaluwarsa 30/60/90 hari, pesanan baru, error ke admin.
 */
class LayananPeringatan
{
    public function cekStokKritis(Obat $obat): void
    {
        $stok = (int) $obat->batch()->sum('sisa');
        if ($stok > $obat->stok_minimum) {
            return;
        }

        $sudahAda = Peringatan::query()
            ->where('jenis', JenisPeringatan::StokKritis)
            ->where('obat_id', $obat->id)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($sudahAda) {
            return;
        }

        Peringatan::query()->create([
            'jenis' => JenisPeringatan::StokKritis,
            'tingkat' => $stok === 0 ? TingkatKeparahan::Kritis : TingkatKeparahan::Peringatan,
            'judul' => 'Stok kritis: '.$obat->nama,
            'pesan' => "Sisa {$stok} unit, minimum {$obat->stok_minimum}.",
            'obat_id' => $obat->id,
        ]);
    }

    /**
     * Dipanggil scheduler harian (JobCekKedaluwarsa).
     * Ambang 30 / 60 / 90 hari sesuai dokumen tugas.
     */
    public function cekKedaluwarsa(): int
    {
        $jumlah = 0;
        $daftarBatch = BatchObat::query()
            ->with('obat')
            ->where('sisa', '>', 0)
            ->whereDate('tanggal_kedaluwarsa', '<=', now()->addDays(90))
            ->get();

        foreach ($daftarBatch as $batch) {
            $hari = $batch->hariMenujuKedaluwarsa();
            $ambang = $hari <= 30 ? 30 : ($hari <= 60 ? 60 : 90);
            $tingkat = $hari <= 30 ? TingkatKeparahan::Kritis : TingkatKeparahan::Peringatan;

            $sudahAda = Peringatan::query()
                ->where('jenis', JenisPeringatan::Kedaluwarsa)
                ->where('obat_id', $batch->obat_id)
                ->where('created_at', '>=', now()->subDay())
                ->where('pesan', 'like', '%batch #'.$batch->id.'%')
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Peringatan::query()->create([
                'jenis' => JenisPeringatan::Kedaluwarsa,
                'tingkat' => $tingkat,
                'judul' => "Kedaluwarsa ≤{$ambang} hari: ".$batch->obat->nama,
                'pesan' => 'batch #'.$batch->id.' kedaluwarsa '.$batch->tanggal_kedaluwarsa->toDateString()." ({$hari} hari).",
                'obat_id' => $batch->obat_id,
            ]);
            $jumlah++;
        }

        return $jumlah;
    }

    public function transaksiBaru(Transaksi $transaksi): void
    {
        Peringatan::query()->create([
            'jenis' => JenisPeringatan::PesananBaru,
            'tingkat' => TingkatKeparahan::Info,
            'judul' => 'Transaksi baru '.$transaksi->kode_transaksi,
            'pesan' => 'Total Rp '.number_format((float) $transaksi->total, 2, ',', '.'),
            'transaksi_id' => $transaksi->id,
        ]);
    }

    public function kesalahan(string $judul, string $pesan, TingkatKeparahan $tingkat = TingkatKeparahan::Kritis): void
    {
        Peringatan::query()->create([
            'jenis' => JenisPeringatan::Kesalahan,
            'tingkat' => $tingkat,
            'judul' => $judul,
            'pesan' => $pesan,
        ]);
    }
}
