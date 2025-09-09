<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Kayıt formunu gösterir.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Kayıt isteğini işler (Customer guard ile).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:customers,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Customer oluştur (modelde 'password' => 'hashed' cast var)
        $customer = Customer::create($data);

        // E-posta doğrulamasını tetikle
        event(new Registered($customer));

        // Customer guard ile oturum aç
        Auth::guard('customer')->login($customer);

        // Daha net akış: doğrulama uyarı sayfasına gönder
        return redirect()->route('verification.notice');

        // Alternatif: dashboard'a gönderirsen, 'verified' middleware'i zaten
        // doğrulanmamışsa otomatik olarak verification.notice'a yönlendirir:
        // return redirect()->intended(route('dashboard'));
    }
}
