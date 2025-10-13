<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFaq extends Model
{
    use HasFactory;

    protected $table = 'service_faqs';

    protected $fillable = [
        'faqable_type',
        'faqable_id',
        'question',
        'answer',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // İlişki
    public function faqable()
    {
        return $this->morphTo();
    }

    // Sıralama & arama (opsiyonel)
    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    public function scopeSearch($q, ?string $term)
    {
        if (blank($term)) return $q;
        $term = trim($term);
        return $q->where(function ($qq) use ($term) {
            $qq->where('question', 'like', "%{$term}%")
               ->orWhere('answer', 'like', "%{$term}%");
        });
    }

    /* ------------ Accessors: tablo closure’sız, ::class’sız kullanacağız ------------ */

    public function getFaqableNameAttribute(): string
    {
        // İlişkideki kaydın adı; yoksa ID fallback
        return $this->faqable?->name ?: "ID #{$this->faqable_id}";
    }

    public function getFaqableTypeLabelAttribute(): string
    {
        // null-güvenli sınıf adı çözümü (değişken üstünde ::class yok!)
        $base = class_basename((string) $this->faqable_type);
        return $base === 'Service' ? 'Hizmet'
             : ($base === 'ServicePackage' ? 'Paket' : '—');
    }

    
}
