<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Support\Rules\ServiceNameRule;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Filament’te çoğunlukla 'record', controller’da 'service' olabilir.
        $id = $this->route('record') ?? $this->route('service') ?? $this->route('id');

        return [
            'name'              => ['sometimes','required','string','max:150',
                                    new ServiceNameRule($id),
                                    Rule::unique('services','name')->ignore($id)],
            'slug'              => ['nullable','alpha_dash','max:160',
                                    Rule::unique('services','slug')->ignore($id)],
            'code'              => ['nullable','string','max:64',
                                    Rule::unique('services','code')->ignore($id)],

            'price'             => ['sometimes','required','numeric','min:0'],
            'duration_minutes'  => ['sometimes','required','integer','min:5','max:1440'],

            'is_active'         => ['sometimes','boolean'],
            'description'       => ['nullable','string','max:1000'],

            // 'category_id'    => ['nullable','integer', Rule::exists('service_categories','id')],
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
            'name'      => is_string($this->name) ? trim($this->name) : $this->name,
            'code'      => is_string($this->code) ? trim($this->code) : $this->code,
        ]);
    }
}
