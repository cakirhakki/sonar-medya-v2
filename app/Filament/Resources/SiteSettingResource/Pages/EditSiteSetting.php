<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    public function mount($record = null): void
    {
        $record = SiteSetting::firstOrCreate(['id' => 1]);
        parent::mount($record->getKey());
    }

    protected function getHeaderActions(): array
    {
        return []; // Sil vb. buton yok
    }

    protected function afterSave(): void
    {
        // Senin kullandığın anahtarı unut:
        Cache::forget('site_settings_single');
        Cache::forget('site_settings');
    }

    /** Kayıt sonrası bildirimi biz oluşturuyoruz (title ZORUNLU). */
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Kaydedildi')
            ->body('Site ayarları başarıyla güncellendi.')
            ->success();
    }
}
