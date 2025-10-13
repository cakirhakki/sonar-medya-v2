<?php

namespace App\Filament\Resources\PackageCategoryResource\Pages;

use App\Filament\Resources\PackageCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class CreatePackageCategory extends CreateRecord
{
    protected static string $resource = PackageCategoryResource::class;

    /**
     * Kayıt öncesi: Slug boşsa adı baz alarak otomatik üret.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['slug'] ?? null) && filled($data['name'] ?? null)) {
            $data['slug'] = Str::slug((string) $data['name'], '-', 'tr');
        }

        return $data;
    }

    /**
     * Başarılı oluşturma bildirimi.
     */
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Paket kategorisi oluşturuldu')
            ->success()
            ->send();
    }

    /**
     * Oluşturma sonrası liste sayfasına yönlendir.
     */
    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
