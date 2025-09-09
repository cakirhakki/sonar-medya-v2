<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\Rules\PhoneNumberRule;

class CustomerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gerekirse policy ile sınırlandırırsın
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],

            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('customers', 'email'),
            ],

            // Avatar dosyası (opsiyonel)
            // image => jpeg/png/bmp/gif/svg/webp
            // mimes ile daraltıyoruz; max:2048 -> 2MB
            'avatar_path' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'phone' => [
                'nullable',
                new PhoneNumberRule(),
                Rule::unique('customers', 'phone'),
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
