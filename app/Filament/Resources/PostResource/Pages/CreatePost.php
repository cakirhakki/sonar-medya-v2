<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        // Kaydettikten sonra listeye dön
        return PostResource::getUrl();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Varsayılan yazar (yoksa oturum kullanıcısı)
        $data['author_id'] = $data['author_id'] ?? Filament::auth()->id();
        return $data;
    }
}
