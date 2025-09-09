<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    // Policy (Shield) otomatik devrede: create_user izni gerekir.

    // Kayıt oluşturulduktan sonra nereye?
    protected function getRedirectUrl(): string
    {
        // List sayfasına dön
        return static::getResource()::getUrl('index');
    }
}
