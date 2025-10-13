<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Profile;
use App\Filament\Widgets\CalendarWidget;
use App\Models\SiteSetting;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Tek kayıtlı site ayarını (varsa) güvenli şekilde getirir.
     * DB/tablo yoksa veya kayıt yoksa null döner.
     */
    protected function site(): ?SiteSetting
    {
        // Model class'ı ve tablo var mı?
        if (!class_exists(SiteSetting::class) || !Schema::hasTable('site_settings')) {
            return null;
        }

        return Cache::remember('site_settings_single', now()->addMinutes(60), fn() => SiteSetting::query()->first());
    }

    /**
     * Filament panel tanımı
     */
    public function panel(Panel $panel): Panel
    {
        $site = $this->site();

        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')

            // Guard
            ->authGuard('admin')

            // Auth akışı
            ->login()
            // ->registration()
            ->passwordReset();

        if (app()->environment('production')) {
            $panel->emailVerification();
        }

        return $panel
            // ---- Marka / Kimlik (SiteSetting yoksa config('app.name') ile devam)
            ->brandName($site?->site_title ?? config('app.name'))
            ->brandLogo(fn() => $site?->logo_url) // null dönebilir, Filament tolere eder
            ->favicon(fn() => $site?->favicon_url ?? asset('favicon.ico')) // panel favicon

            // Tema rengi
            ->colors([
                'primary' => Color::Amber,
            ])

            // Resource & Page keşfi
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')

            // Panel sayfaları
            ->pages([Pages\Dashboard::class, Profile::class])

            // 🔌 Pluginler
            // ->plugins([
            //     FilamentShieldPlugin::make(),
            //     FilamentFullCalendarPlugin::make(),
            // ])

            // Widget’lar
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([Widgets\AccountWidget::class, Widgets\FilamentInfoWidget::class, CalendarWidget::class])

            // Kullanıcı menüsü
            ->userMenuItems([MenuItem::make()->label('Profilim')->icon('heroicon-m-user-circle')->url(fn(): string => Profile::getUrl())])

            // Middleware
            ->middleware([EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class])
            ->authMiddleware([Authenticate::class]);
    }

    /**
     * v3: Custom CSS/JS eklemek için render hook kullan.
     * public/filament-custom.css ve public/filament-custom.js varsa yükler.
     */
    public function boot(): void
    {
        // CSS
        $cssPath = public_path('filament-custom.css');
        if (file_exists($cssPath)) {
            $href = asset('filament-custom.css') . '?v=' . @filemtime($cssPath);

            FilamentView::registerRenderHook(
                'panels::head.end',
                fn() => <<<HTML
                <link rel="stylesheet" href="{$href}">
                HTML,
            );
        }

        // JS
        $jsPath = public_path('filament-custom.js');
        if (file_exists($jsPath)) {
            $src = asset('filament-custom.js') . '?v=' . @filemtime($jsPath);

            FilamentView::registerRenderHook(
                'panels::body.end',
                fn() => <<<HTML
                <script src="{$src}"></script>
                HTML,
            );
        }
    }
}
