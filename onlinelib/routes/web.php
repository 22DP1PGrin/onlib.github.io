<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AllBooksController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ClassicBookController;
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

// Sākums
Route::get('/', [AllBooksController::class, 'getHomePageBooks'])->name('Home');

// Žanrus
Route::get('/genres', [GenreController::class, 'index']);

//AUTENTIFIKĀCIJA, VERIFIKĀCIJA, PAROLES MAINĪŠANA
// E-pasta verifikācijas paziņojums
Route::get('/verify-email', function () {
    return Inertia::render('Auth/VerifyEmail', [
        'status' => session('status')
    ]);
})->name('verification.notice');

// E-pasta verifikācija, kad lietotājs izmainīja savu e-pastu adresi
Route::get('/email-change', function () {
    return Inertia::render('Profile/VerifyEmailChange');
})->name('verify.change.notice');

// E-pasta apstiprināšana ar tokenu
Route::get('/verify-pending/{token}', [RegisteredUserController::class, 'verify'])->name('verify.pending');

// Apstiprināt e-pasta maiņu ar tokenu
Route::get('/email-change/{token}', [ProfileController::class, 'verify'])->name('email.change.confirm');

// Verifikācijas atkārtota nosūtīšana
Route::post('/verify-resend', [RegisteredUserController::class, 'resend'])->name('verification.resend');

// Atsūtīt atkārtoti e-pasta maiņas apstiprinājuma vēstuli
Route::post('/email-resend', [ProfileController::class, 'resend'])->name('email.change.resend');

// Paroles atjaunošanas sākuma lapa (e-pasta ievade)
Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
})->name('forgot-password.page');

// Lapa, kurā lietotājs ievada verifikācijas kodu un/vai jauno paroli
Route::get('/reset-password', function () {
    return Inertia::render('Auth/ResetPassword', [
        'verified' => request()->query('verified') === 'true',
    ]);
})->name('password.showReset');

// E-pasta nosūtīšana ar verifikācijas kodu
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])->name('password.email');

//  Verifikācijas koda pārbaude
Route::post('/check-code', [PasswordResetController::class, 'checkCode'])->name('password.check');

// Paroles atjaunošana pēc veiksmīgas koda pārbaudes
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset');


// FILTRĒŠANA, MEKLĒŠANA UN NOVERTĒŠANA
//Filtrēšana
Route::get('/Filter', [AllBooksController::class, 'filter'])->name('books.filter');

// Mēklēšana
Route::get('/search', [AllBooksController::class, 'searchBooks'])->name('search.books');

// Klasiskas grāmatas novertēšana
Route::post('/classic-books/{book}/rate', [ClassicBookController::class, 'rateBook'])->middleware('auth');

// Klasiskas grāmatas novertēšana
Route::post('/user-books/{book}/rate', [UserBookController::class, 'rateBook'])->middleware('auth');


//LAPAS NO KAJIENES(STATISKAS LAPAS)
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


// PROFILS
Route::middleware('auth')->group(function () {
    // Rādīt profila lapu
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');

    // Atjaunināt profila datus
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Dzēst lietotāja kontu
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Atjaunināt lietotāja paroli
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Augšupielādēt vai mainīt lietotāja avataru
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');

    // Rādīt profila iestatījumu lapu
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
});
//Tehniskais atbalsts
Route::post('/support', [SupportController::class, 'store']);


// LIETOTĀJA GRĀMATAS
Route::middleware('auth')->group(function () {

    // Izveidot jaunu stāstu datu bazē
    Route::post('/CreateStory', [UserBookController::class, 'store'])->name('store');

    //Lapa stāstu veidošanai
    Route::get('/CreateStory', [UserBookController::class, 'create'])->name('NewStory');

    // Lietotāja stāstu saraksts
    Route::get('/StoryList', [UserBookController::class, 'index'])->name('StoryList');

    //Lapa stāstu rediģēšanai
    Route::get('/writing/{id}/edit', [UserBookController::class, 'edit'])->name('EditStory');

    //Rediģēšana
    Route::put('/books/{id}', [UserBookController::class, 'update'])->name('books.update');

    // Dzēšana
    Route::delete('/stories/{id}', [UserBookController::class, 'delete'])->name('deleteStory');

    // LIETOTĀJA GRĀMATU NODAĻAS
    //Lapa nodaļu veidošanai
    Route::get('/user-books/{book}/chapters/create', [ChapterController::class, 'createUser'])->name('user.chapters.create');

    // Izveidot jaunu nodāļu datu bazē
    Route::post('/user-books/{book}/chapters', [ChapterController::class, 'storeUser'])->name('user.chapters.store');

    // Dzēšana
    Route::delete('/user-books/chapters/{chapter}', [ChapterController::class, 'destroyUser'])->name('user.chapters.destroy');

    // Lapa nodaļu rediģēšanai
    Route::get('/user-books/chapters/{chapter}/edit', [ChapterController::class, 'editUser'])->name('user.chapters.edit');

    // Rediģēšana
    Route::put('/user-books/chapters/{chapter}', [ChapterController::class, 'updateUser'])->name('user.chapters.update');
});

