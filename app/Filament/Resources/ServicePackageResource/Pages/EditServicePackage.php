<?php

namespace App\Filament\Resources\ServicePackageResource\Pages;

use App\Filament\Resources\ServicePackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServicePackage extends EditRecord
{
    protected static string $resource = ServicePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Sil'),
        ];
    }

    public function getTitle(): string
    {
        return 'Paketi Düzenle';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
