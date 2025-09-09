<?php

namespace App\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneServiceRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        // value dizi değilse veya boşsa: başarısız
        if (!is_array($value)) {
            return false;
        }

        // En az 1 adet service id olmalı
        return count(array_filter($value, fn ($v) => (int) $v > 0)) >= 1;
    }

    public function message(): string
    {
        return 'Paket en az bir hizmet içermelidir.';
    }
}