// KLASISKĀS GRĀMATAS
Route::middleware(['auth', 'can:Admin'])->group(function () {
    // Izveidot jaunu grāmatu datu bazē
    Route::post('/CreateBook', [ClassicBookController::class, 'store'])->name('classic.store');

    //Lapa grāmatu veidošanai
    Route::get('/CreateBook', [ClassicBookController::class, 'create'])->name('NewBook');

    //Lapa rediģēšanai
    Route::get('/Classic/{id}/edit', [ClassicBookController::class, 'editClassic'])->name('EditClassicBook');

    //Rediģēšana
    Route::put('/Classic/{id}', [ClassicBookController::class, 'updateClassic'])->name('classic.books.update');

    // Dzēšana
    Route::delete('/classic-books/{id}', [ClassicBookController::class, 'destroyBook'])->name('classic_books.destroy');

    // KLASiSKA GRĀMATU NODAĻAS
    //Lapa nodaļu veidošanai
    Route::get('/classic-books/{book}/chapters/create', [ChapterController::class, 'createClassic'])->name('classic.chapters.create');

    // Izveidot jaunu nodāļu datu bazē
    Route::post('/classic-books/{book}/chapters', [ChapterController::class, 'storeClassic'])->name('classic.chapters.store');

    // Dzēšana
    Route::delete('/classic-books/chapters/{chapter}', [ChapterController::class, 'destroyClassic'])
        ->name('classic.chapters.destroy');

    //Lapa nodaļu rediģēšanai
    Route::get('/classic-books/chapters/{chapter}/edit', [ChapterController::class, 'editClassic'])
        ->name('classic.chapters.edit');

    // Rediģēšana
    Route::put('/classic-books/chapters/{chapter}', [ChapterController::class, 'updateClassic'])
        ->name('classic.chapters.update');
});


// ADMINISTRĀCIJA
Route::middleware(['auth', 'can:Admin'])->group(function () {

    // Rāda visu lietotāju sarakstu
    Route::get('/UserControl', [AdminController::class, 'showUsers'])->name('users');

    // Rāda visu bloķētu lietotāju sarakstu
    Route::get('/BlockUserControl', [AdminController::class, 'showBlockUsers'])->name('block.users');

    // Rāda konkrēta lietotāja profila apskates lapu
    Route::get('/User/{id}/watch', [AdminController::class, 'Watch'])->name('users.watch');

    // Lomu mainīšana
    Route::put('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.updateRole');

    // Lietotāju konta dzēšana
    Route::delete('/users/{user}', [AdminController::class, 'delete'])->name('users.destroy');

    // Rāda tehniskā atbalsta iesniegumu sarakstu
    Route::get('/Forms', [SupportController::class, 'showProblems'])->name('problems');

    // Rāda konkrētu tehniskā atbalsta iesniegumu
    Route::get('/Forms/{id}', [SupportController::class, 'showForm'])->name('problems.show');

    // Formu dzēšana
    Route::delete('/Forms/{id}', [SupportController::class, 'destroy'])->name('problems.destroy');

    // Rāda visu grāmatu sarakstu
    Route::get('/BookList', [AdminController::class, 'showAllList'])->name('book.lists');

    // Rāda visu bloķētu grāmatu sarakstu
    Route::get('/BlockBooksList', [AdminController::class, 'showAllBlocksList'])->name('block.book.lists');

    // Klasiskas grāmatas bloķēšanas/atbloķēšanas
    Route::post('/admin/classic-books/{book}/toggle-block', [AdminController::class, 'toggleClassic'])
        ->name('classicBooks.toggleBlock');

    // Lietotāja grāmatas bloķēšanas/atbloķēšanas
    Route::post('/admin/user-books/{userBook}/toggle-block', [AdminController::class, 'toggleUser'])
        ->name('userBooks.toggleBlock');

    // Lietotāja konta bloķēšanas/atbloķēšanas
    Route::post('/admin/user/{user}/toggle-block', [AdminController::class, 'toggleUserBlock'])
        ->name('user.toggleBlock');
});


// LASĪŠANA
// Lapa ar visiem grāmatām
Route::get('/Library', [AllBooksController::class, 'showAll'])->name('library');

// Informācija par gramatām
Route::get('/Classic/{bookId}', [ClassicBookController::class, 'showInfo'])->name('ClassicRead');

// Informācija par nodaļām
Route::get('/books/{bookId}/read/{chapterId}', [ChapterController::class, 'showContent'])->name('chapter.content');

// Informācija par stāstiem
Route::get('/User/{bookId}', [UserBookController::class, 'showInfo'])->name('UserRead');


// GRAMATZĪMES
Route::middleware(['auth'])->group(function () {
    // Pievieņošāna pie grāmatzīme
    Route::post('/bookmarks/add', [BookmarkController::class, 'add'])->name('bookmarks.add');

    // Dzešāna no grāmatzīmes
    Route::delete('/bookmarks/remove/{bookId}', [BookmarkController::class, 'remove'])->name('bookmarks.remove');

    //Izlasīts
    Route::get('/bookmarks/read', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.read')
        ->defaults('typeId', 1);

    // Lasu
    Route::get('/bookmarks/reading', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.reading')
        ->defaults('typeId', 2);

    //Pamests
    Route::get('/bookmarks/dropped', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.dropped')
        ->defaults('typeId', 3);

    //Plānots lasīt
    Route::get('/bookmarks/planned', [BookmarkController::class, 'bookmarkPage'])
        ->name('bookmarks.planned')
        ->defaults('typeId', 4);
});

require __DIR__.'/auth.php';

