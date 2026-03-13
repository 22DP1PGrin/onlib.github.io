<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassicBook;
use App\Models\Comment;
use App\Models\ObjectReport;
use App\Models\UserBook;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Izveido komentāru klasiskajai grāmatai
    public function CreateCommentForClassic(Request $request, ClassicBook $classicBook)
    {
        // Tiek validēti ievadītie dati
        $validated = $request->validate([
            'content' => 'required|string',
            'comment_parent_id' => 'nullable|exists:comments,id'
        ]);

        // Tiek izveidots jauns komentārs datubāzē
        Comment::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'classic_book_id' => $classicBook->id,
            'comment_parent_id' => $validated['comment_parent_id'] ?? null,
        ]);

        return back()->with('success', 'Komentārs nosūtīts');
    }

    // Izveido komentāru lietotāja stāstam
    public function CreateCommentForUser(Request $request, UserBook $userBook)
    {
        // Tiek validēti ievadītie dati
        $validated = $request->validate([
            'content' => 'required|string',
            'comment_parent_id' => 'nullable|exists:comments,id'
        ]);

        // Tiek izveidots jauns komentārs datubāzē
        Comment::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'user_book_id' => $userBook->id,
            'comment_parent_id' => $validated['comment_parent_id'] ?? null,
        ]);

        return back()->with('success', 'Komentārs nosūtīts');
    }

    // Atjaunina esošo komentāru
    public function update(Request $request, Comment $comment)
    {
        $user = auth()->user();

        // Tiek pārbaudītas lietotāja tiesības rediģēt komentāru
        if (
            $user->id !== $comment->user_id &&
            !in_array($user->role, ['admin', 'superadmin'])
        ) {
            abort(403, 'Nav atļauts rediģēt šo komentāru');
        }

        // Tiek validēts jaunais komentāra saturs
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        // Komentārs tiek atjaunināts
        $comment->update([
            'content' => $validated['content']
        ]);

        return response()->json([
            'message' => 'Komentārs atjaunināts',
            'comment' => $comment
        ]);
    }

    // Dzēš komentāru
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();

        // Tiek pārbaudītas lietotāja tiesības dzēst komentāru
        if (
            $user->id !== $comment->user_id &&
            !in_array($user->role, ['admin', 'superadmin'])
        ) {
            abort(403, 'Nav atļauts dzēst šo komentāru');
        }

        // Komentārs tiek dzēsts no datubāzes
        $comment->delete();

        return redirect()->back()->with('success', 'Komentārs dzēsts');
    }

    // Nodrošina sūdzības iesniegšanu
    public function reportComment(Request $request, $commentId)
    {
        // Validē ienākošos datus no formas
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'problem' => 'required|string|max:500',
        ]);

        // Atrod konkrēto stāstu pēc ID
        $comment = Comment::findOrFail($commentId);

        // Izveido jaunu sūdzību datu bāzē
        ObjectReport::create([
            'subject' => $validated['subject'],
            'problem' => $validated['problem'],
            'reporter_user_id' => auth()->id(),
            'comment_id' => $comment->id,
        ]);

        return back()->with('success', 'Sūdzība nosūtīta.');
    }
}
