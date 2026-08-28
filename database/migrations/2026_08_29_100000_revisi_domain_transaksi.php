<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * REVISI UJIKOM — Selaraskan domain:
 * - Kategori: slot, deskripsi, is_active, email
 * - Obat: harga desimal (pajak)
 * - Pesanan → transaksi, nomor → kode_transaksi, status disederhanakan
 * - Pembayaran: metode, bukti upload, alamat pengiriman
 * - Resep: status pending/verifikasi/ditolak, catatan_verifikasi
 * - Mutasi: jenis masuk/keluar/expired/return
 * - Notifikasi pasien
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori_obat', function (Blueprint $table) {
            $table->unsignedSmallInteger('slot')->default(0)->after('slug');
            $table->text('deskripsi')->nullable()->after('slot');
            $table->boolean('is_active')->default(true)->after('deskripsi');
            $table->string('email')->nullable()->after('is_active');
        });

        Schema::table('obat', function (Blueprint $table) {
            // Harga desimal (termasuk pajak, mis. 3300.50)
        });
        DB::statement('ALTER TABLE obat MODIFY harga DECIMAL(12,2) NOT NULL');

        DB::statement('ALTER TABLE item_pesanan MODIFY harga_satuan DECIMAL(12,2) NOT NULL');
        DB::statement('ALTER TABLE item_pesanan MODIFY subtotal DECIMAL(14,2) NOT NULL');

        Schema::rename('pesanan', 'transaksi');
        Schema::rename('item_pesanan', 'item_transaksi');

        Schema::table('transaksi', function (Blueprint $table) {
            $table->renameColumn('nomor', 'kode_transaksi');
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('metode_pembayaran', 60)->nullable()->after('total');
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            $table->text('alamat_pengiriman')->nullable()->after('bukti_pembayaran');
        });
        DB::statement('ALTER TABLE transaksi MODIFY total DECIMAL(14,2) NOT NULL');

        Schema::table('item_transaksi', function (Blueprint $table) {
            $table->renameColumn('pesanan_id', 'transaksi_id');
        });

        Schema::table('resep', function (Blueprint $table) {
            $table->renameColumn('pesanan_id', 'transaksi_id');
            $table->renameColumn('catatan_apoteker', 'catatan_verifikasi');
        });

        Schema::table('mutasi_stok', function (Blueprint $table) {
            $table->renameColumn('pesanan_id', 'transaksi_id');
        });

        Schema::table('peringatan', function (Blueprint $table) {
            $table->renameColumn('pesanan_id', 'transaksi_id');
        });

        // Pemetaan status lama → baru
        DB::table('transaksi')->whereIn('status', [
            'menunggu_bayar', 'dikonfirmasi', 'menunggu_resep', 'menunggu_verifikasi',
        ])->update(['status' => 'pending']);
        DB::table('transaksi')->where('status', 'diproses')->update(['status' => 'diproses']);
        DB::table('transaksi')->where('status', 'selesai')->update(['status' => 'selesai']);
        DB::table('transaksi')->where('status', 'dibatalkan')->update(['status' => 'dibatalkan']);

        DB::table('resep')->where('status', 'menunggu')->update(['status' => 'pending']);
        DB::table('resep')->where('status', 'disetujui')->update(['status' => 'verifikasi']);
        DB::table('resep')->where('status', 'ditolak')->update(['status' => 'ditolak']);

        DB::table('mutasi_stok')->where('jenis', 'masuk')->update(['jenis' => 'masuk']);
        DB::table('mutasi_stok')->where('jenis', 'keluar')->update(['jenis' => 'keluar']);

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksi')->nullOnDelete();
            $table->string('jenis', 40)->index();
            $table->string('judul');
            $table->text('pesan');
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');

        Schema::table('peringatan', function (Blueprint $table) {
            $table->renameColumn('transaksi_id', 'pesanan_id');
        });

        Schema::table('mutasi_stok', function (Blueprint $table) {
            $table->renameColumn('transaksi_id', 'pesanan_id');
        });

        Schema::table('resep', function (Blueprint $table) {
            $table->renameColumn('catatan_verifikasi', 'catatan_apoteker');
            $table->renameColumn('transaksi_id', 'pesanan_id');
        });

        Schema::table('item_transaksi', function (Blueprint $table) {
            $table->renameColumn('transaksi_id', 'pesanan_id');
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'bukti_pembayaran', 'alamat_pengiriman']);
            $table->renameColumn('kode_transaksi', 'nomor');
        });

        Schema::rename('item_transaksi', 'item_pesanan');
        Schema::rename('transaksi', 'pesanan');

        Schema::table('kategori_obat', function (Blueprint $table) {
            $table->dropColumn(['slot', 'deskripsi', 'is_active', 'email']);
        });
    }
};
