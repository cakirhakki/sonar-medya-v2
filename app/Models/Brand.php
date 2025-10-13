<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'url',
        'group',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'display_order' => 'int',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $m) {
            $m->slug = $m->slug ?: Str::slug($m->name);
        });

        $flush = function () {
            cache()->forget('brands.top');
            cache()->forget('brands.bottom');
            cache()->forget('brands.default');
        };

        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
        static::forceDeleted($flush);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public static function byGroup(string $group): \Illuminate\Support\Collection
    {
        $key = "brands.$group";

        return cache()->rememberForever($key, function () use ($group) {
            return static::query()
                ->where('group', $group)
                ->where('is_active', true)
                ->orderBy('display_order')
                ->orderBy('name')
                ->get();
        });
    }

    // Alias: bileşen eski adıyla çağırıyorsa çalışsın
    public static function listByGroup(string $group): \Illuminate\Support\Collection
    {
        return static::byGroup($group);
    }
}
