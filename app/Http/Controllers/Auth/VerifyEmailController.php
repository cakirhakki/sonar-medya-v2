<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Oturumdaki müşteri e-postasını doğrulanmış olarak işaretler.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Oturum yoksa login'e dön
        $customer = $request->user('customer');
        if (! $customer) {
            return redirect()->route('login');
        }

        // Zaten doğrulandıysa dashboard
        if ($customer->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        // Doğrula ve olayı yayınla
        if ($customer->markEmailAsVerified()) {
            event(new Verified($customer));
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
