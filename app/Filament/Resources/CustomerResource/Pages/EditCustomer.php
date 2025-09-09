<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(), // ✅ üst kısımda "Sil" butonu
        ];
    }

    /**
     * Kayıt güncellenmeden hemen önce telefon numarasını normalize et.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['phone'])) {
            $data['phone'] = \App\Support\Rules\PhoneNumberRule::normalize($data['phone']);
        }

        return $data;
    }

    /**
     * Güncelleme bittiğinde nereye yönlendirileceği.
     */
    protected function getRedirectUrl(): string
    {
        return CustomerResource::getUrl(); // -> /customers (index)
    }
}
