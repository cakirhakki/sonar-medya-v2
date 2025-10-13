<?php

namespace App\Filament\Resources\ServiceFaqResource\Pages;

use App\Filament\Resources\ServiceFaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceFaqs extends ListRecords
{
    protected static string $resource = ServiceFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Yeni SSS'),
        ];
    }

    public function getTitle(): string
    {
        return 'Hizmet SSS';
    }
}
