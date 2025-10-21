<?php

namespace App\Http\Controllers;

use App\Mail\PasswordMessage;
use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{


    // Atgriež lietotāja iestatījumu skatu
    public function settings(){
        return Inertia::render('Profile/Settings');
    }

    // Atgriež lietotāja profila rediģēšanas skatu
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    // Atjaunina lietotāja profila informāciju
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nickname' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nickname')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users')->ignore($user->id),
                'lowercase'
            ],
            'bio' => ['nullable', 'string', 'max:150'],
        ]);

        try {
            $user->update($validated);

            return back()->with('success', 'Profils veiksmīgi atjaunināts!');
        } catch (\Exception $e) {
            return back()->with('error', 'Kļūda atjauninot profilu: ' . $e->getMessage());
        }
    }

    // Dzēš lietotāja kontu
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Atjaunina lietotāja paroli
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current' => ['required', 'string'],
            'new' => ['required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ], [
        ]);

        $user = $request->user();

        if (!Hash::check($request->current, $user->password)) {
            throw ValidationException::withMessages([
                'current' => ['Nepareiza pašreizējā parole.'],
            ]);
        }

        $user->password = Hash::make($request->new);
        $user->save();

        Mail::to($user->email)->send(new PasswordMessage($user));

        return back()->with('success', 'Parole veiksmīgi nomainīta!');
        // Nosūtām e-pastu ar paziņojumu, ka parole mainīta


    }

    // Atgriež lietotāja profila skatu ar grāmatu skaitu
    public function profile()
    {
        $user = auth()->user();
        $booksCount = $user->books()->count();
        $classicRatingsCount = $user->classicBookRatings()->count();
        $userWorkRatingsCount = $user->bookRatings()->count();
        $totalRatingsCount = $classicRatingsCount + $userWorkRatingsCount;

        // grāmatu skaits (ar grāmatzīmes veidu)
        $bookmarkCounts = Bookmark::where('user_id', $user->id)
            ->selectRaw('bookmark_type_id, count(*) as count')
            ->groupBy('bookmark_type_id')
            ->pluck('count', 'bookmark_type_id')
            ->toArray();

        // Izlasīto grāmatu skaits (ar grāmatzīmes veidu "Izlasīts")
        $readBooksCount = $bookmarkCounts[1] ?? 0;


        return Inertia::render('Profile/Profile', [
            'user' => $user,
            'booksCount' => $booksCount,
            'classicRatingsCount' => $classicRatingsCount,
            'userWorkRatingsCount' => $userWorkRatingsCount,
            'totalRatingsCount' => $totalRatingsCount,
            'readBooksCount' => $readBooksCount,
        ]);
    }

    // Atgriež visu parasto lietotāju sarakstu (administratora funkcija)
    public function showUsers()
    {
        $users = User::where('role', 'user')
            ->orderBy('nickname', 'asc')
            ->get();

        return Inertia::render('Control/Users/Users', [
            'users' => $users,
        ]);
    }

    // Dzēš konkrētu lietotāju (administratora funkcija)
    public function delete(User $user)
    {

        $user->delete();

        return redirect()->route('users')
            ->with('success', 'Lietotājs veiksmīgi dzēsts!');
    }

    public function Watch($id)
    {

        $users = User::findOrFail($id);

        // Atgriežam skatu ar grāmatas datiem un iespējām rediģēt
        return Inertia::render('Control/Users/UserInfo', [
            'users' => $users,
        ]);
    }

}
