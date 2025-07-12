<?php

use App\Http\Controllers\AdditionalDataController;
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

// Mail preview
// Route::get('/mailable', function () {
//     return new DeleteAccount('qwerty1234', Auth::user());
//     // return new Notification();
// })->name('mailview');

Route::get('/search', [SearchController::class, 'search'])
    ->middleware('auth')->name('search');

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show')
        ->middleware('auth')->name('profile');

    Route::put('/profile', 'update')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('profile.update');

    Route::delete('/profile', 'destroy')
        ->middleware(['auth', 'throttle:onePerMinute'])
        ->name('profile.destroy');
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'index')->middleware('guest')->name('login');

    Route::post('/login', 'store')
        ->middleware(['guest', 'throttle:thirtyPerMinute'])
        ->name('login.store');

    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
});

Route::controller(RegistrationController::class)->group(function () {
    Route::get('/registration', 'index')
        ->middleware(['guest'])->name('registration.index');

    Route::post('/registration', 'store')
        ->middleware(['guest', 'throttle:thirtyPerMinute'])
        ->name('registration.store');
});

// email verifucation routs
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get(
    '/email/verify/{id}/{hash}',
    function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with([
            'verificationStatus' =>
            'Your email address has been successfully verified.'
        ]);
    }
)->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with(['verificationStatus' => 'Verification link sent!']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// reset password routs
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showEmailForm')
        ->middleware(['guest'])->name('password.request');

    Route::post('/forgot-password', 'sendResetLink')
        ->middleware(['guest', 'throttle:onePerMinute'])
        ->name('passwword.email');

    Route::get('/reset-password/{token}', 'showPasswordForm')
        ->middleware('guest')->name('password.reset');

    Route::post('/reset-password/', 'resetPassword')
        ->middleware('guest')->name('password.update');
});

//change password routs
Route::controller(ChangePasswordController::class)->group(function () {
    Route::post('/change-password/', 'changePassword')
        ->middleware(['auth', 'throttle:onePerMinute'])
        ->name('changePassword.update');
});

// delete account routs
Route::controller(DeleteAccountController::class)->group(function () {
    Route::get('/delete-account/{token}', 'destroy')
        ->middleware('auth')
        ->name('deleteAccount.delete');

    Route::post('/delete-account/', 'sendLink')
        ->middleware(['auth', 'throttle:onePerMinute'])
        ->name('deleteAccount.email');
});


Route::get('/welcom', function () {
    return view('welcome');
})->name('welcome');

Route::controller(AdditionalDataController::class)->group(function () {
    Route::get('/additional-data/contexts', 'allContexts')
        ->middleware(['auth', 'throttle:sixtyPerMinute'])
        ->name('additionalData.contexts');

    Route::get('/additional-data/bf-autocomplete', 'autocompleteData')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('additionalData.autocomplete');

    Route::get('/additional-data/bf-thumbnails', 'potentialThumbnails')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('additionalData.thumbnails');
});

Route::controller(BookmarkController::class)->group(function () {
    Route::get('/bookmark/{id}', 'show')->whereNumber('id')
        ->middleware(['auth'])->name('bookmarks.show');

    Route::post('/bookmarks/', 'store')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('bookmarks.store');

    Route::put('/bookmarks/{id}', 'update')->whereNumber('id')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('bookmarks.update');

    Route::delete('/bookmarks/{id}', 'destroy')->whereNumber('id')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('bookmarks.destroy');
});

Route::controller(ContextController::class)->group(function () {
    Route::get('/', 'index')
        ->middleware(RedirectGuestToRoute::class . ':welcome')->name('home');

    Route::get('/contexts/{id}', 'showContextData')->whereNumber('id')
        ->middleware(['auth'])->name('showContextData');

    Route::get('/context/{id}', 'show')->whereNumber('id')
        ->middleware(['auth'])->name('context');

    Route::post('/context/', 'store')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('contexts.store');

    Route::put('/contexts/{id}', 'update')->whereNumber('id')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('contexts.update');

    Route::delete('/contexts/{id}', 'destroy')->whereNumber('id')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('contexts.destroy');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'index')
        ->middleware(['auth', 'throttle:sixtyPerMinute'])->name('tags.index');

    Route::get('/tags/{id}', 'show')->whereNumber('id')
        ->middleware('auth')->name('tags.show');

    Route::post('/tags', 'store')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])->name('tags.store');

    Route::put('/tags/{id}', 'update')->whereNumber('id')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('tags.update');

    Route::delete('/tags/{id}', 'destroy')->whereNumber('id')
        ->middleware(['auth', 'throttle:thirtyPerMinute'])
        ->name('tags.destroy');
});

// Route::get('/add-bookmark', function () {
//     return view('addBookmark');
// })->name('addBookmark');
