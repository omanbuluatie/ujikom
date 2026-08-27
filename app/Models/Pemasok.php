<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** UJIKOM — CRUD Supplier (istilah aplikasi: pemasok). */
class Pemasok extends Model
{
    protected $table = 'pemasok';

    protected $fillable = ['nama', 'telepon', 'alamat'];

    public function obat(): HasMany
    {
        return $this->hasMany(Obat::class);
    }
}
