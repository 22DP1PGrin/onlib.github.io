<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\ClassicBook;
use App\Models\BookChapter;
use App\Models\Rating;
use App\Models\Genre;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ClassicBookController extends Controller
{

// Metode, kas atver grāmatas izveides formu
    public function create()
    {
        // Atgriežam skatu ar žanriem un vecuma ierobežojumiem
        return Inertia::render('Control/Books/NewInfo/NewClassicBook', [
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
        return Inertia::render('Control/Books/EditInfo/ClassicEdit', [
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

    // Metode, kas parāda visu informāciju par grāmatu
    public function showInfo($id)
    {
        // Atrodam grāmatu ar visiem saistītajiem datiem
        $book = ClassicBook::with(['genres', 'chapters' => function($query) {
        }])
            ->findOrFail($id);


        // Saņemiet vērtējuma datus
        $ratingsData = Rating::where('classic_book_id', $id)
            ->selectRaw('AVG(grade) as average, COUNT(*) as count')
            ->first();

        // Iegūstiet pašreizējā lietotāja vērtējumu
        $userRating = auth()->check()
            ? Rating::where('classic_book_id', $id)
                ->where('user_id', auth()->id())
                ->value('grade')
            : null;

        //Grāmatzīmju atrašana
        $userBookmark = null;
        if (auth()->check()) {
            $bookmark = Bookmark::where('user_id', auth()->id())
                ->where('classic_book_id', $id)
                ->with('bookmarkType')
                ->first();

            if ($bookmark) {
                $userBookmark = [
                    'id' => $bookmark->bookmark_type_id,
                    'name' => $bookmark->bookmarkType->name
                ];
            }
        }

        // Atgriežam rediģēšanas skatu ar nepieciešamajiem datiem
        return Inertia::render('Reading/ClassicBooks/ClassicBook', [
            'book' => $book,
            'genres' => $book->genres,
            'chapters' => $book->chapters,
            'initialAverageRating' => (float) ($ratingsData->average ?? 0),
            'initialRatingsCount' => $ratingsData->count ?? 0,
            'initialUserRating' => $userRating,
            'initialUserBookmark' => $userBookmark
        ]);
    }

     // Grāmatas vērtēšanas funkcija
    public function rateBook(Request $request, $bookId)
    {
        // Validācija - vērtējumam jābūt no 1 līdz 5
        $request->validate([
            'grade' => 'required|integer|min:1|max:5'
        ]);

        // Atjaunina esošu vai izveido jaunu vērtējumu
        $rating = Rating::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'classic_book_id' => $bookId
            ],
            [
                'grade' => $request->grade
            ]
        );

        // Pārrēķina vidējo vērtējumu grāmatai
        $average = Rating::where('classic_book_id', $bookId)
            ->avg('grade');

        // Saskaita vērtējumu skaitu grāmatai
        $count = Rating::where('classic_book_id', $bookId)
            ->count();

        // Atgriež veiksmīgu atbildi ar datiem
        return response()->json([
            'success' => true,
            'averageRating' => (float) $average,  // Vidējais vērtējums
            'ratingsCount' => $count,             // Vērtējumu skaits
            'userRating' => $rating->grade       // Lietotāja vērtējums
        ]);

    }
}
