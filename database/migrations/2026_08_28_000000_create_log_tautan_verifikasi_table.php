<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * UJIKOM — Simpan tautan aktivasi email untuk demo (MAIL_MAILER=log).
 * Admin membaca tautan di Pemantauan tanpa buka laravel.log.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_tautan_verifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('email');
            $table->text('tautan');
            $table->timestamp('kadaluarsa_pada');
            $table->timestamp('dipakai_pada')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'dipakai_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_tautan_verifikasi');
    }
};
