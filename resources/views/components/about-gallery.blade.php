{{-- Hazır tema sınıfları korunur --}}
@if($media->isNotEmpty())
<section class="about__gallery-area fix">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10">
                <div class="about__gallery-slider pl-50 pr-50 p-relative">
                    <div class="about__gallery-slider-active">
                        @foreach ($media as $m)
                            @php
                                // Varsa dönüştürme kullan, yoksa orijinal URL
                                $src = method_exists($m, 'getUrl') ? $m->getUrl('about_lg') : $m->getUrl();
                                $alt = $m->getCustomProperty('alt') ?: ($m->name ?: 'Görsel');
                            @endphp
                            <div class="about__gallery-item">
                                <div class="about__gallery-thumb m-img">
                                    <img src="{{ $src }}" alt="{{ $alt }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="about__gallery-arrow d-none d-sm-block"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
