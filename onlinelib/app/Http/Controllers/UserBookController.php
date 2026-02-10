<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\UserBook;
use App\Models\Genre;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserBookController extends Controller
{
    // Metode, kas atgriež visus lietotāja grāmatas
    public function index()
    {
        // Saņem visus lietotāja grāmatas
        $works = UserBook::where('user_id', auth()->id())
        ->select('id', 'name', 'description', 'is_blocked', 'created_at')
        ->orderBy('name', 'asc')
        ->get();

        // Atgriež skatu ar visām grāmatām
        return Inertia::render('Writing/StoryList', [
            'works' => $works
        ]);
    }

    // Metode, kas atver grāmatas izveides formu
    public function create()
    {
        // Atgriež skatu ar žanriem un vecuma ierobežojumiem
        return Inertia::render('Writing/NewInfo/NewStory', [
            'genres' => Genre::all(), // Saņem visus žanrus no datu bāzes
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
        // Valide saņemtos datus
        $validated = $request->validate([
            'title' => 'required|string|max:25',
            'description' => 'required|string|max:250',
            'rating_id' => 'required|in:0+,6+,12+,16+,18+',
            'genres' => 'required|array|min:1', // Jāizvēlas vismaz viens žanrs
            'genres.*' => 'exists:genres,id' // Katram žanram jābūt derīgam
        ], [
            'title.max' => 'Nosaukums nedrīkst pārsniegt 25 rakstzīmes.',
            'description.max' => 'Apraksts nedrīkst pārsniegt 250 rakstzīmes.',
            'genres.required' => 'Jāizvēlas vismaz viens žanrs.',
            'genres.min' => 'Jāizvēlas vismaz viens žanrs.'
        ]);

        // Izveido jaunu grāmatu
        $book = UserBook::create([
            'user_id' => auth()->id(),
            'name' => $validated['title'],
            'description' => $validated['description'],
            'age_limit' => $validated['rating_id'],
        ]);

        // Pievieno žanrus grāmatai
        $book->genres()->attach($validated['genres']);

        // Pāradrese uz grāmatu sarakstu
        return redirect()->route('StoryList');
    }

    // Metode, kas parāda grāmatas rediģēšanas formu
    public function show($id)
    {
        // Atrod grāmatu pēc ID un lietotāja ID
        $book = UserBook::where('user_id', auth()->id())
            ->with('genres', 'chapters', 'block') // Iekļaujam žanrus un nodaļas
            ->findOrFail($id);

        // Atgriežam skatu ar grāmatas datiem
        return Inertia::render('Writing/EditInfo/EditStory', [
            'book' => $book,
            'genres' => Genre::all(), // Visus žanrus
        ]);
    }

    // Metode, kas atver grāmatas rediģēšanas formu
    public function edit($id)
    {
        // Atrod grāmatu pēc ID un lietotāja ID
        $book = UserBook::where('user_id', auth()->id())
            ->with('genres', 'chapters', 'block') // Iekļauj žanrus un nodaļas
            ->findOrFail($id);

        // Atgriež skatu ar grāmatas datiem un iespējām rediģēt
        return Inertia::render('Writing/EditInfo/EditStory', [
            'book' => $book,
            'genres' => Genre::all(),
            'chapters' => $book->chapters,
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
    public function update(Request $request, $id)
    {
        // Atrod grāmatu pēc ID un lietotāja ID
        $book = UserBook::where('user_id', Auth::id())
            ->findOrFail($id);

        // Valide saņemtos datus
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'description' => 'required|string|max:250',
            'age_limit' => 'required|in:0+,6+,12+,16+,18+',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'status' => 'nullable|in:Procesā,Pabeigts,Pamests',
        ], [
            'name.max' => 'Nosaukums nedrīkst pārsniegt 25 rakstzīmes.',
            'description.max' => 'Apraksts nedrīkst pārsniegt 250 rakstzīmes.',
            'genres.required' => 'Jāizvēlas vismaz viens žanrs.',
            'genres.min' => 'Jāizvēlas vismaz viens žanrs.'
        ]);

        // Atjaunina grāmatu
        $book->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'age_limit' => $validated['age_limit'],
            'status' => $validated['status'] ?? $book->status,
        ]);

        // Atjauno žanrus
        if (isset($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        }

        // Pāradreseuz grāmatu sarakstu ar ziņojumu par veiksmi
        return redirect()->route('StoryList')->with('success', 'Stāsts veiksmīgi atjaunināts!');
    }

    // Metode, kas dzēš grāmatu
    public function delete($id)
    {
        // Atrod lietotāja grāmatu pēc ID
        $book = UserBook::findOrFail($id);

        // Iegūst pašreiz autentificēto lietotāju
        $user = auth()->user();

        // Pārbauda, vai lietotājs ir superadmin vai grāmatas autors
        if ($user->role === 'superadmin' || $user->id === $book->user_id) {

            // Noņem saistītus datus
            $book->genres()->detach();
            $book->chapters()->delete();
            $book->delete();

            return redirect()->back()->with('success', 'Grāmata veiksmīgi dzēsta.');
        }

        // Ja lietotājs nav autors un nav superadmin, nepiešķir dzēšanas tiesības
        return redirect()->back()->with('error', 'Tev nav tiesību dzēst šo grāmatu.');
    }

    // Metode, kas parāda visu informāciju par stāstu
    public function showInfo($id)
    {
        // Atrod grāmatu ar visiem saistītajiem datiem
        $book = UserBook::with(['genres', 'block', 'chapters', 'user'=> function($query) {}])->findOrFail($id);

        // Tikai admin, superadmin un autors var redzēt bloķētu stāstu
        if ($book->is_blocked) {

            // Iegūst autentificēto lietotāju
            $user = auth()->user();

            if (!$user || (!in_array($user->role, ['admin', 'superadmin']) && $user->id !== $book->user_id)) {
                abort(403, 'Šis stāsts ir bloķēts');
            }
        }

        // Saņem vērtējuma datus
        $ratingsData = Rating::where('user_book_id', $id)
            ->selectRaw('AVG(grade) as average, COUNT(*) as count')
            ->first();

        // Iegūst pašreizējā lietotāja vērtējumu
        $userRating = auth()->check()
            ? Rating::where('user_book_id', $id)
                ->where('user_id', auth()->id())
                ->value('grade')
            : null;

        //Grāmatzīmju atrašana
        $userBookmark = null;
        if (auth()->check()) {
            $bookmark = Bookmark::where('user_id', auth()->id())
                ->where('user_book_id', $id)
                ->with('bookmarkType')
                ->first();

            if ($bookmark) {
                $userBookmark = [
                    'id' => $bookmark->bookmark_type_id,
                    'name' => $bookmark->bookmarkType->name
                ];
            }
        }

        // Atgriež rediģēšanas skatu ar nepieciešamajiem datiem
        return Inertia::render('Reading/UserBooks/UserBook', [
            'book' => $book,
            'genres' => $book->genres,
            'chapters' => $book->chapters,
            'user' => $book->user,
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
                'user_book_id' => $bookId
            ],
            [
                'grade' => $request->grade
            ]
        );

        // Pārrēķina vidējo vērtējumu grāmatai
        $average = Rating::where('user_book_id', $bookId)
            ->avg('grade');

        // Saskaita vērtējumu skaitu grāmatai
        $count = Rating::where('user_book_id', $bookId)
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
