<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassicBook;
use App\Models\ClassicBookChapter;
use App\Models\Genre;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ClassicBookController extends Controller
{

// Metode, kas atver grāmatas izveides formu
    public function create()
    {
        // Atgriežam skatu ar žanriem un vecuma ierobežojumiem
        return Inertia::render('Control/NewClassicBook', [
            'genres' => Genre::all(), // Saņemam visus žanrus no datu bāzes
            'ratings' => [
                ['id' => '0+', 'label' => '0+ '],
                ['id' => '6+', 'label' => '6+'],
                ['id' => '12+', 'label' => '12+'],
                ['id' => '16+', 'label' => '16+'],
                ['id' => '18+', 'label' => '18+'],
            ]
        ]);
    }

    // Metode, kas saglabā jaunu grāmatu
    public function store(Request $request)
    {
        // Validējam saņemtos datus
        $validated = $request->validate([
            'title' => 'required|string|max:25',
            'description' => 'required|string|max:250',
            'Author_name'=> 'required|string|max:30',
            'Author_surname'=> 'required|string|max:30',
            'Year_publication'=> 'required|integer|digits:4',
            'rating_id' => 'required|in:0+,6+,12+,16+,18+',
            'genres' => 'required|array|min:1', // Jāizvēlas vismaz viens žanrs
            'genres.*' => 'exists:genres,id' // Katram žanram jābūt derīgam
        ], [
            'title.max' => 'Nosaukums nedrīkst pārsniegt 25 rakstzīmes.',
            'description.max' => 'Apraksts nedrīkst pārsniegt 250 rakstzīmes.',
            'genres.required' => 'Jāizvēlas vismaz viens žanrs.',
            'genres.min' => 'Jāizvēlas vismaz viens žanrs.',
            'Author_name.min' => 'Autora vārds nedrīkst pārsniegt 30 rakstzīmes.',
            'Author_surname.min' =>'Autora vārds nedrīkst pārsniegt 30 rakstzīmes.',
            'Year_publication.integer' => 'Publikācijas gadam jābūt veselam skaitlim',
            'Year_publication.digits' => 'Publicēšanas gadam jābūt tieši 4 cipariem',
        ]);

        // Izveidojam jaunu grāmatu
        $book = ClassicBook::create([
            'user_id' => auth()->id(),
            'name' => $validated['title'],
            'description' => $validated['description'],
            'age_limit' => $validated['rating_id'],
            'Author_name' => $validated['Author_name'],
            'Author_surname' =>$validated['Author_surname'],
            'Year_publication' =>$validated['Year_publication'],
        ]);

        // Pievienojam žanrus grāmatai
        $book->genres()->attach($validated['genres']);

        // Pāradresējam uz grāmatu sarakstu
        return redirect()->route('book.lists');
    }

    // Metode, kas atver grāmatas rediģēšanas formu
    public function editClassic($id)
    {
        // Atrodam grāmatu pēc ID un lietotāja ID
        $classic_book = ClassicBook::findOrFail($id)
            ->with('genres', 'chapters') // Iekļaujam žanrus un nodaļas
            ->findOrFail($id);

        // Atgriežam skatu ar grāmatas datiem un iespējām rediģēt
        return Inertia::render('Control/ClassicEdit', [
            'classic_book' => $classic_book,
            'genres' => Genre::all(),
            'chapters' => $classic_book->chapters,
            'ratings' => [
                ['id' => '0+', 'label' => '0+'],
                ['id' => '6+', 'label' => '6+'],
                ['id' => '12+', 'label' => '12+'],
                ['id' => '16+', 'label' => '16+'],
                ['id' => '18+', 'label' => '18+'],
            ],
            'flash' => session('flash')
        ]);
    }

    // Metode, kas atjaunina esošu grāmatu
    public function updateClassic(Request $request, $id)
    {
        // Atrodam grāmatu pēc ID un lietotāja ID
        $classic_book = ClassicBook::findOrFail($id)
            ->findOrFail($id);

        // Validējam saņemtos datus
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:250',
            'age_limit' => 'required|in:0+,6+,12+,16+,18+',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'Year_publication'=> 'required|integer|digits:4',
            'Author_name'=> 'required|string|max:30',
            'Author_surname'=> 'required|string|max:30'
        ], [
            'name.max' => 'Nosaukums nedrīkst pārsniegt 25 rakstzīmes.',
            'description.max' => 'Apraksts nedrīkst pārsniegt 250 rakstzīmes.',
            'genres.required' => 'Jāizvēlas vismaz viens žanrs.',
            'genres.min' => 'Jāizvēlas vismaz viens žanrs.',
            'Author_name.min' => 'Autora vārds nedrīkst pārsniegt 30 rakstzīmes.',
            'Author_surname.min' =>'Autora vārds nedrīkst pārsniegt 30 rakstzīmes.',
            'Year_publication.integer' => 'Publikācijas gadam jābūt veselam skaitlim',
            'Year_publication.digits' => 'Publicēšanas gadam jābūt tieši 4 cipariem',
        ]);

        // Atjauninām grāmatu
        $classic_book->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'age_limit' => $validated['age_limit'],
            'Author_name' => $validated['Author_name'],
            'Author_surname' =>$validated['Author_surname'],
            'Year_publication' =>$validated['Year_publication'],

        ]);

        // Atjaunojam žanrus
        if (isset($validated['genres'])) {
            $classic_book->genres()->sync($validated['genres']);
        }

        // Pāradresējam uz grāmatu sarakstu ar ziņojumu par veiksmi
        return redirect()->route('book.lists')->with('success', 'Stāsts veiksmīgi atjaunināts!');
    }


     // Dzēš konkrētu klasisko grāmatu
    public function destroyBook($id)
    {
        // Atrod grāmatu pēc ID vai izmet kļūdu, ja tāda nav atrasta
        $book = ClassicBook::findOrFail($id);

        // Dzēš atrasto grāmatu
        $book->delete();

        // Pāradresē atpakaļ uz iepriekšējo lapu ar veiksmes ziņojumu
        return redirect()->back()->with('success', 'Grāmata veiksmīgi dzēsta.');
    }

    // Atgriež visu grāmatu sarakstu ar saistītajiem datiem
    public function showAll()
    {
        // Iegūst visas lietotāju grāmatas ar saistītajiem lietotājiem un žanriem
        $books = UserBook::with(['user', 'genres'])->get();

        // Iegūst visas klasiskās grāmatas ar saistītajiem žanriem
        $classicBooks = ClassicBook::with('genres')->get();

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

    // Metode, kas parāda visu informāciju par grāmatu
    public function showInfo($id)
    {
        // Atrodam grāmatu ar visiem saistītajiem datiem
        $book = ClassicBook::with(['genres', 'chapters' => function($query) {
        }])
            ->findOrFail($id);

        // Atgriežam rediģēšanas skatu ar nepieciešamajiem datiem
        return Inertia::render('Reading/ClassicBook', [
            'book' => $book,
            'genres' => $book->genres,
            'chapters' => $book->chapters
        ]);
    }

    //Metode filtrešānai
    public function filter(Request $request)
    {
        // Inicializējam vaicājumus ar relācijām
        $query = UserBook::query()->with(['user', 'genres']);
        $classicQuery = ClassicBook::query()->with('genres');

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
}
