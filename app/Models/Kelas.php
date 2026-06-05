<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama_kelas'];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_kelas');
    }

    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'kelas_mapel', 'kelas_id', 'mapel_id');
    }
}
