<?php

namespace App\Http\Controllers;

use App\Models\BookChapter;
use App\Models\ClassicBook;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChapterController extends Controller
{

    // Saglabā jaunu klasisko nodaļu
    public function storeClassic(Request $request, ClassicBook $book)
    {
        // Validē ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'content' => 'required|string',
        ]);

        // Nosaka nodaļas secības numuru
        $order = BookChapter::where('classic_book_id', $book->id)->count() + 1;

        // Izveido jaunu nodaļu datubāzē
        $chapter = BookChapter::create([
            'classic_book_id' => $book->id,
            'name' => $validated['name'],
            'content' => $validated['content'],
            'order' => $order,
        ]);

        // Atgriež JSON atbildi ar veiksmīgu izveidi
        return response()->json([
            'success' => true,
            'chapter' => $chapter,
        ]);
    }

    // Saglabā jaunu lietotāja nodaļu
    public function storeUser(Request $request, UserBook $book)
    {
        // Validē ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'content' => 'required|string',
        ]);

        // Nosaka nodaļas secības numuru
        $order = BookChapter::where('user_book_id', $book->id)->count() + 1;

        // Izveido jaunu nodaļu datubāzē
        $chapter = BookChapter::create([
            'user_book_id' => $book->id,
            'name' => $validated['name'],
            'content' => $validated['content'],
            'order' => $order,
        ]);

        // Atgriež JSON atbildi ar veiksmīgu izveidi
        return response()->json([
            'success' => true,
            'chapter' => $chapter,
        ]);
    }

    // Atver jaunas klasiskās nodaļas izveides formu
    public function createClassic(ClassicBook $book)
    {
        return Inertia::render('Writing/NewInfo/NewChapter', [
            'bookId' => $book->id,
            'type' => 'classic',
        ]);
    }

    // Atver jaunas lietotāja nodaļas izveides formu
    public function createUser(UserBook $book)
    {
        return Inertia::render('Writing/NewInfo/NewChapter', [
            'bookId' => $book->id,
            'type' => 'user',
        ]);
    }

    // Rediģēt klasisko nodaļu
    public function editClassic(BookChapter $chapter)
    {
        return Inertia::render('Writing/EditInfo/EditChapter', [
            'chapter' => $chapter,               // Pašreizējās nodaļas dati
            'bookId' => $chapter->classic_book_id, // Grāmatas ID
            'type' => 'classic',                 // Nodaļas tips
        ]);
    }

    // Rediģēt lietotāja nodaļu
    public function editUser(BookChapter $chapter)
    {
        return Inertia::render('Writing/EditInfo/EditChapter', [
            'chapter' => $chapter,             // Pašreizējās nodaļas dati
            'bookId' => $chapter->user_book_id, // Grāmatas ID
            'type' => 'user',                   // Nodaļas tips
        ]);
    }

    // Atjaunina klasisko nodaļu datubāzē
    public function updateClassic(Request $request, BookChapter $chapter)
    {
        // Validē ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'content' => 'required|string',
        ]);

        // Atjaunina nodaļu ar jaunajiem datiem
        $chapter->update($validated);

        // Atgriež JSON ar veiksmīgu atjaunināšanu
        return response()->json([
            'success' => true,
            'bookId' => $chapter->classic_book_id,
        ]);
    }

    // Atjaunina lietotāja nodaļu datubāzē
    public function updateUser(Request $request, BookChapter $chapter)
    {
        // Validē ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'content' => 'required|string',
        ]);

        // Atjaunina nodaļu ar jaunajiem datiem
        $chapter->update($validated);

        // Atgriež JSON ar veiksmīgu atjaunināšanu
        return response()->json([
            'success' => true,
            'bookId' => $chapter->user_book_id,
        ]);
    }

    // Dzēš klasisko nodaļu
    public function destroyClassic(BookChapter $chapter)
    {
        $chapter->delete();
    }

    // Dzēš lietotāja nodaļu
    public function destroyUser(BookChapter $chapter)
    {
        $chapter->delete();
    }

    // Parāda nodaļas saturu
    public function showContent(int $bookId, int $chapterId)
    {
        // Atrod nodaļu grāmatā
        $chapter = BookChapter::where('classic_book_id', $bookId)->find($chapterId);

        $type = 'classic';

        // Ja nodaļa ir atrasta klasiskajā grāmatā
        if ($chapter) {

            // Iegūst klasisko grāmatu pēc ID
            $book = ClassicBook::findOrFail($bookId);

            // Tikai admin un superadmin var redzēt bloķētu klasisko grāmatu
            if ($book->is_blocked) {

                // Iegūst autentificēto lietotāju
                $user = auth()->user();

                // Ja lietotājs nav admin/superadmin mēt kļudu
                if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                    abort(403, 'Grāmata ir bloķēta');
                }
            }

        } else {

            // Atrod nodaļu stāstā
            $chapter = BookChapter::where('user_book_id', $bookId)->findOrFail($chapterId);

            $type = 'user';

            // Iegūst lietotāja grāmatu
            $book = UserBook::findOrFail($bookId);

            // Tikai admin, superadmin un autors var redzēt bloķētu stāstu
            if ($book->is_blocked) {

                // Iegūst autentificēto lietotāju
                $user = auth()->user();

                if (!$user || (!in_array($user->role, ['admin', 'superadmin']) && $user->id !== $book->user_id)) {
                    abort(403, 'Stāsts ir bloķēts');
                }
            }
        }

        // Atgriež Inertia skatu ar nodaļas saturu
        return Inertia::render('Reading/ChapterContent', [
            'chapter' => $chapter,
            'bookChapters' => BookChapter::where(
                $type === 'classic' ? 'classic_book_id' : 'user_book_id',
                $bookId
            )
                ->orderBy('order')
                ->get(),
            'bookId' => $bookId,
            'type' => $type,
        ]);
    }
}
