<?php

namespace App\Filament\Resources\PackageCategoryResource\Pages;

use App\Filament\Resources\PackageCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class EditPackageCategory extends EditRecord
{
    protected static string $resource = PackageCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    /**
     * Kayıt öncesi: Slug boşsa adı baz alarak otomatik üret.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (blank($data['slug'] ?? null) && filled($data['name'] ?? null)) {
            $data['slug'] = Str::slug((string) $data['name'], '-', 'tr');
        }

        return $data;
    }

    /**
     * Başarılı güncelleme bildirimi.
     */
    protected function afterSave(): void
    {
        Notification::make()
            ->title('Paket kategorisi güncellendi')
            ->success()
            ->send();
    }

    /**
     * Güncelleme sonrası liste sayfasına yönlendir.
     */
    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
