<?php

namespace App\Observers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

final class SiteSettingObserver
{
    public function saved(SiteSetting $m): void  { Cache::forget('site_settings_single'); }
    public function deleted(SiteSetting $m): void{ Cache::forget('site_settings_single'); }
}
