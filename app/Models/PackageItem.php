<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_package_id',
        'parent_item_id',
        'service_id',
        'type',
        'name',
        'description',
        'image_path', // ✅ eklendi
        'image_alt', // ✅ eklendi
        'unit',
        'qty',
        'unit_price',
        'discount_amount',
        'tax_rate_percent',
        'snapshot_json',
        'sort_order',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate_percent' => 'integer',
        'snapshot_json' => 'array',
        'sort_order' => 'integer',
    ];

    // Blade / API dönüşlerinde otomatik görünmesi için
    protected $appends = ['icon_class'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id');
    }

    public function parent()
    {
        return $this->belongsTo(PackageItem::class, 'parent_item_id');
    }

    public function children()
    {
        return $this->hasMany(PackageItem::class, 'parent_item_id')->orderBy('sort_order')->orderBy('id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class)->withDefault(); // nullable; silinse bile snapshot durur
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    public function scopeOfType($q, string $type)
    {
        return $q->where('type', $type);
    }

    /*
    |--------------------------------------------------------------------------
    | Computed: Icon class
    |--------------------------------------------------------------------------
    */
    public function getIconClassAttribute(): string
    {
        // 1) Elle girilmiş ikon (snapshot_json['icon']) öncelikli
        $icon = $this->snapshot_json['icon'] ?? null;
        if (is_string($icon) && trim($icon) !== '') {
            return trim($icon);
        }

        // 2) İsimden otomatik tahmin (Font Awesome Pro sınıfları)
        $name = mb_strtolower((string) $this->name);

        $map = [
            'domain' => 'fa-light fa-earth-europe',
            'dns' => 'fa-light fa-network-wired',
            'ga4' => 'fa-light fa-chart-simple',
            'analytics' => 'fa-light fa-chart-mixed',
            'gtm' => 'fa-light fa-boxes-stacked',
            'google ads' => 'fa-light fa-rectangle-ad',
            'ads' => 'fa-light fa-rectangle-ad',
            'hız|speed' => 'fa-light fa-gauge-high',
            'kurulum' => 'fa-light fa-screwdriver-wrench',
            'pixel' => 'fa-light fa-crop-simple',
        ];

        foreach ($map as $needle => $cls) {
            if (preg_match('/(' . $needle . ')/u', $name)) {
                return $cls;
            }
        }

        // 3) Fallback
        return 'fa-light fa-bolt';
    }

    /*
    |--------------------------------------------------------------------------
    | Line Totals (vergisiz / vergi / vergili)
    |--------------------------------------------------------------------------
    */
    public function lineSubtotal(): float
    {
        // (qty * unit_price) - discount
        $qty = (float) $this->qty;
        $price = (float) $this->unit_price;
        $disc = (float) $this->discount_amount;

        $subtotal = max(0, $qty * $price - $disc);
        return round($subtotal, 2);
    }

    public function lineTaxAmount(): float
    {
        $rate = (int) ($this->tax_rate_percent ?? 0);
        if ($rate <= 0) {
            return 0.0;
        }
        return round($this->lineSubtotal() * ($rate / 100), 2);
    }

    public function lineTotal(): float
    {
        return round($this->lineSubtotal() + $this->lineTaxAmount(), 2);
    }

    public function getImageUrlAttribute(): ?string
    {
        $p = $this->image_path;
        if (blank($p)) {
            return null;
        }
        return Str::startsWith($p, ['http://', 'https://', '//']) ? $p : asset('storage/' . $p);
    }
}
