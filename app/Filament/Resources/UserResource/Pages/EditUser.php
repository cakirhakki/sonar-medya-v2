<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Kaydet (Değişiklikleri Kaydet) işleminden sonra nereye gitsin?
     * -> Kullanıcılar listesine dön.
     */
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    /**
     * Başlıktaki aksiyonlar (butonlar).
     * DeleteAction: delete_user izni varsa görünür/çalışır.
     * İptal: her zaman listeye döner.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('cancel')
                ->label('İptal')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
    // Not: Policy (Shield) otomatik devrede: update_user / delete_user izinleri gerekir.
}
