<?php

use App\Http\Controllers\Admin\DasborController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MigrasiController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\Admin\PemasokController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Api\ObatAutocompleteController;
use App\Http\Controllers\Api\StatusRealtimeController;
use App\Http\Controllers\Apoteker\ResepController;
use App\Http\Controllers\Apoteker\StokController;
use App\Http\Controllers\Autentikasi\MasukController;
use App\Http\Controllers\Autentikasi\VerifikasiEmailController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\Kasir\TransaksiController as KasirTransaksiController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/**
 * UJIKOM — Rute Indonesia.
 * CSRF otomatis di grup web. Role dipisah middleware `peran`.
 * XSS: tampilan Blade memakai {{ }} (escape).
 */

Route::get('/', fn () => redirect()->route('katalog'))->name('beranda');

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/katalog/{obat}', [KatalogController::class, 'detail'])->name('katalog.detail');
Route::get('/api/obat/autocomplete', ObatAutocompleteController::class)->name('api.obat.autocomplete');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [MasukController::class, 'form'])->name('masuk');
    Route::post('/masuk', [MasukController::class, 'proses']);
    Route::get('/daftar', [MasukController::class, 'formDaftar'])->name('daftar');
    Route::post('/daftar', [MasukController::class, 'daftar']);
});

Route::post('/keluar', [MasukController::class, 'keluar'])->middleware('auth')->name('keluar');

Route::middleware('auth')->group(function () {
    Route::get('/email/verifikasi', [VerifikasiEmailController::class, 'pemberitahuan'])->name('verification.notice');
    Route::get('/email/verifikasi/{id}/{hash}', [VerifikasiEmailController::class, 'verifikasi'])
        ->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verifikasi/kirim', [VerifikasiEmailController::class, 'kirimUlang'])
        ->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::post('/keranjang/{obat}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('/keranjang/{obat}', [KeranjangController::class, 'ubah'])->name('keranjang.ubah');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/checkout', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
    Route::post('/transaksi', [TransaksiController::class, 'buat'])->name('transaksi.buat');
    Route::get('/transaksi/{transaksi}/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
    Route::post('/transaksi/{transaksi}/bayar', [TransaksiController::class, 'prosesBayar'])->name('transaksi.proses-bayar');
    Route::post('/transaksi/{transaksi}/resep', [TransaksiController::class, 'unggahResep'])->name('transaksi.resep');
});

Route::middleware(['auth', 'verified', 'peran:kasir,admin'])->group(function () {
    Route::get('/kasir/transaksi', [KasirTransaksiController::class, 'form'])->name('kasir.transaksi');
    Route::post('/kasir/transaksi', [KasirTransaksiController::class, 'simpan'])->name('kasir.transaksi.simpan');
});

Route::middleware(['auth', 'verified', 'peran:apoteker,admin'])->prefix('apoteker')->name('apoteker.')->group(function () {
    Route::get('/resep', [ResepController::class, 'index'])->name('resep.index');
    Route::post('/resep/{resep}', [ResepController::class, 'putuskan'])->name('resep.putuskan');
    Route::get('/stok', [StokController::class, 'index'])->name('stok');
});

Route::middleware(['auth', 'verified', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dasbor', [DasborController::class, 'index'])->name('dasbor');
    Route::get('/dasbor/polling', [DasborController::class, 'polling'])->name('dasbor.polling');
    Route::get('/api/realtime', StatusRealtimeController::class)->name('realtime');

    Route::resource('obat', ObatController::class)->except('show');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/pemasok', [PemasokController::class, 'index'])->name('pemasok.index');
    Route::post('/pemasok', [PemasokController::class, 'store'])->name('pemasok.store');
    Route::delete('/pemasok/{pemasok}', [PemasokController::class, 'destroy'])->name('pemasok.destroy');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::post('/pelanggan/{pelanggan}/verifikasi-email', [PelangganController::class, 'verifikasiEmail'])->name('pelanggan.verifikasi-email');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/ekspor-csv', [AdminTransaksiController::class, 'eksporCsv'])->name('transaksi.ekspor-csv');
    Route::get('/transaksi/{transaksi}', [AdminTransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{transaksi}/setujui-bayar', [AdminTransaksiController::class, 'setujuiPembayaran'])->name('transaksi.setujui-bayar');
    Route::post('/transaksi/{transaksi}/tolak-bayar', [AdminTransaksiController::class, 'tolakPembayaran'])->name('transaksi.tolak-bayar');
    Route::post('/transaksi/{transaksi}/batal', [AdminTransaksiController::class, 'batalkan'])->name('transaksi.batal');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
    Route::post('/laporan/antrian', [LaporanController::class, 'antrian'])->name('laporan.antrian');
    Route::get('/migrasi', [MigrasiController::class, 'index'])->name('migrasi');
    Route::post('/migrasi', [MigrasiController::class, 'impor'])->name('migrasi.impor');
    Route::post('/migrasi/rollback', [MigrasiController::class, 'rollback'])->name('migrasi.rollback');
    Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('pemantauan');
});
