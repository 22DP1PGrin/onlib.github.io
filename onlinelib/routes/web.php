<?php

use App\Http\Controllers\AllBooksController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ClassicBookController;
use App\Http\Controllers\ClassicChapterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserBookController;
use App\Models\UserBook;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/api/is-logged-in', function () {
    return response()->json([
        'isLoggedIn' => auth()->check(),
        'user' => auth()->user(),
    ]);
});

// Verifikācija
Route::get('/verify-pending/{token}', [RegisteredUserController::class, 'verify'])
    ->name('verify.pending');

Route::post('/verify-resend', [RegisteredUserController::class, 'resend'])
    ->name('verification.resend');

Route::middleware('guest')->group(function () {
    Route::get('/verify-email', function () {
        return Inertia::render('Auth/VerifyEmail', [
            'status' => session('status')
        ]);
    })->name('verification.notice');
});

// --- Страница с вводом email (GET)
Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
})->name('forgot-password.page');

// --- Отправка email (POST)
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])
    ->name('password.email');

// --- Проверка кода
Route::post('/check-code', [PasswordResetController::class, 'checkCode'])
    ->name('password.check');

// --- Сброс пароля
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.reset');

// --- Страница с вводом кода и новым паролем
Route::get('/reset-password', function () {
    return Inertia::render('Auth/ResetPassword', [
        'verified' => request()->query('verified') === 'true', // показывает форму нового пароля
    ]);
})->name('password.showReset');

// Sākums
Route::get('/', [AllBooksController::class, 'getHomePageBooks'])->name('Home');

// Mēklēšana
Route::get('/search', [AllBooksController::class, 'searchBooks'])->name('search.books');


//Lapas no kājenes
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

//Tehniskais atbalsts
Route::post('/support', [SupportController::class, 'store']);


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

Route::get('/User/{id}/watch', [ProfileController::class, 'Watch'])
    ->middleware('can:Admin')
    ->name('users.watch');

Route::middleware(['auth', 'can:Admin'])->group(function () {
    Route::delete('/users/{user}', [ProfileController::class, 'delete'])->name('users.destroy');
});

Route::get('/Forms', [SupportController::class, 'showProblems'])
    ->middleware('can:Admin')
    ->name('problems');

Route::get('/Forms/{id}', [SupportController::class, 'showForm'])
    ->middleware('can:Admin')
    ->name('problems.show');

Route::delete('/Forms/{id}', [SupportController::class, 'destroy'])
    ->middleware('can:Admin')
    ->name('problems.destroy');


Route::get('/BookList', [AllBooksController::class, 'showAllList'])
    ->middleware('can:Admin')
    ->name('book.lists');


Route::delete('/books/{id}', [UserBookController::class, 'destroyBook'])
    ->name('books.destroy');

Route::delete('/classic-books/{id}', [ClassicBookController::class, 'destroyBook'])
    ->middleware('can:Admin')
    ->name('classic_books.destroy');


Route::post('/CreateBook', [ClassicBookController::class, 'store'])
    ->middleware('can:Admin')
    ->name('classic.store');

Route::get('/CreateBook', [ClassicBookController::class, 'create'])
    ->middleware('can:Admin')
    ->name('NewBook');

Route::get('/Classic/{id}/edit', [ClassicBookController::class, 'editClassic'])
    ->middleware('can:Admin')
    ->name('EditClassicBook');

Route::put('/Classic/{id}', [ClassicBookController::class, 'updateClassic'])
    ->middleware('can:Admin')
    ->name('classic.books.update');



Route::get('/NewClassicChapter', function () {
    return Inertia::render('Control/Books/NewInfo/NewClassicChapter');
})->name('NewClassicChapter')->middleware('can:Admin');

Route::get('/Classic/{book}/chapters/create', [ClassicChapterController::class, 'create'])
    ->middleware('can:Admin')
    ->name('classic_chapters.create');

Route::post('/Classic_chapters', [ClassicChapterController::class, 'store'])
    ->middleware('can:Admin')
    ->name('classic.chapters.store');

Route::get('/classic-chapters/{chapter}/edit', [ClassicChapterController::class, 'edit'])
    ->middleware('can:Admin')
    ->name('classic.chapters.edit');

Route::put('/classic-chapters/{chapter}', [ClassicChapterController::class, 'update'])
    ->middleware('can:Admin')
    ->name('classic.chapters.update');

Route::delete('/classic-chapters/{id}', [ClassicChapterController::class, 'destroy'])
    ->middleware('can:Admin')
    ->name('classic.chapters.destroy');


Route::get('/Library', [AllBooksController::class, 'showAll'])->name('library');

Route::get('/Classic/{bookId}', [ClassicBookController::class, 'showInfo'])
    ->name('ClassicRead');

Route::get('/Classic/{bookId}/Read/{chapterId}', [ClassicChapterController::class, 'showContent'])
    ->name('ClassicContent');

Route::get('/User/{bookId}', [UserBookController::class, 'showInfo'])
    ->name('UserRead');

Route::get('/User/{bookId}/Read/{chapterId}', [ChapterController::class, 'showContent'])
    ->name('UserContent');


Route::get('/Filter', [AllBooksController::class, 'filter'])
    ->name('books.filter');


Route::post('/classic-books/{book}/rate', [ClassicBookController::class, 'rateBook'])
    ->name('classic-books.rate')
    ->middleware('auth');

Route::post('/user-books/{book}/rate', [UserBookController::class, 'rateBook'])
    ->name('user-books.rate')
    ->middleware('auth');


Route::middleware(['auth'])->group(function () {
    Route::post('/bookmarks/add', [BookmarkController::class, 'add'])
        ->name('bookmarks.add');
    Route::delete('/bookmarks/remove/{bookId}', [BookmarkController::class, 'remove'])
        ->name('bookmarks.remove');

    Route::get('/bookmarks/read', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.read')
        ->defaults('typeId', 1);

    Route::get('/bookmarks/reading', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.reading')
        ->defaults('typeId', 2);

    Route::get('/bookmarks/dropped', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.dropped')
        ->defaults('typeId', 3);

    Route::get('/bookmarks/planned', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.planned')
        ->defaults('typeId', 4);
});

require __DIR__.'/auth.php';

