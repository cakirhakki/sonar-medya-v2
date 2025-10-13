<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\{SiteSetting, Post, PostCategory, Service, ServiceCategory};
use App\Observers\{SiteSettingObserver, PostObserver, PostCategoryObserver, ServiceObserver, ServiceCategoryObserver};

final class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        SiteSetting::observe(SiteSettingObserver::class);
        Post::observe(PostObserver::class);
        PostCategory::observe(PostCategoryObserver::class);
        Service::observe(ServiceObserver::class);
        ServiceCategory::observe(ServiceCategoryObserver::class);
    }
}
