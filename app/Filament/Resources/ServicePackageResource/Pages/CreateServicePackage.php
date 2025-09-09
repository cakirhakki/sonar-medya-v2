<?php

namespace App\Filament\Resources\ServicePackageResource\Pages;

use App\Filament\Resources\ServicePackageResource;
use App\Http\Requests\ServicePackageStoreRequest;
use App\Support\Rules\ServicePackageRules as R;     // <-- KURALLARI BURADAN KULLAN
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Validator;

class CreateServicePackage extends CreateRecord
{
    protected static string $resource = ServicePackageResource::class;

    /** @var array<int,array{service_id:int,quantity:int}> */
    protected array $itemsToSync = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1) boş string indirim -> null (nullable numeric)
        if (array_key_exists('discount_percent', $data) && $data['discount_percent'] === '') {
            $data['discount_percent'] = null;
        }

        // 2) Doğrulamayı R kurallarıyla yap, validate() dönüşünü $data'ya ata
        $rules = [
            'name'                 => R::name(),
            'summary'              => R::summary(),
            'discount_percent'     => R::discountPercent(),
            'is_active'            => R::isActive(),

            'items'                => R::itemsRequired(),   // create’de zorunlu
            'items.*.service_id'   => R::itemsServiceId(),
            'items.*.quantity'     => R::itemsQuantity(),
        ];

        $data = Validator::make(
            $data,
            $rules,
            (new ServicePackageStoreRequest())->messages(),
            (new ServicePackageStoreRequest())->attributes()
        )->validate();

        // 3) Pivot verisini kenara al
        $this->itemsToSync = $data['items'] ?? [];
        unset($data['items']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->services()->sync($this->mapItemsForSync($this->itemsToSync));
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Oluştur')
            ->successRedirectUrl($this->getResource()::getUrl('index'))
            ->after(fn () => $this->redirect($this->getResource()::getUrl('index')));
    }

    public function getTitle(): string
    {
        return 'Paket Oluştur';
    }

    /**
     * @param array<int,array{service_id?:int,quantity?:int}> $items
     */
    private function mapItemsForSync(array $items): array
    {
        return collect($items)
            ->mapWithKeys(function ($item) {
                $sid = (int) ($item['service_id'] ?? 0);
                $qty = max(1, (int) ($item['quantity'] ?? 1));
                return $sid > 0 ? [$sid => ['quantity' => $qty]] : [];
            })
            ->all();
    }
}
