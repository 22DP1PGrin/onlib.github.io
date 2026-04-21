<?php

namespace App\Http\Controllers;

use App\Mail\UpdateRoleMessage;
use App\Mail\UserBlocked;
use App\Mail\UserBookBlocked;
use App\Mail\UserBookUnblocked;
use App\Mail\UserDeleted;
use App\Mail\UserUnblocked;
use App\Models\ClassicBook;
use App\Models\ObjectReport;
use App\Models\StoryBlock;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

// Kontrolieris, kas apstrādā administrācijas darbības
class AdminController
{
    // Atgriež ne-bloķēto grāmatu sarakstu (ar meklēšanu)
    public function showAllList(Request $request)
    {
        $search = $request->input('search'); // Meklēšanas pieprasījums

        // Lietotāju grāmatas (ne-bloķētas)
        $book = UserBook::with('user')
            ->where('is_blocked', false)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%"); // Filtrē pēc nosaukuma
            })
            ->orderBy('name', 'asc') // Sakārto pēc nosaukuma
            ->get();

        // Klasiskās grāmatas (ne-bloķētas)
        $classicBooks = ClassicBook::where('is_blocked', false)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->get();

        // Nosūta datus uz Inertia skatu
        return Inertia::render('Control/Books/BooksList', [
            'book' => $book,
            'classicBooks' => $classicBooks,
            'filters' => [
                'search' => $search // Aktīvais filtrs
            ]
        ]);
    }

    // Atgriež bloķēto grāmatu sarakstu (ar meklēšanas)
    public function showAllBlocksList(Request $request)
    {
        $search = $request->input('search'); // Meklēšanas pieprasījums

        // Lietotāju bloķētās grāmatas
        $book = UserBook::with('user')
            ->where('is_blocked', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%"); // Filtrē pēc nosaukuma
            })
            ->orderBy('name', 'asc') // Sakārto alfabētiski
            ->get();

        // Klasiskās bloķētās grāmatas
        $classicBooks = ClassicBook::where('is_blocked', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->get();

        // Nosūta datus uz skatu
        return Inertia::render('Control/Books/BlocksBook', [
            'authUser' => auth()->user(), // Pašreizējais lietotājs
            'book' => $book,
            'classicBooks' => $classicBooks,
            'filters' => [
                'search' => $search // Aktīvais filtrs
            ]
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

    // Atgriež ne-bloķēto lietotāju un administratoru sarakstu (ar meklēšanu)
    public function showUsers(Request $request)
    {
        $currentUser = auth()->user(); // Pašreiz autentificētais lietotājs
        $search = $request->input('search'); // Meklēšanas pieprasījums

        // Parastie lietotāji
        $usersQuery = User::where('role', 'user')
            ->where('is_blocked', false);

        if ($search) {
            $usersQuery->where('nickname', 'like', "%{$search}%"); // Filtrē pēc lietotājvārda
        }
        // Sakārto pēc lietotājvārda alfabētiski
        $users = $usersQuery
            ->orderBy('nickname', 'asc')
            ->get();

        // Administratori (redzami tikai superadminam)
        $admins = collect();

        if ($currentUser->role === 'superadmin') {
            $adminsQuery = User::where('role', 'admin')
                ->where('is_blocked', false);

            if ($search) {
                $adminsQuery->where('nickname', 'like', "%{$search}%");
            }

            // Sakārto pēc lietotājvārda alfabētiski
            $admins = $adminsQuery
                ->orderBy('nickname', 'asc')
                ->get();
        }

        // Nosūta datus uz Inertia skatu
        return Inertia::render('Control/Users/Users', [
            'users' => $users,
            'admins' => $admins,
            'currentUser' => $currentUser,
            'filters' => [
                'search' => $search // Aktīvais filtrs
            ]
        ]);
    }

    // Atgriež bloķēto lietotāju un administratoru sarakstu (ar meklēšanu)
    public function showBlockUsers(Request $request)
    {
        $currentUser = auth()->user(); // Pašreiz autentificētais lietotājs
        $search = $request->input('search'); // Meklēšanas pieprasījums

        // Visi parastie lietotāji
        $usersQuery = User::where('role', 'user')
            ->where('is_blocked', true);

        if ($search) {
            $usersQuery->where('nickname', 'like', "%{$search}%"); // Filtrē pēc lietotājvārda
        }

        // Sakārto pēc lietotājvārda alfabētiski
        $users = $usersQuery
            ->orderBy('nickname', 'asc')
            ->get();

        // Administratori (redzami tikai superadminam)
        $admins = collect();

        if ($currentUser->role === 'superadmin') {
            $adminsQuery = User::where('role', 'admin')
                ->where('is_blocked', true);

            if ($search) {
                $adminsQuery->where('nickname', 'like', "%{$search}%");
            }

            // Sakārto pēc lietotājvārda alfabētiski
            $admins = $adminsQuery
                ->orderBy('nickname', 'asc')
                ->get();
        }

        // Nosūta datus uz Inertia skatu
        return Inertia::render('Control/Users/BlockUsers', [
            'users' => $users,
            'admins' => $admins,
            'currentUser' => $currentUser,
            'filters' => [
                'search' => $search // Aktīvais filtrs
            ]
        ]);
    }

    // Dzēš konkrētu lietotāju
    public function delete(User $user)
    {
        // Iegūst pašreiz autentificēto lietotāju
        $currentUser = Auth::user();

        // Pārbauda, vai pašreizējais lietotājs ir superadmins
        if ($currentUser->role !== 'superadmin') {
            abort(403, 'Jums nav atļaujas dzēst lietotājus.');
        }

        // Nosūta e-pastu dzēstā lietotāja informēšanai
        Mail::to($user->email)->send(new UserDeleted($user));

        // Dzēš lietotāju no datubāzes
        $user->delete();

        return redirect()->back()->with('success', 'Lietotājs veiksmīgi dzēsts.');
    }

    // Atjaunīna lietotāju lomu
    public function updateRole(Request $request, User $user)
    {
        // Iegūst izvelēto autentificēto lietotāju
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
                'problem' => 'required|string',
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

    // Atgriež visu sūdzības sarakstu(ar meklēšanu)
    public function indexReports(Request $request)
    {
        $search = $request->input('search'); // Meklēšanas pieprasījums

        // Saņem sūdzības par lietotāju stāstiem
        $storyReports = ObjectReport::with(['userBook', 'reporter'])
            ->whereNotNull('user_book_id')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('userBook', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        // Saņem sūdzības par klasiskajām grāmatām
        $bookReports = ObjectReport::with(['classicBook', 'reporter'])
            ->whereNotNull('classic_book_id')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('classicBook', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        // Saņem sūdzības par lietotājiem
        $userReports = ObjectReport::with(['reportedUser', 'reporter',])
            ->whereNotNull('reported_user_id')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('reportedUser', function ($q) use ($search) {
                    $q->where('nickname', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        // Saņem sūdzības par komentāriem
        $commentReports = ObjectReport::with(['comment.user', 'reporter', 'classicBook', 'userBook'])
            ->whereNotNull('comment_id')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('comment.user', function ($q) use ($search) {
                    $q->where('nickname',    'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();


        // Nosūta datus uz Inertia skatu
        return Inertia::render('Control/Reports/Reports', [
            'storyReports' => $storyReports,
            'bookReports' => $bookReports,
            'userReports' => $userReports,
            'commentReports' => $commentReports,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    // Dzēš konkrētu sūdzību
    public function deleteReport(ObjectReport $report)
    {
        // Dzēš konkrēto sūdzību no datubāzes
        $report->delete();

        return back()->with('success', 'Sūdzība veiksmīgi dzēsta.');
    }
}
