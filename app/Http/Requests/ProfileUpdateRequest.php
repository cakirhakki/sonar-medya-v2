<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\Rules\PhoneNumberRule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Profil formu doğrulama kuralları (Customer için).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customer = $this->user('customer');

        return [
            'name'  => ['required', 'string', 'max:255'],

            // Email benzersiz; mevcut kaydı es geç
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('customers', 'email')->ignore($customer?->id),
            ],

            // Telefon: özel kural + benzersiz; mevcut kaydı es geç
            'phone' => [
                'nullable',
                'string',
                'max:30',
                new PhoneNumberRule,
                Rule::unique('customers', 'phone')->ignore($customer?->id),
            ],

            'birth_date' => ['nullable', 'date', 'before:tomorrow'],
            'gender'     => ['nullable', Rule::in(['male', 'female', 'other'])],
            'address'    => ['nullable', 'string', 'max:255'],

            // Checkbox gönderilmezse false sayılır (prepareForValidation'da set ediliyor)
            'receive_newsletters' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Checkbox gönderilmediğinde false kabul et
        // Telefonu normalize et (+90XXXXXXXXXX) — kuralın normalize() metodunu kullanıyoruz
        $this->merge([
            'receive_newsletters' => $this->boolean('receive_newsletters'),
            'phone' => PhoneNumberRule::normalize($this->input('phone')),
        ]);
    }
}
