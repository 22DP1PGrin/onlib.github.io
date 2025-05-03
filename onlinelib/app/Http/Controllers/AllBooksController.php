<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassicBook;
use App\Models\Genre;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AllBooksController extends Controller
{
    //Atgriež visu grāmatu sarakstu
    public function showAllList()
    {
        $book = UserBook::with('user')
            ->orderBy('name', 'asc')
            ->get();
        $classicBooks = ClassicBook::orderBy('name', 'asc') ->get();
        return Inertia::render('Control/Books/BooksList', [
            'book' => $book,
            'classicBooks' => $classicBooks,
        ]);
    }

    // Atgriež visu grāmatu sarakstu ar saistītajiem datiem
    public function showAll()
    {

        $userId = auth()->id();

        // Iegūst visas lietotāju grāmatas ar saistītajiem lietotājiem un žanriem
        $books = UserBook::query()
            ->with([
                'user',
                'genres',
                'bookmark.bookmarkType'
            ])
            ->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->get()
            ->each(function ($book) {
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });



        // Iegūst visas klasiskās grāmatas ar saistītajiem žanriem
        $classicBooks = ClassicBook::query()
            ->with([
                'genres',
                'bookmark.bookmarkType'
            ])
            ->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->get()
            ->each(function ($book) {
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });

        // Iegūst visus žanrus
        $allGenres = Genre::orderBy('name', 'asc')->get();
        $ratings = [
            ['id' => '0+', 'label' => '0+'],
            ['id' => '6+', 'label' => '6+'],
            ['id' => '12+', 'label' => '12+'],
            ['id' => '16+', 'label' => '16+'],
            ['id' => '18+', 'label' => '18+'],
        ];

        // Atgriež Inertia skatu ar abu veidu grāmatu kolekcijām
        return Inertia::render('Reading/Library', [
            'books' => $books,
            'classicBooks' => $classicBooks,
            'allGenres' => $allGenres,
            'ratings' => $ratings,
        ]);
    }

    //Metode filtrešānai
    public function filter(Request $request)
    {
        // Lietotāju grāmatas ar vidējo vērtējumu un skaitu
        $query = UserBook::query()
            ->with(['user', 'genres'])
            ->withAvg('ratings as ratings_avg_grade', 'grade')
            ->withCount('ratings as ratings_count');

        // Klasiskās grāmatas ar vidējo vērtējumu un skaitu
        $classicQuery = ClassicBook::query()
            ->with('genres')
            ->withAvg('ratings as ratings_avg_grade', 'grade')
            ->withCount('ratings as ratings_count');

        // Lietotāju grāmatu filtrēšana (ja nav atlasīts tikai klasiskās)
        if ($request->bookType !== 'classic') {
            // Filtrē pēc vecuma ierobežojuma
            if ($request->ratings) {
                $query->whereIn('age_limit', $request->ratings);
            }

            // Filtrē pēc žanriem
            if (!empty($request->genres)) {
                $query->whereHas('genres', function($q) use ($request) {
                    $q->whereIn('genres.id', $request->genres); // Precīzi norādam tabulu
                });
            }

            // Filtrē pēc statusa (tikai lietotāju grāmatām)
            if (!empty($request->statuses)) {
                $query->whereIn('status', $request->statuses);
            }
        }

        // Klasisko grāmatu filtrēšana (ja nav atlasīts tikai lietotāju grāmatas)
        if ($request->bookType !== 'user') {
            // Filtrē pēc vecuma ierobežojuma
            if ($request->ratings) {
                $classicQuery->whereIn('age_limit', $request->ratings);
            }

            // Filtrē pēc žanriem
            if (!empty($request->genres)) {
                $classicQuery->whereHas('genres', function($q) use ($request) {
                    $q->whereIn('genres.id', $request->genres); // Precīzi norādam tabulu
                });
            }
        }

        // Atgriežam Inertia skatu ar filtrētajiem rezultātiem
        return Inertia::render('Reading/Library', [
            // Filtrētās lietotāju grāmatas (ja nav atlasīts tikai klasiskās)
            'books' => $request->bookType === 'classic' ? [] : $query->get(),

            // Filtrētās klasiskās grāmatas (ja nav atlasīts tikai lietotāju grāmatas)
            'classicBooks' => $request->bookType === 'user' ? [] : $classicQuery->get(),

            // Visi pieejamie žanri (sakārtoti pēc nosaukuma)
            'allGenres' => Genre::orderBy('name')->get(),

            // Vecuma ierobežojumu opcijas
            'ratings' => [
                ['id' => '0+', 'label' => '0+'],
                ['id' => '6+', 'label' => '6+'],
                ['id' => '12+', 'label' => '12+'],
                ['id' => '16+', 'label' => '16+'],
                ['id' => '18+', 'label' => '18+'],
            ],

            // Statusu opcijas (tikai lietotāju grāmatām)
            'statuses' => [
                ['id' => 'Procesā', 'label' => 'Procesā'],
                ['id' => 'Pabeigts', 'label' => 'Pabeigts'],
                ['id' => 'Pamests', 'label' => 'Pamests'],
            ],

            // Aktīvie filtri (tiek izmantoti, lai atjauninātu saskarni)
            'filters' => [
                'bookType' => $request->bookType ?? 'all', // Grāmatu tips
                'ratings' => $request->ratings ?? [], // Atlasītie vērtējumi
                'genres' => is_array($request->genres)
                    ? array_map('strval', $request->genres) // Pārveidojam žanru ID par string
                    : [], // Atlasītie žanri
                'statuses' => $request->statuses ?? [] // Atlasītie statusi

            ],
            'hasFilteredResults' => ($request->bookType !== 'classic' && $query->count() > 0) ||
                ($request->bookType !== 'user' && $classicQuery->count() > 0)
        ]);
    }
    public function searchBooks(Request $request)
    {
        $query = $request->input('query');
        $userId = auth()->id();

        // Поиск пользовательских книг
        $books = UserBook::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->with(['user', 'genres', 'bookmark.bookmarkType'])
            ->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->get()
            ->each(function ($book) {
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });

        // Поиск классических книг
        $classicBooks = ClassicBook::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->with(['genres', 'bookmark.bookmarkType'])
            ->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->get()
            ->each(function ($book) {
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });

        return Inertia::render('Search/Results', [
            'books' => $books,
            'classicBooks' => $classicBooks,
            'searchQuery' => $query,
            'allGenres' => Genre::orderBy('name', 'asc')->get(),
            'ratings' => [
                ['id' => '0+', 'label' => '0+'],
                ['id' => '6+', 'label' => '6+'],
                ['id' => '12+', 'label' => '12+'],
                ['id' => '16+', 'label' => '16+'],
                ['id' => '18+', 'label' => '18+'],
            ]
        ]);
    }

    public function getHomePageBooks()
    {
        // Iegūst 3 jaunākās klasiskās grāmatas, sakārtotas pēc izveides datuma (jaunākās pirmās)
        $classicBooks = ClassicBook::query()
            ->orderBy('created_at', 'desc') // Sakārto dilstošā secībā pēc izveides laika
            ->take(3) // Ierobežo rezultātus līdz 3 ierakstiem
            ->with(['genres']) // Iekļauj saistītos žanrus
            ->withAvg('ratings', 'grade') // Aprēķina vidējo vērtējumu
            ->withCount('ratings') // Saskaita vērtējumus
            ->get()
            ->each(function ($book) {
                // Pārveido vidējo vērtējumu par float tipu
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
            });

        // Iegūst 3 jaunākās lietotāju grāmatas, sakārtotas pēc izveides datuma
        $userBooks = UserBook::query()
            ->orderBy('created_at', 'desc') // Sakārto dilstošā secībā
            ->take(3) // Ierobežo līdz 3 ierakstiem
            ->with(['user', 'genres', 'bookmark.bookmarkType']) // Iekļauj saistītos datus
            ->withAvg('ratings', 'grade') // Aprēķina vidējo vērtējumu
            ->withCount('ratings') // Saskaita vērtējumus
            ->get()
            ->each(function ($book) {
                // Pārveido vērtējumu un pievieno grāmatzīmes datus
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });

        // Atgriež Inertia skatu ar abu veidu grāmatām
        return Inertia::render('HomeView', [
            'classicBooks' => $classicBooks, // Klasiskās grāmatas
            'userBooks' => $userBooks // Lietotāju grāmatas
        ]);
    }
}
