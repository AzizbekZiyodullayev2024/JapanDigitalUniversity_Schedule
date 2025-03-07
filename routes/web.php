<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    // Foydalanuvchini bazadan qidirish yoki yaratish
    $user = User::updateOrCreate([
        'email' => $googleUser->getEmail(),
    ], [
        'name' => $googleUser->getName(),
        'google_id' => $googleUser->getId(),
        'avatar' => $googleUser->getAvatar(),
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});