<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'nim',
        'alamat',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $punyaRolePanel = $this->hasAnyRole([
            'super_admin',
            'admin',
            'owner',
        ]);

        $roleKolomManual = in_array($this->role, [
            'admin',
            'owner',
        ], true);

        return ($punyaRolePanel || $roleKolomManual)
            && ($this->status === 'aktif' || blank($this->status));
    }

    public function permintaanLayanan(): HasMany
    {
        return $this->hasMany(PermintaanLayanan::class, 'user_id');
    }

    public function logStatusPermintaan(): HasMany
    {
        return $this->hasMany(LogStatusPermintaan::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin')
            || $this->hasRole('admin')
            || $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->hasRole('owner')
            || $this->role === 'owner';
    }

    public function isUser(): bool
    {
        return $this->hasRole('user')
            || $this->role === 'user';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}