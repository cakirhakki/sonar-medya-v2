<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\FooterComposer;
use App\View\Composers\SeoComposer;
use App\View\Composers\HeaderServicesComposer;
use App\View\Composers\BlogSidebarComposer;

final class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Site footer
        View::composer(['frontend.includes.footer'], FooterComposer::class);
        // SEO
        View::composer(['frontend.*', 'layouts.frontend*'], SeoComposer::class);
        // Header (sadece Service/ServiceCategory)
        View::composer('frontend.includes.header', HeaderServicesComposer::class);
        // Blog sidebar
        View::composer('frontend.pages.blogs.blog-side-bar', BlogSidebarComposer::class);
    }
}
