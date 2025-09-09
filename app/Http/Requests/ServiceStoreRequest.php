<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Support\Rules\ServiceNameRule;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gerekirse policy/permission ekleyebilirsin
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:150',
                                    new ServiceNameRule(),
                                    Rule::unique('services', 'name')],
            'slug'              => ['nullable', 'alpha_dash', 'max:160',
                                    Rule::unique('services', 'slug')],
            'code'              => ['nullable', 'string', 'max:64',
                                    Rule::unique('services', 'code')],

            'price'             => ['required', 'numeric', 'min:0'],
            'duration_minutes'  => ['required', 'integer', 'min:5', 'max:1440'],

            'is_active'         => ['sometimes', 'boolean'],
            'description'       => ['nullable', 'string', 'max:1000'],

            // kategori/id gibi başka alanların varsa burada ekle:
            // 'category_id'    => ['nullable','integer', Rule::exists('service_categories','id')],
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
            'name'      => is_string($this->name) ? trim($this->name) : $this->name,
            'code'      => is_string($this->code) ? trim($this->code) : $this->code,
        ]);
    }

    public function messages(): array
    {
        return [
            'price.min' => 'Fiyat negatif olamaz.',
            'duration_minutes.min' => 'Süre en az 5 dakika olmalıdır.',
        ];
    }
}
