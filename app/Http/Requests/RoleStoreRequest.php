<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\Rules\RoleNameRule;

class RoleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // İstersen policy/permission ile sınırla:
        // return $this->user()->can('roles.create');
        return true;
    }

    public function rules(): array
    {
        return [
            // Rol adı: zorunlu + tekil (guard=admin kapsamında)
            'name' => [
                'required',
                'string',
                'max:100',
                new RoleNameRule(), // rezerve isim koruması (örn: super_admin için)
                Rule::unique('roles', 'name')->where(fn ($q) => $q->where('guard_name', 'admin')),
            ],

            // guard_name’ı dışarıdan almıyoruz ama doğrulama emniyeti:
            'guard_name' => ['in:admin'],

            // Opsiyonel: permissions alanlarını görmezden gelmek yerine base doğrulama (iş akışına göre)
            'permissions'   => ['sometimes', 'array'],
            'permissions.*' => ['string'], // izin adları string (spatie: name)
        ];
    }

    protected function prepareForValidation(): void
    {
        // Kullanıcıdan gelmese bile tek doğruluk kaynağı: admin
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
