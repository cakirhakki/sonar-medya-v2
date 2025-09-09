<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Müşterinin (customer guard) şifresini günceller.
     */
    public function update(Request $request): RedirectResponse
    {
        // Oturum yoksa login'e gönder
        if (! $request->user('customer')) {
            return redirect()->route('login');
        }

        // current_password kuralını customer guard'a göre doğrula
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:customer'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Şifreyi güncelle (Hash::make — modelde 'password' => 'hashed' cast varsa da uyumlu)
        $request->user('customer')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
