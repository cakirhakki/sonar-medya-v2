<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
// İlişkiler
use App\Models\ServiceCategory;
use App\Models\ServiceFaq;

// Spatie Tags & Media
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use HasTags;
    use InteractsWithMedia;

    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'excerpt',
        'description',
        'is_active',
        'is_featured',
        'display_order',
        'unit',
        'base_price',
        'setup_fee',
        'tax_rate_percent',
        'duration_minutes',
    ];

    protected $casts = [
        'is_active'         => 'bool',
        'is_featured'       => 'bool',
        'display_order'     => 'int',
        'duration_minutes'  => 'int',
        'tax_rate_percent'  => 'int',
        'base_price'        => 'decimal:2',
        'setup_fee'         => 'decimal:2',
    ];

    protected $appends = ['duration_readable'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function faqs()
    {
        return $this->morphMany(ServiceFaq::class, 'faqable')
            ->orderBy('sort_order')->orderBy('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('display_order')->orderBy('name');
    }

    public function scopeSearch($q, ?string $term)
    {
        if (blank($term)) return $q;
        $term = trim($term);

        return $q->where(function ($qq) use ($term) {
            $qq->where('name', 'like', "%{$term}%")
               ->orWhere('slug', 'like', "%{$term}%")
               ->orWhere('excerpt', 'like', "%{$term}%")
               ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Boot (slug + menu cache)
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        // Slug üretimi
        static::saving(function (self $model) {
            if (blank($model->slug) || $model->isDirty('name')) {
                $base = Str::slug($model->slug ?: $model->name);
                $model->slug = static::uniqueSlug($base, $model->getKey());
            }
        });

        // Menü cache invalidation
        $forget = fn () => \Illuminate\Support\Facades\Cache::forget('menu.services');
        static::saved($forget);
        static::deleted($forget);
        static::restored($forget);
        static::forceDeleted($forget);
    }

    protected static function uniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = $base ?: Str::random(8);
        $original = $slug;
        $i = 2;

        while (
            static::query()
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | Spatie Media Library
    |--------------------------------------------------------------------------
    */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    // İnsan-okur süre: 43200 -> "30 gün", 120 -> "2 saat", 45 -> "45 dk"
    public function getDurationReadableAttribute(): ?string
    {
        $m = (int) ($this->duration_minutes ?? 0);
        if ($m <= 0) return null;

        if ($m % 1440 === 0) {
            return ($m / 1440) . ' gün';
        }
        if ($m % 60 === 0) {
            return ($m / 60) . ' saat';
        }
        return $m . ' dk';
    }

    // View'larda özet için: excerpt varsa onu, yoksa description
    public function getSummaryAttribute(): ?string
    {
        return $this->excerpt ?? $this->description ?? null;
    }
}
