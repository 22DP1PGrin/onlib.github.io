<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nickname' => 'required|string|max:50|unique:'.User::class,
            'email' => 'required|string|lowercase|email|max:50|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'bio' => 'nullable|string|max:150',
        ], [
            'password.confirmed' => 'Parolēmj ir jāsakrīt!',
        ]);

        User::create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio'=>$request->bio
        ]);

//        event(new Registered($user));



        return redirect(route('Home', absolute: false));
    }
}
