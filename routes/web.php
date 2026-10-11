<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserLikeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LightNotificationController;
use App\Http\Controllers\FreeDatingFilterController;

Route::get('/', [AuthController::class, 'landing'])->name('landing');
Route::get('/login', fn () => redirect()->route('landing'))->name('login');
Route::get('/register', fn () => redirect()->route('landing'))->name('register');

// Local email/password (modal form)
Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signout', [AuthController::class, 'signout'])->middleware('auth')->name('signout');

// OAuth
Route::get('/auth/{provider}/redirect', [AuthController::class, 'oauthRedirect'])
     ->whereIn('provider', ['google','facebook'])->name('oauth.redirect');
Route::get('/auth/{provider}/callback', [AuthController::class, 'oauthCallback'])
     ->whereIn('provider', ['google','facebook'])->name('oauth.callback');

// Page Free-Online-Dating
Route::get('/free-online-dating', [HomeController::class, 'index'])//     ->middleware('auth')
    ->name('free-online-dating');

    Route::get('/free-online-dating/filter', [FreeDatingFilterController::class, 'filter'])
    ->name('free-online-dating.filter');

Route::middleware('auth')->group(function () {
Route::get('/u/{user}', [ProfileController::class, 'view'])->name('users.show');
});

// Page Supports
Route::view('/support', 'support')->name('support');

// Page Profile Account
Route::get('/profile', [ProfileController::class,'show'])
    ->middleware('auth')
    ->name('profile');

// Edit Social media
Route::middleware('auth')->group(function () {
    Route::patch('/profile/social', [ProfileController::class, 'updateSocial'])
        ->name('profile.social.update');
});
// Edit Preferences
Route::middleware('auth')->group(function () {
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])
        ->name('profile.preferences.update');
});
// Edit Information
Route::middleware(['auth'])->group(function () {
    Route::patch('/profile/update-information', [ProfileController::class, 'updateInformation'])
        ->name('updateInformation');
});

// routes/web.php
Route::middleware('auth')->group(function () {
    Route::post('/profile/album',  [ProfileController::class,'albumUpdate'])->name('profile.album.update');
    Route::delete('/profile/album', [ProfileController::class,'albumDelete'])->name('profile.album.delete');
});

// Edit factfile
Route::middleware('auth')->group(function () {
    Route::post('/profile/factfile', [ProfileController::class, 'updateFactfile'])
        ->name('profile.factfile.update');
    Route::get('/likes', [UserLikeController::class, 'index'])->name('likes.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/u/{user}', [ProfileController::class, 'view'])
    ->whereNumber('user')
        ->name('users.show');
});

Route::post('/users/{user}/like', [UserLikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('users.like');

Route::middleware('auth')->post('/cards/next', [HomeController::class, 'nextCard'])
    ->name('cards.next');

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');                 // split view
    Route::get('/chat/conversations', [ChatController::class, 'conversations'])->name('chat.conversations'); // data sidebar

    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');            // (tetap)
    Route::get('/chat/{user}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/{user}/messages', [ChatController::class, 'send'])->name('chat.send');
});


Route::middleware('auth')->group(function () {
    Route::get('/light-notifications', [LightNotificationController::class, 'index'])
        ->name('light_notifications.index');
    Route::post('/light-notifications/seen', [LightNotificationController::class, 'markSeen'])
        ->name('light_notifications.seen');
});

Route::middleware('auth')->delete('/profile/delete', [ProfileController::class,'destroy'])
     ->name('profile.destroy');
