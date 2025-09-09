<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Listeye dön')
                ->icon('heroicon-o-arrow-left')
                ->url(CustomerResource::getUrl()),   // index sayfasına döner
        ];
    }

    /**
     * Kayıt oluşturulmadan hemen önce telefon numarasını normalize et.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['phone'])) {
            $data['phone'] = \App\Support\Rules\PhoneNumberRule::normalize($data['phone']);
        }

        return $data;
    }

    /**
     * Oluşturma bittiğinde nereye yönlendirileceği.
     */
    protected function getRedirectUrl(): string
    {
        return CustomerResource::getUrl(); // -> /customers (index)
    }
}
