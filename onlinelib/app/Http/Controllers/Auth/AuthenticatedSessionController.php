<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

// Kontrolieris, kas apstrādā lietotāja autentifikāciju
class AuthenticatedSessionController extends Controller
{
    // Attēlo pieteikšanās lapu
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    // Apstrādā pieteikšanās pieprasījumu
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // Veic lietotāja autentifikāciju

        $request->session()->regenerate(); // Atjauno sesiju drošības nolūkos

        // Novirza uz sākotnēji pieprasīto lapu
        return redirect()->intended(route('Home', absolute: false));
    }

    // Apstrādā izrakstīšanos
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout(); // Izraksta lietotāju

        $request->session()->invalidate(); // Dzēš sesijas datus

        $request->session()->regenerateToken(); // Atjauno CSRF tokenu

        return redirect('/'); // Novirza uz sākumlapu
    }
}
