<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.pages.contacts.contact', [
            'metaTitle' => 'İletişim',
            'metaDescription' => null,
        ]);
    }

    /**
     * İletişim formu kaydı (DB only)
     */
    public function store(Request $req)
    {
        // 1) Honeypot
        if ($req->filled('website')) {
            return $this->respondBackOrJson($req, false, 'İşleminiz reddedildi.');
        }

        // 2) Validasyon
        $data = $req->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'website' => ['nullable', 'string', 'max:100'], // honeypot
            'agree' => ['nullable', 'in:on,1,true'], // KVKK kutusu
            'page_url' => ['nullable', 'url'],
        ]);

        try {
            // 3) Kayıt
            $record = ContactMessage::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'message' => $data['message'],
                'phone' => $data['phone'] ?? null,
                'company' => $data['company'] ?? null,
                'page_url' => $data['page_url'] ?? ($req->headers->get('referer') ?: url()->current()),
                'ip' => $req->ip(),
                'user_agent' => (string) $req->userAgent(),
                'consent_at' => in_array($data['agree'] ?? null, ['on', '1', 'true'], true) ? now() : null,
                'status' => 'new',
            ]);

            if (!$record) {
                return $this->respondBackOrJson($req, false, 'Bir hata oluştu. Lütfen tekrar deneyin.');
            }

            return $this->respondBackOrJson($req, true, 'Mesajınız alındı. En kısa sürede sizinle iletişime geçeceğiz.', ['id' => $record->id]);
        } catch (\Throwable $e) {
            report($e);
            return $this->respondBackOrJson($req, false, 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * AJAX/Fetch isteklerde JSON, klasik POST’ta flash+redirect döndürür.
     */
    protected function respondBackOrJson(Request $req, bool $ok, string $message, array $extra = [])
    {
        if ($req->expectsJson() || $req->wantsJson() || $req->ajax()) {
            return response()->json(
                array_merge(
                    [
                        'ok' => $ok,
                        'message' => $message,
                    ],
                    $extra,
                ),
                $ok ? 200 : 422,
            );
        }

        // Flash mesaj (temadaki sınıflara göre 'success' | 'warning' | 'error' | 'info')
        $type = $ok ? 'success' : 'error';

        return back()
            ->with('flash.type', $type)
            ->with('flash.message', $message)
            ->withInput(!$ok ? $req->except(['message']) : []); // hata varsa formu doldu bırak
    }

    /**
     * Formun AJAX/normal gönderimine uygun yanıtlayıcı
     */
    protected function respond(Request $req, bool $ok, string $message)
    {
        if ($req->expectsJson() || $req->ajax()) {
            return response()->json(
                [
                    'success' => $ok,
                    'message' => $message,
                ],
                $ok ? 200 : 422,
            );
        }

        $resp = back()->with($ok ? 'success' : 'error', $message);

        if (!$ok) {
            // önceki form girdilerini flashla
            $resp->withInput();
        }

        return $resp;
    }
}
