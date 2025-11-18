<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class PasswordFlow
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route()->getName();

        // Aizsargātie maršruti paroles atjaunošanas plūsmai
        $protected = [
            'password.showReset',
            'password.check',
            'password.reset',
        ];

        // Ja maršruts nav aizsargāts — piekļuve tiek atļauta
        if (!in_array($route, $protected)) {
            return $next($request);
        }

        // Ja lietotājs nav ievadījis e-pastu — plūsma vēl nav sākta
        if (!Session::get('reset_flow_email')) {
            return redirect()->route('forgot-password.page');
        }

        // Ja tiek parādīta parole ievadīšanas lapa, taču kods vēl nav pārbaudīts
        if ($route === 'password.showReset' && !$request->query('verified')) {
            // Atļauj lapu tikai tad, ja kods vēl nav apstiprināts
            return $next($request);
        }

        // Ja lietotājs vēlas atjaunot paroli (verified=true), bet nav pabeidzis koda pārbaudi — liegts
        if ($route === 'password.showReset' && $request->query('verified') == 'true') {
            if (!Session::get('reset_flow_verified')) {
                return redirect()->route('password.showReset', ['verified' => 'false']);
            }
        }

        // Ja notiek paroles atjaunošanas POST pieprasījums, bet kods vēl nav apstiprināts — piekļuve tiek liegta
        if (in_array($route, ['password.reset']) && !Session::get('reset_flow_verified')) {
            return redirect()->route('password.showReset', ['verified' => 'false']);
        }

        return $next($request);
    }
}
