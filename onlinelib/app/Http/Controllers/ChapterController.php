<?php

namespace App\Http\Controllers;

use App\Models\UserBookChapter;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChapterController extends Controller
{
    // Metode, kas saglabā jaunu nodaļu
    public function store(Request $request)
    {
        // Validējam ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'content' => 'required|string',
            'book_id' => 'required|exists:user_books,id',
        ], [
            'name.max' => 'Nosaukums nedrīkst pārsniegt 25 rakstzīmes.', // Paziņojums, ja nosaukums ir pārāk garš
        ]);

        // Iegūstam pašreizējo lietotāju
        $user = Auth::user();

        // Meklējam grāmatu, kas pieder lietotājam
        $book = UserBook::where('id', $validated['book_id'])
            ->where('user_id', $user->id)
            ->first();

        // Ja grāmata netiek atrasta, atgriežam kļūdas atbildi
        if (!$book) {
            return response()->json(['error' => 'Grāmata nav atrasta!'], 422);
        }

        // Izveidojam jaunu nodaļu
        $chapter = UserBookChapter::create([
            'book_id' => $book->id,  // Saistām nodaļu ar grāmatu
            'name' => $validated['name'],
            'content' => $validated['content'],
            'order' => UserBookChapter::where('book_id', $book->id)->count() + 1,
        ]);

        // Atgriežam veiksmīgu atbildi ar izveidotās nodaļas datiem
        return response()->json(['success' => true, 'chapter' => $chapter]);
    }

    // Metode, kas atver jaunas nodaļas izveides formu
    public function create(UserBook $book)
    {
        return Inertia::render('Writing/NewInfo/NewChapter', [
            'bookId' => $book->id
        ]);
    }

    // Metode, kas dzēš nodaļu
    public function destroy($id)
    {
        // Meklējam nodaļu pēc ID
        $chapter = UserBookChapter::findOrFail($id);

        // Ja nodaļa nepieder pašreizējam lietotājam, atsakām piekļuvi
        if ($chapter->book->user_id !== auth()->id()) {
            abort(403, 'Jums nav piekļuves šai nodaļai.');
        }

        // Dzēšam nodaļu
        $chapter->delete();

        // Atgriežam lietotāju atpakaļ ar ziņojumu
        return back()->with('message', 'Nodaļa veiksmīgi dzēsta!');
    }

    // Metode, kas atver nodaļas rediģēšanas formu
    public function edit(UserBookChapter $chapter)
    {
        return Inertia::render('Writing/EditInfo/EditChapter', [
            'chapter' => $chapter,  // Nododam nodaļas datus uz priekšējo pusi
        ]);
    }

    // Metode, kas atjaunina nodaļas datus
    public function update(Request $request, UserBookChapter $chapter)
    {
        // Validējam ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'content' => 'required|string',
        ]);

        // Atjauninām nodaļas datus
        $chapter->update($request->only('name', 'content'));

        // Iegūstam grāmatu, pie kuras pieder nodaļa
        $book = $chapter->book;

        // Pāradresējam uz grāmatas rediģēšanas lapu ar veiksmīgu ziņu
        return redirect()->route('EditStory', $book->id)
            ->with('success', 'Nodaļa veiksmīgi atjaunināta!');
    }

    // Rāda konkrētas nodaļas saturu
    public function showContent($bookId, $chapterId)
    {
        // Atrodam nodaļu, pārliecinoties, ka tā pieder norādītajai grāmatai
        $chapter = UserBookChapter::where('book_id', $bookId)
            ->findOrFail($chapterId);

        // Atgriežam Inertia skatu ar nepieciešamajiem datiem
        return Inertia::render('Reading/UserBooks/UserContent', [
            'chapter' => $chapter,
            'bookChapters' => $chapter->book->chapters,
            'bookId' => $bookId
        ]);
    }
}
