<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('frontend.pages.home.index'))
    ->name('site.home');
