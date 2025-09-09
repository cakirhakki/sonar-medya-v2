<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Toplu atamaya açık alanlar.
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_path',
        'phone',
        'birth_date',
        'gender',
        'address',
        'loyalty_points',
        'receive_newsletters',
        'password',
    ];

    /**
     * Serileştirmede gizlenecek alanlar.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tip dönüşümleri.
     */
    protected $casts = [
        'email_verified_at'   => 'datetime',
        'birth_date'          => 'date',
        'receive_newsletters' => 'boolean',
        'loyalty_points'      => 'integer',
        'password'            => 'hashed',
    ];

    /**
     * Otomatik eklenecek sanal alanlar.
     */
    protected $appends = ['avatar_url'];

    /**
     * Avatar için erişimci:
     * - Dosya yüklenmişse public diskten URL üret.
     * - Yoksa ui-avatars ile isimden placeholder üret.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('public');

            return $disk->url($this->avatar_path);
        }

        $name = rawurlencode($this->name ?: 'User');

        // ui-avatars placeholder (200px, açık gri arka plan)
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=EEE&color=555";
    }

    /**
     * Dosya temizlikleri:
     * - Avatar değişirken eski dosyayı sil.
     * - Kalıcı silmede (force delete) avatarı sil.
     */
    protected static function booted(): void
    {
        // Edit sırasında yeni avatar yüklendiğinde eskisini sil
        static::updating(function (self $customer) {
            if ($customer->isDirty('avatar_path')) {
                $old = $customer->getOriginal('avatar_path');
                if ($old) {
                    Storage::disk('public')->delete($old);
                }
            }
        });

        // Soft delete'de dosyayı koru; sadece kalıcı silmede temizle
        static::forceDeleted(function (self $customer) {
            if ($customer->avatar_path) {
                Storage::disk('public')->delete($customer->avatar_path);
            }
        });
    }
}
