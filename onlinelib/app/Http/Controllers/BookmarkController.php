<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\BookmarkType;
use App\Models\ClassicBook;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookmarkController extends Controller
{

    // Pievieno jaunu grāmatzīmi vai atjauno esošu
    public function add(Request $request)
    {
        // Validācija ievaddatiem
        $request->validate([
            'book_id' => 'required|integer', // Grāmatas ID
            'bookmark_type_id' => 'required|integer|exists:bookmark_types,id', // Grāmatzīmes tipa ID
            'is_classic' => 'required|boolean' // Vai grāmata ir klasiska
        ]);

        // Izveido vai atjauno grāmatzīmi
        $bookmark = Bookmark::updateOrCreate(
            [
                'user_id' => Auth::id(), // Pašreizējais lietotājs
                $request->is_classic ? 'classic_book_id' : 'user_book_id' => $request->book_id // Atkarībā no grāmatas tipa
            ],
            [
                'bookmark_type_id' => $request->bookmark_type_id // Grāmatzīmes tips
            ]
        );

        // Atgriež grāmatzīmes datus
        return response()->json([
            'bookmark' => [
                'id' => $bookmark->bookmark_type_id, // Grāmatzīmes tipa ID
                'name' => $bookmark->bookmarkType->name // Grāmatzīmes nosaukums
            ]
        ]);
    }

     //Dzēš gramatas grāmatzīmē
    public function remove($bookId)
    {
        // Atrod un dzēš grāmatas
        Bookmark::where('user_id', Auth::id())
            ->where('classic_book_id', $bookId)
            ->delete();

        // Atgriež veiksmīgas dzēšanas paziņojumu
        return response()->json(['success' => true]);
    }

    //Atgriež grāmatzīmju lapu pēc tipa
    public function bookmarkPage($typeId)
    {
        $userId = auth()->id(); // Pašreizējā lietotāja ID
        $bookmarkType = BookmarkType::findOrFail($typeId); // Atrod grāmatzīmes tipu

        // Kartēšana starp grāmatzīmju tipu ID un nosaukumiem
        $typeTitles = [
            1 => 'Izlasītās grāmatas',
            2 => 'Grāmatas lasāmas',
            3 => 'Pamestas grāmatas',
            4 => 'Plānotās grāmatas'
        ];

        // Sagatavo datus skatam
        $data = [
            'books' => $this->getBooks(UserBook::class, 'user_book_id', $userId, $bookmarkType->id),
            'classicBooks' => $this->getBooks(ClassicBook::class, 'classic_book_id', $userId, $bookmarkType->id),
            'pageTitle' => $typeTitles[$bookmarkType->id] ?? 'Grāmatas',
            'activeTab' => $bookmarkType->id,
            'viewingUserId' => $userId
        ];

        return Inertia::render('Bookmarks/SingleBookmarkPage', $data);
    }

    // Atgriež cita lietotāja grāmatzīmes pēc tipa
    public function userBookmarkPage($userId, $typeId)
    {
        // Atro grāmatzīmes tipu, lai iegūtu nosaukumu
        $bookmarkType = BookmarkType::findOrFail($typeId);

        $typeTitles = [
            1 => 'Izlasītās grāmatas',
            2 => 'Grāmatas lasāmas',
            3 => 'Pamestas grāmatas',
            4 => 'Plānotās grāmatas'
        ];

        // Sagatavo datus skatam
        $data = [
            'books' => $this->getBooks(UserBook::class, 'user_book_id', $userId, $bookmarkType->id),
            'classicBooks' => $this->getBooks(ClassicBook::class, 'classic_book_id', $userId, $bookmarkType->id),
            'pageTitle' => $typeTitles[$bookmarkType->id] ?? 'Grāmatas',
            'activeTab' => $bookmarkType->id,
            'viewingUserId' => $userId
        ];

        return Inertia::render('Bookmarks/UserSingleBookmarkPage', $data);
    }

     //Iegūst grāmatas ar noteikto grāmatzīmi
    protected function getBooks($model, $field, $userId, $typeId)
    {
        // Veido vaicājumu grāmatām ar grāmatzīmēm
        $query = $model::where('is_blocked', false)
        ->whereHas('bookmark', fn($q) => $q
            ->where('bookmark_type_id', $typeId)
            ->where('user_id', $userId)
        );

        // Pievieno attiecības atkarībā no modeļa tipa
        $query->with(['genres', 'ratings', 'bookmark.user:id,nickname']);

        // Lietotāja grāmatām pievieno arī lietotāja datus
        if ($model === UserBook::class) {
            $query->with('user:id,nickname');
        }

        // Atgriež sagatavoto grāmatu sarakstu
        return $query->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->latest()
            ->get()
            ->map(function ($book) use ($model) {
                $book->is_classic = $model === ClassicBook::class;
                return $book;});
    }
}
