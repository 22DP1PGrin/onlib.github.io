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

// Kontrolieris paroles atiestatīšanai ar verifikācijas kodu
class PasswordResetController extends Controller
{
    // Nosūta atiestatīšanas kodu uz lietotāja e-pastu
    public function sendResetCode(Request $request)
    {
        // Validē e-pastu
        $request->validate(['email' => 'required|email']);

        // Meklē lietotāju pēc e-pasta
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Ģenerē 6 ciparu kodu
            $code = rand(100000, 999999);

            // Saglabā kodu kešā uz 10 minūtēm
            Cache::put('password_reset_code_' . $user->email, $code, now()->addMinutes(10));

            // Nosūta e-pastu ar kodu
            Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user));

            // Saglabā e-pastu sesijā
            session([
                'reset_email' => $user->email,
            ]);

            session()->save();
        }

        // Atgriež kodu ievades lapu
        return Inertia::render('Auth/ResetPassword', [
            'verified' => $request->get('verified', false)
        ]);
    }

    // Pārbauda ievadīto atiestatīšanas kodu
    public function checkCode(Request $request)
    {
        // Validē kodu
        $request->validate([
            'code'  => 'required|digits:6',
        ],
            [
                'code.digits' => 'Paroles atiestatīšanas kodam jābūt 6 ciparu garumā.',
            ]);

        // Iegūst kodu no keša pēc e-pasta
        $cachedCode = Cache::get('password_reset_code_' . session('reset_email'));

        // Pārbauda, vai kods ir derīgs
        if (!$cachedCode || $cachedCode != $request->code) {
            return back()->withErrors(['code' => 'Nederīgs vai beidzies kods.']);
        }

        // Ja kods pareizs, novirza ar apstiprinājumu
        return redirect()->route('password.showReset', ['verified' => true]);
    }

    // Atjaunina lietotāja paroli
    public function resetPassword(Request $request)
    {
        // Iegūst e-pastu no sesijas
        $email = session('reset_email');

        // Pārbauda, vai e-pasts eksistē
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Atiestatīšanas saite nav derīga.']);
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

        // Meklē lietotāju
        $user = User::where('email', $email)->first();

        // Saglabā jauno paroli
        $user->update(['password' => Hash::make($request->password)]);

        // Dzēš kodu no keša
        Cache::forget('password_reset_code_' . $email);

        // Dzēš e-pastu no sesijas
        session()->forget('reset_email');

        // Nosūta paziņojumu par paroles maiņu
        Mail::to($user->email)->send(new PasswordMessage($user));

        return back()->with('success', 'Parole veiksmīgi atjaunināta!');
    }
}
