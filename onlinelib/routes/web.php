<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserBookController;
use App\Models\UserBook;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;


Route::get('/api/is-logged-in', function () {
    return response()->json([
        'isLoggedIn' => auth()->check(),
        'user' => auth()->user(), // Include the authenticated user
    ]);
});

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

Route::post('/support', [SupportController::class, 'store']);

Route::get('/registerp', function () {
    return Inertia::render('Auth/Register', []);
})->name('registerp');

Route::get('/login', function () {
    return Inertia::render('Auth/Login', []);
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
});

Route::middleware('auth')->group(function () {
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});

Route::get('/profile/settings', [ProfileController::class, 'settings'])
    ->name('profile.settings')
    ->middleware('auth');

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');



Route::get('/CreateStory', [UserBook::class, 'create'])->name('NewStory');

Route::middleware('auth')->group(function () {
    Route::post('/CreateStory', [UserBookController::class, 'store'])->name('store');
    Route::get('/CreateStory', [UserBookController::class, 'create'])->name('NewStory');
});
Route::get('/genres', [GenreController::class, 'index']);

Route::get('/StoryList', [UserBookController::class, 'index'])
    ->middleware('auth')
    ->name('StoryList');


Route::delete('/stories/{id}', [UserBookController::class, 'delete'])->name('deleteStory');
Route::get('/writing/{id}/edit', [UserBookController::class, 'edit'])->name('EditStory');

Route::put('/books/{id}', [UserBookController::class, 'update'])
    ->name('books.update');

Route::get('/NewChapter', function () {
    return Inertia::render('Writing/NewChapter', []);
})->name('NewChapter');

Route::get('/books/{book}/chapters/create', [ChapterController::class, 'create'])
    ->name('chapters.create');
Route::post('/chapters', [ChapterController::class, 'store'])->name('chapters.store');
Route::get('/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
Route::put('/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
Route::delete('/chapters/{id}', [ChapterController::class, 'destroy'])->name('chapters.destroy');


Route::get('/UserControl', [ProfileController::class, 'showUsers'])
    ->middleware('can:Admin')
    ->name('users');

Route::middleware(['auth', 'can:Admin'])->group(function () {
    Route::delete('/users/{user}', [ProfileController::class, 'delete'])->name('users.destroy');
});
require __DIR__.'/auth.php';

