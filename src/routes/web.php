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

Route::get('/mailable', function () {
    return new DeleteAccount('qwerty1234', Auth::user());
    // return new Notification();
})->name('mailview');

Route::get('/search', [SearchController::class, 'search'])
    ->middleware('auth')->name('search');

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show')
        ->middleware('auth')->name('profile');
    Route::put('/profile', 'update')
        ->middleware('auth')->name('profile.update');
    Route::delete('/profile', 'destroy')
        ->middleware('auth')->name('profile.destroy');
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'index')->middleware('guest')->name('login');
    Route::post('/login', 'store')->middleware('guest')->name('login.store');
    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
});

Route::controller(RegistrationController::class)->group(function () {
    Route::get('/registration', 'index')
        ->middleware(['guest'])->name('registration.index');
    Route::post('/registration', 'store')
        ->middleware(['guest'])->name('registration.store');
});
// ->middleware(['guest'])

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
        ->middleware('auth')
        ->name('changePassword.update');
});

// delete account routs
Route::controller(DeleteAccountController::class)->group(function () {
    Route::get('/delete-account/{token}', 'destroy')
        ->middleware('auth')
        ->name('deleteAccount.delete');
    Route::post('/delete-account/', 'sendLink')
        ->middleware('auth')
        ->name('deleteAccount.email');
});


Route::get('/welcom', function () {
    return view('welcome');
})->name('welcome');

Route::controller(AdditionalDataController::class)->group(function () {
    Route::get('/additional-data/contexts', 'allContexts')
        ->middleware('auth')
        ->name('additionalData.contexts');
    Route::get('/additional-data/bf-autocomplete', 'autocompleteData')
        ->middleware('auth')
        ->name('additionalData.autocomplete');
    Route::get('/additional-data/bf-thumbnails', 'potentialThumbnails')
        ->middleware('auth')
        ->name('additionalData.thumbnails');
});

Route::controller(BookmarkController::class)->group(function () {
    Route::get('/bookmark/{id}', 'show')
        ->middleware(['auth'])->name('bookmarks.show');
    Route::post('/bookmarks/', 'store')
        ->middleware(['auth'])->name('bookmarks.store');
    Route::put('/bookmarks/{id}', 'update')
        ->middleware(['auth'])->name('bookmarks.update');
    Route::delete('/bookmarks/{id}', 'destroy')
        ->middleware(['auth'])->name('bookmarks.destroy');
});

Route::controller(ContextController::class)->group(function () {
    Route::get('/', 'index')
        ->middleware(RedirectGuestToRoute::class . ':welcome')->name('home');
    Route::get('/contexts/{id}', 'showContextData')
        ->middleware(['auth'])->name('showContextData');
    Route::get('/context/{id}', 'show')
        ->middleware(['auth'])->name('context');
    Route::post('/context/', 'store')
        ->middleware(['auth'])->name('contexts.store');
    Route::put('/contexts/{id}', 'update')
        ->middleware(['auth'])->name('contexts.update');
    Route::delete('/contexts/{id}', 'destroy')
        ->middleware(['auth'])->name('contexts.destroy');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/tags/{id}', 'show')
        ->middleware('auth')->name('tags.show');
    Route::get('/tags', 'index')
        ->middleware('auth')->name('tags.index');
    Route::post('/tags', 'store')
        ->middleware('auth')->name('tags.store');
    Route::put('/tags/{id}', 'update')
        ->middleware('auth')->name('tags.update');
    Route::delete('/tags/{id}', 'destroy')
        ->middleware('auth')->name('tags.destroy');
});

// Route::get('/add-bookmark', function () {
//     return view('addBookmark');
// })->name('addBookmark');
