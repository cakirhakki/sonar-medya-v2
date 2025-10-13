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
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Spatie Permission kayıtları admin guard'ında tutuluyor.
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

        // Profil alanları
        'bio',
        'avatar_path',
        'avatar_alt',

        // Sosyal / web linkleri
        'website_url',
        'twitter_url',
        'linkedin_url',
        'facebook_url',
        'instagram_url',
        'github_url',
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
     * Yazar → yazıları ilişkisi.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * Avatar için pratik URL erişimi.
     * - Storage yolu verilmişse normalize edip /storage/... URL'i üretir
     * - Harici (http/https) ise olduğu gibi döner
     * - Hiçbiri yoksa Gravatar (identicon) ya da local placeholder döner
     */
    public function getAvatarUrlAttribute(): string
    {
        $p = $this->avatar_path ?? null;

        if ($p) {
            // Harici URL ise direkt dön
            if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
                return $p;
            }

            // Storage-relative'e normalize et
            $norm = (string) Str::of($p)
                ->replace('\\', '/')
                ->replaceStart('public/storage/', '')
                ->replaceStart('/storage/', '')
                ->replaceStart('public/', '')
                ->ltrim('/');

            return asset('storage/' . $norm);
        }

        // Gravatar fallback (e-posta varsa)
        if ($this->email) {
            $hash = md5(strtolower(trim($this->email)));
            return "https://www.gravatar.com/avatar/{$hash}?s=200&d=identicon";
        }

        // Son çare: local placeholder (mevcut tema görseliniz)
        return asset('assets/img/users/user-12.jpg');
    }

    /**
     * (İsteğe bağlı) Avatar thumb URL'i (ThumbController kullanır).
     * - Storage yolu varsa kırpılmış thumb linki üretir
     * - Harici URL veya boş ise getAvatarUrlAttribute fallback’lerine döner
     */
    public function thumbAvatar(int $w = 80, int $h = 80, string $fit = 'cover'): ?string
    {
        $p = $this->avatar_path ?? null;

        if (!$p) {
            // Gravatar / placeholder
            return $this->avatar_url;
        }

        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            // Harici görsel için kırpma yapamayız, olduğu gibi döner
            return $p;
        }

        $norm = (string) Str::of($p)
            ->replace('\\', '/')
            ->replaceStart('public/storage/', '')
            ->replaceStart('/storage/', '')
            ->replaceStart('public/', '')
            ->ltrim('/');

        return route('thumb', [
            'path' => $norm,
            'w'    => $w,
            'h'    => $h,
            'fit'  => $fit,
        ]);
    }

    /**
     * Kullanıcının Filament admin paneline erişip erişemeyeceğini belirler.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // geçici
    }
}
