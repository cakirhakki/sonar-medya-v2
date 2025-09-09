<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServicePackage extends Model
{
    protected $fillable = ['name', 'summary', 'discount_percent', 'is_active'];
    protected $casts = [
        'discount_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'package_service')
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    public function getComputedDurationMinutesAttribute(): int
    {
        return $this->services->sum(fn($service) => $service->duration_minutes * $service->pivot->quantity);
    }

    public function getComputedPriceAttribute(): float
    {
        $sum = $this->services->sum(fn($service) => $service->price * $service->pivot->quantity);
        $final = max($sum * (1 - $this->discount_percent / 100), 0);
        return $final;
    }

    public function getComputedGrossPriceAttribute(): float
    {
        // İndirimsiz toplam = ∑ (hizmet fiyatı × adet)
        return (float) $this->services->sum(fn($s) => (float) $s->price * (int) ($s->pivot->quantity ?? 1));
    }
}
