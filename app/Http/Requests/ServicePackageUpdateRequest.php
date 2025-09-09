<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Support\Rules\AtLeastOneServiceRule;

class ServicePackageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('record') ?? $this->route('service_package') ?? $this->route('id');

        return [
            'name'              => ['sometimes', 'required', 'string', 'max:150',
                                    Rule::unique('service_packages', 'name')->ignore($id)],
            'slug'              => ['nullable', 'alpha_dash', 'max:160',
                                    Rule::unique('service_packages', 'slug')->ignore($id)],
            'code'              => ['nullable', 'string', 'max:64',
                                    Rule::unique('service_packages', 'code')->ignore($id)],

            'price'             => ['sometimes', 'required', 'numeric', 'min:0'],
            'duration_minutes'  => ['sometimes', 'required', 'integer', 'min:5', 'max:1440'],

            'is_active'         => ['sometimes', 'boolean'],
            'description'       => ['nullable', 'string', 'max:1000'],

            'services'          => ['sometimes', 'array', new AtLeastOneServiceRule()],
            'services.*'        => ['integer', 'distinct', Rule::exists('services', 'id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');
        if (!$slug && $this->filled('name')) {
            $slug = Str::slug($this->input('name'));
        }

        $this->merge([
            'slug'      => $slug ?: null,
            'is_active' => $this->has('is_active') ? (bool) $this->boolean('is_active') : $this->input('is_active'),
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
