<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * UJIKOM — Audit log: siapa mengubah apa.
 */
class LogAudit extends Model
{
    protected $table = 'log_audit';

    protected $fillable = [
        'user_id',
        'aksi',
        'objek_tipe',
        'objek_id',
        'keterangan',
        'ip',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function catat(string $aksi, ?object $objek = null, ?string $keterangan = null): void
    {
        static::query()->create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'objek_tipe' => $objek ? class_basename($objek) : null,
            'objek_id' => $objek->id ?? null,
            'keterangan' => $keterangan,
            'ip' => Request::ip(),
        ]);
    }
}
