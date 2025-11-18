<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RegistrationFlow
{

    public function handle(Request $request, Closure $next): Response
    {
        // Iegūst pašreizējā maršruta nosaukumu
        $route = $request->route()->getName();

        // Definē maršrutus, kuri ir pieejami tikai reģistrācijas procesa laikā
        $protectedRoutes = [
            'verification.notice',
            'verify.pending',
            'verification.resend',
        ];

        // Ja pašreizējais maršruts nav sarakstā, tiek atļauta turpmāka piekļuve
        if (!in_array($route, $protectedRoutes)) {
            return $next($request);
        }

        // Ja tiek saņemts parametru "verified=1", tas nozīmē, ka e-pasts ir veiksmīgi apstiprināts – piekļuve tiek atļauta
        if ($route === 'verification.notice' && $request->query('verified') == 1) {
            return $next($request);
        }

        // Ja lietotājs tikko ir izgājis reģistrācijas procesu, viņam tiek atļauta piekļuve aizsargātajām lapām
        if (session('from_registration') === true) {
            return $next($request);
        }

        // Pretējā gadījumā lietotājs tiek pāradresēts uz sākumlapu, jo tieša piekļuve nav atļauta
        return redirect('/');
    }
}
