<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    // Pārbauda lietotāja tiesības.
    public function boot(): void
    {
        // HTTPS
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Tiek definēta piekļuves pārbaude
        Gate::define('Admin', function ($user) {
            // Tiek pārbaudīts, vai lietotāja loma ir "admin" vai "superadmin"
            return in_array($user->role, ['admin', 'superadmin']);
        });
    }
}
