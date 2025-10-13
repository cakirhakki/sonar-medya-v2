<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        // Meta / genel
        'site_title','meta_title','meta_description','copyright_text',

        // İletişim
        'company_name','tax_office','tax_number','phone','mobile','email','address','google_map_url',

        // WhatsApp FAB
        'show_whatsapp_fab','whatsapp',

        // Sosyal
        'facebook','instagram','twitter','linkedin','youtube',

        // Görseller (public disk relative path)
        'logo_path','favicon_path','meta_image_path',

        // JSON
        'bank_accounts','footer_description',

        // Analitik / Kod
        'ga_measurement_id','gtm_id','meta_pixel_id','head_scripts','body_scripts',

        // Hakkımızda Galerisi (ayarlar)
        'about_gallery_enabled',
        'about_gallery_max_items',
        'about_gallery_aspect_ratio',
        'about_gallery_note',
    ];

    protected $casts = [
        'show_whatsapp_fab'        => 'boolean',
        'bank_accounts'            => 'array',
        'about_gallery_enabled'    => 'boolean',
        'about_gallery_max_items'  => 'integer',
    ];

    /* -------------------- Media Library -------------------- */

    public function registerMediaCollections(): void
    {
        // Hakkımızda galerisi: çoklu, public disk
        $this->addMediaCollection('about_gallery')->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // 16:9 dönüşümler (blade'de getUrl('about_lg') vb. kullanılabilir)
        $this->addMediaConversion('about_lg')->fit(Fit::Crop, 1200, 675)->format('webp')->optimize();
        $this->addMediaConversion('about_md')->fit(Fit::Crop, 992, 558)->format('webp')->optimize();
        $this->addMediaConversion('about_sm')->fit(Fit::Crop, 768, 432)->format('webp')->optimize();
    }

    /* -------------------- Public URL helpers -------------------- */

    private function makePublicUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        return asset('storage/' . ltrim($path, '/'));
    }

    public function getLogoUrlAttribute(): ?string      { return $this->makePublicUrl($this->logo_path); }
    public function getFaviconUrlAttribute(): ?string   { return $this->makePublicUrl($this->favicon_path); }
    public function getMetaImageUrlAttribute(): ?string { return $this->makePublicUrl($this->meta_image_path); }

    /* -------------------- Phone / WhatsApp normalize -------------------- */

    private function normalizePhone(?string $raw): ?string
    {
        if (!$raw) return null;
        $d = preg_replace('/\D+/', '', $raw);

        if (strlen($d) === 10) {             // 5xx xxx xx xx
            $d = '90' . $d;
        } elseif (strlen($d) === 11 && str_starts_with($d, '0')) {
            $d = '9' . substr($d, 1);
        } elseif (str_starts_with($d, '00')) {
            $d = substr($d, 2);
        }

        return $d ?: null;
    }

    protected function phoneE164(): Attribute
    {
        return Attribute::get(fn () => $this->normalizePhone($this->phone));
    }

    protected function mobileE164(): Attribute
    {
        return Attribute::get(fn () => $this->normalizePhone($this->mobile));
    }

    protected function primaryTelHref(): Attribute
    {
        return Attribute::get(function () {
            $e164 = $this->phone_e164 ?: $this->mobile_e164;
            return $e164 ? 'tel:+' . $e164 : null;
        });
    }

    protected function primaryTelDisplay(): Attribute
    {
        return Attribute::get(fn () => $this->phone ?: $this->mobile);
    }

    protected function whatsappE164(): Attribute
    {
        return Attribute::get(fn () => $this->normalizePhone($this->whatsapp));
    }

    protected function whatsappUrl(): Attribute
    {
        return Attribute::get(fn () => $this->whatsapp_e164 ? "https://wa.me/{$this->whatsapp_e164}" : null);
    }

    /* -------------------- Thumb helper -------------------- */

    /** ✅ ThumbController’a URL değil, storage relative PATH gönder */
    public function thumbLogo(int $w = 120, int $h = 120, string $fit = 'cover'): ?string
    {
        if (!$this->logo_path) return null;
        $src = str_replace('\\','/', ltrim($this->logo_path, '/')); // örn: site/01K5....png
        return route('thumb', ['src' => $src, 'w' => $w, 'h' => $h, 'fit' => $fit]);
    }
}
