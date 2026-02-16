<?php

namespace App\Http\Controllers;

use App\Mail\PasswordMessage;
use App\Mail\PendingUserVerification;
use App\Models\Bookmark;
use App\Models\ObjectReport;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Log;

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

    // Augšpielāde attēļu un saglabā to ceļu datu bāzē.
    public function uploadAvatar(Request $request)
    {
        // Validē ienākošo failu – tam jābūt attēlam, ne lielākam par 2MB
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        // Saņem pašreiz pieslēgto lietotāju
        $user = Auth::user();

        // Ģenerē unikālu faila nosaukumu
        $filename = Str::uuid() . '.' . $request->avatar->getClientOriginalExtension();

        // Saglabā failu mapē public/avatars
        $path = $request->avatar->storeAs('avatars', $filename, 'public');

        // Saglabā ceļu uz avataru datubāzē
        $user->avatar = $path;
        $user->save();

        // Atgriež atpakaļ ar veiksmes ziņojumu
        return back()->with('success', 'Attēls ir veiksmīgi augšupielādēts!');
    }

    // Atjaunīna datus
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validē ienākošos datus
        $validated = $request->validate([
            'nickname' => ['required', 'string', 'max:50',
                Rule::unique('users', 'nickname')->ignore($user->id)],
            'email'    => ['required', 'string', 'email', 'max:50',
                Rule::unique('users')->ignore($user->id), 'lowercase'],
            'bio'      => ['nullable', 'string', 'max:400'],
        ]);

        // Nosaka, kuri lauki ir mainīti
        $emailChanged    = $validated['email'] !== $user->email;
        $nicknameChanged = $validated['nickname'] !== $user->nickname;
        $bioChanged      = $validated['bio'] !== $user->bio;

        // Ja e-pasta adrese ir mainīta
        if ($emailChanged) {

            // Izveido pārbaudes tokenu
            $token = Str::random(32);

            $pending = [
                'user_id'  => $user->id,
                'nickname' => $nicknameChanged ? $validated['nickname'] : $user->nickname,
                'new_email' => $validated['email'],
                'token'    => $token,
            ];

            // Ja mainās arī segvārds
            if ($nicknameChanged) {
                $pending['nickname'] = $validated['nickname'];
            }

            // Ja mainās arī bio
            if ($bioChanged) {
                $pending['bio'] = $validated['bio'];
            }

            // Saglabā datus sesijā
            session(['pending_user' => $pending]);
            Session::save();

            // Nosūta e-pastu ar apstiprinājuma linku
            Mail::to($validated['email'])->send(new PendingUserVerification($pending));

            // Uzreiz piemēro izmaiņas, kas nav saistītas ar e-pastu
            $updates = [];

            if ($nicknameChanged) {
                $updates['nickname'] = $validated['nickname'];
            }

            if ($bioChanged) {
                $updates['bio'] = $validated['bio'];
            }

            if ($updates) {
                $user->update($updates);
            }

            // Novirza uz lapu, kas informē par apstiprināšanas nepieciešamību
            return redirect()->route('verify.change.notice')
                ->with('status', 'email-change-verification-sent');
        }

        //Ja e-pasts nav mainīts — vienkārši atjaunojam segvārdu/bio
        if ($nicknameChanged || $bioChanged) {

            $user->update($validated);

            return back()->with('profile_updated', true);
        }

        //Ja nekas nav mainīts
        return back();
    }

    // Apstiprina e-pasta nomaiņu pēc verifikācijas saites noklikšķināšanas
    public function verify(string $token)
    {
        $pending = Session::get('pending_user'); // Iegūst gaidošos datus no sesijas

        // Pārbauda, vai dati eksistē un tokens sakrīt
        if (!$pending || $pending['token'] !== $token) {
            return redirect()->route('profile.edit')
                ->with('error','Nederīga vai beigusies verifikācijas saite.');
        }

        $user = User::find($pending['user_id']); // Atrod lietotāju pēc ID
        if (!$user) {
            return redirect()->route('profile.edit')
                ->with('error','Lietotājs nav atrasts.');
        }

        // Maina lietotāja e-pastu uz jauno e-pastu
        $user->email = $pending['new_email'];
        $user->save(); // Saglabā izmaiņas datubāzē

        Session::forget('pending_user'); // Izdzēš gaidošos datus no sesijas

        // Pāradresē uz verifikācijas paziņojuma lapu ar apstiprinājumu
        return redirect()->route('verify.change.notice', ['verified'=>1])
            ->with('status','email-verified');
    }

    // Nosūta verifikācijas e-pastu atkārtoti
    public function resend()
    {
        $pending = Session::get('pending_user'); // Iegūst gaidošos datus no sesijas

        if (!$pending) {
            return redirect()->route('profile.edit')
                ->with('error', 'Nav atrasts e-pasta maiņas pieprasījums.');
        }

        // Ģenerē jaunu tokenu
        $pending['token'] = Str::random(32);
        Session::put('pending_user', $pending); // Saglabā jauno tokenu sesijā

        // Nosūta jaunu verifikācijas e-pastu uz jauno e-pasta adresi
        Mail::to($pending['new_email'])->send(new PendingUserVerification($pending));

        return back()->with('status', 'verification-link-sent');
    }

    // Atjaunina lietotāja paroli
    public function updatePassword(Request $request)
    {
        // Validē paroles datus
        $request->validate([
            'current' => ['required', 'string'],
            'new' => ['required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ],
        );

        $user = $request->user(); // Iegūst pašreizējo lietotāju

        // Pārbauda, vai ievadītā pašreizējā parole sakrīt
        if (!Hash::check($request->current, $user->password)) {
            throw ValidationException::withMessages([
                'current' => ['Nepareiza pašreizējā parole.'],
            ]);
        }

        $user->password = Hash::make($request->new); // Hash jauno paroli
        $user->save(); // Saglabā jauno paroli datubāzē

        // Nosūta e-pastu ar paziņojumu par paroles maiņu
        Mail::to($user->email)->send(new PasswordMessage($user));

        return back()->with('password_updated', true);
    }

    // Dzēš lietotāja kontu
    public function destroy(Request $request): RedirectResponse
    {
        // Parbauda vēco paroli
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user(); // Iegūst pašreizējo lietotāju

        Auth::logout(); // Izlogo lietotāju

        $user->delete(); // Dzēš lietotāja kontu no datubāzes

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/'); // Pāradresē uz sākumlapu
    }

    // Atgriež lietotāja profila skatu ar grāmatu skaitu
    public function profile()
    {
        $user = auth()->user(); // Iegūst autentificēto lietotāju
        $booksCount = $user->books()->count(); // Saskaita lietotāja grāmatas
        $RatingsCount = $user->BookRatings()->count(); // Kopējais vērtējumu skaits

        // Grāmatzīmju skaits grupēts pēc tipa
        $bookmarkCounts = Bookmark::where('user_id', $user->id)
            ->selectRaw('bookmark_type_id, count(*) as count')
            ->groupBy('bookmark_type_id')
            ->pluck('count', 'bookmark_type_id')
            ->toArray();

        // Izlasīto grāmatu skaits (grāmatzīmju tips 1)
        $readBooksCount = $bookmarkCounts[1] ?? 0; // Ja nav grāmatzīmju, tad 0

        // Atgriež profila skatu ar visiem datiem
        return Inertia::render('Profile/Profile', [
            'user' => $user, // Lietotāja dati
            'booksCount' => $booksCount, // Grāmatu skaits
            'totalRatingsCount' => $RatingsCount, // Kopējais vērtējumu skaits
            'readBooksCount' => $readBooksCount, // Izlasīto grāmatu skaits
        ]);
    }

    // Atgriež cita konkrēta lietotāja informācijas skatu
    public function Watch($id)
    {
        $user = User::findOrFail($id);
        $booksCount = $user->books()->count();
        $ratingsCount = $user->BookRatings()->count();

        // Grāmatzīmju skaits grupēts pēc tipa
        $bookmarkCounts = Bookmark::where('user_id', $user->id)
            ->selectRaw('bookmark_type_id, count(*) as count')
            ->groupBy('bookmark_type_id')
            ->pluck('count', 'bookmark_type_id')
            ->toArray();

        $readBooksCount = $bookmarkCounts[1] ?? 0; // Ja nav grāmatzīmju, tad 0

        // Atgriež skatu ar lietotāja datiem
        return Inertia::render('UserProfile', [
            'user' => $user, // Lietotāja dati
            'booksCount' => $booksCount, // Grāmatu skaits
            'totalRatingsCount' => $ratingsCount, // Kopējais vērtējumu skaits
            'readBooksCount' => $readBooksCount, // Izlasīto grāmatu skaits
        ]);
    }

    // Nodrošina sūdzības iesniegšanu
    public function reportUser(Request $request, $userId)
    {
        // Validē ienākošos datus no formas
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'problem' => 'required|string|max:500',
        ]);

        // Atrod konkrēto lietotāju pēc ID
        $user = User::findOrFail($userId);

        // Izveido jaunu sūdzību datu bāzē
        ObjectReport::create([
            'subject' => $validated['subject'],
            'problem' => $validated['problem'],
            'reporter_user_id' => auth()->id(),
            'reported_user_id' => $user->id,
        ]);

        return back()->with('success', 'Sūdzība nosūtīta.');
    }
}
