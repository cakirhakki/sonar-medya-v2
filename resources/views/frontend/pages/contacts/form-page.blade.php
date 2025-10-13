@props([
    'title' => 'Bize mesaj gönderin',
    'button' => 'Mesajı Gönder',
])

<div class="contact__form-2">
    <h3 class="contact__form-2-title">{{ $title }}</h3>

    {{-- Flash success/error --}}
    @if (session('flash.message'))
        <div class="alert alert-{{ session('flash.type') }} mb-3">
            {{ session('flash.message') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="alert alert-error mb-3">
            <ul class="m-0 ps-4">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
        @csrf
        <input type="hidden" name="page_url" value="{{ url()->current() }}">
        <div style="display:none"><input type="text" name="website" autocomplete="off"></div> {{-- honeypot --}}

        <div class="row">
            <div class="col-md-6">
                <div class="contact__input-2">
                    <input name="name" type="text" placeholder="Adınız" value="{{ old('name') }}">
                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="contact__input-2">
                    <input name="email" type="email" placeholder="E-posta adresiniz" value="{{ old('email') }}">
                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="contact__input-2">
                    <input name="phone" type="text" placeholder="Mobil / Telefon" value="{{ old('phone') }}">
                    @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="contact__input-2">
                    <input name="company" type="text" placeholder="Şirket" value="{{ old('company') }}">
                    @error('company')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="contact__input-2">
                    <textarea name="message" placeholder="Mesajınız">{{ old('message') }}</textarea>
                    @error('message')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="contact__agree d-flex align-items-start mb-25">
                    <input class="e-check-input" type="checkbox" id="e-agree" name="agree" {{ old('agree') ? 'checked' : '' }}>
                    <label class="e-check-label ms-2" for="e-agree">
                        Aydınlatma metnini okudum, kabul ediyorum.
                    </label>
                </div>
            </div>

            <div class="col-md-5">
                <div class="contact__btn-2">
                    <button type="submit" class="tp-btn">{{ $button }}</button>
                </div>
            </div>

            <div class="col-md-7">
                <div class="contact__form-call float-md-end">
                    <span>Danışma Hattı</span>
                    @if(!empty($site?->primary_tel_href))
                        <p><a href="{{ $site->primary_tel_href }}"><i class="fa-solid fa-phone-flip"></i> {{ $site->primary_tel_display }}</a></p>
                    @else
                        <p><a href="#"><i class="fa-solid fa-phone-flip"></i> —</a></p>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <p class="ajax-response"></p>
</div>
