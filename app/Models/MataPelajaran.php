<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataPelajaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['kode_mapel', 'nama_mapel'];

    /**
     * Relasi many-to-many ke Guru melalui pivot guru_mapel.
     */
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'mapel_id', 'guru_id')
                    ->withTimestamps();
    }

    /**
     * Relasi one-to-many ke Nilai.
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }
}
