<?php

namespace Database\Seeders;

use App\Enums\PeranPengguna;
use App\Layanan\LayananPeringatan;
use App\Layanan\LayananStok;
use App\Models\KategoriObat;
use App\Models\Obat;
use App\Models\Pemasok;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data demo uji kompetensi: 4 peran, obat bebas + keras, stok kritis, batch hampir kedaluwarsa.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            ['name' => 'Admin Klinik', 'email' => 'admin@makmurjaya.test', 'peran' => PeranPengguna::Admin],
            ['name' => 'Apt. Sari', 'email' => 'apoteker@makmurjaya.test', 'peran' => PeranPengguna::Apoteker],
            ['name' => 'Kasir Budi', 'email' => 'kasir@makmurjaya.test', 'peran' => PeranPengguna::Kasir],
            ['name' => 'Pasien Rina', 'email' => 'pasien@makmurjaya.test', 'peran' => PeranPengguna::Pasien],
        ];

        foreach ($akun as $data) {
            User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => 'Password1',
                    'peran' => $data['peran'],
                    'email_verified_at' => now(),
                    'telepon' => '08123456789',
                    'alamat' => 'Jl. Makmur No. 12',
                ]
            );
        }

        $kategori = [
            'Analgesik' => KategoriObat::query()->firstOrCreate(['slug' => 'analgesik'], [
                'nama' => 'Analgesik', 'slot' => 1, 'deskripsi' => 'Obat pereda nyeri', 'is_active' => true, 'email' => 'analgesik@makmurjaya.test',
            ]),
            'Antibiotik' => KategoriObat::query()->firstOrCreate(['slug' => 'antibiotik'], [
                'nama' => 'Antibiotik', 'slot' => 2, 'deskripsi' => 'Obat anti infeksi (resep)', 'is_active' => true, 'email' => 'antibiotik@makmurjaya.test',
            ]),
            'Vitamin' => KategoriObat::query()->firstOrCreate(['slug' => 'vitamin'], [
                'nama' => 'Vitamin', 'slot' => 3, 'deskripsi' => 'Suplemen vitamin', 'is_active' => true, 'email' => 'vitamin@makmurjaya.test',
            ]),
        ];

        $pemasok = Pemasok::query()->firstOrCreate(
            ['nama' => 'PT Sinar Farma'],
            ['telepon' => '021555000', 'alamat' => 'Jakarta']
        );

        $stok = app(LayananStok::class);

        $daftarObat = [
            ['kode' => 'PCT500', 'nama' => 'Paracetamol 500mg', 'kat' => 'Analgesik', 'harga' => 3300.50, 'min' => 20, 'resep' => false, 'stok' => 80, 'exp' => now()->addMonths(14), 'masuk' => now()->subMonths(2)],
            ['kode' => 'IBU400', 'nama' => 'Ibuprofen 400mg', 'kat' => 'Analgesik', 'harga' => 4950.75, 'min' => 15, 'resep' => false, 'stok' => 40, 'exp' => now()->addMonths(8), 'masuk' => now()->subMonths(1)],
            ['kode' => 'AMX500', 'nama' => 'Amoxicillin 500mg', 'kat' => 'Antibiotik', 'harga' => 8800.00, 'min' => 10, 'resep' => true, 'stok' => 35, 'exp' => now()->addMonths(10), 'masuk' => now()->subDays(40)],
            ['kode' => 'CTM4', 'nama' => 'CTM 4mg', 'kat' => 'Analgesik', 'harga' => 2200.25, 'min' => 25, 'resep' => false, 'stok' => 8, 'exp' => now()->addMonths(6), 'masuk' => now()->subMonths(3)],
            ['kode' => 'VITC', 'nama' => 'Vitamin C 500mg', 'kat' => 'Vitamin', 'harga' => 5500.00, 'min' => 12, 'resep' => false, 'stok' => 50, 'exp' => now()->addDays(25), 'masuk' => now()->subMonths(5)],
            ['kode' => 'VITB', 'nama' => 'Vitamin B Complex', 'kat' => 'Vitamin', 'harga' => 7700.99, 'min' => 10, 'resep' => false, 'stok' => 22, 'exp' => now()->addMonths(18), 'masuk' => now()->subDays(10)],
        ];

        foreach ($daftarObat as $baris) {
            $obat = Obat::query()->updateOrCreate(
                ['kode' => $baris['kode']],
                [
                    'nama' => $baris['nama'],
                    'kategori_obat_id' => $kategori[$baris['kat']]->id,
                    'pemasok_id' => $pemasok->id,
                    'harga' => $baris['harga'],
                    'stok_minimum' => $baris['min'],
                    'butuh_resep' => $baris['resep'],
                    'deskripsi' => 'Data demo Klinik Makmur Jaya.',
                ]
            );

            if ($obat->batch()->count() === 0) {
                $stok->masukkanBatch(
                    $obat,
                    $baris['stok'],
                    $baris['exp']->toDateString(),
                    $baris['masuk']->toDateString(),
                    'seeder'
                );
            }
        }

        // Batch kedua Paracetamol (lebih baru) supaya FIFO terlihat: batch lama keluar dulu.
        $pct = Obat::query()->where('kode', 'PCT500')->first();
        if ($pct && $pct->batch()->count() === 1) {
            $stok->masukkanBatch($pct, 30, now()->addYear()->toDateString(), now()->toDateString(), 'seeder');
        }

        app(LayananPeringatan::class)->cekKedaluwarsa();
        foreach (Obat::all() as $obat) {
            app(LayananPeringatan::class)->cekStokKritis($obat);
        }
    }
}
