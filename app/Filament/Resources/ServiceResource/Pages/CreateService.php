<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    public function getTitle(): string
    {
        return 'Yeni Hizmet';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Hizmet oluşturuldu';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 'tags' veritabanı alanı değil; kayıttan sonra sync edilecek.
        unset($data['tags']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $tags = $this->data['tags'] ?? [];
        try {
            $this->record->syncTags($tags);
        } catch (\Throwable $e) {
            // Tag tabloları yoksa sessiz geç
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
