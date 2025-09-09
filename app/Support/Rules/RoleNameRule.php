<?php

namespace App\Support\Rules;

use Illuminate\Contracts\Validation\Rule as LegacyRule; // Laravel <10
use Illuminate\Contracts\Validation\ValidationRule;     // Laravel 10+
use Illuminate\Support\Facades\DB;

/**
 * Rezerve rol adlarını (örn: super_admin) korur.
 *
 * - CREATE: super_admin oluşturulmasını engeller (seed ile geliyor varsayımı).
 * - UPDATE: sadece mevcut kayıt zaten super_admin ise adı super_admin olarak kalabilir.
 *           Başka herhangi bir rolün adını super_admin yapmak yasak.
 *
 * İsteğe göre RESERVED dizisine başka isimler de eklenebilir.
 */
class RoleNameRule implements ValidationRule, LegacyRule
{
    protected ?int $currentId;
    protected const RESERVED = ['super_admin'];

    public function __construct(?int $currentId = null)
    {
        $this->currentId = $currentId;
    }

    // Laravel 10+ (ValidationRule)
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $name = trim($value);
        $isReserved = in_array(mb_strtolower($name), self::RESERVED, true);

        if (! $isReserved) {
            return;
        }

        if ($this->currentId === null) {
            // CREATE: super_admin yasak (seed’den gelir, elle eklenmesin)
            $fail('Bu rol adı rezerve edilmiştir ve oluşturulamaz: :attribute.');
            return;
        }

        // UPDATE: yalnızca mevcut kayıt gerçekten super_admin ise izin ver
        $existing = DB::table('roles')->where('id', $this->currentId)->first();
        if (! $existing) {
            $fail('Geçersiz rol.');
            return;
        }

        $existingIsSuper = in_array(mb_strtolower($existing->name), self::RESERVED, true)
            && $existing->guard_name === 'admin';

        if (! $existingIsSuper) {
            // farklı bir rolü super_admin yapmak yasak
            $fail('Rezerve rol adına değiştirilemez.');
        }
    }

    // Laravel <10 (Legacy Rule) — geriye dönük uyumluluk
    public function passes($attribute, $value): bool
    {
        $errors = [];
        $this->validate($attribute, $value, function ($msg) use (&$errors) {
            $errors[] = $msg;
        });

        return empty($errors);
    }

    public function message(): string
    {
        return 'Rezerve rol adına izin verilmiyor.';
    }
}
