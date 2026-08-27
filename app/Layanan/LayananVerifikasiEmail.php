<?php

namespace App\Layanan;

use App\Models\LogTautanVerifikasi;
use App\Models\User;

/** Tandai tautan verifikasi terpakai setelah email diverifikasi. */
class LayananVerifikasiEmail
{
    public function tandaiTerpakai(User $pengguna): void
    {
        LogTautanVerifikasi::query()
            ->where('user_id', $pengguna->id)
            ->whereNull('dipakai_pada')
            ->update(['dipakai_pada' => now()]);
    }
}
