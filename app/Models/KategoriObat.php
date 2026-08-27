<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** UJIKOM — CRUD Kategori. */
class KategoriObat extends Model
{
    protected $table = 'kategori_obat';

    protected $fillable = ['nama', 'slug'];

    public function obat(): HasMany
    {
        return $this->hasMany(Obat::class, 'kategori_obat_id');
    }
}
