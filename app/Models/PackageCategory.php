<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackageCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'image_alt',
        'is_active',
        'sort',
    ];

    // İlişki: bu kategori altındaki paketler
    public function packages()
    {
        return $this->hasMany(ServicePackage::class, 'package_category_id');
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (blank($m->slug) && filled($m->name)) {
                $m->slug = static::uniqueSlug($m->name);
            }
        });

        static::updating(function (self $m) {
            if ($m->isDirty('name') && blank($m->slug)) {
                $m->slug = static::uniqueSlug($m->name, $m->id);
            }
        });
    }

    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $i = 2;

        $q = static::withTrashed();
        if ($ignoreId) $q->where('id', '!=', $ignoreId);

        while ($q->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $q = static::withTrashed();
            if ($ignoreId) $q->where('id', '!=', $ignoreId);
            $i++;
        }

        return $slug;
    }

    // Küçük yardımcı: tam URL (storage link varsa)
    public function getImageUrlAttribute(): ?string
    {
        $path = $this->image_path;
        if (!$path) return null;

        // Dış URL ise olduğu gibi döndür
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Storage yolu varsayımı: storage/app/public/…
        return asset('storage/' . ltrim($path, '/'));
    }
    public function servicePackages()
{
    return $this->hasMany(\App\Models\ServicePackage::class, 'package_category_id');
}
}
