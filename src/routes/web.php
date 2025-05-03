<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', [BookmarkController::class, 'index'])->name('home');

Route::get('/add-bookmark', function () {
    return view('addBookmark');
})->name('addBookmark');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// Route::controller();

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/logout', function () {
    return view('logout');
})->name('logout');

Route::controller(RegistrationController::class)->group(function () {
    Route::get('/registration', 'index')->name('registration.index');
    Route::post('/registration', 'store')->name('registration.store');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->name('verification.verify');

Route::get('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6:1'])->name('verification.send');

Route::controller(BookmarkController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::get('/{id}', 'show')->name('home.show');
});
