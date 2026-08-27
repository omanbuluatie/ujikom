<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Log tautan verifikasi email untuk demo admin (Pemantauan). */
class LogTautanVerifikasi extends Model
{
    protected $table = 'log_tautan_verifikasi';

    protected $fillable = [
        'user_id',
        'email',
        'tautan',
        'kadaluarsa_pada',
        'dipakai_pada',
    ];

    protected function casts(): array
    {
        return [
            'kadaluarsa_pada' => 'datetime',
            'dipakai_pada' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function masihBerlaku(): bool
    {
        return $this->dipakai_pada === null && $this->kadaluarsa_pada->isFuture();
    }
}
