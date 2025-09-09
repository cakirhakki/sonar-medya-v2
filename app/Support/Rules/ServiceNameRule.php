<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ServiceNameRule implements ValidationRule
{
    protected ?int $currentId;

    protected const RESERVED = [];

    public function __construct(?int $currentId = null)
    {
        $this->currentId = $currentId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        $name = trim($value);

        if (mb_strlen(preg_replace('/\s+/', '', $name)) < 2) {
            $fail('Servis adı en az 2 karakter olmalıdır.');
            return;
        }

        if (str_starts_with($name, '-') || str_ends_with($name, '-')) {
            $fail('Servis adı tire ile başlayamaz veya bitemez.');
            return;
        }

        if (in_array(mb_strtolower($name), self::RESERVED, true)) {
            $fail('Bu servis adı rezerve edilmiştir.');
            return;
        }

        if ($this->currentId) {
            $row = DB::table('services')->where('id', $this->currentId)->first();
            if ($row && Str::slug($row->name) === Str::slug($name)) {
                return;
            }
        }
        $table = 'services';
        $normalized = Str::slug($name);

        $exists = Schema::hasColumn($table, 'slug')
            ? DB::table($table)
                ->when($this->currentId, fn ($q) => $q->where('id', '!=', $this->currentId))
                ->where('slug', $normalized)
                ->exists()
            : DB::table($table)
                ->when($this->currentId, fn ($q) => $q->where('id', '!=', $this->currentId))
                ->whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$this->normalizeName($name)])
                ->exists();

        if ($exists) {
            $fail('Bu ada çok benzeyen bir hizmet zaten kayıtlı.');
        }
    }

    protected function normalizeName(string $value): string
    {
        return mb_strtolower(str_replace(' ', '', $value));
    }
}

