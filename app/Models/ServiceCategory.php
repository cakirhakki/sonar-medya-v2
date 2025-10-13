<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'display_order', 'show_in_menu', 'menu_mode', 'menu_selected_service_ids', 'menu_excluded_service_ids'];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'show_in_menu' => 'boolean',
        'menu_mode' => 'integer',
        'menu_selected_service_ids' => 'array',
        'menu_excluded_service_ids' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'service_category_id')->when(Schema::hasColumn('services', 'display_order'), fn($q) => $q->orderBy('display_order')->orderBy('name'), fn($q) => $q->orderBy('name'));
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Mutators (defansif dizi parse)
    |--------------------------------------------------------------------------
    */
    protected function menuSelectedServiceIds(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_array($value)) {
                    return array_values($value);
                }
                if (is_null($value) || $value === '') {
                    return [];
                }
                $decoded = json_decode($value, true);
                return is_array($decoded) ? array_values($decoded) : [];
            },
            set: fn($value) => array_values((array) $value),
        );
    }

    protected function menuExcludedServiceIds(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_array($value)) {
                    return array_values($value);
                }
                if (is_null($value) || $value === '') {
                    return [];
                }
                $decoded = json_decode($value, true);
                return is_array($decoded) ? array_values($decoded) : [];
            },
            set: fn($value) => array_values((array) $value),
        );
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

    public function scopeOrdered($q)
    {
        if (Schema::hasColumn($this->getTable(), 'display_order')) {
            return $q->orderBy('display_order')->orderBy('name');
        }
        return $q->orderBy('name');
    }

    public function scopeMenu($q)
    {
        return $q->where('is_active', true)->where('show_in_menu', true)->ordered();
    }

    /*
    |--------------------------------------------------------------------------
    | Boot (slug + cache)
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if (blank($model->slug) || $model->isDirty('name')) {
                $base = Str::slug($model->slug ?: $model->name);
                $model->slug = static::uniqueSlug($base, $model->getKey());
            }
        });

        $forgetMenu = fn() => Cache::forget('menu.services');

        static::saved($forgetMenu);
        static::deleted($forgetMenu);
        static::restored($forgetMenu);
        static::forceDeleted($forgetMenu);
    }

    protected static function uniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = $base ?: Str::random(8);
        $original = $slug;
        $i = 2;

        $query = static::withTrashed();
        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        while ($query->where('slug', $slug)->exists()) {
            $slug = "{$original}-{$i}";
            $i++;

            $query = static::withTrashed();
            if ($ignoreId) {
                $query->whereKeyNot($ignoreId);
            }
        }

        return $slug;
    }
}
