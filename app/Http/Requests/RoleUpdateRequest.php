<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\Rules\RoleNameRule;

class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()->can('roles.update');
        return true;
    }

    public function rules(): array
    {
        // Filament’te route param genelde 'record' olur; controller’da 'role' olabilir.
        $id = $this->route('record') ?? $this->route('role') ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                // rezerve isim koruması: mevcut kayıt super_admin ise aynı isimle kalabilir,
                // ama başka kayıtlar super_admin olamaz. Rule sınıfı bunu ele alıyor.
                new RoleNameRule($id),

                // (name, guard_name=admin) benzersizliği — güncellemede mevcut kaydı yok say
                Rule::unique('roles', 'name')
                    ->where(fn ($q) => $q->where('guard_name', 'admin'))
                    ->ignore($id),
            ],

            'guard_name' => ['in:admin'],

            'permissions'   => ['sometimes', 'array'],
            'permissions.*' => ['string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'guard_name' => 'admin',
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Rol adı zorunludur.',
            'name.max'       => 'Rol adı en fazla 100 karakter olabilir.',
            'name.unique'    => 'Bu rol adı (guard=admin) zaten mevcut.',
            'guard_name.in'  => 'Geçersiz guard.',
        ];
    }
}
