<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCategory extends EditRecord
{
    protected static string $resource = ServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Sil'),
        ];
    }

    public function getTitle(): string
    {
        return 'Kategori Düzenle';
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Kategori güncellendi';
    }

    // KAYITTAN SONRA LİSTEYE DÖN
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
