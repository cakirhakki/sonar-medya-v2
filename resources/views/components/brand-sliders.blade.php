<section class="brand__area pb-120">
    <div class="container-fluid g-0">
        <div class="row gx-0 gy-2">
            <div class="col-xxl-12">
                <div class="{{ $wrapperTop }}">
                    <div class="brand__slider-5">
                        <div class="{{ $activeTop }}">
                            @forelse ($top as $brand)
                                @php $src = $brand->getFirstMediaUrl('logo') ?: asset('site/assets/img/brand/5/brand-1.png'); @endphp
                                <div class="brand__item-5">
                                    @if($brand->url)
                                        <a href="{{ $brand->url }}" rel="nofollow noopener" target="_blank">
                                            <img src="{{ $src }}" alt="{{ $brand->name }}">
                                        </a>
                                    @else
                                        <img src="{{ $src }}" alt="{{ $brand->name }}">
                                    @endif
                                </div>
                            @empty
                                @for($i=1;$i<=8;$i++)
                                    <div class="brand__item-5">
                                        <img src="{{ asset("site/assets/img/brand/5/brand-$i.png") }}" alt="Brand {{ $i }}">
                                    </div>
                                @endfor
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12">
                <div class="{{ $wrapperBottom }}">
                    <div class="brand__slider-5">
                        <div class="{{ $activeBottom }}">
                            @forelse ($bottom as $brand)
                                @php $src = $brand->getFirstMediaUrl('logo') ?: asset('site/assets/img/brand/5/brand-1.png'); @endphp
                                <div class="brand__item-5">
                                    @if($brand->url)
                                        <a href="{{ $brand->url }}" rel="nofollow noopener" target="_blank">
                                            <img src="{{ $src }}" alt="{{ $brand->name }}">
                                        </a>
                                    @else
                                        <img src="{{ $src }}" alt="{{ $brand->name }}">
                                    @endif
                                </div>
                            @empty
                                @for($i=1;$i<=8;$i++)
                                    <div class="brand__item-5">
                                        <img src="{{ asset("site/assets/img/brand/5/brand-$i.png") }}" alt="Brand {{ $i }}">
                                    </div>
                                @endfor
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
