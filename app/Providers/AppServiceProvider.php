<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteSetting;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as FilamentLogoutResponse;
use App\View\Components\BrandSliders;
use App\View\Components\AboutGallery;
use Illuminate\Support\Facades\Blade;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FilamentLogoutResponse::class, function () {
            return new class implements FilamentLogoutResponse {
                public function toResponse($request) { return redirect()->route('site.home'); }
            };
        });
    }

    public function boot(): void
    {
        // Sadece site ayarını paylaş
        $site = null;
        if (Schema::hasTable('site_settings')) {
            $site = Cache::remember('site_settings_single', now()->addMinutes(60),
                fn() => SiteSetting::query()->first()
            );
        }
        View::share('site', $site);
        Blade::component('brand-sliders', BrandSliders::class);
        Blade::component('about-gallery', AboutGallery::class);
    }
}
