<?php

namespace App\Services;

use Illuminate\Support\Collection;

class SolutionsExtractor
{
    /**
     * Filament KeyValue hem assoc-map hem de [{key,value}] olarak gelebilir.
     * Dönüş: [solutions(array), necessary(string)]
     */
    public function parse($raw): array
    {
        if (is_string($raw)) {
            $raw = json_decode($raw, true) ?: [];
        }
        if (!is_array($raw)) {
            $raw = [];
        }

        $isList = function (array $a): bool {
            if (function_exists('array_is_list')) {
                return array_is_list($a);
            }
            $i = 0;
            foreach ($a as $k => $_) {
                if ($k !== $i++) return false;
            }
            return true;
        };

        if ($raw && $isList($raw) && isset($raw[0]) && is_array($raw[0]) && array_key_exists('key', $raw[0])) {
            $assoc = [];
            foreach ($raw as $row) {
                $k = trim((string) ($row['key'] ?? ''));
                $v = trim((string) ($row['value'] ?? ''));
                if ($k !== '') $assoc[$k] = $v;
            }
        } else {
            $assoc = [];
            foreach ($raw as $k => $v) {
                $k = trim((string) $k);
                if ($k === '') continue;
                if (is_array($v)) $v = trim(implode(' ', array_filter($v, fn($x) => filled($x))));
                $assoc[$k] = trim((string) $v);
            }
        }

        $solutions = array_keys($assoc);
        $vals = array_map(fn($v) => trim((string) $v), array_values($assoc));
        $necessary = implode("\n", array_filter($vals, fn($v) => $v !== ''));

        return [$solutions, $necessary];
    }

    /** Root tab’lar için toplu çıkarım */
    public function forTabs(Collection $rootItems): array
    {
        $solutionsById = [];
        $necessaryById = [];

        foreach ($rootItems as $item) {
            [$solutions, $necessary] = $this->parse($item->snapshot_json ?? []);
            $solutionsById[$item->id] = $solutions;
            $necessaryById[$item->id] = $necessary;
        }

        return [$solutionsById, $necessaryById];
    }
}
