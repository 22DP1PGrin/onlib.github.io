<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PendingUserVerification;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    // Parāda reģistrācijas lapu
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    // Saglabā pagaidu lietotāja datus un nosūta verifikācijas e-pastu
    public function store(Request $request)
    {
        // Validē ievadītos datus
        $request->validate([
            'nickname' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => ['required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'bio' => 'nullable|string|max:150',
        ]);

        // Izveido unikālu tokenu e-pasta verifikācijai
        $token = Str::random(32);

        // Saglabā pagaidu lietotāja informāciju sesijā
        session([
            'pending_user' => [
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'bio' => $request->bio,
                'token' => $token,
            ]
        ]);

        // Nosūta e-pastu ar verifikācijas saiti
        Mail::to($request->email)->send(new PendingUserVerification(session('pending_user')));

        // Pāradresē uz lapu ar paziņojumu, ka saite nosūtīta
        return redirect()->route('verification.notice')
            ->with('status', 'verification-link-sent');
    }

    // Apstiprina lietotāja reģistrāciju pēc verifikācijas saites noklikšķināšanas
    public function verify(string $token): RedirectResponse
    {
        // Iegūst pagaidu lietotāja datus no sesijas
        $pending = Session::get('pending_user');

        // Pārbauda, vai tokens sakrīt un vai dati pastāv
        if (!$pending || $pending['token'] !== $token) {
            return redirect()->route('register')->with('error', 'Nederīga verifikācijas saite.');
        }

        // Izveido jaunu lietotāju datubāzē
        $user = User::create([
            'nickname' => $pending['nickname'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'bio' => $pending['bio'],
        ]);

        // Notīra sesijas datus un nosūta sveiciena e-pastu
        Session::forget(['pending_user', 'from_registration']);
        Mail::to($user->email)->send(new Welcome($user));

        // Pāradresē uz lapu ar apstiprinājuma paziņojumu
        return redirect()->route('verification.notice', ['verified' => 1]);
    }

    // Nosūta verifikācijas e-pastu atkārtoti
    public function resend()
    {
        // Iegūst pagaidu lietotāju no sesijas
        $pending = Session::get('pending_user');

        // Ja nav saglabāta informācija, atgriež kļūdas paziņojumu
        if (!$pending) {
            return redirect()->route('register')
                ->with('error', 'Nav atrasts lietotājs.');
        }

        // Izveido jaunu tokenu un saglabā sesijā
        $pending['token'] = Str::random(32);
        session(['pending_user' => $pending]);

        // Nosūta jaunu verifikācijas e-pastu
        Mail::to($pending['email'])->send(new PendingUserVerification($pending));

        // Atgriež paziņojumu, ka e-pasts nosūtīts
        return back()->with('status', 'verification-link-sent');
    }
}
