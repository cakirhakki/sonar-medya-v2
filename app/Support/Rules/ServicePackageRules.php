<?php

namespace App\Support\Rules;

use Illuminate\Validation\Rule;

class ServicePackageRules
{
    /**
     * Paket adı kuralı.
     * $required = true => required|string|...
     * $required = false => sometimes|string|...
     */
    public static function name(?int $ignoreId = null, bool $required = true): array
    {
        $unique = Rule::unique('service_packages', 'name');
        if ($ignoreId) {
            $unique = $unique->ignore($ignoreId);
        }

        $base = ['string', 'max:160', $unique];

        if ($required) {
            array_unshift($base, 'required');
        } else {
            array_unshift($base, 'sometimes');
        }

        return $base;
    }

    public static function summary(): array
    {
        return ['nullable', 'string', 'max:2000'];
    }

    public static function discountPercent(): array
    {
        return ['nullable', 'numeric', 'min:0', 'max:100'];
    }

    public static function isActive(): array
    {
        return ['boolean'];
    }

    /** Create için en az 1 item; Update'te opsiyonel kullanacağız */
    public static function itemsRequired(): array
    {
        return ['required', 'array', 'min:1'];
    }

    /** Update için yalnızca gönderildiyse kontrol */
    public static function itemsSometimes(): array
    {
        return ['sometimes', 'array', 'min:1'];
    }

    public static function itemsServiceId(): array
    {
        return ['required', 'integer', 'exists:services,id'];
    }

    public static function itemsQuantity(): array
    {
        return ['required', 'integer', 'min:1', 'max:50'];
    }
}
