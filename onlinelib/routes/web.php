<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
//
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});
//

Route::get('/', function () {
    return Inertia::render('HomeView', []);
})->name('Home');

Route::get('/contentpolicy', function () {
    return Inertia::render('ContentPolicy', []);
})->name('ContentPolicy');

Route::get('/termsofservice', function () {
    return Inertia::render('TermsOfService', []);
})->name('TermsOfService');

Route::get('/privacypolicy', function () {
    return Inertia::render('PrivacyPolicy', []);
})->name('PrivacyPolicy');

Route::get('/dmcapolicy', function () {
    return Inertia::render('DMCAPolicy', []);
})->name('DMCAPolicy');

Route::get('/faq', function () {
    return Inertia::render('FAQ', []);
})->name('FAQ');

Route::get('/technicalsupport', function () {
    return Inertia::render('TechnicalSupport', []);
})->name('TechnicalSupport');

Route::get('/registerp', function () {
    return Inertia::render('Auth/Register', []);
})->name('registerp');

require __DIR__.'/auth.php';

