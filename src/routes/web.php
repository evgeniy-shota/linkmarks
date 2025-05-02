<?php

use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookmarkController::class, 'index'])->name('home');

Route::get('/add-bookmark', function () {
    return view('addBookmark');
})->name('addBookmark');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/registration', function () {
    return view('registration');
})->name('registration');


// Route::get('/', function () {
//     return view('welcome');
// });
