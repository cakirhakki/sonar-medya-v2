<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Spatie Permission kayıtları admin guard'ında tutuluyor.
     * (Shield izin/rolleri guard=admin ile üretir.)
     */
    protected string $guard_name = 'admin';

    /**
     * Toplu atamaya açık alanlar.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * Serileştirmede gizlenecek alanlar.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Öznitelik tip dönüşümleri.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Kullanıcının Filament admin paneline erişip erişemeyeceğini belirler.
     * - Yanlış panel id'sini reddeder.
     * - super_admin her zaman izinlidir.
     * - En az bir admin-guard izni olan (rolü üzerinden de olabilir) kullanıcı panele girebilir.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() !== 'admin') {
            return false;
        }

        if ($this->hasRole(config('filament-shield.super_admin.name'))) {
            return true;
        }

        return $this->getAllPermissions()->isNotEmpty();
    }
}
