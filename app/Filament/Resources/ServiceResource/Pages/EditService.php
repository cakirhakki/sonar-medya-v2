<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Sil'),
        ];
    }

    public function getTitle(): string
    {
        return 'Hizmeti Düzenle';
    }

    /**
     * Formu doldurmadan hemen önce mevcut verileri **ek alanlarla** zenginleştir.
     * DİKKAT: Burada yalnızca ekleme/değiştirme yap, diğer anahtarları silme.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mevcut tag'leri dizi olarak form state'ine ekle
        try {
            $data['tags'] = $this->record->tags?->pluck('name')?->all() ?? [];
        } catch (\Throwable $e) {
            $data['tags'] = [];
        }

        return $data;
    }

    /**
     * Kaydetmeden önce form datasından sanal alanları çıkar.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['tags']);
        return $data;
    }

    protected function afterSave(): void
    {
        $tags = $this->form->getState()['tags'] ?? [];
        try {
            $this->record->syncTags($tags);
        } catch (\Throwable $e) {
            // sessiz geç
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
