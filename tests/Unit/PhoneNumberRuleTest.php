<?php

namespace Tests\Unit;

use App\Support\Rules\PhoneNumberRule;
use PHPUnit\Framework\TestCase;

class PhoneNumberRuleTest extends TestCase
{
    public function test_passes_for_valid_tr_mobile_numbers(): void
    {
        $rule = new PhoneNumberRule();

        $this->assertTrue($this->passes($rule, '5455899873'));
        $this->assertTrue($this->passes($rule, '05455899873'));
        $this->assertTrue($this->passes($rule, '+905455899873'));
        $this->assertTrue($this->passes($rule, '00905455899873'));
        $this->assertTrue($this->passes($rule, '90 545 589 98 73'));
    }

    public function test_fails_for_invalid_numbers(): void
    {
        $rule = new PhoneNumberRule();

        // kısa
        $this->assertFalse($this->passes($rule, '4444444'));
        // 6 ile başlıyor
        $this->assertFalse($this->passes($rule, '64555899873'));
        // HARF içerdiği için (strict) geçersiz olmalı
        $this->assertFalse($this->passes($rule, 'abc5455899873xyz'));
    }

    public function test_normalize_returns_e164_like_format(): void
    {
        $this->assertSame('+905455899873', PhoneNumberRule::normalize('545 589 98 73'));
        $this->assertSame('+905455899873', PhoneNumberRule::normalize('05455899873'));
        $this->assertSame('+905455899873', PhoneNumberRule::normalize('+90 545 589 98 73'));
        $this->assertNull(PhoneNumberRule::normalize(null));
        $this->assertNull(PhoneNumberRule::normalize(''));
        // STRICT davranış: harf içeren değerler normalize edilemez → null
        $this->assertNull(PhoneNumberRule::normalize('abc5455899873xyz'));
    }

    /**
     * Laravel 12'de passes() olmadığı için validate() üzerinden
     * "geçti mi" bilgisini döndüren küçük yardımcı.
     */
    private function passes(PhoneNumberRule $rule, mixed $value): bool
    {
        $ok = true;

        $rule->validate('phone', $value, function () use (&$ok) {
            $ok = false; // validate() fail çağırırsa geçmedi
        });

        return $ok;
    }
}
