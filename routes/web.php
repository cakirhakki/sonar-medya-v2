<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ServicePackageController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\CookieConsentController;

use App\Http\Controllers\ThumbController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/about', AboutController::class)->name('site.about');
// Statik yasal sayfalar
Route::view('/gizlilik-politikasi', 'frontend.pages.static.privacy')->name('legal.privacy');
Route::view('/kullanim-sartlari', 'frontend.pages.static.terms')->name('legal.terms');
Route::view('/cerez-politikasi', 'frontend.pages.static.cookies')->name('legal.cookies');

Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::post('/blog/{post:slug}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store');

Route::get('/thumb', [ThumbController::class, 'show'])->name('thumb');
Route::post('/cookie-consent', [CookieConsentController::class, 'store'])
    ->name('cookie-consent.store');

/*
|--------------------------------------------------------------------------
| Services (slug ile)
|--------------------------------------------------------------------------
*/
Route::get('/services', [ServiceController::class, 'index'])
    ->name('frontend.services.index');

Route::get('/services/category/{category:slug}', [ServiceController::class, 'category'])
    ->name('frontend.services.category');

Route::get('/services/{slug}', [ServiceController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-_]+')
    ->name('frontend.services.show');

/*
|--------------------------------------------------------------------------
| Packages (ayrı menü)
|--------------------------------------------------------------------------
*/
Route::prefix('paketler')
    ->name('frontend.service-packages.')
    ->controller(ServicePackageController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');                         // /paketler
        Route::get('/kategori/{slug}', 'category')->name('category');    // /paketler/kategori/{slug}
        Route::get('/{slug}', 'show')->name('show');                     // /paketler/{slug}
    });
/*
|--------------------------------------------------------------------------
| Contact form
|--------------------------------------------------------------------------
*/
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:10,1'); // dakikada en fazla 10 istek

Route::middleware(['auth:customer', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
