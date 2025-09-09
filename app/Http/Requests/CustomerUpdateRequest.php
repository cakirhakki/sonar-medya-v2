<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\Rules\PhoneNumberRule;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gerekirse policy ile sınırlandır
    }

    public function rules(): array
    {
        // Kayıt id’sini farklı senaryolara göre yakala:
        // - Filament edit: route('record')
        // - Web route (resource): route('customer')
        // - Profil sayfası: authenticated user id
        $id = $this->route('record')
            ?? $this->route('customer')
            ?? $this->input('id')
            ?? optional($this->user())->id;

        return [
            'name'  => ['sometimes', 'required', 'string', 'max:255'],

            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('customers', 'email')->ignore($id),
            ],

            // Avatar dosyası (opsiyonel)
            'avatar_path' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'phone' => [
                'nullable',
                new PhoneNumberRule(),
                Rule::unique('customers', 'phone')->ignore($id),
            ],

            'birth_date' => ['nullable', 'date', 'before:today'],

            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],

            'address' => ['nullable', 'string', 'max:255'],

            'loyalty_points' => ['nullable', 'integer', 'min:0'],

            'receive_newsletters' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->filled('phone')) {
            $merge['phone'] = PhoneNumberRule::normalize($this->input('phone'));
        }

        if ($this->has('receive_newsletters')) {
            $merge['receive_newsletters'] = $this->boolean('receive_newsletters');
        }

        $this->merge($merge);
    }

    public function messages(): array
    {
        return [
            'avatar_path.image' => 'Avatar bir resim dosyası olmalıdır.',
            'avatar_path.mimes' => 'Avatar yalnızca jpg, jpeg, png veya webp olmalıdır.',
            'avatar_path.max'   => 'Avatar dosyası en fazla 2MB olabilir.',
        ];
    }
}
