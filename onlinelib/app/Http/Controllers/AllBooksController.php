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
    // Atgriež visu grāmatu sarakstu ar saistītajiem datiem bibliotēkai
    public function showAll()
    {
        $userId = auth()->id();

        // Iegūst visas lietotāju grāmatas ar saistītajiem lietotājiem un žanriem
        $books = UserBook::query()
            ->with([
                'user',
                'genres',
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)->with('bookmarkType');
                }
            ])
            ->Where('is_blocked', false)
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
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)->with('bookmarkType');
                }
            ])
            ->Where('is_blocked', false)
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
        $sort = $request->sort ?? 'date';
        $direction = $request->direction ?? 'desc';
        $userId = auth()->id();

        // Lietotāju grāmatas ar vidējo vērtējumu un skaitu
        $query = UserBook::query()
            ->where('is_blocked', false)
            ->with(['user', 'genres',
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->with('bookmarkType');
            }])
            ->withAvg('ratings as ratings_avg_grade', 'grade')
            ->withCount('ratings as ratings_count')
            ->withCount('comments as comments_count')
            ->withCount('chapters as chapters_count');

        // Klasiskās grāmatas ar vidējo vērtējumu un skaitu
        $classicQuery = ClassicBook::query()
            ->where('is_blocked', false)
            ->with(['genres',
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->with('bookmarkType');
            }])
            ->withAvg('ratings as ratings_avg_grade', 'grade')
            ->withCount('ratings as ratings_count')
            ->withCount('comments as comments_count')
            ->withCount('chapters as chapters_count');

        // Lietotāju grāmatu filtrēšana (ja nav atlasīts tikai klasiskās)
        if ($request->bookType !== 'classic') {
            // Filtrē pēc vecuma ierobežojuma
            if ($request->ratings) {
                $query->whereIn('age_limit', $request->ratings);
            }

            // Filtrē pēc žanriem
            if (!empty($request->includeGenres)) {
                $query->whereHas('genres', fn($q) => $q->whereIn('genres.id', $request->includeGenres));
            }

            if (!empty($request->excludeGenres)) {
                $query->whereDoesntHave('genres', fn($q) => $q->whereIn('genres.id', $request->excludeGenres));
            }

            // Filtrē pēc statusa (tikai lietotāju grāmatām)
            if (!empty($request->statuses)) {
                $query->whereIn('status', $request->statuses);
            }

            $this->applySorting($query, $sort, $direction);
        }

        // Klasisko grāmatu filtrēšana (ja nav atlasīts tikai lietotāju grāmatas)
        if ($request->bookType !== 'user') {
            // Filtrē pēc vecuma ierobežojuma
            if ($request->ratings) {
                $classicQuery->whereIn('age_limit', $request->ratings);
            }

            // Filtrē pēc žanriem
            if (!empty($request->includeGenres)) {
                $classicQuery->whereHas('genres', fn($q) => $q->whereIn('genres.id', $request->includeGenres));
            }

            if (!empty($request->excludeGenres)) {
                $classicQuery->whereDoesntHave('genres', fn($q) => $q->whereIn('genres.id', $request->excludeGenres));
            }

            $this->applySorting($classicQuery, $sort, $direction);
        }

        $books = $request->bookType === 'classic'
            ? collect()
            : $query->get()->each(function ($book) {
                $book->current_bookmark = $book->bookmark?->first()?->bookmarkType;
            });

        $classicBooks = $request->bookType === 'user'
            ? collect()
            : $classicQuery->get()->each(function ($book) {
                $book->current_bookmark = $book->bookmark?->bookmarkType;
            });

        // Atgriež Inertia skatu ar filtrētajiem rezultātiem
        return Inertia::render('Reading/Library', [
            // Filtrētās lietotāju grāmatas (ja nav atlasīts tikai klasiskās)
            'books' => $books,

            // Filtrētās klasiskās grāmatas (ja nav atlasīts tikai lietotāju grāmatas)
            'classicBooks' => $classicBooks,

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
                'bookType' => $request->bookType ?? 'all',
                'ratings' => $request->ratings ?? [],
                'statuses' => $request->statuses ?? [],
                'includeGenres' => $request->includeGenres ?? [],
                'excludeGenres' => $request->excludeGenres ?? [],
                'sort' => $request->sort ?? 'date',
                'direction' => $request->direction ?? 'desc',
            ],

            'hasFilteredResults' => count($books) > 0 || count($classicBooks) > 0,
        ]);
    }

    // Kārto grāmatas vai stāstus
    private function applySorting($query, $sort, $direction = 'desc')
    {
        // Aizsardzība pret nederīgu virzienu: ja nav "asc", tad tiek izmantots "desc"
        $direction = $direction === 'asc' ? 'asc' : 'desc';

        // Pārslēgšanās pēc kārtošanas parametra
        switch ($sort) {
            case 'rating':
                // Kārto pēc vidējā vērtējuma; NULL vērtības aizstāj ar 0
                return $query->orderByRaw('COALESCE(ratings_avg_grade, 0) ' . $direction);

            case 'chapters':
                // Kārto pēc nodaļu skaita
                return $query->orderBy('chapters_count', $direction);

            case 'comments':
                // Kārto pēc komentāru skaita
                return $query->orderBy('comments_count', $direction);

            case 'date':
            default:
                // Noklusējuma kārtošana pēc izveides datuma
                return $query->orderBy('created_at', $direction);
        }
    }

    // Mēkle grāmatu vai stāstu pēc to nosaukuma
    public function searchBooks(Request $request)
    {
        $query = $request->input('query');
        $userId = auth()->id();

        // Lietotāju grāmatu meklēšanu
        $books = UserBook::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->where('is_blocked', false)
            ->with(['user', 'genres',
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->with('bookmarkType');
                }])
            ->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->get()
            ->each(function ($book) {
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });

        // Klasisko grāmatu meklēšanu
        $classicBooks = ClassicBook::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->where('is_blocked', false)
            ->with(['genres',
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->with('bookmarkType');
            }])
            ->withAvg('ratings', 'grade')
            ->withCount('ratings')
            ->get()
            ->each(function ($book) {
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
                $book->current_bookmark = $book->bookmark ? $book->bookmark->bookmarkType : null;
            });

        // Atgriež Inertia skatu ar meklēšanas rezultātiem
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

    // Atgriež 3 jaunākās klasiskās un lietotāju grāmatas mājaslapas skata sākumlapai
    public function getHomePageBooks()
    {
        $userId = auth()->id();

        // Iegūst 3 jaunākās klasiskās grāmatas, sakārtotas pēc izveides datuma (jaunākās pirmās)
        $classicBooks = ClassicBook::query()
            ->Where('is_blocked', false)
            ->orderBy('created_at', 'desc') // Sakārto dilstošā secībā pēc izveides laika
            ->take(3) // Ierobežo rezultātus līdz 3 ierakstiem
            ->with(['genres',
                'bookmark' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->with('bookmarkType');
                }])
            ->withAvg('ratings', 'grade') // Aprēķina vidējo vērtējumu
            ->withCount('ratings') // Saskaita vērtējumus
            ->get()
            ->each(function ($book) {
                // Pārveido vidējo vērtējumu par float tipu
                $book->ratings_avg_grade = (float)$book->ratings_avg_grade;
            });

        // Iegūst 3 jaunākās lietotāju grāmatas, sakārtotas pēc izveides datuma
        $userBooks = UserBook::query()
            ->Where('is_blocked', false)
            ->orderBy('created_at', 'desc') // Sakārto dilstošā secībā
            ->take(3) // Ierobežo līdz 3 ierakstiem
            ->with(['user', 'genres', 'bookmark' => function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->with('bookmarkType');
            }])
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
