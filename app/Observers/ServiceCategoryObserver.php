<?php

namespace App\Observers;

use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Cache;

final class ServiceCategoryObserver
{
    public function saved(ServiceCategory $m): void       { Cache::forget('menu.services'); }
    public function deleted(ServiceCategory $m): void     { Cache::forget('menu.services'); }
    public function restored(ServiceCategory $m): void    { Cache::forget('menu.services'); }
    public function forceDeleted(ServiceCategory $m): void{ Cache::forget('menu.services'); }
}
