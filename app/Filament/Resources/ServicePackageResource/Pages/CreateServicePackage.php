<?php

namespace App\Filament\Resources\ServicePackageResource\Pages;

use App\Filament\Resources\ServicePackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServicePackage extends CreateRecord
{
    protected static string $resource = ServicePackageResource::class;

    public function getTitle(): string
    {
        return 'Yeni Paket';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Paket oluşturuldu';
    }

    protected function getRedirectUrl(): string
    {
          return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
