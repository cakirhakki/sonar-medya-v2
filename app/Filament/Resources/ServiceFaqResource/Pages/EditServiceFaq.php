<?php

namespace App\Filament\Resources\ServiceFaqResource\Pages;

use App\Filament\Resources\ServiceFaqResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceFaq extends EditRecord
{
    protected static string $resource = ServiceFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Sil'),
        ];
    }

    public function getTitle(): string
    {
        return 'SSS Düzenle';
    }

    // KAYITTAN SONRA LİSTEYE DÖN
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
