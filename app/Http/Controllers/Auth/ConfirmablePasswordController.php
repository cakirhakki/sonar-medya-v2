<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Şifre doğrulama ekranını gösterir.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Kullanıcının şifresini (customer guard) doğrular.
     */
    public function store(Request $request): RedirectResponse
    {
        // Mevcut oturumdaki customer'ın e-postasını al
        $email = optional($request->user('customer'))->email;

        // Oturum yoksa login sayfasına gönder
        if (! $email) {
            return redirect()->route('login');
        }

        // Customer guard ile şifreyi doğrula
        if (! Auth::guard('customer')->validate([
            'email'    => $email,
            'password' => (string) $request->input('password'),
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Başarılı doğrulama -> "password confirmed" timestamp'i güncelle
        $request->session()->put('auth.password_confirmed_at', time());

        // İstenen sayfaya yönlendir (yoksa dashboard)
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
