@props([
    'title' => 'Ücretsiz teklif alın',
    'button' => 'Gönder',
])

<div class="services__widget-item-2 mb-30">
    <div class="services__contact">
        <h4 class="services__contact-title">{{ $title }}</h4>

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
            @csrf
            <input type="hidden" name="page_url" value="{{ url()->current() }}">
            <div style="display:none"><input type="text" name="website" autocomplete="off"></div> {{-- honeypot --}}

            <div class="services__contact-input">
                <input name="name" type="text" placeholder="Adınız" value="{{ old('name') }}">
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="services__contact-input">
                <input name="email" type="email" placeholder="E-posta adresiniz" value="{{ old('email') }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="services__contact-input">
                <textarea name="message" placeholder="Mesajınız">{{ old('message') }}</textarea>
                @error('message')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="services__contact-btn">
                <button type="submit" class="tp-btn w-100">{{ $button }}</button>
            </div>
        </form>

        <p class="ajax-response"></p>
    </div>
</div>
