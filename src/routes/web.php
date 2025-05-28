<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\AutofillForms\BookmarksFormController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContextController;
use App\Http\Controllers\DeleteAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\RedirectGuestToRoute;
use App\Mail\ChangePassword;
use App\Mail\DeleteAccount;
use App\Mail\Notification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', [BookmarkController::class, 'index'])->name('home');

Route::get('/mailable', function () {
    return new DeleteAccount('qwerty1234', Auth::user());
    // return new Notification();
})->name('mailview');

Route::get('/search', [SearchController::class, 'search'])->middleware('auth')->name('search');

Route::get('/autofill-bf', BookmarksFormController::class)->name('autofillBookmarksFrorm');

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show')->middleware(['auth'])->name('profile');
    Route::put('/profile', 'update')->name('profile.update');
    Route::delete('/profile', 'destroy')->name('profile.destroy');
})->middleware(['auth']);

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'index')->name('login')->middleware('guest');
    Route::post('/login', 'store')->name('login.store')->middleware('guest');
    Route::post('/logout', 'destroy')->name('logout')->middleware('auth');
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
    return redirect()->route('home')->with([
        'verificationStatus' => 'Your email address has been successfully verified.'
    ]);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with(['verificationStatus' => 'Verification link sent!']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


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

//change password routs
Route::controller(ChangePasswordController::class)->group(function () {
    Route::post('/change-password/', 'changePassword')
        ->name('changePassword.update');
})->middleware('auth');

// delete account routs
Route::controller(DeleteAccountController::class)->group(function () {
    Route::get('/delete-account/{token}', 'destroy')->name('deleteAccount.delete');
    Route::post('/delete-account/', 'sendLink')->name('deleteAccount.email');
})->middleware('auth');


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

Route::controller(TagController::class)->group(function () {
    Route::get('/tags/{id}', 'show')->name('tags.index');
    Route::get('/tags', 'index')->name('tags.index');
    Route::post('/tags', 'store')->name('tags.index');
    Route::put('/tags/{id}', 'update')->name('tags.index');
    Route::delete('/tags/{id}', 'destroy')->name('tags.index');
});

// Route::get('/add-bookmark', function () {
//     return view('addBookmark');
// })->name('addBookmark');
