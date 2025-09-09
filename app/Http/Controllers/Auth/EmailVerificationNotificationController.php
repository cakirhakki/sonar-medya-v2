<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Yeni bir e-posta doğrulama bildirimi gönderir (customer guard).
     */
    public function store(Request $request): RedirectResponse
    {
        // Müşteri oturumu yoksa login'e gönder
        if (! $request->user('customer')) {
            return redirect()->route('login');
        }

        // Zaten doğrulanmışsa dashboard'a dön
        if ($request->user('customer')->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Doğrulama e-postasını gönder
        $request->user('customer')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
