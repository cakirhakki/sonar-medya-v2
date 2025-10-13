<div class="services__widget-2 pr-50">
  {{-- PAKET: Sekmeler + her sekmenin altındaki tekil hizmetler --}}
  @if(($type ?? null) === 'package' && !empty($tabs) && count($tabs))
    <div class="services__widget-item-2 mb-30">
      <div class="services__widget-tab-2 tp-tab">
        <ul>
          @foreach ($tabs as $tab)
            <li>
              <a href="#tab-{{ $tab->id }}" class="{{ $loop->first ? 'active' : '' }}">
                <span><i class="{{ $tab->icon_class }}"></i></span>
                {{ $tab->name }}
                <i class="fa-regular fa-angle-right"></i>
              </a>

              {{-- Bu sekmenin altına, pakete bağlı tekil hizmetleri listele --}}
              @php
                $servicesInTab = $tab->children->filter(fn($c) => $c->service);
              @endphp
              @if($servicesInTab->count())
                <ul class="mt-10 ml-25">
                  @foreach ($servicesInTab as $child)
                    <li class="mb-5">
                      <a href="{{ route('frontend.services.show', $child->service->slug) }}">
                        {{ $child->service->name }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  {{-- TEKİL HİZMET: kategorileri göster --}}
  @if(($type ?? null) === 'service' && !empty($serviceCategories) && $serviceCategories->count())
    <div class="services__widget-item-2 mb-30">
      <div class="services__widget-tab-2 tp-tab">
        <ul>
          @foreach ($serviceCategories as $cat)
            @php
              $isActive = isset($service) && $service->category && $service->category->id === $cat->id;
            @endphp
            <li>
              <a href="{{ route('frontend.services.category', $cat->slug) }}" class="{{ $isActive ? 'active' : '' }}">
                <span><i class="{{ $cat->icon_class ?? 'fa-regular fa-folder' }}"></i></span>
                {{ $cat->name }}
                @if($cat->services_count ?? false)
                  <span class="ml-5">({{ $cat->services_count }})</span>
                @endif
                <i class="fa-regular fa-angle-right"></i>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif


  @include('frontend.pages.contacts.form-widget', [
    'title'  => 'Ücretsiz teklif alın',
    'button' => 'Gönder',
  ])

  <div class="services__widget-item-2 mb-30">
    <div class="services__contact-info">
      <div class="services__contact-info-item d-flex align-items-center">
        <div class="services__contact-info-icon">
          <span>
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19.582 6.74489C20.9848 7.01859 22.2741 7.70466 23.2847 8.7153C24.2953 9.72594 24.9814 11.0152 25.2551 12.418M19.582 1C22.4965 1.32378 25.2143 2.62893 27.2891 4.70116C29.364 6.77339 30.6726 9.48953 31 12.4036M29.5638 23.8647V28.1733C29.5654 28.5733 29.4835 28.9693 29.3232 29.3357C29.163 29.7022 28.928 30.0312 28.6332 30.3016C28.3385 30.572 27.9905 30.7779 27.6116 30.9061C27.2327 31.0342 26.8312 31.0818 26.4328 31.0458C22.0133 30.5656 17.7681 29.0554 14.0382 26.6366C10.568 24.4315 7.62595 21.4894 5.42086 18.0192C2.99361 14.2724 1.48309 10.0065 1.01166 5.56719C0.975767 5.17003 1.02297 4.76974 1.15025 4.39182C1.27754 4.0139 1.48212 3.66662 1.75098 3.3721C2.01983 3.07757 2.34706 2.84226 2.71185 2.68113C3.07663 2.52 3.47096 2.4366 3.86974 2.43622H8.17841C8.87542 2.42936 9.55114 2.67619 10.0796 3.13068C10.6081 3.58518 10.9533 4.21635 11.0509 4.90653C11.2327 6.2854 11.57 7.63927 12.0562 8.94231C12.2494 9.45637 12.2913 10.0151 12.1767 10.5521C12.0622 11.0892 11.7961 11.5823 11.4099 11.9727L9.58591 13.7967C11.6305 17.3924 14.6076 20.3695 18.2033 22.4141L20.0273 20.5901C20.4177 20.2039 20.9108 19.9378 21.4478 19.8233C21.9849 19.7087 22.5436 19.7506 23.0577 19.9438C24.3607 20.43 25.7146 20.7673 27.0935 20.9491C27.7911 21.0476 28.4283 21.399 28.8838 21.9365C29.3392 22.4741 29.5813 23.1603 29.5638 23.8647Z"
               stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
        </div>
        <div class="services__contact-info-content">
          <span>Çağrı Merkezi</span>
          @if (!empty($site?->primary_tel_href))
            <h4><a href="{{ $site->primary_tel_href }}">{{ $site->primary_tel_display }}</a></h4>
          @else
            <h4>—</h4>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
