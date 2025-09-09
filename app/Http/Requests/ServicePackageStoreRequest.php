<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Support\Rules\AtLeastOneServiceRule;

class ServicePackageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // policy/permission eklemek istersen burada sınırla
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:150',
                                    Rule::unique('service_packages', 'name')],
            'slug'              => ['nullable', 'alpha_dash', 'max:160',
                                    Rule::unique('service_packages', 'slug')],
            'code'              => ['nullable', 'string', 'max:64',
                                    Rule::unique('service_packages', 'code')],

            // fiyat/süre temel alanları — isimler projendeki migration'a göre olabilir
            'price'             => ['required', 'numeric', 'min:0'],
            'duration_minutes'  => ['required', 'integer', 'min:5', 'max:1440'],

            'is_active'         => ['sometimes', 'boolean'],
            'description'       => ['nullable', 'string', 'max:1000'],

            // Paket hangi hizmetleri içeriyor? (Filament Repeater/CheckboxList vs.)
            // Örn: services => [1,2,3]
            'services'          => ['sometimes', 'array', new AtLeastOneServiceRule()],
            'services.*'        => ['integer', 'distinct', Rule::exists('services', 'id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        // slug boşsa isimden üret
        $slug = $this->input('slug');
        if (!$slug && $this->filled('name')) {
            $slug = Str::slug($this->input('name'));
        }

        $this->merge([
            'slug'      => $slug ?: null,
            'is_active' => (bool) $this->boolean('is_active'),
            'code'      => is_string($this->input('code')) ? trim($this->input('code')) : $this->input('code'),
        ]);
    }

    public function messages(): array
    {
        return [
            'services.*.distinct' => 'Paket içinde aynı hizmet birden fazla kez seçilemez.',
            'services.*.exists'   => 'Seçilen hizmetlerden bazıları bulunamadı.',
        ];
    }
}
