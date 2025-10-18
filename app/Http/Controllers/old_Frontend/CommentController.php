<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // --- Rate limit: IP + Post bazlı (dakikada 5 deneme) ---
        $key = 'cmt:' . $post->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()
                ->withErrors(['comment' => 'Çok hızlı gidiyorsunuz. Lütfen biraz sonra tekrar deneyin.'])
                ->withInput();
        }
        RateLimiter::hit($key, 60);

        // --- Honeypot: gizli alan doluysa reddet ---
        if ($request->filled('website')) {
            return back()
                ->withErrors(['comment' => 'İşleminiz reddedildi.'])
                ->withInput();
        }

        // Guard durumları (açık ve IDE-dostu)
        $isCustomer = Auth::guard('customer')->check();
        $isAdmin    = Auth::guard('admin')->check();

        // Validasyon
        $rules = [
            'content'      => ['required', 'string', 'min:5', 'max:3000'],
            'parent_id'    => ['nullable', 'integer', Rule::exists('comments', 'id')],
            // Misafir için zorunlu — girişlilerde opsiyonel
            'author_name'  => [$isCustomer || $isAdmin ? 'nullable' : 'required', 'string', 'max:120'],
            'author_email' => [$isCustomer || $isAdmin ? 'nullable' : 'required', 'email', 'max:150'],
        ];

        $data = $request->validate($rules);

        // Yorum nesnesi
        $comment = new Comment([
            'content'      => $data['content'],
            'parent_id'    => $data['parent_id'] ?? null,
            'author_name'  => $data['author_name'] ?? null,
            'author_email' => $data['author_email'] ?? null,
        ]);

        // İlişkilendirme (öncelik: customer → admin)
        if ($isCustomer) {
            $customer = Auth::guard('customer')->user();
            $comment->customer()->associate($customer);
            // formdan gelmemişse profil bilgileriyle doldur
            $comment->author_name  = $customer->name;
            $comment->author_email = $customer->email;
        } elseif ($isAdmin) {
            $admin = Auth::guard('admin')->user();
            $comment->user()->associate($admin);
            $comment->author_name  = $comment->author_name  ?: $admin->name;
            $comment->author_email = $comment->author_email ?: $admin->email;
        }

        // İlgili post'a kaydet (polymorphic)
        $post->comments()->save($comment);

        return back()->with('ok', 'Yorumunuz alındı. Onaylandıktan sonra görünecek.');
    }
}
