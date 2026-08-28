<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * REVISI — CRUD Kategori obat.
 * Field: nama, slot, deskripsi, is_active, email.
 */
class KategoriObat extends Model
{
    protected $table = 'kategori_obat';

    protected $fillable = [
        'nama',
        'slug',
        'slot',
        'deskripsi',
        'is_active',
        'email',
    ];

    protected function casts(): array
    {
        return [
            'slot' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function obat(): HasMany
    {
        return $this->hasMany(Obat::class, 'kategori_obat_id');
    }
}
