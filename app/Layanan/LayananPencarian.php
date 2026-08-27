<?php

namespace App\Layanan;

use App\Models\Obat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * UJIKOM — Algoritma: fuzzy search, autocomplete, pagination, sorting, filtering.
 *
 * Fuzzy di sini disengaja sederhana (bukan Elasticsearch):
 * 1. SQL LIKE memakai query binding (cegah SQL Injection).
 * 2. similar_text() PHP menghitung kemiripan ejaan (paracet ≈ Paracetamol).
 * 3. Peringkat: nama diawali kata → kode persis → skor similar tertinggi.
 *
 * Autocomplete memakai fungsi yang sama, dibatasi 10 baris.
 */
class LayananPencarian
{
    /**
     * @return LengthAwarePaginator<int, Obat>
     */
    public function cariFuzzy(?string $kata, ?int $kategoriId, string $urut = 'nama', string $arah = 'asc'): LengthAwarePaginator
    {
        $kueri = $this->dasarKueri($kata, $kategoriId);

        $kolomUrut = in_array($urut, ['nama', 'harga', 'kode'], true) ? $urut : 'nama';
        $arahUrut = $arah === 'desc' ? 'desc' : 'asc';

        return $kueri
            ->with(['kategori', 'batch'])
            ->withSum('batch as batch_sum_sisa', 'sisa')
            ->orderBy($kolomUrut, $arahUrut)
            ->paginate(12)
            ->withQueryString();
    }

    /**
     * Endpoint JSON autocomplete (dokumentasi ada di dokumentasi/api.md).
     *
     * @return Collection<int, array{id:int,kode:string,nama:string,harga:int,skor:int}>
     */
    public function autocomplete(string $kata, int $batas = 10): Collection
    {
        $kata = trim($kata);
        if ($kata === '') {
            return collect();
        }

        return $this->dasarKueri($kata, null)
            ->withSum('batch as batch_sum_sisa', 'sisa')
            ->limit(40)
            ->get()
            ->map(function (Obat $obat) use ($kata) {
                similar_text(mb_strtolower($obat->nama), mb_strtolower($kata), $persen);

                $skor = (int) $persen;
                if (str_starts_with(mb_strtolower($obat->nama), mb_strtolower($kata))) {
                    $skor += 40;
                }
                if (strcasecmp($obat->kode, $kata) === 0) {
                    $skor += 50;
                }

                return [
                    'id' => $obat->id,
                    'kode' => $obat->kode,
                    'nama' => $obat->nama,
                    'harga' => $obat->harga,
                    'stok' => (int) ($obat->batch_sum_sisa ?? 0),
                    'butuh_resep' => $obat->butuh_resep,
                    'skor' => $skor,
                ];
            })
            ->sortByDesc('skor')
            ->take($batas)
            ->values();
    }

    /**
     * Kueri dasar: Eloquent binding, tidak ada SQL string dari input pengguna.
     */
    private function dasarKueri(?string $kata, ?int $kategoriId): Builder
    {
        $kueri = Obat::query();

        if ($kategoriId) {
            $kueri->where('kategori_obat_id', $kategoriId);
        }

        $kata = trim((string) $kata);
        if ($kata !== '') {
            $kueri->where(function (Builder $q) use ($kata) {
                $q->where('nama', 'like', '%'.$kata.'%')
                    ->orWhere('kode', 'like', '%'.$kata.'%')
                    ->orWhereHas('kategori', fn (Builder $k) => $k->where('nama', 'like', '%'.$kata.'%'));
            });
        }

        return $kueri;
    }
}
