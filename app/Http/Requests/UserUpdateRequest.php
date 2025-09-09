<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\Rules\UserNameRule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()->can('users.update');
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('record') ?? $this->route('user') ?? null;

        return [
            'name'     => ['sometimes','required','string','max:150', new UserNameRule()],
            'email'    => ['required','email','max:255',
                           Rule::unique('users','email')->where(fn($q) => $q->where('guard_name','admin'))->ignore($id)],
            // Parola update sırasında opsiyonel
            'password' => ['nullable','string','min:8','confirmed'],
            'roles'    => ['sometimes','array'],
            'roles.*'  => ['string', Rule::exists('roles','name')->where('guard_name','admin')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'  => is_string($this->name) ? trim($this->name) : $this->name,
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
        ]);
    }
}
