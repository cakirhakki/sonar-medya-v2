<?php

namespace Tests\Helpers;

trait ModelSoftDeletes
{
    protected function modelUsesSoftDeletes(string $modelClass): bool
    {
        return in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($modelClass));
    }
}
