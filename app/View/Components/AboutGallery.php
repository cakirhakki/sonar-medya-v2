<?php

namespace App\View\Components;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AboutGallery extends Component
{
    public function __construct(
        public ?int $limit = null // boşsa ayardan al
    ) {}

    public function render(): View|Closure|string
    {
        $site = SiteSetting::query()->first();

        $enabled = (bool) ($site?->about_gallery_enabled ?? false);
        $max     = (int)  ($site?->about_gallery_max_items ?? 12);
        $take    = $this->limit ?: $max;

        $media = collect();
        if ($enabled && $site) {
            $media = $site->getMedia('about_gallery')->take($take);
        }

        return view('components.about-gallery', [
            'media' => $media,
        ]);
    }
}
