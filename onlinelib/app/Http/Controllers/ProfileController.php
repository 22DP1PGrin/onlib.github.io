<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function settings(){
        return Inertia::render('Profile/Settings');
    }
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nickname' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'nickname')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
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

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current' => ['required', 'string'],
            'new' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new.min' => 'Parolei jābūt vismaz 8 simbolu garai.',
            'new.confirmed' => 'Jaunā parole nesakrīt ar apstiprinājumu.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current, $user->password)) {
            throw ValidationException::withMessages([
                'current' => ['Nepareiza pašreizējā parole.'],
            ]);
        }

        $user->password = Hash::make($request->new);
        $user->save();

        return back()->with('success', 'Parole veiksmīgi nomainīta!');
    }

    public function profile()
    {
        $user = auth()->user();
        $booksCount = $user->books()->count();

        return Inertia::render('Profile/Profile', [
            'user' => $user,
            'booksCount' => $booksCount,
        ]);
    }
    public function showUsers()
    {
        $users = User::where('role', 'user')->get();

        return Inertia::render('Control/Users', [
            'users' => $users,
        ]);
    }
    public function delete(User $user)
    {

        $user->delete();

        return redirect()->route('users')
            ->with('success', 'Lietotājs veiksmīgi dzēsts!');
    }

}
