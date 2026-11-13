<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::get('/test-session', function () {
    session(['test_login' => 'berhasil']);

    return response()->json([
        'session' => session('test_login'),
        'session_id' => session()->getId(),
        'auth' => Auth::guard('web')->check(),
    ]);
});

Route::get('/test-auth', function () {
    Auth::guard('web')->loginUsingId(2);

    return response()->json([
        'auth' => Auth::guard('web')->check(),
        'user' => Auth::guard('web')->user()?->email,
        'session_id' => session()->getId(),
    ]);
});

Route::get('/test-session', function () {
    session()->put('test_browser', 'berhasil');

    return [
        'session_id' => session()->getId(),
        'test_browser' => session()->get('test_browser'),
    ];
});

Route::get('/test-login', function () {
    Auth::guard('web')->loginUsingId(2);
    session()->save();

    return redirect('/admin');
});
