<?php

namespace App\Support\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class UserNameRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!is_string($value)) return;

        $name = trim($value);

        // En az 2 karakter olmalı
        if (mb_strlen(preg_replace('/\s+/', '', $name)) < 2) {
            $fail('Kullanıcı adı en az 2 karakter olmalıdır.');
            return;
        }

        // Başında/sonunda tire veya rakam olmasın
        if (preg_match('/^-|-$|^[0-9]/', $name)) {
            $fail('Kullanıcı adı tire ile başlayamaz/bitemez ve rakamla başlayamaz.');
            return;
        }
    }
}
