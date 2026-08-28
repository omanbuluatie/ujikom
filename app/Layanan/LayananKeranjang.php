<?php

namespace App\Layanan;

use App\Models\Obat;
use Illuminate\Support\Collection;

/**
 * UJIKOM — E-commerce cart.
 * Keranjang disimpan di sesi (bukan tabel) supaya alur tetap sederhana.
 * Kunci: obat_id → jumlah. Harga diambil ulang dari DB saat checkout (anti manipulasi).
 */
class LayananKeranjang
{
    public const KUNCI = 'keranjang';

    /** @return array<int, int> */
    public function semua(): array
    {
        return session(self::KUNCI, []);
    }

    public function tambah(int $obatId, int $jumlah = 1): void
    {
        $isi = $this->semua();
        $isi[$obatId] = ($isi[$obatId] ?? 0) + max(1, $jumlah);
        session([self::KUNCI => $isi]);
    }

    public function ubah(int $obatId, int $jumlah): void
    {
        $isi = $this->semua();
        if ($jumlah <= 0) {
            unset($isi[$obatId]);
        } else {
            $isi[$obatId] = $jumlah;
        }
        session([self::KUNCI => $isi]);
    }

    public function kosongkan(): void
    {
        session()->forget(self::KUNCI);
    }

    public function jumlahItem(): int
    {
        return array_sum($this->semua());
    }

    /**
     * @return Collection<int, array{obat: Obat, jumlah: int, subtotal: float}>
     */
    public function rincian(): Collection
    {
        $isi = $this->semua();
        if ($isi === []) {
            return collect();
        }

        $daftarObat = Obat::query()->whereIn('id', array_keys($isi))->get()->keyBy('id');

        return collect($isi)->map(function (int $jumlah, int $obatId) use ($daftarObat) {
            $obat = $daftarObat->get($obatId);
            if (! $obat) {
                return null;
            }

            return [
                'obat' => $obat,
                'jumlah' => $jumlah,
                'subtotal' => (float) $obat->harga * $jumlah,
            ];
        })->filter()->values();
    }

    public function total(): float
    {
        return (float) $this->rincian()->sum('subtotal');
    }
}
