<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostCategory extends CreateRecord
{
    protected static string $resource = PostCategoryResource::class;

    // "Oluştur" butonuna basınca listeye dön
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
