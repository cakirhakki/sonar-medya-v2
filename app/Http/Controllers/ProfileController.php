<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Müşterinin profil formunu gösterir (customer guard).
     */
    public function edit(Request $request): View|RedirectResponse
    {
        // Oturum yoksa login'e yönlendir
        if (! $request->user('customer')) {
            return Redirect::route('login');
        }

        return view('profile.edit', [
            'user' => $request->user('customer'),
        ]);
    }

    /**
     * Müşterinin profil bilgilerini günceller.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $customer = $request->user('customer');

        if (! $customer) {
            return Redirect::route('login');
        }

        $customer->fill($request->validated());

        // E-posta değiştiyse doğrulamayı sıfırla
        if ($customer->isDirty('email')) {
            $customer->email_verified_at = null;
        }

        $customer->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Müşteri hesabını siler.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $customer = $request->user('customer');

        if (! $customer) {
            return Redirect::route('login');
        }

        // current_password kuralını customer guard'a göre doğrula
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:customer'],
        ]);

        // Önce oturum kapat
        Auth::guard('customer')->logout();

        // Hesabı sil
        $customer->delete();

        // Oturumu temizle
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
