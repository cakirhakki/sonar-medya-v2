<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image_path',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ---------------- Relationships ---------------- */

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'primary_post_category_id');
    }

    /* ---------------- Scopes ---------------- */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* ---------------- Accessors ---------------- */

    public function getImageUrlAttribute(): ?string
    {
        $p = $this->image_path;
        if (!$p) return null;

        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            return $p;
        }

        $normalized = (string) Str::of($p)
            ->replace('\\', '/')
            ->replaceStart('public/', '')
            ->replaceStart('/storage/', '');

        return asset('storage/' . ltrim($normalized, '/'));
    }

    /* ---------------- Model Events ---------------- */

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if (blank($model->slug) && filled($model->name)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
