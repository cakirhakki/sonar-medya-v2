<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\HasTags;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasTags;

    // protected $with = ['author','primaryCategory'];

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'featured_image_path', 'featured_image_alt', 'featured_image_caption', 'featured_image_credit_text', 'featured_image_credit_url', 'published_at', 'status', 'views', 'reading_time', 'author_id', 'primary_post_category_id', 'meta_title', 'meta_description', 'meta_image'];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'reading_time' => 'integer',
    ];

    protected $attributes = [
        'views' => 0,
    ];

    /* ---------------- Relationships ---------------- */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function primaryCategory()
    {
        return $this->belongsTo(PostCategory::class, 'primary_post_category_id');
    }

    /* ---------------- Scopes ---------------- */

    public function scopePublished($q)
    {
        return $q->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeScheduled($q)
    {
        return $q->where('status', 'scheduled')->whereNotNull('published_at')->where('published_at', '>', now());
    }

    public function scopeDraft($q)
    {
        return $q->where('status', 'draft');
    }

    /* ---------------- Routing ---------------- */

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* ---------------- Accessors ---------------- */

    // Kapak görseli için erişilebilir URL (harici ise aynen döner)
    public function getFeaturedImageUrlAttribute(): ?string
    {
        $p = $this->featured_image_path;
        if (!$p) {
            return null;
        }

        // Harici URL ise direkt dön
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            return $p;
        }

        // Yerel yolu normalize et
        $norm = (string) \Illuminate\Support\Str::of($p)->replace('\\', '/')->replaceStart('public/storage/', '')->replaceStart('/storage/', '')->replaceStart('public/', '')->ltrim('/');

        return asset('storage/' . $norm);
    }

    /* ---------------- Model Events / Helpers ---------------- */

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            // 1) Slug
            if (blank($post->slug) && filled($post->title)) {
                $post->slug = Str::slug($post->title);
            }
            if (filled($post->slug)) {
                $post->slug = static::ensureUniqueSlug($post->slug, $post->id);
            }

            // 2) Publish tarihi
            if ($post->status === 'published' && blank($post->published_at)) {
                $post->published_at = now();
            }

            // 3) Okuma süresi
            $text = static::collectTextForReadingTime($post);
            $wordCount = static::unicodeWordCount($text);
            $post->reading_time = max(1, (int) ceil($wordCount / 200)); // ~200 wpm
        });
    }

    protected static function ensureUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug);
        $original = $slug;
        $i = 2;

        $exists = static::query()->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->where('slug', $slug)->exists();

        while ($exists) {
            $slug = "{$original}-{$i}";
            $exists = static::query()->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->where('slug', $slug)->exists();
            $i++;
        }

        return $slug;
    }

    protected static function collectTextForReadingTime(Post $post): string
    {
        if (filled($post->content)) {
            return strip_tags((string) $post->content);
        }

        if (filled($post->excerpt)) {
            return (string) $post->excerpt;
        }

        return '';
    }

    protected static function unicodeWordCount(string $text): int
    {
        if ($text === '') {
            return 0;
        }
        preg_match_all('/[\p{L}\p{N}\']+/u', $text, $m);
        return count($m[0] ?? []);
    }

    // Thumbnail URL (Picsum için boyutlu, yerel için ThumbController, diğer harici için orijinal)
    public function thumbUrl(int $w = 360, int $h = 260, string $fit = 'cover'): ?string
    {
        $p = $this->featured_image_path;
        if (!$p) {
            return null;
        }

        // Harici URL
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            $host = parse_url($p, PHP_URL_HOST) ?? '';
            // Picsum ise aynı seed ile istenen boyutta döndür
            if (Str::contains($host, 'picsum.photos')) {
                $path = parse_url($p, PHP_URL_PATH) ?? '';
                $segs = array_values(array_filter(explode('/', $path)));
                $i = array_search('seed', $segs, true);
                if ($i !== false && isset($segs[$i + 1])) {
                    $seed = $segs[$i + 1];
                    return "https://picsum.photos/seed/{$seed}/{$w}/{$h}";
                }
                return "https://picsum.photos/{$w}/{$h}";
            }
            // Diğer harici kaynaklar: kırpma yapmadan orijinali ver
            return $p;
        }

        // Yerel dosya → ThumbController
        $src = (string) Str::of($p)->replace('\\', '/')->replaceStart('public/storage/', '')->replaceStart('/storage/', '')->replaceStart('public/', '')->ltrim('/');

        return route('thumb', ['src' => $src, 'w' => $w, 'h' => $h, 'fit' => $fit]);
    }

    // Kök yorumlar (parent_id null)
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable')->whereNull('parent_id');
    }

    // Tüm yorumlar
    public function allComments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }

    
    
}
