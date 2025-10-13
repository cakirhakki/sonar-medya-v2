<?php

namespace App\View\Components;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class BrandSliders extends Component
{
    public ?string $group;
    public string $wrapperTop;
    public string $activeTop;
    public string $wrapperBottom;
    public string $activeBottom;
    public string $split;

    /** @var Collection<int,\App\Models\Brand> */
    public Collection $top;
    /** @var Collection<int,\App\Models\Brand> */
    public Collection $bottom;

    public function __construct(
        ?string $group = null,
        string $wrapperTop = 'brand__slider-5 brand__style-square',
        string $activeTop  = 'brand__slider-active-5',
        string $wrapperBottom = 'brand__slider-5-1 brand__style-square',
        string $activeBottom  = 'brand__slider-active-5-1',
        string $split = 'alternate' // alternate|half
    ) {
        $this->group = $group;
        $this->wrapperTop = $wrapperTop;
        $this->activeTop = $activeTop;
        $this->wrapperBottom = $wrapperBottom;
        $this->activeBottom = $activeBottom;
        $this->split = $split;

        $all = Brand::query()
            ->when($group, fn($q) => $q->where('group', $group))
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get()
            ->values();

        if ($split === 'half') {
            $mid = (int) ceil($all->count() / 2);
            $this->top = $all->slice(0, $mid)->values();
            $this->bottom = $all->slice($mid)->values();
        } else {
            $this->top = $all->filter(fn($_, $i) => $i % 2 === 0)->values();
            $this->bottom = $all->filter(fn($_, $i) => $i % 2 === 1)->values();
        }
    }

    public function render(): View
    {
        return view('components.brand-sliders', [
            'top'           => $this->top,
            'bottom'        => $this->bottom,
            'wrapperTop'    => $this->wrapperTop,
            'activeTop'     => $this->activeTop,
            'wrapperBottom' => $this->wrapperBottom,
            'activeBottom'  => $this->activeBottom,
        ]);
    }
}
