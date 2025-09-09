<?php

namespace App\Filament\Resources\ServicePackageResource\Pages;

use App\Filament\Resources\ServicePackageResource;
use App\Http\Requests\ServicePackageUpdateRequest;
use App\Support\Rules\ServicePackageRules as R;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EditServicePackage extends EditRecord
{
    protected static string $resource = ServicePackageResource::class;

    /** @var array<int,array{service_id:int,quantity:int}>|null */
    protected ?array $itemsToSync = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['items'] = $this->record->services()
            ->get()
            ->map(fn ($s) => [
                'service_id' => (int) $s->id,
                'quantity'   => max(1, (int) $s->pivot->quantity),
            ])
            ->values()
            ->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $id = (int) $this->record->getKey();

        // Trim normalize
        if (array_key_exists('name', $data)) {
            $data['name'] = trim((string) $data['name']);
        }
        if (array_key_exists('summary', $data)) {
            $data['summary'] = trim((string) $data['summary']);
        }

        // İndirim boş string gelirse null yap (nullable decimal)
        if (array_key_exists('discount_percent', $data) && $data['discount_percent'] === '') {
            $data['discount_percent'] = null;
        }

        // items gönderildiyse pivot'u güncelleyeceğiz; gönderilmediyse dokunmayacağız
        $hasItemsKey = array_key_exists('items', $data);

        $rules = [
            // Edit: sadece gönderilmişse doğrula
            'name'             => R::name($id, required: false),
            'summary'          => R::summary(),
            'discount_percent' => R::discountPercent(),
            'is_active'        => R::isActive(),
        ];

        if ($hasItemsKey) {
            $rules += [
                'items'              => R::itemsSometimes(),
                'items.*.service_id' => R::itemsServiceId(),
                'items.*.quantity'   => R::itemsQuantity(),
            ];
        }

        Validator::make(
            $data,
            $rules,
            (new ServicePackageUpdateRequest())->messages(),
            (new ServicePackageUpdateRequest())->attributes()
        )->validate();

        if ($hasItemsKey) {
            $this->itemsToSync = $data['items'] ?? [];
            unset($data['items']);
        } else {
            $this->itemsToSync = null; // pivot'u elleme
        }

        return $data;
    }

    /**
     * Kaydetme + pivot güncelleme işlemlerini tek transaction içinde yap.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $record->fill($data)->save();

            if ($this->itemsToSync !== null) {
                $record->services()->sync($this->mapItemsForSync($this->itemsToSync));
            }

            return $record;
        });
    }

    // Kaydetten sonra listeye dön
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
