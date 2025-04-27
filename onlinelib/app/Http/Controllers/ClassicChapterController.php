<?php

namespace App\Http\Controllers;

use App\Models\ClassicBook;
use App\Models\ClassicBookChapter;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ClassicChapterController extends Controller
{
    // Metode, kas saglabā jaunu nodaļu
    public function store(Request $request)
    {
        // Validējam ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'content' => 'required|string',
            'book_id' => 'required|exists:classic_book,id',
        ], [
            'name.max' => 'Nosaukums nedrīkst pārsniegt 25 rakstzīmes.', // Paziņojums, ja nosaukums ir pārāk garš
        ]);


        // Meklējam grāmatu, kas pieder lietotājam
        $Classicbook = ClassicBook::find($validated['book_id']);

        // Ja grāmata netiek atrasta, atgriežam kļūdas atbildi
        if (!$Classicbook) {
            return response()->json(['error' => 'Grāmata nav atrasta!'], 422);
        }

        // Izveidojam jaunu nodaļu
        $chapter = ClassicBookChapter::create([
            'book_id' => $Classicbook->id,  // Saistām nodaļu ar grāmatu
            'name' => $validated['name'],
            'content' => $validated['content'],
            'order' => ClassicBookChapter::where('book_id', $Classicbook->id)->count() + 1,
        ]);

        // Atgriežam veiksmīgu atbildi ar izveidotās nodaļas datiem
        return response()->json(['success' => true, 'chapter' => $chapter]);
    }

    // Metode, kas atver jaunas nodaļas izveides formu
    public function create(ClassicBook $book)
    {
        return Inertia::render('Control/NewClassicChapter', [
            'bookId' => $book->id
        ]);
    }

    // Metode, kas dzēš nodaļu
    public function destroy($id)
    {
        // Meklējam nodaļu pēc ID
        $chapter = ClassicBookChapter::findOrFail($id);

        // Dzēšam nodaļu
        $chapter->delete();

        // Atgriežam lietotāju atpakaļ ar ziņojumu
        return back()->with('message', 'Nodaļa veiksmīgi dzēsta!');
    }

    // Metode, kas atver nodaļas rediģēšanas formu
    public function edit(ClassicBookChapter $chapter)
    {
        return Inertia::render('Control/EditClassicChapter', [
            'chapter' => $chapter,  // Nododam nodaļas datus uz priekšējo pusi
        ]);
    }

    // Metode, kas atjaunina nodaļas datus
    public function update(Request $request, ClassicBookChapter $chapter)
    {
        // Validējam ievadītos datus
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'content' => 'required|string',
        ]);

        // Atjauninām nodaļas datus
        $chapter->update($request->only('name', 'content'));

        // Iegūstam grāmatu, pie kuras pieder nodaļa
        $book = $chapter->classic_books;

        // Pāradresējam uz grāmatas rediģēšanas lapu ar veiksmīgu ziņu
        return redirect()->route('EditClassicBook', $book->id)
            ->with('success', 'Nodaļa veiksmīgi atjaunināta!');
    }

    // Rāda konkrētas nodaļas saturu
    public function showContent($bookId, $chapterId)
    {
        // Atrodam nodaļu, pārliecinoties, ka tā pieder norādītajai grāmatai
        $chapter = ClassicBookChapter::where('book_id', $bookId)
            ->findOrFail($chapterId);

        // Atgriežam Inertia skatu ar nepieciešamajiem datiem
        return Inertia::render('Reading/ClassicContent', [
            'chapter' => $chapter,
            'bookChapters' => $chapter->classic_book->chapters,
            'bookId' => $bookId
        ]);
    }
}

