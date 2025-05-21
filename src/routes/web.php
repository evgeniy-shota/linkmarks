<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\AutofillForms\BookmarksFormController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ContextController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Middleware\RedirectGuestToRoute;
use App\Mail\Notification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', [BookmarkController::class, 'index'])->name('home');

Route::get('/mailable', function () {
    return new Notification();
})->name('mailview');

Route::get('/autofill-bf', BookmarksFormController::class)->name('autofillBookmarksFrorm');

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show')->middleware(RedirectGuestToRoute::class . ':welcome')->name('profile');
    Route::put('/profile', 'update')->name('profile.update');
    Route::delete('/profile', 'destroy')->name('profile.destroy');
})->middleware(['auth']);

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'store')->name('login.store');
    Route::post('/logout', 'destroy')->name('logout');
});

Route::controller(RegistrationController::class)->group(function () {
    Route::get('/registration', 'index')->name('registration.index');
    Route::post('/registration', 'store')->name('registration.store');
});

// email verifucation routs
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


// reset password routs
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showEmailForm')
        ->middleware('guest')->name('password.request');

    Route::post('/forgot-password', 'sendResetLink')
        ->middleware('guest')->name('passwword.email');

    Route::get('/reset-password/{token}', 'showPasswordForm')
        ->middleware('guest')->name('password.reset');

    Route::post('/reset-password/', 'resetPassword')
        ->middleware('guest')->name('password.update');
});

// Route::get('/forgot-password', [ResetPasswordController::class, 'showEmailForm'])
//     ->middleware('guest')->name('password.request');

// Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])
//     ->middleware('guest')->name('passwword.email');

// Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showPasswordForm'])
//     ->middleware('guest')->name('password.reset');

// Route::post('/reset-password/', [ResetPasswordController::class, 'resetPassword'])
//     ->middleware('guest')->name('password.update');


Route::get('/welcom', function () {
    return view('welcome');
})->name('welcome');

Route::controller(BookmarkController::class)->group(function () {
    Route::get('/bookmark/{id}', 'show')->name('home.show');
    Route::post('/bookmarks/', 'store')->name('home.store');
    Route::put('/bookmarks/{id}', 'update')->name('home.update');
    Route::delete('/bookmarks/{id}', 'destroy')->name('home.destroy');
})->middleware(['auth']);

Route::controller(ContextController::class)->group(function () {
    Route::get('/', 'index')->middleware(RedirectGuestToRoute::class . ':welcome')->name('home');
    Route::get('/contexts/{id}', 'showContextData')->middleware(['auth'])->name('showContextData');
    Route::get('/context/{id}', 'show')->middleware(['auth'])->name('context');
    Route::post('/context/', 'store')->middleware(['auth'])->name('contexts.store');
    Route::put('/contexts/{id}', 'update')->middleware(['auth'])->name('contexts.update');
    Route::delete('/contexts/{id}', 'destroy')->middleware(['auth'])->name('contexts.destroy');
});

// Route::get('/add-bookmark', function () {
//     return view('addBookmark');
// })->name('addBookmark');
