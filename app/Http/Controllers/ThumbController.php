<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ThumbController extends Controller
{
    public function show(Request $r)
    {
        // 1) Geriye dönük uyumluluk: 'path' veya 'src' kabul et
        $path = $r->query('path');
        if (blank($path)) {
            $path = $r->query('src'); // yeni çağrılardan gelebilir
        }

        // Boyutlar (mevcut defaultları koruyoruz)
        $w   = (int) $r->query('w', 360);
        $h   = (int) $r->query('h', 260);
        $fit = (string) $r->query('fit', 'cover'); // cover|contain|crop
        $q   = (int) $r->query('q', 85);           // kalite (JPEG/WEBP/PNG için)

        // Basit güvenlik
        if (blank($path) || str_contains($path, '..')) {
            abort(404);
        }

        // 2) Normalize et
        $srcRel = urldecode($path);
        $srcRel = ltrim(str_replace('\\', '/', $srcRel), '/');

        // "storage/..." ile gelirse public disk'e göre kırp (storage:link varsayımı)
        if (Str::startsWith($srcRel, 'storage/')) {
            $srcRel = Str::after($srcRel, 'storage/');
        }

        // Whitelist – istenmeyen klasörleri engelle
        $allowed = ['site/', 'posts/', 'users/', 'avatars/', 'categories/', 'uploads/'];
        if (! Str::startsWith($srcRel, $allowed)) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($srcRel)) {
            abort(404);
        }

        // 3) Cache anahtarı (boyut + fit)
        //    Aynı kaynak için farklı boyut/fit istekleri ayrı saklansın
        $cacheRel = "cache/{$w}x{$h}-{$fit}/{$srcRel}";

        if (! $disk->exists($cacheRel)) {
            // Kaynak resmi oku
            $img = Image::read($disk->path($srcRel));

            // Eğer tek ölçü verilirse kare kabul et
            if ($w > 0 && $h === 0) { $h = $w; }
            if ($h > 0 && $w === 0) { $w = $h; }

            // Boyutlandırma
            if ($w > 0 || $h > 0) {
                switch ($fit) {
                    case 'contain':
                        // Kutuyu aşmadan sığdır (boş kenarlar kalabilir)
                        $img = $img->scaleDown(width: $w ?: null, height: $h ?: null);
                        break;

                    case 'crop':
                    case 'cover':
                    default:
                        // Orantıyı koruyarak merkezden kırp
                        $img = $img->cover($w ?: $h, $h ?: $w);
                        break;
                }
            }

            // Klasörü oluştur ve kaydet
            $disk->makeDirectory(dirname($cacheRel));
            // Not: Intervention 3.x formatı dosya uzantısına göre seçer (png/jpg/webp korur),
            // kalite parametresi uygunsa kullanılır.
            $img->save($disk->path($cacheRel), $q);
        }

        // 4) Dosyayı servis et
        return response()->file($disk->path($cacheRel));
    }
}
