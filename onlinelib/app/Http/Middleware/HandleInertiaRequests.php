<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

// Inertia middleware, kas nodod kopīgos datus uz frontend
class HandleInertiaRequests extends Middleware
{
    // Galvenais Blade skats
    protected $rootView = 'app';

    // Nosaka frontend versiju
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    // Kopīgie dati, kas pieejami visās lapās
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
        ];
    }
}
