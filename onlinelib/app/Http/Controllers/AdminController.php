<?php

namespace App\Http\Controllers;

use App\Mail\UpdateRoleMessage;
use App\Mail\UserBlocked;
use App\Mail\UserBookBlocked;
use App\Mail\UserBookUnblocked;
use App\Mail\UserUnblocked;
use App\Models\ClassicBook;
use App\Models\StoryBlock;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminController
{
    // Atgriež visu ne-bloķēto lietotāju un klasisko grāmatu sarakstu
    public function showAllList()
    {
        $book = UserBook::with('user')
            ->Where('is_blocked', false)
            ->orderBy('name', 'asc')
            ->get();
        $classicBooks = ClassicBook::orderBy('name', 'asc')
            ->Where('is_blocked', false)
            ->get();

        // Atgriež Inertia skatu ar grāmatu datiem
        return Inertia::render('Control/Books/BooksList', [
            'book' => $book,
            'classicBooks' => $classicBooks,
        ]);
    }

    // Atgriež visu bloķēto lietotāju un klasisko grāmatu sarakstu, sakārtotu pēc nosaukuma
    public function showAllBlocksList()
    {
        $book = UserBook::with('user')
            ->Where('is_blocked', true)
            ->orderBy('name', 'asc')
            ->get();
        $classicBooks = ClassicBook::orderBy('name', 'asc')
            ->Where('is_blocked', true)
            ->get();

        // Atgriež Inertia skatu ar grāmatu datiem
        return Inertia::render('Control/Books/BlocksBook', [
            'book' => $book,
            'classicBooks' => $classicBooks,
            'authUser' => auth()->user(),
        ]);
    }

    // Atgriež konkrēta lietotāja informācijas skatu
    public function Watch($id)
    {
        $users = User::findOrFail($id); // Atrod lietotāju pēc ID

        // Atgriež skatu ar lietotāja datiem
        return Inertia::render('Control/Users/UserInfo', [
            'users' => $users,
        ]);
    }

    // Atgriež visu lietotāju sarakstu
    public function showUsers()
    {
        $currentUser = auth()->user();

        // Visi parastie lietotāji
        $users = User::where('role', 'user')
            ->where('is_blocked', false)
            ->orderBy('nickname', 'asc')
            ->get();

        // Visi administratori(tikai superadminiem)
        $admins = $currentUser->role === 'superadmin'
            ? User::where('role', 'admin')
                ->where('is_blocked', false)
                ->orderBy('nickname')->get()
            : collect();

        return Inertia::render('Control/Users/Users', [
            'users' => $users,
            'admins' => $admins,
            'currentUser' => $currentUser
        ]);
    }

    // Atgriež visu bloķētu lietotāju sarakstu
    public function showBlockUsers()
    {
        $currentUser = auth()->user();

        // Visi parastie lietotāji
        $users = User::where('role', 'user')
            ->where('is_blocked', true)
            ->orderBy('nickname', 'asc')
            ->get();

        // Visi administratori(tikai superadminiem)
        $admins = $currentUser->role === 'superadmin'
            ? User::where('role', 'admin')
                ->where('is_blocked', true)
                ->orderBy('nickname')->get()
            : collect();

        return Inertia::render('Control/Users/BlockUsers', [
            'users' => $users,
            'admins' => $admins,
            'currentUser' => $currentUser
        ]);
    }

    // Dzēš konkrētu lietotāju
    public function delete(User $user)
    {
        $user->delete(); // Dzēš lietotāju no datubāzes

        return redirect()->route('users')->with('success', 'Lietotājs veiksmīgi dzēsts!');
    }

    // Atjaunīna lietotāju lomu
    public function updateRole(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Tikai superadmins drīkst mainīt lietotāja lomu
        if ($currentUser->role !== 'superadmin') {
            abort(403, 'Netiek atļauts mainīt lomu.');
        }

        // Nevar piešķirt superadmin lomu citam lietotājam
        if ($request->role === 'superadmin') {
            abort(403, 'Nav atļauts piešķirt superadmin lomu.');
        }

        // Veic iesniegtās lomas validāciju
        $request->validate([
            'role' => 'required|in:admin,user', // pieļauj tikai 'admin' vai 'user'
        ]);

        // Atjaunina lietotāja lomu datu bāzē
        $user->role = $request->role;
        $user->save();

        Mail::to($user->email)->send(new UpdateRoleMessage($user));

        // Pāradresē atpakaļ
        return redirect()->back()->with('success', 'Loma veiksmīgi atjaunināta.');
    }

    // Pārslēdz klasiskās grāmatas bloķēšanas statusu
    public function toggleClassic(ClassicBook $book)
    {
        // Maina grāmatas bloķēšanas statusu uz pretējo
        $book->is_blocked = !$book->is_blocked;
        $book->save();

        return redirect()->back();
    }

    // Pārslēdz lietotāja grāmatas bloķēšanas statusu
    public function toggleUser(Request $request, UserBook $userBook)
    {
        $user = $userBook->user;

        // Pārbauda, vai grāmata jau ir bloķēta
        if ($userBook->is_blocked) {

            // Atbloķē grāmatu un dzēš saistītos bloķēšanas ierakstus
            StoryBlock::where('user_book_id', $userBook->id)->delete();
            $userBook->is_blocked = false;

            // Nosūta e-pastu lietotājam, informējot, ka grāmata ir atbloķēta
            Mail::to($user->email)->send(new UserBookUnblocked($userBook->name, $user->nickname));
        } else {
            // Bloķē grāmatu un validē saņemto tēmu un pamatojumu
            $validated = $request->validate([
                'subject' => 'required|string',
                'problem' => 'required|string|max:500',
            ]);

           // Izveido jaunu bloķēšanas ierakstu datubāzē
            StoryBlock::create([
                'user_book_id' => $userBook->id,
                'subject' => $validated['subject'],
                'problem' => $validated['problem'],
            ]);

            $userBook->is_blocked = true;

            // Nosūta e-pastu lietotājam ar bloķēšanas informāciju
            Mail::to($user->email)->send(new UserBookBlocked($userBook->name, $user->nickname, $validated['subject'], $validated['problem']));
        }

        $userBook->save();

        return redirect()->back();
    }

    // Pārslēdz lietotāja konta bloķēšanas statusu
    public function toggleUserBlock(Request $request, User $user)
    {
        // Pārbauda, vai lietotājs jau ir bloķēts
        if ($user->is_blocked) {

            // Atbloķē lietotāju un noņem bloķēšanas termiņu
            $user->update([
                'is_blocked' => false,
                'blocked_until' => null,
            ]);

            // Nosūta e-pastu lietotājam par atbloķēšanu
            Mail::to((string) $user->email)->send(new UserUnblocked($user->nickname));

            return back()->with('success', 'Lietotājs atbloķēts');
        }

        // Validē saņemto bloķēšanas tēmu, problēmu un ilgumu
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'problem' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:1'
        ]);

        $blockedUntil = null;

        // Ja norādīts ilgums, aprēķina bloķēšanas beigu datumu
        if (!empty($validated['duration'])) {
            $blockedUntil = now()->addWeeks($validated['duration']);
        }

        // Atjauno lietotāja statusu — bloķē un iestata bloķēšanas termiņu
        $user->update([
            'is_blocked' => true,
            'blocked_until' => $blockedUntil,
        ]);

        Mail::to($user->email)->send(new UserBlocked($user->nickname, $validated['subject'], $validated['problem'], $blockedUntil));

        return back()->with('success', 'Lietotājs bloķēts');
    }
}
