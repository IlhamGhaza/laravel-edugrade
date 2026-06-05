<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'id_guru', 'nama_guru'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guru) {
            if (empty($guru->id_guru)) {
                $guru->id_guru = 'GR-' . now()->format('Ymd') . '-' . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-many ke MataPelajaran melalui pivot guru_mapel.
     */
    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mapel', 'guru_id', 'mapel_id')
                    ->withTimestamps();
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}

