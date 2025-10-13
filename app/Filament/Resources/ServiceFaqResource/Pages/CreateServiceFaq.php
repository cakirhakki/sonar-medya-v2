<?php

namespace App\Filament\Resources\ServiceFaqResource\Pages;

use App\Filament\Resources\ServiceFaqResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceFaq extends CreateRecord
{
    protected static string $resource = ServiceFaqResource::class;

    public function getTitle(): string
    {
        return 'Yeni SSS';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'SSS oluşturuldu';
    }

    // KAYITTAN SONRA LİSTEYE DÖN
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
