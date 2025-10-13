<?php

namespace App\Services;

use App\Models\ServicePackage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PricingTableBuilder
{
    public function __construct(private ServicePackageFinder $finder) {}

    /**
     * Aynı kategorideki paketleri alır, uniq özellik listesini ve paket×özellik matrisini üretir.
     * Dönen diziyi doğrudan view’a gönderebilirsin.
     */
    public function build(ServicePackage $package): array
    {
        $packages = $this->finder->peersOf($package);
        [$features, $featureKeys, $matrix] = $this->buildMatrix($packages);

        return [
            'packages'      => $packages,          // Collection<ServicePackage>
            'features'      => $features,          // key => ['label','icon','tooltip']
            'featureKeys'   => $featureKeys,       // ['key1','key2',...]
            'matrix'        => $matrix,            // [packageId => [key => true]]
            'highlight_id'  => $package->id,       // “Best Choice” için referans
        ];
    }

    protected function buildMatrix(Collection $packages): array
    {
        $features = [];
        $matrix   = [];

        $keyOf = function (?int $serviceId, ?string $name): string {
            if ($serviceId) return 'svc:' . $serviceId;
            $norm = trim(mb_strtolower((string) $name));
            return 'name:' . Str::slug($norm, '-', 'tr');
        };

        foreach ($packages as $p) {
            $matrix[$p->id] = $matrix[$p->id] ?? [];

            foreach ($p->items as $item) {
                // İstersen başlık/grup tiplerini dışarıda bırak:
                // if (in_array($item->type, ['heading','group'])) continue;

                $label = $item->service?->name ?: ($item->name ?: null);
                if (!$label) continue;

                $key = $keyOf($item->service_id, $label);

                if (!isset($features[$key])) {
                    $icon = method_exists($item, 'getIconClassAttribute') ? $item->icon_class : null;
                    $tip  = $item->description ?: ($item->snapshot_json['tooltip'] ?? null);
                    $features[$key] = ['label' => $label, 'icon' => $icon, 'tooltip' => $tip];
                }

                $matrix[$p->id][$key] = true;
            }
        }

        // Ada göre sort
        uasort($features, fn($a, $b) => strcasecmp($a['label'], $b['label']));
        $featureKeys = array_keys($features);

        return [$features, $featureKeys, $matrix];
    }
}
