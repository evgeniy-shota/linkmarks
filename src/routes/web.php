<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\BookmarkController;
use App\Mail\Notification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', [BookmarkController::class, 'index'])->name('home');

Route::get('/mailable', function () {
    return new Notification();
})->name('mailview');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'store')->name('login.store');
    Route::post('/logout', 'destroy')->name('logout');
});

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
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6:1'])->name('verification.send');

Route::get('/welcom', function () {
    return view('welcome');
})->name('welcome');

Route::controller(BookmarkController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/bookmark/{id}', 'show')->name('home.show');
    Route::post('/bookmarks/', 'store')->name('home.store');
});

// Route::get('/add-bookmark', function () {
//     return view('addBookmark');
// })->name('addBookmark');
