<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Http\Requests\ServiceStoreRequest;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Validator;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    /**
     * Kaydetmeden önce sunucu tarafı doğrulama (FormRequest).
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Validator::make(
            $data,
            (new ServiceStoreRequest())->rules(),
            (new ServiceStoreRequest())->messages(),
            (new ServiceStoreRequest())->attributes()
        )->validate();

        return $data;
    }

    /**
     * Kayıt sonrası listeye dön.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Hizmet Oluştur';
    }
}
