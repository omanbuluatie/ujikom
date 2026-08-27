<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * UJIKOM — Alert, monitoring, audit log, bukti migrasi.
 * Dipisah dari master data agar dampak perubahan modul stok tidak menghapus jejak pengawasan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peringatan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 30)->index();
            $table->string('tingkat', 20)->index();
            $table->string('judul');
            $table->text('pesan');
            $table->foreignId('obat_id')->nullable()->constrained('obat')->nullOnDelete();
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanan')->nullOnDelete();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('log_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aksi', 60);
            $table->string('objek_tipe', 60)->nullable();
            $table->unsignedBigInteger('objek_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
            $table->index(['objek_tipe', 'objek_id']);
        });

        Schema::create('log_kesalahan', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat', 20)->index();
            $table->string('pesan');
            $table->text('jejak')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::create('log_migrasi', function (Blueprint $table) {
            $table->id();
            $table->string('batch_migrasi_id', 40)->index();
            $table->unsignedInteger('baris_ke');
            $table->string('kode_obat')->nullable();
            $table->string('status', 20);
            $table->text('pesan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_migrasi');
        Schema::dropIfExists('log_kesalahan');
        Schema::dropIfExists('log_audit');
        Schema::dropIfExists('peringatan');
    }
};
