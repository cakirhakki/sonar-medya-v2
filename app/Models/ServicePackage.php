<?php

namespace App\Models;

use App\Models\PackageItem;
use App\Models\PackageCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; // ← eklendi

class ServicePackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_category_id', // nullable FK
        'name',
        'slug',
        'code',
        'description',
        'short_description',
        'currency',
        'currency_rate',
        'override_price',
        'show_price',
        'status',
        'sent_at',
        'accepted_at',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'currency_rate' => 'decimal:6',
        'override_price' => 'decimal:2',
        'show_price' => 'boolean',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Durumlar
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PUBLISHED = 'published';

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo(PackageCategory::class, 'package_category_id')->withDefault();
    }

    public function items()
    {
        return $this->hasMany(PackageItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function rootItems()
    {
        return $this->hasMany(PackageItem::class)->whereNull('parent_item_id')->orderBy('sort_order')->orderBy('id');
    }

    public function faqs()
    {
        return $this->morphMany(\App\Models\ServiceFaq::class, 'faqable')->orderBy('sort_order')->orderBy('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (mevcut + eklenenler)
    |--------------------------------------------------------------------------
    */
    public function scopeStatus($q, string $status)
    {
        return $q->where('status', $status);
    }

    /** Yayında olanlar (published_at <= now) */
    public function scopePublished($q)
    {
        return $q->where('status', self::STATUS_PUBLISHED)->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /** Detay sayfasında gereken eager load kalıbı */
    public function scopeWithDetail($q)
    {
        return $q->with([
            'category:id,name',
            'rootItems' => fn($q) => $q
                ->orderBy('sort_order')
                ->orderBy('id')
                ->with([
                    'children' => fn($c) => $c->orderBy('sort_order')->orderBy('id')->with('service'),
                    'service',
                ]),
            'faqs' => fn($q) => $q->orderBy('sort_order')->orderBy('id'),
        ]);
    }

    /** Menü / liste için hafif eager load */
    public function scopeWithCategory($q)
    {
        return $q->with(['category:id,name']);
    }

    /** Kategoriye göre filtre (nullable) */
    public function scopeInCategory($q, ?int $categoryId)
    {
        return $q->when($categoryId, fn($qq) => $qq->where('package_category_id', $categoryId));
    }

    /**
     * Aynı kategorideki paketler; kategorisizse sadece kendisi.
     * ->published() ile birlikte kullan.
     */
    public function scopePeersOf($q, self $pkg)
    {
        return $q->when($pkg->package_category_id, fn($qq) => $qq->where('package_category_id', $pkg->package_category_id), fn($qq) => $qq->where('id', $pkg->id));
    }

    /** Pricing matrisi için minimal item + service yükü */
    public function scopeWithItemsForPricing($q)
    {
        return $q->with([
            'items' => fn($iq) => $iq
                ->select(['id', 'service_package_id', 'parent_item_id', 'service_id', 'type', 'name', 'description', 'snapshot_json', 'sort_order'])
                ->orderBy('sort_order')
                ->orderBy('id'),
            'items.service:id,name',
        ]);
    }

    /**
     * Sıralama: eğer tabloda sort_order sütunu varsa ona göre, yoksa ada göre.
     * (Schema::hasColumn kontrolünü 1 kez yapıp cache’liyoruz.)
     */
    protected static ?bool $hasSortOrderColumn = null;

    public function scopeOrdered($q)
    {
        if (static::$hasSortOrderColumn === null) {
            static::$hasSortOrderColumn = Schema::hasColumn('service_packages', 'sort_order');
        }

        return static::$hasSortOrderColumn ? $q->orderBy('sort_order')->orderBy('name') : $q->orderBy('name');
    }

    /*
    |--------------------------------------------------------------------------
    | Totals
    |--------------------------------------------------------------------------
    */
    public function computedSubtotal(): float
    {
        return (float) $this->items->sum(fn(PackageItem $i) => $i->lineSubtotal());
    }

    public function computedTaxTotal(): float
    {
        return (float) $this->items->sum(fn(PackageItem $i) => $i->lineTaxAmount());
    }

    public function computedTotal(): float
    {
        return (float) $this->items->sum(fn(PackageItem $i) => $i->lineTotal());
    }

    public function finalTotal(): float
    {
        return !is_null($this->override_price) ? (float) $this->override_price : $this->computedTotal();
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /*
    |--------------------------------------------------------------------------
    | Boot (code & slug generation)
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (blank($model->currency)) {
                $model->currency = 'TRY';
            }

            if (blank($model->code)) {
                $model->code = static::generateUniqueCode();
            }

            if (blank($model->slug) && filled($model->name)) {
                $model->slug = static::generateUniqueSlug(name: $model->name, categoryId: $model->package_category_id);
            }
        });

        static::updating(function (self $model) {
            // İsim veya kategori değişmiş ve slug hâlâ boşsa otomatik üret
            if (($model->isDirty('name') || $model->isDirty('package_category_id')) && blank($model->slug)) {
                $model->slug = static::generateUniqueSlug(name: $model->name, categoryId: $model->package_category_id, ignoreId: $model->id);
            }
        });
    }

    protected static function generateUniqueCode(): string
    {
        $prefix = 'PKG-' . now()->format('Y-m-d') . '-';
        do {
            $code = $prefix . Str::upper(Str::random(4));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Kategori seçiliyse "kategori-slugu + paket adı" üzerinden; değilse sadece paket adına göre slug üretir.
     */
    protected static function generateUniqueSlug(string $name, ?int $categoryId = null, ?int $ignoreId = null): string
    {
        $baseString = static::buildSlugBase($categoryId, $name);
        $base = Str::slug($baseString, '-', 'tr') ?: 'paket';
        $slug = $base;
        $i = 2;

        // Soft-deleted kayıtları da kontrol ederek benzersizliği sağla
        $query = static::withTrashed();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $query = static::withTrashed();
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $i++;
        }

        return $slug;
    }

    /**
     * Slug için temel metni oluşturur: "kategori-slugu name" ya da sadece "name".
     */
    protected static function buildSlugBase(?int $categoryId, string $name): string
    {
        $parts = [];

        if ($categoryId) {
            // Soft-deleted kategori de dahil edilerek slug/name alınır
            $cat = PackageCategory::withTrashed()->find($categoryId);
            if ($cat) {
                $catSlug = $cat->slug ?: Str::slug($cat->name ?? '', '-', 'tr');
                if ($catSlug) {
                    $parts[] = $catSlug;
                }
            }
        }

        $parts[] = $name;

        // Arada boşluk bırakıp sonra Str::slug ile normalize edeceğiz
        return trim(implode(' ', $parts));
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /** Menü/başlık etiketi: kategori adı varsa onu, yoksa paket adı */
    public function getMenuLabelAttribute(): string
    {
        return $this->category?->name ?: (string) $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        return trim(($this->category?->name ? $this->category->name . ' ' : '') . ($this->name ?? ''));
    }
}
