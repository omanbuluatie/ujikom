<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * UJIKOM — SQL & data transaksi.
 * Satu migrasi domain agar relasi Obat → Batch → Mutasi → Pesanan mudah ditunjukkan saat demo.
 * Indeks pada kolom filter/laporan mendukung skalabilitas 2.000 jenis obat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('pemasok', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telepon', 30)->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->unique();
            $table->string('nama');
            $table->foreignId('kategori_obat_id')->constrained('kategori_obat')->restrictOnDelete();
            $table->foreignId('pemasok_id')->nullable()->constrained('pemasok')->nullOnDelete();
            $table->unsignedInteger('harga');
            $table->unsignedInteger('stok_minimum')->default(10);
            $table->boolean('butuh_resep')->default(false);
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->index(['kategori_obat_id', 'nama']);
        });

        // Unit FIFO: stok tampilan = SUM(sisa), bukan kolom terpisah yang bisa menyimpang.
        Schema::create('batch_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('obat')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_masuk');
            $table->unsignedInteger('sisa');
            $table->date('tanggal_masuk');
            $table->date('tanggal_kedaluwarsa');
            $table->timestamps();
            $table->index(['obat_id', 'tanggal_masuk']);
            $table->index(['obat_id', 'tanggal_kedaluwarsa']);
        });

        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('obat')->cascadeOnDelete();
            $table->foreignId('batch_obat_id')->constrained('batch_obat')->cascadeOnDelete();
            $table->string('jenis', 10);
            $table->unsignedInteger('jumlah');
            $table->string('sumber', 40);
            $table->unsignedBigInteger('pesanan_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
        Schema::dropIfExists('batch_obat');
        Schema::dropIfExists('obat');
        Schema::dropIfExists('pemasok');
        Schema::dropIfExists('kategori_obat');
    }
};
