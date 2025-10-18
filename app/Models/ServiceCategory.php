<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description',
        'is_active', 'display_order',
        'show_in_menu', 'menu_mode', // 0=Hepsi, 1=Seçililer
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'display_order'=> 'integer',
        'show_in_menu' => 'boolean',
        'menu_mode'    => 'integer',
    ];

    /* İlişkiler */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'service_category_id')
            ->when(
                Schema::hasColumn('services', 'display_order'),
                fn ($q) => $q->orderBy('display_order')->orderBy('name'),
                fn ($q) => $q->orderBy('name')
            );
    }

    /** Menü “Seçililer” için pivot ilişki */
    public function menuServices(): BelongsToMany
    {
        return $this->belongsToMany(
                Service::class,
                'service_category_menu_services',
                'service_category_id',
                'service_id'
            )
            ->withPivot('position')
            ->orderBy('service_category_menu_services.position');
    }

    /** Menüde gösterilecek hizmet listesi */
    public function getMenuServiceListAttribute()
    {
        if (! $this->show_in_menu) {
            return collect();
        }

        // 1=Seçililer: pivot’tan sırayla
        if ((int) ($this->menu_mode ?? 0) === 1) {
            return $this->menuServices()->where('is_active', true)->get();
        }

        // 0=Hepsi: tüm aktif hizmetler (display_order varsa ona göre)
        return $this->services()->where('is_active', true)->get();
    }

    /* Scopes */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        $table = $q->getModel()->getTable();
        return Schema::hasColumn($table, 'display_order')
            ? $q->orderBy('display_order')->orderBy('name')
            : $q->orderBy('name');
    }

    public function scopeMenu($q)
    {
        return $q->where('is_active', true)
            ->where('show_in_menu', true)
            ->ordered();
    }

    /* Boot */
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if (blank($model->slug) || $model->isDirty('name')) {
                $base = Str::slug($model->slug ?: $model->name);
                $model->slug = static::uniqueSlug($base, $model->getKey());
            }
            // menüde gösteriliyorsa kategori her zaman aktif
            if ($model->show_in_menu) {
                $model->is_active = true;
            }
        });

        $forget = fn () => Cache::forget('menu.services');
        static::saved($forget);
        static::deleted($forget);
        static::restored($forget);
        static::forceDeleted($forget);
    }

    protected static function uniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = $base ?: Str::random(8);
        $original = $slug; $i = 2;

        $query = static::withTrashed();
        if ($ignoreId) $query->whereKeyNot($ignoreId);

        while ($query->where('slug', $slug)->exists()) {
            $slug = "{$original}-{$i}";
            $i++;
            $query = static::withTrashed();
            if ($ignoreId) $query->whereKeyNot($ignoreId);
        }
        return $slug;
    }
}
