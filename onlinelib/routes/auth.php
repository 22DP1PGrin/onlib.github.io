<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

//Route::get('/login', [AuthenticatedSessionController::class, 'create'])
//    ->name('login');
//Route::post('/login', [AuthenticatedSessionController::class, 'store']);
//
//Route::get('/register', [RegisteredUserController::class, 'create']);
//
//Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
//
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});


//
//
//    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
//        ->name('password.confirm');
//
//    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
//
//    Route::put('password', [PasswordController::class, 'update'])->name('password.update');



