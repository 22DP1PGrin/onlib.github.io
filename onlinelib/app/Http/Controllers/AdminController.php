<?php

namespace App\Http\Controllers;

use App\Mail\UpdateRoleMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminController
{
    // Atgriež konkrēta lietotāja informācijas skatu (administratora funkcija)
    public function Watch($id)
    {
        $users = User::findOrFail($id); // Atrod lietotāju pēc ID

        // Atgriež skatu ar lietotāja datiem
        return Inertia::render('Control/Users/UserInfo', [
            'users' => $users,
        ]);
    }

    // Atgriež visu lietotāju sarakstu (administratora funkcija)
    public function showUsers()
    {
        $currentUser = auth()->user();

        // Visi parastie lietotāji
        $users = User::where('role', 'user')
            ->orderBy('nickname', 'asc')
            ->get();

        // Visi administratori(tikai superadminiem)
        $admins = $currentUser->role === 'superadmin'
            ? User::where('role', 'admin')->orderBy('nickname')->get()
            : collect();

        return Inertia::render('Control/Users/Users', [
            'users' => $users,
            'admins' => $admins,
            'currentUser' => $currentUser
        ]);
    }

    // Dzēš konkrētu lietotāju (administratora funkcija)
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
}
