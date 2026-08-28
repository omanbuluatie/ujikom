<?php

namespace App\Models;

use App\Enums\PeranPengguna;
use App\Notifications\VerifikasiEmail as NotifikasiVerifikasiEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * UJIKOM — Email verification, password hashing, role-based access.
 * MustVerifyEmail memaksa pasien mengklik tautan verifikasi sebelum bertransaksi.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'peran',
        'telepon',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // Hash otomatis saat diisi — tidak pernah simpan password mentah.
            'password' => 'hashed',
            'peran' => PeranPengguna::class,
        ];
    }

    public function adalah(PeranPengguna ...$daftarPeran): bool
    {
        return in_array($this->peran, $daftarPeran, true);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new NotifikasiVerifikasiEmail);
    }
}
