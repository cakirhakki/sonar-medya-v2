<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Http\Requests\ServiceUpdateRequest;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Validator;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    /**
     * Kaydetmeden önce sunucu tarafı doğrulama (FormRequest).
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        Validator::make(
            $data,
            (new ServiceUpdateRequest())->rules(),
            (new ServiceUpdateRequest())->messages(),
            (new ServiceUpdateRequest())->attributes()
        )->validate();

        return $data;
    }

    /**
     * Güncelleme sonrası listeye dön.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Hizmeti Düzenle';
    }
}
