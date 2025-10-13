<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Resources\Pages\ListRecords;

class ManageSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return []; // create vb. buton yok
    }

    public function mount(): void
    {
        $record = SiteSetting::firstOrCreate(['id' => 1]);
        $this->redirect(
            SiteSettingResource::getUrl('edit', ['record' => $record->getKey()])
        );
    }
}
