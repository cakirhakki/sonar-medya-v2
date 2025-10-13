<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    public function store(Request $request)
    {
        // 12 ay (dakika)
        $minutes = 60 * 24 * 365;

        // cookie(name, value, minutes, path, domain, secure, httpOnly, raw, sameSite)
        Cookie::queue(
            Cookie::make(
                'cookie_consent',
                '1',
                $minutes,
                '/',                              // path
                config('session.domain'),         // domain
                (bool) config('session.secure'),  // secure
                true,                             // httpOnly
                false,                            // raw
                'lax'                             // sameSite: 'lax' | 'strict' | 'none'
            )
        );

        return response()->noContent(); // 204
    }
}
