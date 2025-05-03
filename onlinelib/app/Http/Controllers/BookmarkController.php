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
            1 => 'Izlasītās grāmatas',    // ID 1 = izlasītās
            2 => 'Grāmatas lasāmas',      // ID 2 = lasāmas
            3 => 'Pamestas grāmatas',     // ID 3 = pamestas
            4 => 'Plānotās grāmatas'      // ID 4 = plānotās
        ];

        // Sagatavo datus skatam
        $data = [
            'books' => $this->getBooks(UserBook::class, 'user_book_id', $userId, $bookmarkType->id), // Lietotāja grāmatas
            'classicBooks' => $this->getBooks(ClassicBook::class, 'classic_book_id', $userId, $bookmarkType->id), // Klasiskās grāmatas
            'pageTitle' => $typeTitles[$bookmarkType->id] ?? 'Grāmatas', // Lapas virsraksts
            'activeTab' => $bookmarkType->id // Aktīvā cilne
        ];

        return Inertia::render('Bookmarks/SingleBookmarkPage', $data);
    }


     //Iegūst grāmatas ar noteikto grāmatzīmi
    protected function getBooks($model, $field, $userId, $typeId)
    {
        // Veido vaicājumu grāmatām ar grāmatzīmēm
        $query = $model::whereHas('bookmark', fn($q) => $q
            ->where('user_id', $userId) // Filtrē pēc lietotāja ID
            ->where('bookmark_type_id', $typeId) // Filtrē pēc grāmatzīmes tipa
        );

        // Pievieno attiecības atkarībā no modeļa tipa
        $query->with(['genres', 'ratings']); // Iekļauj žanrus un vērtējumus

        // Lietotāja grāmatām pievieno arī lietotāja datus
        if ($model === UserBook::class) {
            $query->with('user:id,nickname');
        }

        // Atgriež sagatavoto grāmatu sarakstu
        return $query->withAvg('ratings', 'grade') // Vērtējumu vidējais
        ->withCount('ratings') // Vērtējumu skaits
        ->latest() // Jaunākās pirmās
        ->get()
            ->map(function($book) use ($model) {
                $book->is_classic = $model === ClassicBook::class; // Atzīmē klasiskās grāmatas
                return $book;
            });
    }
}
