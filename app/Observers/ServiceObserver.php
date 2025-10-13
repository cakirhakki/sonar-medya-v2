<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;

final class ServiceObserver
{
    public function saved(Service $m): void       { Cache::forget('menu.services'); }
    public function deleted(Service $m): void     { Cache::forget('menu.services'); }
    public function restored(Service $m): void    { Cache::forget('menu.services'); }
    public function forceDeleted(Service $m): void{ Cache::forget('menu.services'); }
}
