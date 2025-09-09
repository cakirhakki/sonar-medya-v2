<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Support\Rules\UserNameRule;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Policy veya izin kontrolü eklemek istersen buraya:
        // return $this->user()->can('users.create');
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:150', new UserNameRule()],
            'email'    => ['required', 'email', 'max:255',
                           Rule::unique('users','email')->where(fn($q) => $q->where('guard_name','admin'))],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles'    => ['sometimes','array'],
            'roles.*'  => ['string', Rule::exists('roles','name')->where('guard_name','admin')],
        ];
    }

    protected function prepareForValidation(): void
    {
        // name ve email'i temizle
        $this->merge([
            'name'  => is_string($this->name) ? trim($this->name) : $this->name,
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
        ]);
    }
}
