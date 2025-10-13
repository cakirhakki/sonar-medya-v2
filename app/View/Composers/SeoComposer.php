<?php

namespace App\View\Composers;

use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

final class SeoComposer
{
    public function compose(View $view): void
    {
        $name = (string) $view->getName();
        if (str_starts_with($name,'filament::') || str_starts_with($name,'filament.')) return;

        /** @var \App\Models\SiteSetting|null $site */
        $site = ViewFacade::shared('site');

        $d = $view->getData();
        $rawTitle = is_string($d['metaTitle'] ?? ($d['seoTitle'] ?? null)) ? ($d['metaTitle'] ?? $d['seoTitle']) : null;
        $rawDesc  = is_string($d['metaDescription'] ?? ($d['seoDescription'] ?? null)) ? ($d['metaDescription'] ?? $d['seoDescription']) : null;
        $rawImage = is_string($d['metaImage'] ?? ($d['seoImage'] ?? null)) ? ($d['metaImage'] ?? $d['seoImage']) : null;

        $rawTitle = $rawTitle ?? ($site?->meta_title ?? ($site?->site_title ?? config('app.name')));
        $rawDesc  = $rawDesc  ?? ($site?->meta_description ?? null);

        $metaTitle = $rawTitle ? Str::limit(trim($rawTitle), 60, '') : null;
        $metaDesc  = $rawDesc  ? Str::limit(trim($rawDesc ),160, '') : null;
        $metaImage = $rawImage ?? ($site?->meta_image_url ?? null);

        $view->with('seo', [
            'title'            => $metaTitle ?: $site?->site_title ?? config('app.name'),
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
            'meta_image'       => $metaImage,
            'favicon'          => $site?->favicon_url ?? asset('favicon.ico'),
            'logo'             => $site?->logo_url ?? null,
        ]);
    }
}
