<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    // Pārbauda, vai lietotājs ir pilnvarots veikt šo pieprasījumu.
    public function authorize(): bool
    {
        return true;
    }

    // Validācijas noteikumi pieprasījumam.
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    // Mēģina autentificēt lietotāju ar sniegtajām akreditācijām.
    public function authenticate(): void
    {
        // Pārbauda, vai nav pārsniegts mēģinājumu limits
        $this->ensureIsNotRateLimited();

        // Mēģina autentificēt ar e-pastu un paroli
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Ja autentifikācija veiksmīga, notīra mēģinājumu skaitu
        RateLimiter::clear($this->throttleKey());
        $user = Auth::user();

        // Pārbauda, vai lietotājs ir bloķēts
        if ($user->is_blocked) {

            if ($user->blocked_until && now()->greaterThan($user->blocked_until)) {
                $user->update([
                    'is_blocked' => false,
                    'blocked_until' => null,
                ]);

                return;
            }

            Auth::logout();

            // Met kļūdu ar ziņojumu par bloķēšanu un laiku Rīgas zonā
            throw ValidationException::withMessages([
                'email' => 'Jūsu konts ir bloķēts līdz ' .
                    ($user->blocked_until
                        ? $user->blocked_until->format('d.m.Y H:i') . ' pēc Rīgas laika'
                        : 'nenoteiktam laikam'
                    ),
            ]);
        }
    }

    // Pārbauda, vai pieprasījums nav pārsniedzis mēģinājumu limitu.
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Notikums, ka lietotājs tika bloķēts
        event(new Lockout($this));

        // Cik sekundes līdz atjaunošanai
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    // Atgriež atslēgu, pēc kuras tiek skaitīti mēģinājumi (throttle key)
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
