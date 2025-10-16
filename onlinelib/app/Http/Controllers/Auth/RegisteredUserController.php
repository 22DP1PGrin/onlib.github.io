<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PendingUserVerification;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{

    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'bio' => 'nullable|string|max:150',
        ]);

        $token = Str::random(32);

        session([
            'pending_user' => [
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'bio' => $request->bio,
                'token' => $token,
            ]
        ]);

        Mail::to($request->email)->send(new PendingUserVerification(session('pending_user')));

        return redirect()->route('verification.notice')
            ->with('status', 'verification-link-sent');
    }

    public function verify(string $token): RedirectResponse
    {
        $pending = Session::get('pending_user');

        if (!$pending || $pending['token'] !== $token) {
            return redirect()->route('register')->with('error', 'Nederīga verifikācijas saite.');
        }

        $user = User::create([
            'nickname' => $pending['nickname'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'bio' => $pending['bio'],
        ]);

        Session::forget('pending_user');
        Mail::to($user->email)->send(new Welcome($user));
        return redirect()->route('verification.notice', ['verified' => 1]);
    }

    public function resend()
    {
        $pending = Session::get('pending_user');

        if (!$pending) {
            return redirect()->route('register')
                ->with('error', 'Nav atrasts lietotājs.');
        }

        $pending['token'] = Str::random(32);
        session(['pending_user' => $pending]);

        Mail::to($pending['email'])->send(new PendingUserVerification($pending));

        return back()->with('status', 'verification-link-sent');
    }
}
