<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;                      // ✅ Doğru namespace
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array   // ✅ v3'te bu isim kullanılır
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
