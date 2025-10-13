<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceCategory extends CreateRecord
{
    protected static string $resource = ServiceCategoryResource::class;

    public function getTitle(): string
    {
        return 'Yeni Kategori';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Kategori oluşturuldu';
    }

    // KAYITTAN SONRA LİSTEYE DÖN
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
