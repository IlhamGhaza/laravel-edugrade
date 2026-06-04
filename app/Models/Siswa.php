<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'nis', 'nama', 'kelas'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($siswa) {
            if (empty($siswa->nis)) {
                $siswa->nis = 'NIS-' . now()->format('Ymd') . '-' . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    // Penerapan OOP
    public function lihatProfil()
    {
        return $this->nis . ' - ' . $this->nama . ' - ' . $this->kelas;
    }
}
