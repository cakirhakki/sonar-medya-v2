<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    /**
     * Fiyat TL olarak tutulur (DECIMAL(10,2)).
     */
    protected $fillable = [
        'name',
        'summary',
        'duration_minutes',
        'price',      // TL (decimal 10,2)
        'is_active',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'price'            => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
