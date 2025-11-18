<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordMessage;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class PasswordResetController extends Controller
{
    // Funkcija, kas nosūta atiestatīšanas kodu uz lietotāja e-pastu
    public function sendResetCode(Request $request)
    {
        // Validē e-pasta formātu
        $request->validate(['email' => 'required|email']);

        // Atrodam lietotāju pēc e-pasta
        $user = User::where('email', $request->email)->first();

        // Ģenerējam 6 ciparu kodu
        if ($user) {
            $code = rand(100000, 999999);

            // Saglabājam kodu kešā uz 10 minūtēm
            Cache::put('password_reset_code_' . $user->email, $code, now()->addMinutes(10));

            // Nosūtām e-pastu ar atiestatīšanas kodu
            Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user));

            // Saglabājam lietotāja e-pastu sesijā, lai vēlāk pārbaudītu kodu
            session([
                'reset_email' => $user->email,
            ]);

            session()->save();
        }

        // Pāradresējam uz lapu, kur lietotājs ievadīs kodu
        return Inertia::render('Auth/ResetPassword', [
            'verified' => $request->get('verified', false)
        ]);
    }

    // Funkcija, kas pārbauda ievadīto atiestatīšanas kodu
    public function checkCode(Request $request)
    {
        // Validē ievadīto kodu
        $request->validate([
            'code'  => 'required|digits:6',
        ],
        [
            'code.digits' => 'Paroles atiestatīšanas kodam jābūt 6 ciparu garumā.',
        ]);

        // Iegūstam saglabāto kodu kešā pēc sesijas e-pasta
        $cachedCode = Cache::get('password_reset_code_' . session('reset_email'));

        // Ja koda nav vai tas nesakrīt ar ievadīto — atgriež kļūdu
        if (!$cachedCode || $cachedCode != $request->code) {
            return back()->withErrors(['code' => 'Nederīgs vai beidzies kods.']);
        }

        // Ja kods pareizs, pāradresē uz to pašu lapu ar verified=true
        return redirect()->route('password.showReset', ['verified' => true]);
    }

    // Funkcija, kas atiestata lietotāja paroli
    public function resetPassword(Request $request)
    {
        // Iegūstam e-pastu no sesijas
        $email = session('reset_email');

        // Ja sesijā nav e-pasta — saite nav derīga
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Atiestatīšanas saite nav derīga.']);
        }

        // Validē jauno paroli
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        // Atrodam lietotāju pēc e-pasta
        $user = User::where('email', $email)->first();

        // Saglabājam jauno, hash-ēto paroli
        $user->update(['password' => Hash::make($request->password)]);

        // Dzēšam kešā saglabāto kodu
        Cache::forget('password_reset_code_' . $email);

        // Noņemam e-pastu no sesijas
        session()->forget('reset_email');

        // Nosūtām e-pastu ar paziņojumu, ka parole mainīta
        Mail::to($user->email)->send(new PasswordMessage($user));

        return back()->with('success', 'Parole veiksmīgi atjaunināta!');
    }
}
