<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasPanelShield;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================================
    //  RELATIONSHIPS
    // =========================================================================

    /**
     * Relasi ke profil Siswa (one-to-one).
     * User dengan role 'siswa' memiliki satu record di tabel siswas.
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    /**
     * Relasi ke profil Guru (one-to-one).
     * User dengan role 'guru' memiliki satu record di tabel gurus.
     */
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    // =========================================================================
    //  HELPER METHODS
    // =========================================================================

    /**
     * Cek apakah user memiliki role Admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(['admin', 'super_admin']);
    }

    /**
     * Cek apakah user memiliki role Guru.
     */
    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }

    /**
     * Cek apakah user memiliki role Siswa.
     */
    public function isSiswa(): bool
    {
        return $this->hasRole('siswa');
    }

    /**
     * Determine if the user can access a Filament panel.
     * Semua user yang memiliki role apapun boleh akses panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
