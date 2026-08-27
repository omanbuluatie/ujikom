<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * UJIKOM — E-commerce: cart (sesi) + checkout + pembayaran + resep.
 * Keranjang tidak punya tabel: cukup sesi agar alur tetap sederhana.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('status', 30)->index();
            $table->string('sumber', 20)->default('online'); // online | kasir
            $table->unsignedInteger('total');
            $table->timestamp('dibayar_pada')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('obat_id')->constrained('obat')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('harga_satuan');
            $table->unsignedInteger('subtotal');
            $table->timestamps();
        });

        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('berkas_gambar');
            $table->string('status', 20)->default('menunggu')->index();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_apoteker')->nullable();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
        });

        Schema::table('mutasi_stok', function (Blueprint $table) {
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mutasi_stok', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
        });
        Schema::dropIfExists('resep');
        Schema::dropIfExists('item_pesanan');
        Schema::dropIfExists('pesanan');
    }
};
