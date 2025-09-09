<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- E-posta doğrulamasını yeniden gönderme formu (Breeze default) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Ad Soyad --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- E-posta --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Telefon --}}
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input
                id="phone"
                name="phone"
                type="tel"
                class="mt-1 block w-full"
                :value="old('phone', $user->phone)"
                autocomplete="tel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Doğum Tarihi --}}
        <div>
            <x-input-label for="birth_date" :value="__('Birth date')" />
            <x-text-input
                id="birth_date"
                name="birth_date"
                type="date"
                class="mt-1 block w-full"
                :value="old('birth_date', optional($user->birth_date)->format('Y-m-d'))"
                autocomplete="bday"
            />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        {{-- Cinsiyet --}}
        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select
                id="gender"
                name="gender"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                @php $g = old('gender', $user->gender); @endphp
                <option value="" {{ $g === null ? 'selected' : '' }}>{{ __('Select') }}</option>
                <option value="male"   {{ $g === 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ $g === 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="other"  {{ $g === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        {{-- Adres --}}
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input
                id="address"
                name="address"
                type="text"
                class="mt-1 block w-full"
                :value="old('address', $user->address)"
                autocomplete="street-address"
            />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Bülten izni --}}
        <div class="flex items-center gap-2">
            <x-checkbox
                id="receive_newsletters"
                name="receive_newsletters"
                value="1"
                :checked="old('receive_newsletters', (int) $user->receive_newsletters) == 1"
            />
            <x-input-label for="receive_newsletters" :value="__('Receive newsletters')" class="mb-0" />
            <x-input-error class="mt-2" :messages="$errors->get('receive_newsletters')" />
        </div>

        {{-- Sadakat puanı (sadece göster) --}}
        <div>
            <x-input-label for="loyalty_points" :value="__('Loyalty points')" />
            <x-text-input
                id="loyalty_points"
                type="number"
                class="mt-1 block w-full bg-gray-100"
                :value="$user->loyalty_points"
                disabled
            />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
