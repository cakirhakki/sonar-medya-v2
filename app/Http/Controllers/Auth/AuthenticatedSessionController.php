<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Giriş formunu gösterir.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Müşteri (customer guard) ile oturum açar.
     * LoginRequest::authenticate() içinde: rate-limit kontrolü
     * ve Auth::guard('customer')->attempt(...) yapılır.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();          // ✅ throttling + guard=customer

        $request->session()->regenerate(); // ✅ session fixation koruması

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Oturumu kapatır (customer guard).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout(); // ✅ customer guard'tan çıkış

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
