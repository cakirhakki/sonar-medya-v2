<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostCategory extends EditRecord
{
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            // SoftDeletes varsa:
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
        ];
    }

    // "Güncelle" butonuna basınca listeye dön
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
