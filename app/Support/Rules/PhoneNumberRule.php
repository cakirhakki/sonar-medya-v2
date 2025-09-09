<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule
{
    /**
     * Telefon kuralı: sadece TR mobil numaraları (+905XXXXXXXXX).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // null veya boş string kontrolü
        if ($value === null || $value === '') {
            return; // nullable ise sorun yok
        }

        $normalized = self::normalize((string) $value);

        // normalize edilebildiyse ve format uygunsa geçer
        if (! (is_string($normalized) && preg_match('/^\+905\d{9}$/', $normalized))) {
            $fail('Telefon numarası geçersiz. Örn: 05551112233 veya +905551112233');
        }
    }

    /**
     * Form verisini normalize et: tüm varyantları E.164 (+90XXXXXXXXXX) haline çevirir.
     * Uygun değilse null döner.
     */
    public static function normalize(?string $value): ?string
    {
        if (! $value) {
            return null;
        }
    
        // STRICT: eğer rakam + boşluk + + işareti dışında karakter varsa reddet
        if (preg_match('/[^0-9\s\+]/', $value)) {
            return null;
        }
    
        // Rakamları ayıkla
        $digits = preg_replace('/\D+/', '', $value) ?? '';
    
        // 0090 / 90 / 0 öneklerini temizle
        if (str_starts_with($digits, '0090')) {
            $digits = substr($digits, 4);
        } elseif (str_starts_with($digits, '90')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
    
        // TR mobil: 10 hane ve 5 ile başlar
        if (strlen($digits) === 10 && str_starts_with($digits, '5')) {
            return '+90' . $digits;
        }
    
        return null;
    }
}
