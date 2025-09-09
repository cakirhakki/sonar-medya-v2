<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Customer guard için e-posta doğrulama uyarı ekranını gösterir.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Oturum yoksa login'e yönlendir
        if (! $request->user('customer')) {
            return redirect()->route('login');
        }

        // Zaten doğrulandıysa dashboard'a yönlendir
        if ($request->user('customer')->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Doğrulanmamışsa uyarı sayfasını göster
        return view('auth.verify-email');
    }
}
